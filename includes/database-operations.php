<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(plugin_dir_path(__FILE__) . '/logging.php');

global $wpdb;
$GLOBALS['table_name'] = $wpdb->prefix . "recommendly";
// Write all the posts that are related to a specific post and their scores to database

function CreateSimilarPosts($postId, $simpid, $score, $category)
{
    global $wpdb;
    $sqlCheck = "SELECT postid FROM {$GLOBALS['table_name']} WHERE postid = '{$post->ID}' AND category = '{$cat->term_id}' AND simpid = '{$current->ID}'";
    $result = $wpdb->get_results($sqlCheck);
    if (empty($result)) {
    $sql = "INSERT INTO {$GLOBALS['table_name']} (postid,simpid,score,category) VALUES ('{$postId}', '{$simpid}','{$score}','{$category}')";
    dbDelta($sql);
    }
}

// Get all the posts related to a specific post in a specific category

function GetAllRelatedPosts($postId, $category)
{
    global $wpdb;
    $sql = "SELECT simpid as id FROM {$GLOBALS['table_name']} WHERE postid = '{$postId}' AND category = '{$category}' ORDER BY score DESC";
    $result = $wpdb->get_results($sql);
    if ($wpdb->last_error) {
        echo 'wpdb error: ' . $wpdb->last_error;
    }
    plugin_log("One similar post found for the PostId {$postId} and CategoryID {$category}");
    return $result;
}

// Get links count
function GetLinksCount()
{
    global $wpdb;

    $sql = "SELECT COUNT(*) as links FROM {$GLOBALS['table_name']}";
    $result = $wpdb->get_results($sql);
    if ($wpdb->last_error) {
        echo 'wpdb error: ' . $wpdb->last_error;
    }

    return $result;
}
