<?php


// ACF
// --------------
// --------------
// --------------
// --------------



if(class_exists('acf')){
  // remove custom fields tab from menu
  // add_filter('acf/settings/show_admin', '__return_false');
}

if(class_exists('wp_less')){
  // 3. Load LESS css variables
  require_once( get_template_directory() . '/inc/less-variables.php');

}










// USERPRO
// --------------
// --------------
// --------------
// --------------

