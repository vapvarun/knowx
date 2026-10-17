<?php
/**
 * Sample docs: two products with sections and articles, FAQ and changelog.
 *
 * Read by Starter_Content\Component::create_sample_content() and filtered through `knowx_sample_content`.
 * An article is: title, lead, steps, note and an optional code example (not translated).
 *
 * @package knowx
 */

return array(
	'home_excerpt' => __( 'Guides, references and answers for Orbit Helpdesk and the Orbit API.', 'knowx' ),
	'products'     => array(
		array(
			'name'     => __( 'Orbit Helpdesk', 'knowx' ),
			'slug'     => 'orbit-helpdesk',
			'icon'     => 'chat',
			'accent'   => '#2563eb',
			'desc'     => __( 'Shared inbox, ticket routing and reports for support teams.', 'knowx' ),
			'version'  => '3.2',
			'sections' => array(
				__( 'Getting started', 'knowx' )     => array(
					array( __( 'Create your account', 'knowx' ), __( 'Sign up and set up your workspace in a few minutes.', 'knowx' ), array( __( 'Open the sign-up page and enter your work email.', 'knowx' ), __( 'Choose a workspace name; it becomes your address, such as <code>acme.orbit.example</code>.', 'knowx' ), __( 'Confirm your email and pick a plan. Every plan starts with a 14-day trial.', 'knowx' ) ), __( 'You can rename the workspace later under <strong>Settings &gt; General</strong>.', 'knowx' ) ),
					array( __( 'Invite your team', 'knowx' ), __( 'Add agents and decide what each person can do.', 'knowx' ), array( __( 'Open <strong>Settings &gt; Team</strong> and choose <strong>Invite agent</strong>.', 'knowx' ), __( 'Enter one or more email addresses and pick a role: Agent, Manager or Admin.', 'knowx' ), __( 'Send the invitation. Invites expire after seven days.', 'knowx' ) ), __( 'Admins can change a role at any time without resending an invite.', 'knowx' ) ),
					array( __( 'Connect your email', 'knowx' ), __( 'Forward your support address so every message becomes a ticket.', 'knowx' ), array( __( 'Open <strong>Settings &gt; Channels</strong> and choose <strong>Email</strong>.', 'knowx' ), __( 'Copy the forwarding address shown on the page.', 'knowx' ), __( 'In your mail provider, forward <code>support@yourcompany.com</code> to that address.', 'knowx' ), __( 'Send a test message. It appears in the inbox within a minute.', 'knowx' ) ), __( 'Replies are sent from your own address once you verify the domain.', 'knowx' ) ),
				),
				__( 'Managing tickets', 'knowx' )    => array(
					array( __( 'Assign and route tickets', 'knowx' ), __( 'Send each ticket to the right person automatically.', 'knowx' ), array( __( 'Open <strong>Settings &gt; Routing</strong> and add a rule.', 'knowx' ), __( 'Pick a condition, for example <em>Subject contains &ldquo;invoice&rdquo;</em>.', 'knowx' ), __( 'Choose an agent or a team and save. Rules run from top to bottom.', 'knowx' ) ), __( 'Drag rules to change their order. The first matching rule wins.', 'knowx' ) ),
					array( __( 'Use canned replies', 'knowx' ), __( 'Save answers you send often and insert them in two clicks.', 'knowx' ), array( __( 'Open any ticket and choose <strong>Canned replies</strong> in the reply box.', 'knowx' ), __( 'Select <strong>New reply</strong>, name it and write the text. Use <code>{{customer.name}}</code> to personalise it.', 'knowx' ), __( 'Insert it from the same menu next time.', 'knowx' ) ), __( 'Shared replies are visible to the whole team; private ones only to you.', 'knowx' ) ),
					array( __( 'Merge duplicate tickets', 'knowx' ), __( 'Combine tickets from the same customer into one conversation.', 'knowx' ), array( __( 'Open the newer ticket and choose <strong>More &gt; Merge</strong>.', 'knowx' ), __( 'Search for the ticket to keep and confirm.', 'knowx' ), __( 'All messages move to the kept ticket in date order.', 'knowx' ) ), __( 'Merging cannot be undone, so check the customer and subject first.', 'knowx' ) ),
				),
				__( 'Reports and billing', 'knowx' ) => array(
					array( __( 'Read the dashboard', 'knowx' ), __( 'Understand the numbers on the home screen.', 'knowx' ), array( __( '<strong>First response time</strong> is the median time to the first agent reply.', 'knowx' ), __( '<strong>Resolution time</strong> is the median time from opening to closing.', 'knowx' ), __( '<strong>Satisfaction</strong> is the share of positive ratings from customers.', 'knowx' ) ), __( 'Use the date picker to compare with the previous period.', 'knowx' ) ),
					array( __( 'Export a report', 'knowx' ), __( 'Download your data as a spreadsheet.', 'knowx' ), array( __( 'Open <strong>Reports</strong> and choose a report.', 'knowx' ), __( 'Set the date range and filters.', 'knowx' ), __( 'Select <strong>Export</strong> and pick CSV or XLSX.', 'knowx' ) ), __( 'Exports larger than 50,000 rows are emailed to you as a download link.', 'knowx' ) ),
					array( __( 'Change your plan', 'knowx' ), __( 'Upgrade, downgrade or cancel at any time.', 'knowx' ), array( __( 'Open <strong>Settings &gt; Billing</strong>.', 'knowx' ), __( 'Choose <strong>Change plan</strong> and pick a new plan.', 'knowx' ), __( 'Confirm. Upgrades apply immediately; downgrades start at the next renewal.', 'knowx' ) ), __( 'Unused time on an upgrade is credited to your next invoice.', 'knowx' ) ),
				),
			),
		),
		array(
			'name'     => __( 'Orbit API', 'knowx' ),
			'slug'     => 'orbit-api',
			'icon'     => 'code',
			'accent'   => '#047857',
			'desc'     => __( 'REST endpoints, authentication and webhooks for developers.', 'knowx' ),
			'version'  => 'v2',
			'sections' => array(
				__( 'Getting started', 'knowx' ) => array(
					array( __( 'Get an API key', 'knowx' ), __( 'Create a key and keep it safe.', 'knowx' ), array( __( 'Open <strong>Settings &gt; API</strong> and choose <strong>New key</strong>.', 'knowx' ), __( 'Name the key after the app that will use it.', 'knowx' ), __( 'Copy the key now. It is shown once.', 'knowx' ) ), __( 'Never put a key in front-end code. Call the API from your server.', 'knowx' ), "curl https://api.orbit.example/v2/me \\\n  -H \"Authorization: Bearer YOUR_API_KEY\"" ),
					array( __( 'Make your first request', 'knowx' ), __( 'List the latest tickets with one call.', 'knowx' ), array( __( 'Send a <code>GET</code> request to <code>/v2/tickets</code>.', 'knowx' ), __( 'Add the <code>Authorization</code> header with your key.', 'knowx' ), __( 'Read the <code>data</code> array in the JSON response.', 'knowx' ) ), __( 'Responses are always JSON and times are in UTC.', 'knowx' ), "curl \"https://api.orbit.example/v2/tickets?limit=5\" \\\n  -H \"Authorization: Bearer YOUR_API_KEY\"" ),
					array( __( 'Handle errors', 'knowx' ), __( 'Read the status code and the error body.', 'knowx' ), array( __( '<code>400</code> means a parameter is missing or invalid.', 'knowx' ), __( '<code>401</code> means the key is missing or wrong.', 'knowx' ), __( '<code>429</code> means you sent too many requests; wait and retry.', 'knowx' ), __( '<code>5xx</code> means a problem on our side; retry with a delay.', 'knowx' ) ), __( 'Every error body has a <code>code</code> and a human-readable <code>message</code>.', 'knowx' ), "{\n  \"error\": {\n    \"code\": \"invalid_parameter\",\n    \"message\": \"limit must be between 1 and 100\"\n  }\n}" ),
				),
				__( 'Resources', 'knowx' )       => array(
					array( __( 'Tickets endpoint', 'knowx' ), __( 'Create, read and update tickets.', 'knowx' ), array( __( '<code>GET /v2/tickets</code> lists tickets, newest first.', 'knowx' ), __( '<code>POST /v2/tickets</code> creates a ticket.', 'knowx' ), __( '<code>PATCH /v2/tickets/{id}</code> changes the status, assignee or tags.', 'knowx' ) ), __( 'Closed tickets stay available for 24 months.', 'knowx' ), "curl -X POST https://api.orbit.example/v2/tickets \\\n  -H \"Authorization: Bearer YOUR_API_KEY\" \\\n  -d subject=\"Cannot log in\" -d email=\"sam@example.com\"" ),
					array( __( 'Customers endpoint', 'knowx' ), __( 'Look up and update customer records.', 'knowx' ), array( __( '<code>GET /v2/customers?email=</code> finds a customer by email.', 'knowx' ), __( '<code>PUT /v2/customers/{id}</code> updates the name, phone or custom fields.', 'knowx' ), __( '<code>DELETE /v2/customers/{id}</code> removes a customer and anonymises their tickets.', 'knowx' ) ), __( 'Deleting a customer cannot be undone.', 'knowx' ) ),
					array( __( 'Webhooks', 'knowx' ), __( 'Get a call when something changes.', 'knowx' ), array( __( 'Open <strong>Settings &gt; API &gt; Webhooks</strong> and add an endpoint URL.', 'knowx' ), __( 'Choose the events, such as <code>ticket.created</code> or <code>ticket.closed</code>.', 'knowx' ), __( 'Return a <code>2xx</code> response within ten seconds.', 'knowx' ) ), __( 'Failed deliveries are retried five times over one hour.', 'knowx' ) ),
				),
				__( 'Guides', 'knowx' )          => array(
					array( __( 'Paginate large results', 'knowx' ), __( 'Walk through any list without missing items.', 'knowx' ), array( __( 'Pass <code>limit</code> to set the page size, up to 100.', 'knowx' ), __( 'Read <code>next_cursor</code> from the response.', 'knowx' ), __( 'Pass it as <code>cursor</code> in the next request until it is empty.', 'knowx' ) ), __( 'Cursors expire after one hour.', 'knowx' ), "curl \"https://api.orbit.example/v2/tickets?limit=100&cursor=eyJpZCI6MTIzfQ\" \\\n  -H \"Authorization: Bearer YOUR_API_KEY\"" ),
					array( __( 'Rate limits', 'knowx' ), __( 'Stay inside the request budget.', 'knowx' ), array( __( 'Each key may send 100 requests per minute.', 'knowx' ), __( 'Check <code>X-RateLimit-Remaining</code> on every response.', 'knowx' ), __( 'On <code>429</code>, wait for the number of seconds in <code>Retry-After</code>.', 'knowx' ) ), __( 'Contact support if you need a higher limit for a launch.', 'knowx' ) ),
					array( __( 'Verify webhook signatures', 'knowx' ), __( 'Make sure a webhook really came from Orbit.', 'knowx' ), array( __( 'Read the <code>Orbit-Signature</code> header.', 'knowx' ), __( 'Compute an HMAC-SHA256 of the raw body with your signing secret.', 'knowx' ), __( 'Compare the two values in constant time and reject the request if they differ.', 'knowx' ) ), __( 'Use the raw request body, not a re-encoded copy.', 'knowx' ), "const expected = crypto\n  .createHmac('sha256', process.env.ORBIT_SECRET)\n  .update(rawBody)\n  .digest('hex');" ),
				),
			),
		),
	),
	'faq'          => array(
		array( __( 'Is there a free trial?', 'knowx' ), __( 'Yes. Every plan starts with a 14-day trial and no card is needed.', 'knowx' ) ),
		array( __( 'Can I change my plan later?', 'knowx' ), __( 'Yes. Upgrades apply immediately and downgrades start at your next renewal.', 'knowx' ) ),
		array( __( 'Where is my data stored?', 'knowx' ), __( 'In data centres in the EU and the US. You choose the region when you create the workspace.', 'knowx' ) ),
		array( __( 'Do you offer an API?', 'knowx' ), __( 'Yes. See the Orbit API documentation for endpoints, rate limits and webhooks.', 'knowx' ) ),
		array( __( 'How do I contact support?', 'knowx' ), __( 'Reply to any email from us or write to support@orbit.example. We answer within one working day.', 'knowx' ) ),
	),
	'changelog'    => array(
		__( '3.2.0 - October 2026', 'knowx' )   => array( __( 'New: merge duplicate tickets from the ticket menu.', 'knowx' ), __( 'Improve: the dashboard loads twice as fast on large accounts.', 'knowx' ), __( 'Fix: canned replies kept the wrong signature on forwarded tickets.', 'knowx' ) ),
		__( '3.1.0 - September 2026', 'knowx' ) => array( __( 'New: routing rules can match on the sender domain.', 'knowx' ), __( 'Fix: CSV exports now include custom fields.', 'knowx' ) ),
		__( '3.0.0 - August 2026', 'knowx' )    => array( __( 'New: shared inbox with real-time presence.', 'knowx' ), __( 'Improve: a new reply editor with keyboard shortcuts.', 'knowx' ) ),
	),
);
