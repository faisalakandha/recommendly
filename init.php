<?php

require_once(plugin_dir_path(__FILE__) . 'includes/database-manager.php');
// require_once(plugin_dir_path(__FILE__) . 'includes/register-add-roles.php');

/*
 * Run all the necessary functions upon activation
 */


function activation_initial_functions()
{
    create_posts_database();
    // dodo_add_user_role();
}


?>
