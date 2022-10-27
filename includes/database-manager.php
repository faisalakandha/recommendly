<?php

function create_recommendly_database()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "recommendly";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT primary key, 
            postid mediumint(9) NOT NULL,
            simpid mediumint(9) NOT NULL,
            score FLOAT NOT NULL,
            category mediumint(9) NOT NULL
            ) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta( $sql );

}

?>