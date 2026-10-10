#!/bin/sh
# Runs the PHPUnit integration suite against a throwaway MariaDB and the latest WordPress core + matching test lib.
# Usage: tests/docker/run-integration.sh   (from anywhere; needs Docker)
set -e
THEME="$(cd "$(dirname "$0")/../.." && pwd)"
docker network create knowx-it >/dev/null 2>&1 || true
docker rm -f knowx-it-db >/dev/null 2>&1 || true
docker run -d --name knowx-it-db --network knowx-it -e MARIADB_ROOT_PASSWORD=root -e MARIADB_DATABASE=knowx_test mariadb:lts >/dev/null
sleep 20
docker run --rm --network knowx-it -v "$THEME":/themes/knowx -w /themes/knowx \
	-e WP_TESTS_DIR=/tmp/knowx-tests/wordpress-tests-lib -e WP_CORE_DIR=/tmp/knowx-tests/WordPress \
	-e WP_TESTS_DB_HOST=knowx-it-db -e WP_TESTS_DB_USER=root -e WP_TESTS_DB_PASS=root -e WP_TESTS_DB_NAME=knowx_test \
	wordpress:php8.3-apache sh -c '
echo "memory_limit=-1" > /usr/local/etc/php/conf.d/mem.ini
docker-php-ext-install mysqli >/dev/null 2>&1
apt-get update -qq >/dev/null 2>&1; apt-get install -y -qq subversion >/dev/null 2>&1
mkdir -p /tmp/knowx-tests && cd /tmp/knowx-tests && curl -sL https://wordpress.org/latest.tar.gz | tar xz && mv wordpress WordPress
V=$(php -r "include \"/tmp/knowx-tests/WordPress/wp-includes/version.php\"; echo \$wp_version;")
svn export -q "https://develop.svn.wordpress.org/tags/$V/tests/phpunit/" /tmp/knowx-tests/wordpress-tests-lib
cd /themes/knowx; php tests/phpunit/setup-wp-env.php >/dev/null 2>&1
php vendor/bin/phpunit -c phpunit.integration.xml.dist'
rc=$?
docker rm -f knowx-it-db >/dev/null 2>&1; docker network rm knowx-it >/dev/null 2>&1
rm -f "$THEME/tests/phpunit/.wp-tests-setup-complete"
exit $rc
