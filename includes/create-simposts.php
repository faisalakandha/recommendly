<?php

require_once(plugin_dir_path(__FILE__) . '/database-operations.php');
require_once(plugin_dir_path(__FILE__) . '/api.php');

function CreateAllSimilarPosts()
{

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    $post_list = get_posts($args);
    foreach ($post_list as $post) {
        foreach ($post_list as $current) {
            if ($post->ID != $current->ID) {
                $post_categories = get_the_category($post->ID);
                sleep(20);
                $result = GetSimilarTextFromAPI(wp_strip_all_tags($post->post_content), wp_strip_all_tags($current->post_content));
                foreach ($post_categories as $cat) {
                    CreateSimilarPosts($post->ID, $current->ID,$result, $cat->term_id);
                }
            }
        }
    }
}

?>
