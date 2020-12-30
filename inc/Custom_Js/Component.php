<?php
/**
 * Helpdesk\Helpdesk\Custom_Js\Component class
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk\Custom_Js;

use function add_action;
use function get_theme_file_path;
use function get_theme_file_uri;
use function wp_enqueue_script;
use Helpdesk\Helpdesk\Component_Interface;
use function Helpdesk\Helpdesk\helpdesk;
use function wp_script_add_data;

/**
 * Class for improving custom_js among various core features.
 */
class Component implements Component_Interface
{
    /**
     * Gets the unique identifier for the theme component.
     *
     * @return string component slug
     */
    public function get_slug(): string
    {
        return 'custom_js';
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function initialize()
    {
        add_action('wp_enqueue_scripts', [$this, 'action_enqueue_custom_js_script']);
    }

    /**
     * Enqueues a script that improves navigation menu accessibility.
     */
    public function action_enqueue_custom_js_script()
    {
        // If the AMP plugin is active, return early.
        if (helpdesk()->is_amp()) {
            return;
        }

        // Enqueue the superfish script.
        wp_enqueue_script(
            'helpdesk-superfish',
            get_theme_file_uri('/assets/js/superfish.min.js'),
            [],
            helpdesk()->get_asset_version(get_theme_file_path('/assets/js/superfish.min.js')),
            true
        );
        wp_script_add_data('helpdesk-superfish', 'async', true);
        wp_script_add_data('helpdesk-superfish', 'precache', true);

        // Enqueue the isotope script.
        wp_enqueue_script(
            'helpdesk-isotope-pkgd',
            get_theme_file_uri('/assets/js/isotope.pkgd.min.js'),
            [],
            helpdesk()->get_asset_version(get_theme_file_path('/assets/js/isotope.pkgd.min.js')),
            true
        );
        wp_script_add_data('helpdesk-isotope-pkgd', 'async', true);
        wp_script_add_data('helpdesk-isotope-pkgd', 'precache', true);

        // Enqueue the fitVids script.
        wp_enqueue_script(
            'helpdesk-fitvids',
            get_theme_file_uri('/assets/js/fitvids.min.js'),
            [],
            helpdesk()->get_asset_version(get_theme_file_path('/assets/js/fitvids.min.js')),
            true
        );
        wp_script_add_data('helpdesk-fitvids', 'async', true);
        wp_script_add_data('helpdesk-fitvids', 'precache', true);

        // Enqueue the sticky kit script.
        wp_enqueue_script(
            'helpdesk-sticky-kit',
            get_theme_file_uri('/assets/js/sticky-kit.min.js'),
            [],
            helpdesk()->get_asset_version(get_theme_file_path('/assets/js/sticky-kit.min.js')),
            true
        );
        wp_script_add_data('helpdesk-sticky-kit', 'async', true);
        wp_script_add_data('helpdesk-sticky-kit', 'precache', true);

        // Enqueue the custom script.
        wp_enqueue_script(
            'helpdesk-custom',
            get_theme_file_uri('/assets/js/custom.min.js'),
            ['jquery', 'helpdesk-superfish', 'helpdesk-isotope-pkgd', 'helpdesk-fitvids', 'helpdesk-sticky-kit' ],
            helpdesk()->get_asset_version(get_theme_file_path('/assets/js/custom.min.js')),
            true
        );
        wp_script_add_data('helpdesk-custom', 'async', true);
        wp_script_add_data('helpdesk-custom', 'precache', true);
    }
}
