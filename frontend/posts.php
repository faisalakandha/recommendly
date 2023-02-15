<?php
require_once ABSPATH .
    "wp-content/plugins/recommendly/includes/database-operations.php";

function ti_custom_related_posts($content)
{
    if (is_singular("post")) {
        $id = get_the_ID();
        $post_categories = get_the_category($id);
        $postIds = [];

        foreach ($post_categories as $cat) {
            $myIds = [];
            $container = GetAllRelatedPosts($id, $cat->term_id);
            foreach ($container as $con) {
                array_push($myIds, $con->id);
            }

            foreach (array_unique($myIds) as $myId) {
                if (!in_array($myId, $postIds) && $myId != $id) {
                    array_push($postIds, $myId);
                }
            }
        }

        if (isset($postIds)) {
            if (sizeof($postIds) != 0) {
                $args = [
                    "post__in" => $postIds,
                    "post_type" => "post",
                    "post_status" => "publish",
                ];
                $posts = get_posts($args);
                foreach ($posts as &$post) {
                    $post->permalink = get_permalink($post->ID);
                }

                $related_posts = [];
                foreach ($posts as $post) {
                    if (!in_array($post->ID, $related_posts)) {
                        $related_posts[] = $post->ID;
                    }
                }

                if (!empty($related_posts)) {
                    $related_posts_html = "<div>";
                    $related_posts_html .=
                        '<h6 style="margin-bottom:9px; color: #1d2027;font-size: 2em;">Vous pourriez également aimer ceci :</h6>';
                    foreach ($related_posts as $post_id) {
                        $post = get_post($post_id);
                        $related_posts_html .=
                            '<div style="background-color:white; padding: 0px 0px 0px 0px;">';
                        $related_posts_html .=
                            '<p style="margin-bottom:0px; margin-top:0px;">';
                        $related_posts_html .=
                            '<a style="text-decoration: none; color: #1d2027;font-size: 1.5em" href="' .
                            get_permalink($post->ID) .
                            '">' .
                            $post->post_title .
                            "</a>";
                        $related_posts_html .= "</p>";
                        $related_posts_html .=
                            '<p style="margin-top:0px; padding-top:0 px; color: #434343">';
                        $related_posts_html .=
                            substr(strip_tags($post->post_content), 0, 170) .
                            "...";
                        $related_posts_html .= "</p>";
                        $related_posts_html .= "</div>";
                    }
                    $related_posts_html .= "</div>";

                    $content .= $related_posts_html;
                }
            }
        }
    }
    return $content;
}
add_filter("the_content", "ti_custom_related_posts");
?>
