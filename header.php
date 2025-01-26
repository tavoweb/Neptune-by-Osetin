<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <?php if( function_exists( 'wp_site_icon' )){ ?>
    <?php wp_site_icon(); ?>
  <?php } ?>
  <?php wp_head(); ?>
  <?php if ( is_singular( 'osetin_recipe' ) ) { ?>
    <?php  echo osetin_generate_recipe_rich_snippet(); ?>
  <?php } ?>
</head>
<?php 
$is_parallax_background = osetin_get_settings_field('parallax_background');
$background_image_size = osetin_get_settings_field('background_image_size');
$background_contain = ($background_image_size == 'small') ? '' : 'background-size: contain;';
if(is_category()){
  $cat_id =  get_query_var('cat');
  $body_bg_image = osetin_get_field('category_page_background_image', "category_{$cat_id}", false, true);
  if(empty($body_bg_image)){
    $body_bg_image = osetin_get_field('background_image_option', 'option', false, true);
  }
}else{
  $body_bg_image = osetin_get_settings_field('background_image', false, false, false, true);
}
if(!empty($body_bg_image) && is_array($body_bg_image)){
  $size = 'osetin-for-background';
  $body_bg_image_url = $body_bg_image['sizes'][ $size ];
  $body_bg_image_width = $body_bg_image['sizes'][ $size . '-width' ];
  $body_bg_image_height = $body_bg_image['sizes'][ $size . '-height' ];
  if($body_bg_image_width) $body_bg_image_width = str_replace('px', '', $body_bg_image_width);
  if($body_bg_image_height) $body_bg_image_height = str_replace('px', '', $body_bg_image_height);
}else{
  $body_bg_image_url = false;
}
$default_logo = get_template_directory_uri().'/assets/img/neptune-logo.png';
if(osetin_get_field('enable_custom_header_settings') === true){
  $top_menu_version_type = osetin_get_field('top_menu_type');
  $logo_image_url = osetin_get_field('logo_image');
  $logo_image_width = osetin_get_field('logo_image_width');
}else{
  $top_menu_version_type = osetin_get_field('top_menu_type_option', 'option', 'version_1');
  $logo_image_url = osetin_get_field('logo_image_option', 'option', $default_logo);
  $logo_image_width = osetin_get_field('logo_image_width_option', 'option', '210');
}

$top_menu_bg_color =  osetin_get_field('top_menu_background_color', 'option');
$top_menu_bg_color_type =  osetin_get_field('top_menu_background_color_type', 'option', 'light');

$fixed_menu_logo_image_url = osetin_get_field('fixed_header_logo', 'option', $logo_image_url);
$fixed_menu_logo_image_width = osetin_get_field('fixed_header_logo_width', 'option', '210');
$mobile_logo_image_url = osetin_get_field('mobile_logo_image', 'option', $logo_image_url);
$mobile_logo_image_width = osetin_get_field('mobile_logo_image_width', 'option', '210');

?>
<body <?php body_class(); ?> style="<?php echo osetin_get_css_prop('background-color', osetin_get_field('background_color_option', 'option')); ?><?php if(($is_parallax_background != 'yes') && $body_bg_image_url) echo osetin_get_css_prop('background-image', $body_bg_image_url, false, 'background-repeat: repeat; background-position: top center;'.$background_contain); ?>">
  <?php 
  if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
  } else {
    do_action( 'wp_body_open' );
  } ?>
  <?php if(osetin_get_field('custom_css_styles', 'option')){ ?>
    <?php echo '<style type="text/css">'.osetin_get_field('custom_css_styles', 'option').'</style>'; ?>
  <?php } ?>
  <?php if(($is_parallax_background == 'yes') && $body_bg_image_url){ ?>
    <div class="os-parallax" data-width="<?php echo esc_attr($body_bg_image_width); ?>" data-height="<?php echo esc_attr($body_bg_image_height); ?>"><img src="<?php echo esc_attr($body_bg_image_url); ?>" alt="<?php _e('Background Image', 'neptune-by-osetin') ?>"></div>
  <?php } ?>
  <div class="all-wrapper with-animations">
    <div class="print-w">
      <div class="print-logo-w">
        <div><img src="<?php echo $logo_image_url; ?>" alt=""></div>
        <div><?php echo site_url(); ?></div>
      </div>
    </div>
    <?php if(osetin_top_bar_visible()){ ?>
    <?php $member_bar_bg = osetin_get_field('top_member_bar_background_color', 'option'); ?>
    <div class="os-container top-profile-links-box-container">
      <div class="top-profile-links-box-w">
        <div class="top-profile-links-box" style="<?php echo ($member_bar_bg) ? 'background-color: '.$member_bar_bg : ''; ?>">
          <ul>
            <?php if(osetin_top_bar_member_buttons_visible()){ ?>
              <?php if(is_user_logged_in()){ ?>
                <?php 
                global $userpro;
                if(isset($userpro)){
                  $os_current_user = wp_get_current_user(); ?>
                  <li><span><?php _e("Logged in as", 'neptune-by-osetin'); ?> <strong><?php echo $os_current_user->display_name; ?></strong></span></li>
                  <li><a href="<?php echo $userpro->permalink(get_current_user_id()); ?>"><i class="os-icon os-icon-head"></i> <span><?php _e('My Profile', 'neptune-by-osetin') ?></span></a></li>
                  <?php

                    $user_has_posts_query = array(
                        'post_type' => 'osetin_recipe',
                        'author' => get_current_user_id(),
                        'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
                        'posts_per_page' => 1
                    );
                    $user_has_posts = new WP_Query($user_has_posts_query);
                    if (isset($user_has_posts->posts) && count($user_has_posts->posts) > 0){ ?>
                    <li><a href="<?php echo get_author_posts_url( get_current_user_id() ); ?>"><i class="os-icon os-icon-home-03"></i> <span><?php _e('My Recipes', 'neptune-by-osetin') ?></span></a></li>  
                    <?php 
                    }
                  ?>
                  <?php if(get_page_by_title( 'Bookmarks' )){ ?>
                    <li><a href="<?php echo get_permalink( get_page_by_title( 'Bookmarks' ) ); ?>"><i class="os-icon os-icon-ui-33"></i> <span><?php _e('My Bookmarks', 'neptune-by-osetin') ?></span></a></li>
                  <?php } ?>
                  <?php 
                    // try to see if there is a page with meal plans list template
                    $user_meal_plans_permalink = get_user_meal_plans_permalink();
                    if($user_meal_plans_permalink){
                      echo '<li><a href="'.$user_meal_plans_permalink.'"><i class="os-icon os-icon-phone-21"></i> <span>'.__('My Meal Plans', 'neptune-by-osetin').'</span></a></li>';
                    }
                   ?>
                  <li><a href="<?php echo wp_logout_url(); ?>"><i class="os-icon os-icon-signs-12"></i> <span><?php _e('Logout', 'neptune-by-osetin') ?></span></a></li>
                <?php } ?>
              <?php }else{ ?>
                <li><a href="#" class="popup-login"><i class="os-icon os-icon-head"></i> <span><?php _e('Login', 'neptune-by-osetin'); ?></span></a></li>
                <li><a href="#" class="popup-register"><i class="os-icon os-icon-home-03"></i> <span><?php _e('Register', 'neptune-by-osetin'); ?></span></a></li>
              <?php } ?>
            <?php } ?>
            <?php if(osetin_top_bar_cart_button_visible()){ ?>
              <?php if(WC()->cart->cart_contents_count > 0){ ?>
              <li><a href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'neptune-by-osetin' ); ?>"><i class="os-icon os-icon-finance-28"></i> <span><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count, 'neptune-by-osetin' ), WC()->cart->cart_contents_count ); ?> - <?php echo WC()->cart->get_cart_total(); ?></span></a></li>
              <?php }else{ ?>
              <li><a href="<?php echo WC()->cart->get_cart_url(); ?>"><i class="os-icon os-icon-finance-28"></i> <span><?php _e('Cart is empty', 'neptune-by-osetin'); ?></span></a></li>
              <?php } ?>
            <?php } ?>
            <?php if(osetin_top_bar_checkout_button_visible()){ ?>
              <?php if(WC()->cart->cart_contents_count > 0){ ?>
                <li><a href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'Checkout Now', 'neptune-by-osetin' ); ?>"><i class="os-icon os-icon-flag"></i> <span><?php _e( 'Checkout', 'neptune-by-osetin' ); ?></span></a></li>
              <?php } ?>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="os-container main-header-w main-header-<?php echo $top_menu_version_type; ?>">
      <div class="main-header color-scheme-<?php echo $top_menu_bg_color_type; ?> " style="<?php echo osetin_get_css_prop('background-color', $top_menu_bg_color); ?>">
        <?php if($top_menu_version_type != 'version_2'){ ?>
          <div class="logo" style="width: <?php echo $logo_image_width; ?>px;">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
              <img src="<?php echo $logo_image_url; ?>" alt="">
            </a>
          </div>
          <?php wp_nav_menu( array( 'theme_location' => 'header', 'menu_id' => 'header-menu', 'container_class' => 'top-menu menu-activated-on-hover', 'fallback_cb' => false ) ); ?>
          <div class="search-trigger"><i class="os-icon os-icon-thin-search"></i></div>
        <?php }else{ ?>
          <div class="top-logo-w">
            <div class="social-trigger-w">
              <div class="social-trigger">
                <i class="os-icon os-icon-thin-heart"></i>
              </div>
              <?php osetin_social_share_icons('header'); ?>
            </div>
            <div class="logo" style="width: <?php echo $logo_image_width; ?>px;">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <img src="<?php echo $logo_image_url; ?>" alt="">
              </a>
            </div>
            <div class="search-trigger"><i class="os-icon os-icon-thin-search"></i></div>
          </div>
          <div class="top-menu-w">
            <?php wp_nav_menu( array( 'theme_location' => 'header', 'menu_id' => 'header-menu', 'container_class' => 'top-menu menu-activated-on-hover', 'fallback_cb' => false ) ); ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <?php if(osetin_get_field('use_fixed_header', 'option')){ ?>
      <div class="fixed-header-w color-scheme-<?php echo osetin_get_field('fixed_menu_bar_background_color_type', 'option', 'light'); ?>" style="<?php echo osetin_get_css_prop('background-color', osetin_get_field('fixed_menu_bar_background_color', 'option')); ?>">
        <div class="os-container">
          <div class="fixed-header-i">
            <div class="fixed-logo-w" style="width: <?php echo $fixed_menu_logo_image_width; ?>px;">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <img src="<?php echo $fixed_menu_logo_image_url; ?>" alt="">
              </a>
            </div>
            <?php wp_nav_menu( array( 'theme_location' => 'header', 'menu_id' => 'fixed-header-menu', 'container_class' => 'fixed-top-menu-w menu-activated-on-hover', 'fallback_cb' => false ) ); ?>
            <div class="fixed-search-trigger-w">
              <div class="search-trigger"><i class="os-icon os-icon-thin-search"></i></div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
    <div class="mobile-header-w">
      <div class="mobile-header-menu-w menu-activated-on-click color-scheme-<?php echo osetin_get_field('mobile_header_background_color_type', 'option', 'dark'); ?>" style="<?php echo osetin_get_css_prop('background-color', osetin_get_field('mobile_header_background_color', 'option')); ?>">
        <?php wp_nav_menu( array( 'theme_location' => 'header', 'menu_id' => 'mobile-header-menu', 'container' => '', 'fallback_cb' => false ) ); ?>
      </div>
      <div class="mobile-header">
        <div class="mobile-menu-toggler">
          <i class="os-icon os-icon-thin-hamburger"></i>
        </div>
        <div class="mobile-logo" style="width: <?php echo $mobile_logo_image_width; ?>px;">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo $mobile_logo_image_url; ?>" alt=""></a>
        </div>
        <div class="mobile-menu-search-toggler">
          <i class="os-icon os-icon-thin-search"></i>
        </div>
      </div>
    </div>