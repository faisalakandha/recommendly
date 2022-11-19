<?php

require_once(plugin_dir_path(__FILE__) . '/database-operations.php');
require_once(plugin_dir_path(__FILE__) . '/api.php');
require_once(plugin_dir_path(__FILE__) . '/logging.php');
require_once(plugin_dir_path(__FILE__) . '/utility.php');

function CreateAllSimilarPosts()
{

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    $post_list = get_posts($args);
    foreach ($post_list as $post) {
        $post_categories = get_the_category($post->ID);
        foreach ($post_categories as $cat) 
        {
            $args_cat = array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'category' => $cat->term_id
            );

            $post_list_cat = get_posts($args_cat);

            foreach ($post_list_cat as $current) 
            {
                if ($post->ID != $current->ID) 
                {
                    plugin_log($cat->term_id);
                    plugin_log($post_list_cat);
                    sleep(20);
                    $result = GetSimilarTextFromAPI(strip_post_content($post->post_content), strip_post_content($current->post_content));
                    CreateSimilarPosts($post->ID, $current->ID,$result, $cat->term_id);
                    $percentage = $result * 100;
                    plugin_log("PostID {$post->ID} is {$percentage}% similar to PostID {$current->ID} Where CategoryID is {$cat->term_id}");
                }
            }
        }
    }
}
