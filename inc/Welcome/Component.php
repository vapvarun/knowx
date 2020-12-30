<?php
/**
 * Helpdesk\Helpdesk\Welcome\Component class
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk\Welcome;

use Helpdesk\Helpdesk\Component_Interface;
use function add_action;


/**
 * Class Component
 *
 * @package Helpdesk\Helpdesk\Welcome
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'welcome';

	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
	}

	/**
	 *  Add welcome page at admin
	 */
	public function add_admin_menu_page() {
		add_submenu_page(
			'themes.php',
			__( 'Getting Started', 'helpdesk' ),
			__( 'Getting Started', 'helpdesk' ),
			'edit_theme_options',
			'helpdesk-welcome',
			array( &$this, 'submenu_page_callback' )
		);
	}

	public function submenu_page_callback() { ?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Getting started with Helpdesk', 'helpdesk' ); ?></h1>
				
			<div class="helpdesk-dashboard-tabs">

				<section class="content">
					<div class="tabs">
					<div role="tablist" aria-label="<?php esc_attr_e( 'Programming Languages', 'helpdesk' ); ?>">
						<button role="tab" aria-selected="true" id="tab1"><?php esc_html_e( 'Home', 'helpdesk' ); ?></button>
						<button role="tab" aria-selected="false" id="tab2"><?php esc_html_e( 'Get Pro', 'helpdesk' ); ?></button>
					</div>
					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab1', 'helpdesk' ); ?>">
						<div class="helpdesk-home-banner">
							<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/thanks-theme-min.jpg' );?>" class="size-medium_large" alt="<?php esc_attr_e('Thank You','helpdesk');?>" />
						</div>
						<div class="helpdesk-home-body">

							<div class="helpdesk-row knowledge-base">
								<div class="helpdesk-col">
									<div class="helpdesk-title-icon"><span class="dashicons dashicons-welcome-learn-more"></span></div>
									<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Knowledge Base', 'helpdesk' ); ?></h3>
									<p class="helpdesk-col-content"><?php esc_html_e( 'We have created full-proof documentation for you. It will help you to understand how our theme works.', 'helpdesk' ); ?></p>
									<a class="helpdesk-btn helpdesk-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs' ); ?>"><?php esc_html_e( 'Take Me to The Knowledge Page', 'helpdesk' ); ?></a>
								</div>

								<div class="helpdesk-col">
									<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/knowledge-base-min.png' );?>" alt="<?php esc_attr_e('Knowledge Base','helpdesk');?>" />
								</div>
							</div><!-- .knowledge-base -->

							<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

							<div class="helpdesk-row video-tutorial">
								<div class="helpdesk-col">
									<div class="helpdesk-title-icon"><span class="dashicons dashicons-video-alt3"></span></div>
									<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Video Tutorial', 'helpdesk' ); ?></h3>
									<p class="helpdesk-col-content"><?php esc_html_e( 'How to use Floating Effects and manage CSS Transform?', 'helpdesk' ); ?></p>
								</div>
							</div><!-- .video-tutorial -->

							<div class="helpdesk-row">
								<div class="helpdesk-col">
									<a href="<?php echo esc_url( 'https://www.youtube.com/watch?v=Ztogq3dx4-E&feature=youtu.be' ); ?>" class="helpdesk-feature-sub-title-a" target="_blank">
										<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/theme-install-video-v-min.jpg' );?>" class="ha-img-fluid ha-rounded" alt="<?php esc_attr_e('Helpdesk Demo installation','helpdesk');?>" />
										<h4 class="helpdesk-feature-sub-title"><?php esc_html_e( 'Helpdesk Demo installation', 'helpdesk' ); ?></h4>
									</a>
								</div>
								<div class="helpdesk-col">
									<a href="<?php echo esc_url( 'https://www.youtube.com/watch?v=i51CikDsbeg&feature=youtu.be' ); ?>" class="helpdesk-feature-sub-title-a" target="_blank">
										<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/theme-options-v-min.jpg' );?>" class="ha-img-fluid ha-rounded" alt="<?php esc_attr_e('Helpdesk Customizer Option','helpdesk');?>" />
										<h4 class="helpdesk-feature-sub-title"><?php esc_html_e( 'Helpdesk Customizer Option', 'helpdesk' ); ?></h4>
									</a>
								</div>
							</div><!-- .video-tutorial columns -->

							<div class="helpdesk-text-center">
								<a class="helpdesk-btn helpdesk-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs' ); ?>"><?php esc_html_e( 'View More Videos', 'helpdesk' ); ?></a>
							</div><!-- .video-tutorial button-->

							<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

							<div class="helpdesk-row faq">
								<div class="helpdesk-col">
									<div class="helpdesk-title-icon"><span class="dashicons dashicons-format-chat"></span></div>
									<h3 class="helpdesk-feature-title"><?php esc_html_e( 'FAQ', 'helpdesk' ); ?></h3>
									<p class="helpdesk-col-content"><?php esc_html_e( 'Frequently Asked Questions', 'helpdesk' ); ?></p>
								</div>
							</div><!-- faq -->

							<div class="helpdesk-row">
								<div class="helpdesk-col">
									<h4 class="helpdesk-faq-title"><?php esc_html_e( 'Can I use these addons in my client project?', 'helpdesk' ); ?></h4>
									<p class="helpdesk-faq-content"><?php esc_html_e( 'Yes, absolutely, no holds barred. Use it to bring colorful moments to your customers. And don’t forget to check out our premium features.', 'helpdesk' ); ?></p>
								</div>
								<div class="helpdesk-col">
									<h4 class="helpdesk-faq-title"><?php esc_html_e( 'Is there any support policy available for the free users?', 'helpdesk' ); ?></h4>
									<p class="helpdesk-faq-content"><?php esc_html_e( 'Free or pro version, both comes with excellent support from us. However, pro users will get priority support.', 'helpdesk' ); ?></p>
								</div>
							</div><!-- .faq columns -->

							<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

							<div class="helpdesk-row support">
								<div class="helpdesk-col">
									<div class="helpdesk-title-icon"><span class="dashicons dashicons-email"></span></div>
									<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Support', 'helpdesk' ); ?></h3>
									<p class="helpdesk-faq-content"><?php esc_html_e( 'Submit your tickets to get solutions from our WordPress experts within the next 24 hours.', 'helpdesk' ); ?></p>
									<a class="helpdesk-btn helpdesk-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/support' ); ?>"><?php esc_html_e( 'Create Ticket', 'helpdesk' ); ?></a>
								</div>

								<div class="helpdesk-col">
									<div class="helpdesk-title-icon"><span class="dashicons dashicons-phone"></span></div>
									<h4 class="helpdesk-feature-title"><?php esc_html_e( 'Help Desk Hours', 'helpdesk' ); ?></h3>
									<p class="helpdesk-faq-content"><?php esc_html_e( 'Days: Monday-Friday', 'helpdesk' ); ?></br>
										<?php esc_html_e( 'Time: 10AM – 7PM GMT', 'helpdesk' ); ?></br>
										<?php esc_html_e( 'Inquiries received after the working hours or on weekends will be addressed on the next working day.', 'helpdesk' ); ?></p>
									<a class="helpdesk-btn helpdesk-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://support.wbcomdesigns.com/portal/home' ); ?>"><?php esc_html_e( 'Start Chat', 'helpdesk' ); ?></a>
								</div>
							</div><!-- .support -->
							

						</div><!-- .helpdesk-home-body -->

					</div><!-- .tab1 -->
					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab2', 'helpdesk' ); ?>" hidden>
						<div class="helpdesk-home-banner">
							<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/choose-pro-theme-min.jpg' );?>" class="size-medium_large" alt="<?php esc_attr_e('Thank You','helpdesk');?>" />
						</div>

						<div class="helpdesk-text-center">
							<h2 class="helpdesk-feature-title"><?php esc_html_e( 'Features', 'helpdesk' ); ?></h2>
						</div><!-- .video-tutorial button-->
					
						<div class="helpdesk-row features">
							<div class="helpdesk-col">
								<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Multiple Header Variations', 'helpdesk' ); ?></h3>
								<p class="helpdesk-col-content"><?php esc_html_e( 'Website header attracts the site visitors’ attention first. Keeping this fact in mind, Helpdesk theme is designed with attractive header styles that make your site look more interactive. Try out the various header designs and select the best one for your community website.', 'helpdesk' ); ?></p>
							</div>
							<div class="helpdesk-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Multiple-Header-min-1024x418.png' );?>" class="size-large" alt="<?php esc_attr_e('Multiple Header Variations','helpdesk');?>" />
							</div>
						</div><!-- .features -->

						<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

						<div class="helpdesk-row features">
							<div class="helpdesk-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/member-group-layout-min.png' );?>" class="size-large" alt="<?php esc_attr_e('Members and Groups Layout','helpdesk');?>" />
							</div>
							<div class="helpdesk-col">
								<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Members and Groups Layout', 'helpdesk' ); ?></h3>
								<p class="helpdesk-col-content"><?php esc_html_e( 'In order to make your community website more engaging and interactive, Helpdesk theme comes with clean and most flexible members and group directory layouts. It makes your website more user-friendly and attractive.', 'helpdesk' ); ?></p>
							</div>
						</div><!-- .features -->

						<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

						<div class="helpdesk-row features">
							<div class="helpdesk-col">
								<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Multiple Member &#38; Group Header Layouts', 'helpdesk' ); ?></h3>
								<p class="helpdesk-col-content"><?php esc_html_e( 'Helpdesk offers beautiful layouts for members and group pages for your social network community website. Site admin can select layouts to style the profile header of their community members as well as for groups.', 'helpdesk' ); ?></p>
							</div>
							<div class="helpdesk-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Member-Header-1-min.png' );?>" class="size-large" alt="<?php esc_attr_e('Multiple Member &#38; Group Header Layouts','helpdesk');?>" />
							</div>
						</div><!-- .features -->

						<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

						<div class="helpdesk-row features">
							<div class="helpdesk-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/shop-min-1024x757.png' );?>" class="size-large" alt="<?php esc_attr_e('WooCommerce Ready','helpdesk');?>" />
							</div>
							<div class="helpdesk-col">
								<h3 class="helpdesk-feature-title"><?php esc_html_e( 'WooCommerce Ready', 'helpdesk' ); ?></h3>
								<p class="helpdesk-col-content"><?php esc_html_e( 'Want more than just a BuddyPress or bbPress WordPress theme? Helpdesk will match your requirements! Try this amazing social network theme that offers support to the most widely used WordPress e-commerce plugin— WooCommerce. Integrate online store functionalities with your website today and sell your products to your community members.', 'helpdesk' ); ?></p>
							</div>
						</div><!-- .features -->

						<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

						<div class="helpdesk-row features">
							<div class="helpdesk-col">
								<h3 class="helpdesk-feature-title"><?php esc_html_e( 'LearnDash Support', 'helpdesk' ); ?></h3>
								<p class="helpdesk-col-content"><?php esc_html_e( 'If you are looking for a powerful social learning solution, Helpdesk is perfect! The theme is fully compatible with the powerful WordPress LMS plugin→ LearnDash. You can easily create a community for your students to engage learners with a social experience and allow them to share their progress with others.', 'helpdesk' ); ?></p>
							</div>
							<div class="helpdesk-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Learndash-min-1024x668.png' );?>" class="size-large" alt="<?php esc_attr_e('LearnDash Support','helpdesk');?>" />
							</div>
						</div><!-- .features -->

						<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

						<div class="helpdesk-row features">
							<div class="helpdesk-col">
								<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Buddyboss-1024x669.png' );?>" class="size-large" alt="<?php esc_attr_e('Get Your Site Ready With Just 1-Click','helpdesk');?>" />
							</div>
							<div class="helpdesk-col">
								<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Get Your Site Ready With Just 1-Click', 'helpdesk' ); ?></h3>
								<p class="helpdesk-col-content"><?php esc_html_e( 'Do you like the look and style of the Demo? Would you like to create your social network just like it? Helpdesk theme offers one-click demo installation support to help you get started with your website within minutes.', 'helpdesk' ); ?></p>
							</div>
						</div><!-- .features -->

						<div class="helpdesk-divider"></div><!-- .helpdesk-divider -->

						<div class="helpdesk-text-center">
							<h3 class="helpdesk-feature-title"><?php esc_html_e( 'Get Pro and Experience all those exciting features', 'helpdesk' ); ?></h3>
							<a class="helpdesk-btn helpdesk-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/helpdesk-pro-theme/' ); ?>"><?php esc_html_e('Get Pro','helpdesk' ); ?></a>
						</div><!-- .video-tutorial button-->

					</div><!-- .tab2 -->
				</section>

			</div><!-- .helpdesk-dashboard-tabs -->
		</div><!-- .wrap -->
		
	<?php }
}
