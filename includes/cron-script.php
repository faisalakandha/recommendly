<?php

function CreateAllSimilarPosts()
{

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        // 'order' => $sort_by,
        // 'orderby' => 'title',
        'post_status' => 'publish',
        // 'tag' => $tags,
        // 'ignore_sticky_posts' => 1,
    );
    
    $post_list = get_posts($args);  

    $posts = array();

    foreach ( $post_list as $post ) {
        foreach($post_list as $current)
        {
            if($post->ID != $current->ID)
            { 
                // Send the $current post to the API
                // Get the score
                // Write it to the database
            }
        }
    }
}

?>