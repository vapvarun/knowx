<?php
/**
 * Sample content for KnowX: two products, FAQ, changelog, menu and settings.
 *
 * Used by the Playground blueprint (runPHP) and by WP-CLI: `wp eval-file playground/seed.php`.
 * It is not shipped in the theme zip. Running it twice does nothing the second time.
 *
 * @package knowx
 */

if ( ! defined( 'ABSPATH' ) ) {
	require_once '/wordpress/wp-load.php';
}

// Block markup helpers.
$p    = static function ( $text ) {
	return "<!-- wp:paragraph -->\n<p>$text</p>\n<!-- /wp:paragraph -->\n\n";
};
$h2   = static function ( $text ) {
	return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">$text</h2>\n<!-- /wp:heading -->\n\n";
};
$h3   = static function ( $text ) {
	return "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">$text</h3>\n<!-- /wp:heading -->\n\n";
};
$list = static function ( $items, $ordered = false ) {
	$tag   = $ordered ? 'ol' : 'ul';
	$attrs = $ordered ? ' {"ordered":true}' : '';
	$li    = '';
	foreach ( $items as $item ) {
		$li .= "<!-- wp:list-item -->\n<li>$item</li>\n<!-- /wp:list-item -->\n";
	}
	return "<!-- wp:list$attrs -->\n<$tag class=\"wp-block-list\">$li</$tag>\n<!-- /wp:list -->\n\n";
};
$code = static function ( $text ) {
	return "<!-- wp:code -->\n<pre class=\"wp-block-code\"><code>" . esc_html( $text ) . "</code></pre>\n<!-- /wp:code -->\n\n";
};
$note = static function ( $text ) {
	return "<!-- wp:group {\"style\":{\"border\":{\"left\":{\"width\":\"4px\"}},\"spacing\":{\"padding\":{\"left\":\"1rem\",\"top\":\"0.5rem\",\"bottom\":\"0.5rem\"}}},\"backgroundColor\":\"tertiary\"} -->\n<div class=\"wp-block-group has-tertiary-background-color has-background\" style=\"border-left-width:4px;padding-top:0.5rem;padding-bottom:0.5rem;padding-left:1rem\">\n<!-- wp:paragraph -->\n<p><strong>Note.</strong> $text</p>\n<!-- /wp:paragraph -->\n</div>\n<!-- /wp:group -->\n\n";
};

if ( get_term_by( 'slug', 'orbit-helpdesk', 'category' ) ) {
	return;
}

$products = array(
	array(
		'name'     => 'Orbit Helpdesk',
		'slug'     => 'orbit-helpdesk',
		'icon'     => 'chat',
		'accent'   => '#2563eb',
		'desc'     => 'Shared inbox, ticket routing and reports for support teams.',
		'version'  => '3.2',
		'sections' => array(
			'Getting started'     => array(
				array( 'Create your account', 'Sign up and set up your workspace in a few minutes.', array( 'Open the sign-up page and enter your work email.', 'Choose a workspace name; it becomes your address, such as <code>acme.orbit.example</code>.', 'Confirm your email and pick a plan. Every plan starts with a 14-day trial.' ), 'You can rename the workspace later under <strong>Settings &gt; General</strong>.' ),
				array( 'Invite your team', 'Add agents and decide what each person can do.', array( 'Open <strong>Settings &gt; Team</strong> and choose <strong>Invite agent</strong>.', 'Enter one or more email addresses and pick a role: Agent, Manager or Admin.', 'Send the invitation. Invites expire after seven days.' ), 'Admins can change a role at any time without resending an invite.' ),
				array( 'Connect your email', 'Forward your support address so every message becomes a ticket.', array( 'Open <strong>Settings &gt; Channels</strong> and choose <strong>Email</strong>.', 'Copy the forwarding address shown on the page.', 'In your mail provider, forward <code>support@yourcompany.com</code> to that address.', 'Send a test message. It appears in the inbox within a minute.' ), 'Replies are sent from your own address once you verify the domain.' ),
			),
			'Managing tickets'    => array(
				array( 'Assign and route tickets', 'Send each ticket to the right person automatically.', array( 'Open <strong>Settings &gt; Routing</strong> and add a rule.', 'Pick a condition, for example <em>Subject contains &ldquo;invoice&rdquo;</em>.', 'Choose an agent or a team and save. Rules run from top to bottom.' ), 'Drag rules to change their order. The first matching rule wins.' ),
				array( 'Use canned replies', 'Save answers you send often and insert them in two clicks.', array( 'Open any ticket and choose <strong>Canned replies</strong> in the reply box.', 'Select <strong>New reply</strong>, name it and write the text. Use <code>{{customer.name}}</code> to personalise it.', 'Insert it from the same menu next time.' ), 'Shared replies are visible to the whole team; private ones only to you.' ),
				array( 'Merge duplicate tickets', 'Combine tickets from the same customer into one conversation.', array( 'Open the newer ticket and choose <strong>More &gt; Merge</strong>.', 'Search for the ticket to keep and confirm.', 'All messages move to the kept ticket in date order.' ), 'Merging cannot be undone, so check the customer and subject first.' ),
			),
			'Reports and billing' => array(
				array( 'Read the dashboard', 'Understand the numbers on the home screen.', array( '<strong>First response time</strong> is the median time to the first agent reply.', '<strong>Resolution time</strong> is the median time from opening to closing.', '<strong>Satisfaction</strong> is the share of positive ratings from customers.' ), 'Use the date picker to compare with the previous period.' ),
				array( 'Export a report', 'Download your data as a spreadsheet.', array( 'Open <strong>Reports</strong> and choose a report.', 'Set the date range and filters.', 'Select <strong>Export</strong> and pick CSV or XLSX.' ), 'Exports larger than 50,000 rows are emailed to you as a download link.' ),
				array( 'Change your plan', 'Upgrade, downgrade or cancel at any time.', array( 'Open <strong>Settings &gt; Billing</strong>.', 'Choose <strong>Change plan</strong> and pick a new plan.', 'Confirm. Upgrades apply immediately; downgrades start at the next renewal.' ), 'Unused time on an upgrade is credited to your next invoice.' ),
			),
		),
	),
	array(
		'name'     => 'Orbit API',
		'slug'     => 'orbit-api',
		'icon'     => 'code',
		'accent'   => '#047857',
		'desc'     => 'REST endpoints, authentication and webhooks for developers.',
		'version'  => 'v2',
		'sections' => array(
			'Getting started' => array(
				array( 'Get an API key', 'Create a key and keep it safe.', array( 'Open <strong>Settings &gt; API</strong> and choose <strong>New key</strong>.', 'Name the key after the app that will use it.', 'Copy the key now. It is shown once.' ), 'Never put a key in front-end code. Call the API from your server.', "curl https://api.orbit.example/v2/me \\\n  -H \"Authorization: Bearer YOUR_API_KEY\"" ),
				array( 'Make your first request', 'List the latest tickets with one call.', array( 'Send a <code>GET</code> request to <code>/v2/tickets</code>.', 'Add the <code>Authorization</code> header with your key.', 'Read the <code>data</code> array in the JSON response.' ), 'Responses are always JSON and times are in UTC.', "curl \"https://api.orbit.example/v2/tickets?limit=5\" \\\n  -H \"Authorization: Bearer YOUR_API_KEY\"" ),
				array( 'Handle errors', 'Read the status code and the error body.', array( '<code>400</code> means a parameter is missing or invalid.', '<code>401</code> means the key is missing or wrong.', '<code>429</code> means you sent too many requests; wait and retry.', '<code>5xx</code> means a problem on our side; retry with a delay.' ), 'Every error body has a <code>code</code> and a human-readable <code>message</code>.', "{\n  \"error\": {\n    \"code\": \"invalid_parameter\",\n    \"message\": \"limit must be between 1 and 100\"\n  }\n}" ),
			),
			'Resources'       => array(
				array( 'Tickets endpoint', 'Create, read and update tickets.', array( '<code>GET /v2/tickets</code> lists tickets, newest first.', '<code>POST /v2/tickets</code> creates a ticket.', '<code>PATCH /v2/tickets/{id}</code> changes the status, assignee or tags.' ), 'Closed tickets stay available for 24 months.', "curl -X POST https://api.orbit.example/v2/tickets \\\n  -H \"Authorization: Bearer YOUR_API_KEY\" \\\n  -d subject=\"Cannot log in\" -d email=\"sam@example.com\"" ),
				array( 'Customers endpoint', 'Look up and update customer records.', array( '<code>GET /v2/customers?email=</code> finds a customer by email.', '<code>PUT /v2/customers/{id}</code> updates the name, phone or custom fields.', '<code>DELETE /v2/customers/{id}</code> removes a customer and anonymises their tickets.' ), 'Deleting a customer cannot be undone.' ),
				array( 'Webhooks', 'Get a call when something changes.', array( 'Open <strong>Settings &gt; API &gt; Webhooks</strong> and add an endpoint URL.', 'Choose the events, such as <code>ticket.created</code> or <code>ticket.closed</code>.', 'Return a <code>2xx</code> response within ten seconds.' ), 'Failed deliveries are retried five times over one hour.' ),
			),
			'Guides'          => array(
				array( 'Paginate large results', 'Walk through any list without missing items.', array( 'Pass <code>limit</code> to set the page size, up to 100.', 'Read <code>next_cursor</code> from the response.', 'Pass it as <code>cursor</code> in the next request until it is empty.' ), 'Cursors expire after one hour.', "curl \"https://api.orbit.example/v2/tickets?limit=100&cursor=eyJpZCI6MTIzfQ\" \\\n  -H \"Authorization: Bearer YOUR_API_KEY\"" ),
				array( 'Rate limits', 'Stay inside the request budget.', array( 'Each key may send 100 requests per minute.', 'Check <code>X-RateLimit-Remaining</code> on every response.', 'On <code>429</code>, wait for the number of seconds in <code>Retry-After</code>.' ), 'Contact support if you need a higher limit for a launch.' ),
				array( 'Verify webhook signatures', 'Make sure a webhook really came from Orbit.', array( 'Read the <code>Orbit-Signature</code> header.', 'Compute an HMAC-SHA256 of the raw body with your signing secret.', 'Compare the two values in constant time and reject the request if they differ.' ), 'Use the raw request body, not a re-encoded copy.', "const expected = crypto\n  .createHmac('sha256', process.env.ORBIT_SECRET)\n  .update(rawBody)\n  .digest('hex');" ),
			),
		),
	),
);

$order    = 0;
$featured = array();

foreach ( $products as $product ) {
	$root = wp_insert_term(
		$product['name'],
		'category',
		array(
			'slug'        => $product['slug'],
			'description' => $product['desc'],
		)
	);
	$root = (int) $root['term_id'];
	update_term_meta( $root, 'knowx_icon', $product['icon'] );
	update_term_meta( $root, 'knowx_accent', $product['accent'] );

	foreach ( $product['sections'] as $section => $articles ) {
		$term = wp_insert_term(
			$section,
			'category',
			array(
				'parent' => $root,
				'slug'   => $product['slug'] . '-' . sanitize_title( $section ),
			)
		);
		$term = (int) $term['term_id'];

		foreach ( $articles as $position => $article ) {
			list( $title, $lead, $steps, $tip ) = $article;
			$snippet                            = $article[4] ?? '';

			$content  = $p( $lead );
			$content .= $h2( 'Steps' ) . $list( $steps, true );
			if ( $snippet ) {
				$content .= $h3( 'Example' ) . $code( $snippet );
			}
			$content .= $note( $tip );

			$id = wp_insert_post(
				array(
					'post_type'     => 'post',
					'post_status'   => 'publish',
					'post_title'    => $title,
					'post_excerpt'  => $lead,
					'post_content'  => $content,
					'post_category' => array( $term ),
					'menu_order'    => $position + 1,
				)
			);
			update_post_meta( $id, 'knowx_version', $product['version'] );
			if ( 0 === $position && 'Getting started' === $section ) {
				$featured[] = $id;
			}
			++$order;
		}
	}
}
update_option( 'sticky_posts', $featured );

// Pages.
$faq         = array(
	array( 'Is there a free trial?', 'Yes. Every plan starts with a 14-day trial and no card is needed.' ),
	array( 'Can I change my plan later?', 'Yes. Upgrades apply immediately and downgrades start at your next renewal.' ),
	array( 'Where is my data stored?', 'In data centres in the EU and the US. You choose the region when you create the workspace.' ),
	array( 'Do you offer an API?', 'Yes. See the Orbit API documentation for endpoints, rate limits and webhooks.' ),
	array( 'How do I contact support?', 'Reply to any email from us or write to support@orbit.example. We answer within one working day.' ),
);
$faq_content = '';
foreach ( $faq as $row ) {
	$faq_content .= "<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>" . esc_html( $row[0] ) . "</summary><!-- wp:paragraph -->\n<p>" . esc_html( $row[1] ) . "</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->\n\n";
}

$changelog  = $h2( '3.2.0 - October 2026' ) . $list( array( 'New: merge duplicate tickets from the ticket menu.', 'Improve: the dashboard loads twice as fast on large accounts.', 'Fix: canned replies kept the wrong signature on forwarded tickets.' ) );
$changelog .= $h2( '3.1.0 - September 2026' ) . $list( array( 'New: routing rules can match on the sender domain.', 'Fix: CSV exports now include custom fields.' ) );
$changelog .= $h2( '3.0.0 - August 2026' ) . $list( array( 'New: shared inbox with real-time presence.', 'Improve: a new reply editor with keyboard shortcuts.' ) );

$home = wp_insert_post(
	array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => 'Documentation',
		'post_excerpt' => 'Guides, references and answers for Orbit Helpdesk and the Orbit API.',
		'post_content' => '',
	)
);
update_post_meta( $home, '_wp_page_template', 'page-knowledgebase-one.php' );

$faq_page = wp_insert_post(
	array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => 'FAQ',
		'post_content' => $faq_content,
	)
);
update_post_meta( $faq_page, '_wp_page_template', 'page-faq.php' );

$change_page = wp_insert_post(
	array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => 'Changelog',
		'post_content' => $changelog,
	)
);
update_post_meta( $change_page, '_wp_page_template', 'page-changelog.php' );

// Remove WordPress' own sample post and page.
wp_delete_post( 1, true );
wp_delete_post( 2, true );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'blogname', 'Orbit Docs' );
update_option( 'blogdescription', 'Help for Orbit Helpdesk and the Orbit API' );

// Tags and two comments, so the tag archive and the comment list have something to show.
$tagged = array(
	'Get an API key'        => array( 'authentication', 'api' ),
	'Make your first request' => array( 'api' ),
	'Create your account'   => array( 'authentication' ),
);
foreach ( $tagged as $title => $tags ) {
	$found = get_posts(
		array(
			'title'          => $title,
			'post_type'      => 'post',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
		)
	);
	if ( $found ) {
		wp_set_post_tags( $found[0]->ID, $tags );
		if ( 'Get an API key' === $title && ! get_comments( array( 'post_id' => $found[0]->ID, 'count' => true ) ) ) {
			foreach ( array(
				array( 'Maya', 'Does the key work for the sandbox too?' ),
				array( 'Orbit Support', 'Yes. Create a separate key under Settings > API > Sandbox.' ),
			) as $comment ) {
				wp_insert_comment(
					array(
						'comment_post_ID'      => $found[0]->ID,
						'comment_author'       => $comment[0],
						'comment_author_email' => 'sample@example.com',
						'comment_content'      => $comment[1],
						'comment_approved'     => 1,
					)
				);
			}
		}
	}
}

// Menu.
// Replace the menu the theme made on activation so the sample menu has the sample pages.
wp_delete_nav_menu( 'Main menu' );
$menu_id = wp_create_nav_menu( 'Main menu' );
foreach ( array(
	$home        => 'Docs',
	$faq_page    => 'FAQ',
	$change_page => 'Changelog',
) as $page_id => $label ) {
	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => $label,
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $page_id,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
		)
	);
}
$locations            = get_theme_mod( 'nav_menu_locations', array() );
$locations['primary'] = $menu_id;
set_theme_mod( 'nav_menu_locations', $locations );

global $wp_rewrite;
$wp_rewrite->set_permalink_structure( '/%postname%/' );
// Taxonomies registered their rewrite rules while permalinks were plain (WP-CLI), so register them again before flushing.
create_initial_taxonomies();
$wp_rewrite->flush_rules( true );
