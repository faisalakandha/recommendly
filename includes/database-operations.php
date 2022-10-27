<?php

function currentPostRecommendations($postId, $similiarity)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "recommendly_posts";
    $sql = "INSERT INTO {$table_name} (PostIDs, Similiarity) VALUES ('{$postId}', '{$similiarity}')";
}

?>