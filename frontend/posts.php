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
      $container = GetAllRelatedPosts($id, $cat->term_id);
      foreach ($container as $con) {
        array_push($postIds, $con->id);
      }
    }

    if (isset($postIds)) {
      $args = array(
        'post__in' => $postIds,
        'post_type' => 'post',
        'post_status' => 'publish'
      );
      $posts = get_posts($args);
      $datajs = json_encode($posts);
    }
    
    if (!isset($postIds)) 
    {
        $datajs = 0;
    }
?>
    <script type="text/javascript">
      var posts = JSON.stringify(<?php echo $datajs ?>);
      var postsObj = JSON.parse(posts);
      if (postsObj != 0) {
        postsObj.forEach(function(current) {
          console.log("Post Title: " + current.post_title,"\nPost Content: " + current.post_content.substring(25,70) + "......", "\nPost_Link: " + current.guid);
        });
      }
    </script>
<?php
  }
}
add_action('wp_head', 'ti_custom_javascript');

?>
