<?php

// Content wrapper
if ( ! function_exists( 'helpdesk_content_top' ) ) {
    function helpdesk_content_top() { ?>
		<div class="site-wrapper">
	<?php }
}

add_action( 'helpdesk_before_content', 'helpdesk_content_top' );

if ( ! function_exists( 'helpdesk_content_bottom' ) ) {
    function helpdesk_content_bottom() { ?>
		</div>
	<?php }
}

add_action( 'helpdesk_after_content', 'helpdesk_content_bottom' );

// Site Sub Header
if ( ! function_exists( 'helpdesk_sub_header' ) ) {
    add_action( 'helpdesk_sub_header', 'helpdesk_sub_header' );

    function helpdesk_sub_header() {
        global $post;
        if ( is_front_page() ) {
            return;
        } ?>
	<div class="site-sub-header">
		<div class="container">
		    <?php
            $breadcrumbs = get_theme_mod( 'site_breadcrumbs', helpdesk_defaults( 'site-breadcrumbs' ) );
            if ( ! empty($breadcrumbs) ) {
                helpdesk_the_breadcrumb();
            }
            ?>
            <?php get_search_form(); ?>
		</div>
 	</div>
<?php
    }
}

/*
 * BREADCRUMBS
 */
//  to include in functions.php
if ( ! function_exists( 'helpdesk_the_breadcrumb' ) ) {
    function helpdesk_the_breadcrumb() {
        $wpseo_titles = get_option( 'wpseo_titles' );
        if (function_exists( 'yoast_breadcrumb' ) && isset( $wpseo_titles['breadcrumbs-enable'] ) && $wpseo_titles['breadcrumbs-enable'] == 1 ) {
            yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
        } else {			
			helpdesk_get_breadcrumb();
        }
    }
}

// Site Loader
if (!function_exists( 'helpdesk_site_loader') ) {
    function helpdesk_site_loader()
    {
        $loader = get_theme_mod( 'site_loader', helpdesk_defaults('site-loader') );
        if ( $loader == '1' ) {
            echo '<div class="site-loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
        }
    }
}

// Site Search and Woo icon
if (!function_exists('helpdesk_site_menu_icon')) {
    function helpdesk_site_menu_icon() {
        // menu icons
        $searchicon = (int) get_theme_mod('site_search', helpdesk_defaults('site-search'));
        $carticon = (int) get_theme_mod('site_cart', helpdesk_defaults('site-cart'));
        if (!empty($searchicon) || !empty($carticon)) : ?>
			<div class="menu-icons-wrapper"><?php
                if (!empty($searchicon)): ?>
					<div class="search" <?php echo apply_filters( 'helpdesk_search_slide_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<a href="#" id="overlay-search" class="search-icon"> <span class="fa fa-search"> </span> </a>
						<div class="top-menu-search-container" <?php echo apply_filters( 'helpdesk_search_field_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php get_search_form(); ?>
						</div>
					</div>
					<?php
                endif;?>
			</div><?php
        endif;
    }
}

/**
 * Function Footer Custom Text
 */
if ( ! function_exists( 'helpdesk_footer_custom_text' ) ) {
    /**
     * Function Footer Custom Text
     *
     * @since 1.0.14
     * @param string $option Custom text option name.
     * @return mixed         Markup of custom text option.
     */
    function helpdesk_footer_custom_text() {
        
        $copyright = esc_html( get_theme_mod( 'site_copyright_text' ) );
        if ( ! empty( $copyright ) ) {
            $copyright = str_replace( '[current_year]', date_i18n( 'Y' ), $copyright );
            $copyright = str_replace( '[site_title]', '<span class="helpdesk-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $copyright );
            $theme_author = apply_filters(
                    'helpdesk_theme_author',
                    array(
                            'theme_name'       => __( 'Helpdesk WordPress Theme', 'helpdesk' ),
                            'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/helpdesk-theme/' ),
                    )
            );
            $copyright = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $copyright );
            return apply_filters( 'helpdesk_footer_copyright_text', $copyright );
        }else {
            $output = 'Copyright © [current_year] [site_title] | Powered by [theme_author]';
            $output = str_replace( '[current_year]', date_i18n( 'Y' ), $output );
            $output = str_replace( '[site_title]', '<span class="helpdesk-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $output );
            $theme_author = apply_filters(
                    'helpdesk_theme_author',
                    array(
                            'theme_name'       => __( 'Helpdesk WordPress Theme', 'helpdesk' ),
                            'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/helpdesk-theme/' ),
                    )
            );
            $output = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $output );
            return apply_filters( 'helpdesk_footer_copyright_text', $output );
        }
        
    }
}

/**
 * Categorized Blog
 * Find out if blog has more than one category.
 */
if ( ! function_exists( 'helpdesk_categorized_blog' ) ) {
	function helpdesk_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'helpdesk_category_count' ) ) ) {

			$all_the_cool_cats = get_categories( array( 'hide_empty' => 1 ) );
			$all_the_cool_cats = count( $all_the_cool_cats );
			set_transient( 'helpdesk_category_count', $all_the_cool_cats );

		}

		if ( 1 !== (int) $all_the_cool_cats ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Blog Post Meta
 */
if ( ! function_exists( 'helpdesk_posted_on' ) ) {

    function helpdesk_posted_on() {

        global $post;

        if ( is_sticky() && is_home() && ! is_paged() ) {
            echo '<span class="entry-featured">' . esc_html__( 'Sticky', 'helpdesk' ) . '</span>';
        }

        if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && helpdesk_categorized_blog() ) {
            echo '<span class="entry-cat-links">' . get_the_category_list(', ') . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        if ( ! is_search() ) {

            if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
                echo '<span class="entry-comments-link">';
                comments_popup_link( esc_html__( 'Leave a comment', 'helpdesk' ), esc_html__( '1 Comment', 'helpdesk' ), esc_html__( '% Comments', 'helpdesk' ) );
                echo '</span>';
            }
        }

        edit_post_link( esc_html__( 'Edit', 'helpdesk' ), '<span class="entry-edit-link">', '</span>' );
    }

}

/**
 * Managing Login and Register URL in Frontend
 * 
 */

if ( ! function_exists( 'helpdesk_alter_login_url_at_frontend' ) ) {

    add_filter( 'login_url', 'helpdesk_alter_login_url_at_frontend', 10, 3 );

    function helpdesk_alter_login_url_at_frontend( $login_url, $redirect, $force_reauth ) {
        if ( is_admin() ) {
            return $login_url;
        }

        $helpdesk_login_page_id = get_theme_mod( 'helpdesk_login_page', '0' );
        if ($helpdesk_login_page_id) {
            $helpdesk_login_page_url = get_permalink( $helpdesk_login_page_id );
            if ( $helpdesk_login_page_url ) {
                $login_url = $helpdesk_login_page_url;
            }
        }
        return $login_url;
    }
}

if ( ! function_exists( 'helpdesk_alter_register_url_at_frontend' ) ) {

    add_filter( 'register_url', 'helpdesk_alter_register_url_at_frontend', 10, 1 );

    function helpdesk_alter_register_url_at_frontend( $register_url ) {
        if ( is_admin() ) {
            return $register_url;
        }

        $helpdesk_registration_page_id = get_theme_mod( 'helpdesk_registration_page', '0' );
        if ( $helpdesk_registration_page_id ) {
            $helpdesk_registration_page_url = get_permalink( $helpdesk_registration_page_id );
            if ( $helpdesk_registration_page_url ) {
                $register_url = $helpdesk_registration_page_url;
            }
        }
        return $register_url;
    }

}

/**
 * Redirect to selected login page from options.
 */
if ( ! function_exists( 'helpdesk_redirect_login_page' ) ) {
    function helpdesk_redirect_login_page() {

        /* removing conflict with logout url */
        if ( isset($_GET['action']) && ( $_GET['action'] == 'logout' ) ) {
            return;
        }

        global $wbtm_helpdesk_settings;
        $login_page_id = $wbtm_helpdesk_settings['helpdesk_pages']['helpdesk_login_page'];
        $register_page_id = $wbtm_helpdesk_settings['helpdesk_pages']['helpdesk_register_page'];

        $login_page = get_permalink($login_page_id);
        $register_page = get_permalink($register_page_id);
        $page_viewed_url = basename($_SERVER['REQUEST_URI']);
        $exploded_Url = wp_parse_url($page_viewed_url);

        if ( ! isset( $exploded_Url['path'] ) ) {
            return;
        }

        // For register page
        if ( $register_page && 'wp-login.php' == $exploded_Url['path'] && 'action=register' == $exploded_Url['query'] && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            wp_redirect( $register_page );
            exit;
        }

        // For login page
        if ( $login_page && $exploded_Url['path'] == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            wp_redirect( $login_page );
            exit;
        }
    }
}

/**
 * Add 404 page redirect
 */
if ( ! function_exists( 'helpdesk_404_redirect' ) ) {
    function helpdesk_404_redirect() {

        // media popup fix
        if ( strpos( $_SERVER['REQUEST_URI'], "media" ) !== false ) {
            return;
        }

        // media upload fix
        if ( strpos( $_SERVER['REQUEST_URI'], "upload" ) !== false ) {
            return;
        }

        if ( ! is_404() ) {
            return;
        }

        $helpdesk_404_page_id = get_theme_mod( 'helpdesk_404_page', '0' );

        if ( $helpdesk_404_page_id ) {
            $helpdesk_404_page_url = get_permalink( $helpdesk_404_page_id );
            wp_redirect( $helpdesk_404_page_url );
            exit;
        }

    }
    add_action( 'template_redirect', 'helpdesk_404_redirect' );
}


/**
 * Add Elementor Locations Support
 */
if ( ! function_exists( 'helpdesk_register_elementor_locations' ) ) {
    function helpdesk_register_elementor_locations( $elementor_theme_manager ) {

        $elementor_theme_manager->register_all_core_location();

    }
    add_action( 'elementor/theme/register_locations', 'helpdesk_register_elementor_locations' );
}

/**
 * Meta box for knowledge base page template
 */
function helpdesk_metabox_callback( $post ) {
	wp_nonce_field( 'helpdesk_meta_box', 'helpdesk_nonce' );
	$cats_value = get_post_meta( $post->ID, 'helpdesk-exclude-cats', true );
	?>
	<p><?php esc_html_e( 'Setting for the Knowledge Base page template. This field will override the one in Customizer.', 'helpdesk' ); ?></p>
	<p><strong><label for="helpdesk-cats"><?php esc_html_e( 'Exclude category by ID', 'helpdesk' ); ?></label></strong></p>
	<input class="widefat" id="helpdesk-cats" type="text" name="helpdesk-cats" value="<?php echo esc_attr( $cats_value ); ?>" />
	<p><?php esc_html_e( 'Use a comma to separate multiple categories.', 'helpdesk' ); ?></p>
	<?php
}

function helpdesk_save_meta( $post_id ) {
	if ( ! isset( $_POST['helpdesk_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['helpdesk_nonce'], 'helpdesk_meta_box' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ( get_post_type() != 'page' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['helpdesk-cats'] ) ) {
		update_post_meta( $post_id, 'helpdesk-exclude-cats', sanitize_text_field( $_POST['helpdesk-cats'] ) );
	}
}
add_action( 'save_post', 'helpdesk_save_meta' );


/*
 * Set post views count using post meta
 */
function setPostViews($postID) {
    $countKey = 'post_views_count';
    $count = get_post_meta($postID, $countKey, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $countKey);
        add_post_meta($postID, $countKey, '0');
    }else{
        $count++;
        update_post_meta($postID, $countKey, $count);
    }
}