<?php
require_once(ABSPATH . 'wp-content/plugins/recommendly/includes/database-operations.php');

function ti_custom_javascript()
{
  if (is_singular('post')) {
    $id = get_the_ID();
    $post_categories = get_the_category($id);

    $posts = array();
    foreach ($post_categories as $cat) {
      $container = GetAllRelatedPosts($id, $cat->term_id);
      array_push($posts, get_post($container));
    }
    $datajs = json_encode($posts);
?>
    <script type="text/javascript">
      var posts = JSON.stringify(<?php echo $datajs ?>)
      var postsObj = JSON.parse(posts);

      postsObj.forEach(function(current) {
        console.log(current.post_title);
      });
      
    </script>
<?php
  }
}
add_action('wp_head', 'ti_custom_javascript');

?>