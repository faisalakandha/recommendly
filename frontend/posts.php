<?php
require_once(ABSPATH . 'wp-content/plugins/recommendly/includes/database-operations.php');

function ti_custom_javascript()
{
  $datajs = 0;
  if (is_singular('post')) {
    $id = get_the_ID();
    $post_categories = get_the_category($id);
    $postIds = array();

    foreach ($post_categories as $cat) {
      $myIds = array();
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
        $args = array(
          'post__in' => $postIds,
          'post_type' => 'post',
          'post_status' => 'publish'
        );
        $posts = get_posts($args);
        $datajs = json_encode($posts);
      }
    }

    if (!isset($postIds)) {
      if (sizeof($postIds) == 0) {
        $datajs = 0;
      }
    }

    $output = implode(" ", $postIds);
?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="text/javascript">
      var posts = JSON.stringify(<?php echo $datajs ?>);
      var postsObj = JSON.parse(posts);
      if (postsObj != 0) {
        $(document).ready(function() {

          var paragraphs = $('p');

          // Use the .filter() method and a custom function to select only the paragraphs
          // that have at least 15 words and are not empty
          var longNonEmptyParagraphs = paragraphs.filter(function() {
            // The custom function should return true if the paragraph contains at least
            // 50 words and is not empty, and false otherwise
            return $(this).text().trim().split(' ').length >= 50 && $(this).text().trim() !== '';
          });

          // Use the .each() method to loop through each long, non-empty paragraph
          longNonEmptyParagraphs.each(function(index) {
            // Use the .attr() method to set the ID attribute of each paragraph
            // to a unique value based on the index of the paragraph in the loop
            $(this).attr('class', 'paragraph-' + index);
          });
        });
        postsObj.forEach(function(current) {
          //start
          console.log(current.post_content);
          //end
        });
      }
    </script>
<?php
  }
}
add_action('wp_head', 'ti_custom_javascript');

?>