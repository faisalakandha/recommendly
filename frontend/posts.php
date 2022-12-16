<?php
require_once ABSPATH .
    "wp-content/plugins/recommendly/includes/database-operations.php";

function ti_custom_javascript()
{
    $datajs = 0;
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
                if (!in_array($myId, $postIds)) {
                    array_push($postIds, $myId);
                    break;
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
                $datajs = json_encode($posts);
            }
        }

        if (!isset($postIds)) {
            if (sizeof($postIds) == 0) {
                $datajs = 0;
            }
        }
        ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="text/javascript">
      var posts = JSON.stringify(<?php echo $datajs; ?>);
      var postsObj = JSON.parse(posts);
      if (postsObj != 0) {
        $(document).ready(function() {
          var regex = /<[^>]+>|\s{2,}|\n|[^a-zA-Z0-9\s]/g;
          var paragraphs = $('p');

          // Use the .filter() method and a custom function to select only the paragraphs
          // that have at least 15 words and are not empty
          var longNonEmptyParagraphs = paragraphs.filter(function() {
            // The custom function should return true if the paragraph contains at least
            // 50 words and is not empty, and false otherwise
            return $(this).text().trim().split(' ').length >= 50 && $(this).text().trim() !== '';
          });

          var pLength = longNonEmptyParagraphs.length - 1;

          for (var i = 0; i <= postsObj.length - 1; i++) {
            if (pLength >= 0) {
              $(longNonEmptyParagraphs[pLength]).after("<div><h6 style='margin-bottom:9px; color: #1d2027;'>You Might Also Like This:</h6><div style='background-color:white; padding: 0px 0px 0px 0px;'><h5 style='margin-bottom:0px; margin-top:0px;'><a style='text-decoration: none; color: #1d2027' href=" + postsObj[i].permalink + ">" + postsObj[i].post_title + "</a></h5><p style='margin-top:0px; padding-top:0 px; font-size: 16px; color: #434343'>" + postsObj[i].post_content.replace(regex, "")
                .substring(0, 170) + ".....</p></div></div>");
              pLength--;
            } else {
              pLength = longNonEmptyParagraphs.length - 1;
              longNonEmptyParagraphs[pLength].html(postsObj[i].post_title);
              pLength--;
            }
          }
        });
      }
    </script>
<?php
    }
}
add_action("wp_head", "ti_custom_javascript");

?>
