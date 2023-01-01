<?php
require_once(plugin_dir_path(__FILE__) . '/utility.php');
require_once(plugin_dir_path(__FILE__) . '/logging.php');

function CheckForNewUpdates()
{
  // Calculate the timestamp for 5 seconds in the future
  $timestamp = time() + 5;

  // Schedule the event to occur at the specified time
  wp_schedule_single_event($timestamp, 'create_internal_links_for_all');
}

function CheckForNewUpdatesExecutor()
{

  $args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'post_status' => 'publish'
  );

  $post_list = get_posts($args);
  foreach ($post_list as $post) {
    $post_categories = get_the_category($post->ID);
    foreach ($post_categories as $cat) {
      $args_cat = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'category' => $cat->term_id
      );
      $post_list_cat = get_posts($args_cat);
      foreach ($post_list_cat as $current) {
        global $wpdb;
        $GLOBALS['table_name'] = $wpdb->prefix . "recommendly";
        $sql = "SELECT postid FROM {$GLOBALS['table_name']} WHERE postid = '{$post->ID}' AND category = '{$cat->term_id}' AND simpid = '{$current->ID}'";
        $result = $wpdb->get_results($sql);

        if (empty($result)) {
          if ($post->ID != $current->ID) {
            $A_Count = strlen(strip_post_content($post->post_content));
            $B_Count = strlen(strip_post_content($current->post_content));
            plugin_log("Counts are $A_Count, $B_Count");
            $loop = get_bigger_number($A_Count, $B_Count);
            plugin_log("Bigger Number is $loop");
            $result = 0;

            if ($loop > 0) {
              $stringCount = 0;
              $dataSet = array();
              $prev = 0;
              $next = 0;
              for ($i = 1; $i <= $loop; $i++) {
                $prev = $stringCount;
                $next = $stringCount + 630;
                plugin_log("Prev is $prev, Next is $next");
                $A = substr(strip_post_content($post->post_content), $prev, $next);
                $B = substr(strip_post_content($current->post_content), $prev, $next);
                array_push($dataSet, GetSimilarTextFromAPI($A, $B));
                $stringCount += 630;
              }

              foreach ($dataSet as $data) {
                $result += $data;
              }
              plugin_log("Result before avg is $result");
              $result = $result / $loop;
              plugin_log("Result after avg is $result");
            } else {

              $E = strip_post_content($post->post_content);
              $F = strip_post_content($current->post_content);

              $smaller_string = get_smaller_string($E, $F);

              $smaller_string_length = strlen($smaller_string);

              $result = GetSimilarTextFromAPI(substr($E, 1, $smaller_string_length - 1), substr($F, 1, $smaller_string_length - 1));
            }

            plugin_log($cat->term_id);
            plugin_log($post_list_cat);
//            sleep(20);
            CreateSimilarPosts($post->ID, $current->ID, $result, $cat->term_id);
            $percentage = $result * 100;
            plugin_log("PostID {$post->ID} is {$percentage}% similar to PostID {$current->ID} Where CategoryID is {$cat->term_id}");
          }
        }
      }
    }
  }
}


function CronCheckUpdates()
{
  if (get_option('cron_links') == 1) {
    plugin_log("Cron Job is Running......");
    CheckForNewUpdatesExecutor();
  }
}
