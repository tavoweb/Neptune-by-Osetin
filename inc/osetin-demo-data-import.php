<?php
function ocdi_import_files() {
    return array(
        array(
            'import_file_name'           => 'Demo Import 1',
            'categories'                 => array( 'Category 1', 'Category 2' ),
            'import_file_url'            => 'https://s3.amazonaws.com/tf-themes/wp-neptune-by-osetin/neptune-by-osetin-demo-data-with-images.xml',
            'import_widget_file_url'     => 'https://s3.amazonaws.com/tf-themes/wp-neptune-by-osetin/neptune.pinsupreme.com-widgets.wie',
            'import_preview_image_url'   => '',
            'import_notice'              => __( 'Make sure you installed all the required plugins if you want to get the exact look of the demo site', 'neptune-by-osetin' ),
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'ocdi_import_files' );
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

function ocdi_after_import_setup() {
    // Assign menus to their locations.
    $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

    if($top_menu && $footer_menu){
      set_theme_mod( 'nav_menu_locations', array(
              'header' => $top_menu->term_id,
              'footer' => $footer_menu->term_id,
          )
      );
    }

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Homepage' );


    if($front_page_id){
      update_option( 'show_on_front', 'page' );
      update_option( 'page_on_front', $front_page_id->ID );

    }


    // woocommerce pages
    $shop_page_id = get_page_by_title( 'Shop' );
    $cart_page_id = get_page_by_title( 'Cart' );
    $checkout_page_id = get_page_by_title( 'Checkout' );

  if($shop_page_id && $cart_page_id && $checkout_page_id){
    update_option( 'woocommerce_shop_page_id' , $shop_page_id->ID); 
    update_option( 'woocommerce_cart_page_id' , $cart_page_id->ID); 
    update_option( 'woocommerce_checkout_page_id', $checkout_page_id->ID);
  }


  // REMOVE TOP 10 POSTS COUNTERS
  $tptn_settings = get_option('tptn_settings');
  if($tptn_settings && isset($tptn_settings['add_to'])){
    $tptn_settings['add_to'] = false;
    update_option('tptn_settings', $tptn_settings);
  }
}
add_action( 'pt-ocdi/after_import', 'ocdi_after_import_setup' );
