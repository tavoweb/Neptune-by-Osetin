<?php



add_action('admin_init','osetin_acf_options_page_settings');

function osetin_acf_options_page_settings() {

  if( function_exists('acf_add_options_page') ) {
    $pages = acf_get_options_pages();    
      

    if( !empty($pages) ){
      global $wp_filter;
      
      foreach( $pages as $page ){

        if (stripos($page['menu_slug'], 'get-started') === false) continue;
        $hookname = get_plugin_page_hookname( $page['menu_slug'], '' );
        if(isset($wp_filter[$hookname])){
          foreach($wp_filter[$hookname] as $filter_functions){
            foreach($filter_functions as $function_name => $value){
              if (stripos($function_name, 'html') !== false){
                if(remove_action( $hookname, $function_name)){
                  add_action( $hookname, 'osetin_options_page_view');
                }
              }
            }
          }
        }
      }
    }
  }
}





function osetin_options_page_view() {
  $path = get_template_directory() .'/inc/views/options-page.php';
  if( file_exists($path) ) {

    include( $path );
    
  }
}

// ENDLAZA