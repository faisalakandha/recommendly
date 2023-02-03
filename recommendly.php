<?php

/**
 * Plugin Name: Recommendly
 * Plugin URI: https://mywebsite.com/
 * Description: A plugin for Content Recommendation
 * Version: 1.0.0
 * Author: H.A.B.M. Faisal Akandha
 * Author URI: https://faisal.github.io/
 * License: GPL2
 **/

function my_plugin_activate()
{

    add_option('Activated_Plugin', 'Plugin-Slug');

    require_once(plugin_dir_path(__FILE__) . 'includes/database-manager.php');
    create_recommendly_database();
    add_option('cron_links', 0);
    add_option('recommendly_logs', 0);
}
register_activation_hook(__FILE__, 'my_plugin_activate');

function load_plugin()
{

    if (is_admin() && get_option('Activated_Plugin') == 'Plugin-Slug') {

        delete_option('Activated_Plugin');
    }
}
add_action('admin_init', 'load_plugin');

// Includes

require_once(plugin_dir_path(__FILE__) . 'admin/admin-menu.php');
require_once(plugin_dir_path(__FILE__) . 'includes/cron-script.php');
require_once(plugin_dir_path(__FILE__) . 'includes/api.php');
require_once(plugin_dir_path(__FILE__) . 'frontend/posts.php');
require_once(plugin_dir_path(__FILE__) . 'includes/updates.php');

add_action('create_internal_links_for_all', 'CheckForNewUpdatesExecutor');

// Deactivation

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function delete_database()
{
    global $wpdb;
    $table = $wpdb->prefix . "recommendly";
    $sql = "DROP TABLE $table";
    delete_option('cron_links');
    delete_option('nlpcloud_apikey');
    $wpdb->query($sql);
}

function my_deactivation()
{
    wp_clear_scheduled_hook('recommendly_cron_hook');
    delete_database();
    plugin_log("Plugin Deactivated !");
}

register_deactivation_hook(__FILE__, 'my_deactivation');
