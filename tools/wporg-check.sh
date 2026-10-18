#!/bin/sh
# Checks the built zip the way wordpress.org does: installs it on a fresh WordPress with the latest
# Theme Check from wordpress.org and fails on any REQUIRED or WARNING result. Nothing is allow-listed;
# a result we disagree with is fixed in the code, not accepted here.
# It also proves the theme Theme Check reads is the zip, file for file, so a failed install cannot
# leave an older copy being checked.
# Usage: tools/wporg-check.sh [path/to/knowx-x.y.z.zip]   (needs Docker; default: the zip in dist/)
set -e
THEME="$(cd "$(dirname "$0")/.." && pwd)"
ZIP="${1:-$(ls "$THEME"/dist/knowx-*.zip | head -1)}"
ZIP="$(cd "$(dirname "$ZIP")" && pwd)/$(basename "$ZIP")"
[ -f "$ZIP" ] || { echo "No zip at $ZIP. Build it first: SKIP_BROWSER_SMOKE=1 node scripts/distribute.js --current --no-tag"; exit 1; }
WORK="$(mktemp -d)"
trap 'docker rm -f knowx-wporg-db >/dev/null 2>&1; docker network rm knowx-wporg >/dev/null 2>&1; rm -rf "$WORK"' EXIT

# What the zip holds, file by file.
mkdir "$WORK/zip" && unzip -q "$ZIP" -d "$WORK/zip"
(cd "$WORK/zip" && find knowx -type f | LC_ALL=C sort | xargs md5sum) > "$WORK/expected.txt"

docker network create knowx-wporg >/dev/null 2>&1 || true
docker rm -f knowx-wporg-db >/dev/null 2>&1 || true
docker run -d --name knowx-wporg-db --network knowx-wporg -e MARIADB_ROOT_PASSWORD=root -e MARIADB_DATABASE=wp mariadb:lts >/dev/null
sleep 20

docker run --rm --network knowx-wporg -v "$ZIP":/knowx.zip:ro -v "$WORK":/out --user root wordpress:cli-php8.3 sh -c '
set -e
echo "memory_limit=512M" > /usr/local/etc/php/conf.d/mem.ini
cd /var/www/html
wp --allow-root core download --quiet
wp --allow-root config create --dbname=wp --dbuser=root --dbpass=root --dbhost=knowx-wporg-db --quiet
wp --allow-root core install --url=http://localhost --title=Check --admin_user=admin --admin_password=admin --admin_email=check@example.com --skip-email --quiet
wp --allow-root plugin install theme-check --activate --quiet
wp --allow-root theme install /knowx.zip --activate
cd wp-content/themes && find knowx -type f | LC_ALL=C sort | xargs md5sum > /out/installed.txt
cd /var/www/html && wp --allow-root theme-check run knowx > /out/theme-check.txt 2>&1 || true
'

if ! diff -q "$WORK/expected.txt" "$WORK/installed.txt" >/dev/null; then
	echo "The installed theme does not match the zip:"
	diff "$WORK/expected.txt" "$WORK/installed.txt" | head -20
	exit 1
fi
echo "Installed theme matches the zip: $(wc -l < "$WORK/expected.txt" | tr -d ' ') files."

cat "$WORK/theme-check.txt"
if grep -E '^(REQUIRED|WARNING)' "$WORK/theme-check.txt" >/dev/null; then
	echo "Theme Check found REQUIRED or WARNING results. wordpress.org rejects or questions these; fix them in the code."
	exit 1
fi
grep -q 'INFO' "$WORK/theme-check.txt" || { echo "Theme Check produced no results; the run did not happen."; exit 1; }
echo "Theme Check: no REQUIRED or WARNING results."
