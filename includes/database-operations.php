<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

function currentPostRecommendations($postId, $simpid, $score,$category)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "recommendly_posts";
    $sql = "INSERT INTO {$table_name} (postid,simpid,score,category) VALUES ('{$postId}', '{$simpid}','{$score}','{$category}')";
    dbDelta( $sql );
}

?>