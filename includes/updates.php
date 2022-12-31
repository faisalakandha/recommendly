<?php
require_once(plugin_dir_path(__FILE__) . '/utility.php');
require_once(plugin_dir_path(__FILE__) . '/logging.php');

function CheckForNewUpdates() {
  // get all published posts
  $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
      'post_status' => 'publish'
  );
  $post_list = get_posts($args);

  // create an array to store the similar posts that have already been calculated
  $calculated_similar_posts = array();

  // iterate through each post
  foreach ($post_list as $post) {
    $post_categories = get_the_category($post->ID);
    // iterate through each category of the post
    foreach ($post_categories as $cat) {
      // get all published posts in the same category
      $args_cat = array(
          'post_type' => 'post',
          'posts_per_page' => -1,
          'post_status' => 'publish',
          'category' => $cat->term_id
      );
      $post_list_cat = get_posts($args_cat);
      // iterate through each post in the same category
      foreach ($post_list_cat as $current) {
        if ($post->ID == $current->ID) {
          continue; // skip if comparing the same post
        }
        // check if this pair of posts has already been calculated
        if (isset($calculated_similar_posts[$post->ID][$current->ID])) {
          continue;
        }
        $A_Count = strlen(strip_post_content($post->post_content));
        $B_Count = strlen(strip_post_content($current->post_content));
        $loop = get_bigger_number($A_Count, $B_Count);
        $result = 0;
        if ($loop > 0) {
          $stringCount = 0;
          $dataSet = array();
          $prev = 0;
          $next = 0;
          for ($i = 1; $i <= $loop; $i++) {
            $prev = $stringCount;
            $next = $stringCount + 630;
            $A = substr(strip_post_content($post->post_content), $prev, $next);
            $B = substr(strip_post_content($current->post_content), $prev, $next);
            array_push($dataSet, GetSimilarTextFromAPI($A, $B));
            $stringCount += 630;
          }
          foreach ($dataSet as $data) {
            $result += $data;
          }
          $result = $result / $loop;
        } else {
          $E = strip_post_content($post->post_content);
          $F = strip_post_content($current->post_content);
          $smaller_string = get_smaller_string($E, $F);
          $smaller_string_length = strlen($smaller_string);
          $result = GetSimilarTextFromAPI(substr($E, 1, $smaller_string_length - 1), substr($F, 1, $smaller_string-1));
        }
        // mark this pair of posts as calculated
        $calculated_similar_posts[$post->ID][$current->ID] = true;
        $calculated_similar_posts[$current->ID][$post->ID] = true;
        // insert the calculated similarity into the database
        CreateSimilarPosts($post->ID, $current->ID, $result, $cat->term_id);
        $percentage = $result * 100;
        plugin_log("PostID {$post->ID} is {$percentage}% similar to PostID {$current->ID} Where CategoryID is {$cat->term_id}");
      }
    }
  }
}


function CronCheckUpdates()
{
    if (get_option('cron_links') == 1) 
    {   
        plugin_log("Cron Job is Running......");   
        CheckForNewUpdates();
    }
}
