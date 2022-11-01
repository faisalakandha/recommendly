<?php

require_once(plugin_dir_path(__FILE__) . '/database-operations.php');

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

    foreach ($post_list as $post) {
        foreach ($post_list as $current) {
            if ($post->ID != $current->ID) {
                $post_categories = get_the_category($post->ID);
                $result = GetSimilarTextFromAPI($post->post_content, $current->post_content);
                foreach($post_categories as $cat) 
                {
                    CreateSimilarPosts($post->ID, $current->ID, $result->score, $cat->term_id );
                }
            }
        }
    }
}

function GetSimilarTextFromAPI($textA, $textB)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://text-similarity4.p.rapidapi.com/accurate/?text_a='{$textA}'&text_b='{$textB}'",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: text-similarity4.p.rapidapi.com",
            "X-RapidAPI-Key: a82f7456d9msh886e7611491d56bp19708ajsncc33bcc24d0c"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
    return $response;
}
