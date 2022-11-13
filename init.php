<?php

require_once(plugin_dir_path(__FILE__) . 'includes/database-manager.php');


/*
 * Run all the necessary functions upon activation
 */


function activation_initial_functions()
{
    create_recommendly_database();
}


?>
