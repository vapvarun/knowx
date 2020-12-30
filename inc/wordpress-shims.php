<?php
/**
 * Shims for recent WordPress functions.
 */

/*
 * Adds backwards compatibility for helpdesk_wp_body_open() introduced with WordPress 5.2
 */
if (!function_exists('helpdesk_wp_body_open')) {
    /**
     * Run the helpdesk_wp_body_open action.
     *
     * @return void
     */
    function helpdesk_wp_body_open()
    {
        do_action('helpdesk_wp_body_open');
    }
}
