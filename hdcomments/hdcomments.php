<?php
/*
 * Plugin Name: HDComments
 * Description: Simple and elegant comments for WordPress
 * Plugin URI: https://harmonicdesign.ca?utm_source=HDForms&utm_medium=pluginPage
 * Author: Harmonic Design
 * Author URI: https://harmonicdesign.ca?utm_source=HDForms_author&utm_medium=pluginPage
 * Version: 0.1
 * Notes: This plugin is still in the early stages of development, and as such, some features you require may not have been implimented yet.
 */

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

if (!defined('HDCOMMENTS_PLUGIN_VERSION')) {
    define('HDCOMMENTS_PLUGIN_VERSION', '0.1');
}

/* Include the basic required files
------------------------------------------------------- */
require dirname(__FILE__) . '/functions.php'; // functions for printing and saving comments

// function to check if HDForms is active
function hdcomments_exists()
{
    return;
}

/* Enqueue admin scripts to relevant pages
------------------------------------------------------- */
function hdcomments_add_admin_scripts($hook)
{
    global $post;
    // Only enqueue if we're on the
    // add/edit form page or settings page
    if ($hook == "comments_page_hdcomments") {
        function hdcomments_print_scripts()
        {
            wp_enqueue_style(
                'hdcomments_admin_style',
                plugin_dir_url(__FILE__) . './includes/admin.css?v=' . HDCOMMENTS_PLUGIN_VERSION
            );
            wp_enqueue_script(
                'hdcomments_admin_script',
                plugins_url('./includes/admin.js?v=' . HDCOMMENTS_PLUGIN_VERSION, __FILE__),
                array('jquery'),
                '1.0',
                true
            );
        }
        hdcomments_print_scripts();
    }
}
add_action('admin_enqueue_scripts', 'hdcomments_add_admin_scripts', 10, 1);

/* Create HDComments Settings page
------------------------------------------------------- */
function hdcomments_create_settings_page()
{
    function hdcomments_register_settings_page()
    {
        add_submenu_page("edit-comments.php", "HDComments", "HDComments", "manage_options", "hdcomments", 'hdcomments_register_settings_page_callback');
    }

    function hdcomments_register_settings_page_callback()
    {
        require dirname(__FILE__) . '/includes/hdcomments_about_options.php';
    }
    add_action('admin_menu', 'hdcomments_register_settings_page');
}
hdcomments_create_settings_page();
