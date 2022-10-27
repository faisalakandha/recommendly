<?php
/**
* Plugin Name: Recommendly
* Plugin URI: https://mywebsite.com/
* Description: A plugin for Content Recommendation
* Version: 1.0.0
* Author: H.A.B.M. Faisal Akandha
* Author URI: https://faisal.github.io/
* License: GPL2
**/

require_once(plugin_dir_path(__FILE__) . 'admin/admin-menu.php');
require_once(plugin_dir_path(__FILE__) . 'init.php');

// Initial Activation 
activation_initial_functions();

?>