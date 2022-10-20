<?php

function create_dodo_database()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "recommendly_posts";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        island_name MEDIUMTEXT NOT NULL,
        dodo_code MEDIUMTEXT NOT NULL,
        protection BOOLEAN NOT NULL default 0, 
        PRIMARY KEY (id)
    ) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta( $sql );

}

?>