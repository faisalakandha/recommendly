<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(plugin_dir_path(__FILE__) . '/logging.php');

function create_recommendly_database()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "recommendly";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
            id int(11) UNSIGNED AUTO_INCREMENT,      
            postid mediumint(9) NOT NULL,
            simpid mediumint(9) NOT NULL,
            score FLOAT(15) NOT NULL,
            category mediumint(9) NOT NULL,
            PRIMARY KEY (id)
            ) $charset_collate;";
    dbDelta( $sql );
    
}

?>