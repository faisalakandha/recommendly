<?php

register_deactivation_hook( __FILE__, 'my_deactivation' );
 
function my_deactivation() {
    wp_clear_scheduled_hook( 'my_hourly_event' );
}

?>