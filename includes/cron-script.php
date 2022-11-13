<?php

require_once(plugin_dir_path(__FILE__) . '/create-simposts.php');
require_once(plugin_dir_path(__FILE__) . '/logging.php');

// Setting Custom Hook for WP CRON
add_action('recommendly_cron_hook', 'CreateAllSimilarPosts');


// Prevent Duplicate Events
if(!wp_next_scheduled('recommendly_cron_hook', $args))
{
    //Scheduling Recurring Event
    wp_schedule_event(time(), 'daily', 'recommendly_cron_hook');
}


?>
