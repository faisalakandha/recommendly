<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

// Write all the posts that are related to a specific post and their scores to database

function CreateSimilarPosts($postId, $simpid, $score,$category)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "recommendly";
    $sql = "INSERT INTO {$table_name} (postid,simpid,score,category) VALUES ('{$postId}', '{$simpid}','{$score}','{$category}')";
    dbDelta( $sql );
}

// Get all the posts related to a specific post in a specific category

function GetAllRelatedPosts($postId)
{
    global $wpdb;
    
    $sql = "SELECT simpid FROM wp_recommendly WHERE category IN (SELECT DISTINCT category WHERE postid = '{$postId}') ORDER BY score";
    $result = $wpdb->get_results($sql);
    if ( $wpdb->last_error ) 
    {
        echo 'wpdb error: ' . $wpdb->last_error;
    }

    return $result;
}

?>