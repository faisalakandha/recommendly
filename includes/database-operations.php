<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(plugin_dir_path(__FILE__) . '/logging.php');

global $wpdb;
$GLOBALS['table_name'] = $wpdb->prefix . "recommendly";
// Write all the posts that are related to a specific post and their scores to database

function CreateSimilarPosts($postId, $simpid, $score, $category)
{
    global $wpdb;

    // Check if a row with the same postid and simpid values already exists
    $row_exists = $wpdb->get_row("SELECT * FROM {$GLOBALS['table_name']} WHERE postid = '{$postId}' AND simpid = '{$simpid}'");

    if ($row_exists) {
        // Update the score column if the row already exists
        $wpdb->update(
            $GLOBALS['table_name'],
            array(
                'score' => $score,
            ),
            array(
                'postid' => $postId,
                'simpid' => $simpid,
            )
        );
    } else {
        // Insert a new row if the row does not already exist
        $wpdb->insert(
            $GLOBALS['table_name'],
            array(
                'postid' => $postId,
                'simpid' => $simpid,
                'score' => $score,
                'category' => $category,
            )
        );
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
