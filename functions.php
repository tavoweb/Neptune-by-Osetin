<?php
/**
 * Osetin functions and definitions
 *
 * @package Osetin
 */


// Set the version for the theme
if (!defined('OSETIN_THEME_VERSION')) define('OSETIN_THEME_VERSION', '6.3.4');
if (!defined('OSETIN_THEME_UNIQUE_ID')) define('OSETIN_THEME_UNIQUE_ID', 'neptune');

/**
* Activate & configure required plugins
*/

include_once( get_template_directory() . '/inc/osetin-acf.php' );
include_once( get_template_directory() . '/inc/osetin-magic.php' );
include_once( get_template_directory() . '/inc/wp-less/wp-less.php' );
include_once( get_template_directory() . '/inc/activate-plugins.php' );
include_once( get_template_directory() . '/inc/configure-plugins.php' );
include_once( get_template_directory() . '/inc/osetin-feature-like.php' );
include_once( get_template_directory() . '/inc/osetin-feature-vote.php' );
include_once( get_template_directory() . '/inc/osetin-feature-search.php' );
include_once( get_template_directory() . '/inc/osetin-feature-autosuggest.php' );
include_once( get_template_directory() . '/inc/class-cerberus-core.php' );
include_once( get_template_directory() . '/inc/osetin-demo-data-import.php' );
if ( ! function_exists( 'osetin_setup' ) ) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  function osetin_setup() {

    osetin_vote_init();
    osetin_autosuggest_init();
    osetin_search_init();
    osetin_cerberus_init();

    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on _s, use a find and replace
     * to change 'neptune-by-osetin' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'neptune-by-osetin', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );
    add_theme_support('post-thumbnails');
    add_theme_support( 'woocommerce' );
    set_post_thumbnail_size( 500, 500 );
    add_image_size( 'osetin-medium-square-thumbnail', 400, 400, true );
    add_image_size( 'osetin-full-width', 1300, 1300 );
    add_image_size( 'osetin-for-background', 2400, 5000 );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );
    if ( class_exists( 'WooCommerce' ) ) {
      // code that requires WooCommerce
      remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    }

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    //add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
      'header' => esc_html__( 'Top Menu', 'neptune-by-osetin' ),
      'footer' => esc_html__( 'Footer Menu', 'neptune-by-osetin' ),
    ) );



    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
      'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );

    /*
     * Enable support for Post Formats.
     * See http://codex.wordpress.org/Post_Formats
     */
    add_theme_support( 'post-formats', array(
      'image', 'video', 'link', 'gallery',
    ) );

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'osetin_custom_background_args', array(
      'default-color' => 'ffffff',
      'default-image' => '',
    ) ) );

    function add_recipe_custom_query_var( $vars ){
      $vars[] = "recipe_id_to_edit";
      return $vars;
    }
    add_filter( 'query_vars', 'add_recipe_custom_query_var' );
  }
endif; // osetin_setup

add_action( 'after_setup_theme', 'osetin_setup' );

// Enable shortcodes in widgets
add_filter( 'widget_text', 'shortcode_unautop');  
add_filter( 'widget_text', 'do_shortcode', 11);


// output google analytics code if its enter in theme settings
if ( ! function_exists( 'osetin_google_analytics_output' ) ) :
  function osetin_google_analytics_output(){
    if(osetin_get_field('google_analytics_code', 'option')){
      echo osetin_get_field('google_analytics_code', 'option');
    }
  }
endif;

add_action('wp_footer', 'osetin_google_analytics_output', 100);



if ( ! function_exists( 'osetin_add_recipe_to_archives' ) ) :
  function osetin_add_recipe_to_archives( $query ) {
    if ( (is_home() || is_category() || is_tag() || is_search() || is_author()) && $query->is_main_query() ) {

      $post_type = get_query_var('post_type');

      if($post_type) $post_type = $post_type;
      else $post_type = array('post', 'osetin_recipe');

      $query->set( 'post_type', $post_type ); 
    }
    return $query;
  }
endif;

add_filter( 'pre_get_posts', 'osetin_add_recipe_to_archives' );







/**
 * Adds the WordPress Ajax Library to the frontend.
 */
if ( ! function_exists( 'add_ajax_library' ) ) :
  function add_ajax_library() {

      $html = '<script type="text/javascript">';
          $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
      $html .= '</script>';

      echo $html;

  } // end add_ajax_library
endif;

// Include the Ajax library on the front end
add_action( 'wp_head', 'add_ajax_library' );






// LAZA
include_once( get_template_directory() . '/inc/osetin-laza.php' );




if ( ! function_exists( 'osetin_admin_setup' ) ) :

  function osetin_admin_setup()
  {

    if( function_exists('acf_add_options_page') ) {
      acf_add_options_page(array(
        'page_title'  => 'Theme General Settings',
        'menu_title'  => 'Theme Settings',
        'menu_slug'   => 'theme-general-settings',
        'capability'  => 'manage_options',
      ));

      acf_add_options_sub_page(array(
          'page_title'  => 'Theme Settings - Get Started',
          'menu_title'  => 'Get Started',
          'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
        ));


      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - General',
        'menu_title'  => 'General',
        'parent_slug' => 'theme-general-settings',
        'capability'  => 'manage_options',
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Appearance',
        'menu_title'  => 'Appearance',
        'parent_slug' => 'theme-general-settings',
        'capability'  => 'manage_options',
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Fonts',
        'menu_title'  => 'Fonts',
        'parent_slug' => 'theme-general-settings',
        'capability'  => 'manage_options',
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Header',
        'menu_title'  => 'Header',
        'parent_slug' => 'theme-general-settings',
        'capability'  => 'manage_options',
      ));


      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Footer',
        'menu_title'  => 'Footer',
        'parent_slug' => 'theme-general-settings',
        'capability'  => 'manage_options',
      ));
    }
  }

endif;

add_action( 'admin_menu', 'osetin_admin_setup', 98 );






/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if ( ! function_exists( 'osetin_content_width' ) ) :
  function osetin_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'osetin_content_width', 640 );
  }
endif;

add_action( 'after_setup_theme', 'osetin_content_width', 0 );






/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'osetin_widgets_init' ) ) :
  function osetin_widgets_init() {
    register_sidebar( array(
      'name'          => esc_html__( 'Woocommerce - Index Sidebar', 'neptune-by-osetin' ),
      'id'            => 'sidebar-shop',
      'description'   => '',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title"><span>',
      'after_title'   => '</span></h3>',
    ) );
    register_sidebar( array(
      'name'          => esc_html__( 'Archives/Index Sidebar', 'neptune-by-osetin' ),
      'id'            => 'sidebar-index',
      'description'   => '',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title"><span>',
      'after_title'   => '</span></h3>',
    ) );
    register_sidebar(array(
      'name'          => esc_html__( 'Footer Sidebar', 'neptune-by-osetin' ),
      'id'            => 'sidebar-footer',
      'description'   => '',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title"><span>',
      'after_title'   => '</span></h3>',
    ) );
    register_sidebar(array(
      'name'          => esc_html__( 'Single Recipe Sidebar', 'neptune-by-osetin' ),
      'id'            => 'sidebar-single',
      'description'   => '',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title"><span>',
      'after_title'   => '</span></h3>',
    ) );
    register_sidebar(array(
      'name'          => esc_html__( 'Single Recipe Comments Area Sidebar', 'neptune-by-osetin' ),
      'id'            => 'sidebar-single-comments',
      'description'   => '',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title'  => '<h3 class="widget-title"><span>',
      'after_title'   => '</span></h3>',
    ) );
  }
endif;

add_action( 'widgets_init', 'osetin_widgets_init' );






if ( ! function_exists( 'osetin_body_class' ) ) :
  function osetin_body_class($body_classes){
    if(osetin_get_field('use_rounded_corners_for_dropdown_menu', 'option')){
      $body_classes[] = 'dropdown-menu-rounded-corners';
    }
    $body_classes[] = 'dropdown-menu-color-scheme-'.osetin_get_field('menu_dropdown_background_color_type', 'option', 'dark');
    return $body_classes;
  }
endif;

// Add specific CSS class by filter
add_filter('body_class','osetin_body_class');




// Social links for users
if ( ! function_exists( 'osetin_author_social_links' ) ) :
  function osetin_author_social_links( $author_social_link ) {

    $author_social_link['rss_url'] = 'RSS URL';
    $author_social_link['google_profile'] = 'Google Plus Profile URL';
    $author_social_link['twitter_profile'] = 'Twitter Profile URL';
    $author_social_link['facebook_profile'] = 'Facebook Profile URL';
    $author_social_link['linkedin_profile'] = 'Linkedin Profile URL';
    $author_social_link['instagram_profile'] = 'Instagram Profile URL';
    $author_social_link['flickr_profile'] = 'Flickr Profile URL';

    return $author_social_link;
  }
endif;

add_filter( 'user_contactmethods', 'osetin_author_social_links', 10, 1);






// FRONTEND PUBLISHER
if ( ! function_exists( 'osetin_acf_set_featured_image_frontend_publisher' ) ) :
  function osetin_acf_set_featured_image_frontend_publisher( $value, $post_id, $field  ){
      
      if($value != ''){
        //Add the value which is the image ID to the _thumbnail_id meta data for the current post
        add_post_meta($post_id, '_thumbnail_id', $value);
      }
      $value = '';
   
      return $value;
  }
endif;

// acf/update_value/name={$field_name} - filter for a specific field based on it's name
add_filter('acf/update_value/name=acf_featured_image', 'osetin_acf_set_featured_image_frontend_publisher', 10, 3);



// Set recipe status to "pending" when it has been udpated by a user
if ( ! function_exists( 'osetin_pre_save_post' ) ) :
  function osetin_pre_save_post( $post_id ) {  
    // bail early if not a new post
    if( $post_id !== 'new_post' ) {  
      $post_arr = array('ID' => $post_id, 'post_status' => 'pending');
      $post_id = wp_update_post($post_arr);
      return $post_id;  
    }
  }
endif;

add_filter('acf/pre_save_post' , 'osetin_pre_save_post' );




// Add custom css class to an image link that is added to a content, so that we can apply lightbox to it
if ( ! function_exists( 'osetin_add_lightbox_params_to_image_link' ) ) :
  function osetin_add_lightbox_params_to_image_link($html, $id, $caption, $title, $align, $url, $size, $alt){
    $html = str_replace('<a href=', '<a class="osetin-lightbox-trigger-native" href=', $html);
    return $html;
  }
endif;

add_filter('image_send_to_editor', 'osetin_add_lightbox_params_to_image_link', 10, 8);








// generate full url for the enqueue style function to add google fonts support
if ( ! function_exists( 'osetin_google_fonts_url' ) ) :
  function osetin_google_fonts_url(){
      $font_url = '';
      $font_names = osetin_get_google_font_names();
      
      /*
      Translators: If there are characters in your language that are not supported
      by chosen font(s), translate this to 'off'. Do not translate into your own language.
       */
      if ( 'off' !== _x( 'on', 'Google font: on or off', 'neptune-by-osetin' ) ) {
          $font_url = add_query_arg( 'family', $font_names, "//fonts.googleapis.com/css" );
      }
      return $font_url;
  }
endif;



if ( ! function_exists( 'sun_enqueue_custom_fonts_css' ) ) :
  function sun_enqueue_custom_fonts_css() {
    $custom_css = '';
    if( osetin_have_rows('custom_font', 'option') ){
      while ( osetin_have_rows('custom_font', 'option') ) { the_row();
        $font_family_name = get_sub_field('font_family_name');
        $font_url_woff = get_sub_field('font_woff');
        $font_url_woff2 = get_sub_field('font_woff2');
        $font_url_ttf = get_sub_field('font_ttf');
        $font_weight = get_sub_field('font_weight');

        $custom_css.= "@font-face {
                font-family: '".$font_family_name."';
                src: url('".$font_url_woff2."') format('woff2'),
                     url('".$font_url_woff."') format('woff'),
                     url('".$font_url_ttf."') format('truetype');
                font-weight: ".$font_weight.";
                font-style: normal;
              }";
      }
    }
    if($custom_css != ''){
      wp_enqueue_style( 'osetin-custom-fonts', get_template_directory_uri() . '/custom-fonts.css', array(), OSETIN_THEME_VERSION );
      wp_add_inline_style( 'osetin-custom-fonts', $custom_css );
    }

  }
endif;




if ( ! function_exists( 'load_osetin_admin_style' ) ) :
  function load_osetin_admin_style() {
    wp_register_style( 'osetin-admin', get_template_directory_uri() . '/assets/css/osetin-admin.css', false, OSETIN_THEME_VERSION );
    wp_enqueue_style( 'osetin-admin' );

    wp_register_script( 'osetin-admin-js', get_template_directory_uri() . '/assets/js/osetin-admin.js', array('jquery'), OSETIN_THEME_VERSION );
    wp_enqueue_script( 'osetin-admin-js' );
  }
endif;

add_action( 'admin_enqueue_scripts', 'load_osetin_admin_style', 30 );






/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists( 'osetin_scripts' ) ) :
  function osetin_scripts() {


    // ------------//
    // FONTS //
    // ------------//
    // 
    // Preload fonts that are defined in css
    
    wp_enqueue_style( 'osetin-font-in-css', get_template_directory_uri() . '/assets/fonts/osfont/osfont.woff2', array(), OSETIN_THEME_VERSION );

    // Add typekit font support
    if(osetin_get_field('font_library', 'option') == "adobe_typekit_fonts"){
      wp_enqueue_script( 'osetin_typekit', '//use.typekit.net/' . osetin_get_field('adobe_typekit_id', 'option') . '.js');
      add_action( 'wp_head', 'osetin_load_typekit' );
    }elseif(osetin_get_field('font_library', 'option') == "myfonts"){
      add_action( 'wp_head', 'osetin_load_myfonts_script' );
    }elseif(osetin_get_field('font_library', 'option') == "custom_fonts"){
      sun_enqueue_custom_fonts_css();
    }else{
      // Embed google fonts 
      wp_enqueue_style( 'osetin-google-font', osetin_google_fonts_url(), array(), OSETIN_THEME_VERSION );
    }


    // ------------//
    // STYLESHEETS //
    // ------------//

    wp_enqueue_style( 'osetin-owl-carousel', get_template_directory_uri() . '/assets/css/owl.carousel.css' );
    wp_enqueue_style( 'freezeframe', get_template_directory_uri() . '/assets/css/freezeframe_styles.min.css' );
    wp_enqueue_style( 'gifplayer', get_template_directory_uri() . '/assets/css/gifplayer.css' );
    wp_enqueue_style( 'osetin-main', get_template_directory_uri() . '/assets/less/main.less', false, OSETIN_THEME_VERSION );
    wp_enqueue_style( 'osetin-style', get_stylesheet_uri() );



    // ------------//
    // JAVASCRIPTS //
    // ------------//
    
    wp_enqueue_script( 'osetin-feature-review',             get_template_directory_uri() . '/assets/js/osetin-feature-review.js',       array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'osetin-feature-vote',               get_template_directory_uri() . '/assets/js/osetin-feature-vote.js',         array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'osetin-feature-like',               get_template_directory_uri() . '/assets/js/osetin-feature-like.js',         array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'osetin-feature-lightbox',           get_template_directory_uri() . '/assets/js/osetin-feature-lightbox.js',     array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'osetin-feature-autosuggest',        get_template_directory_uri() . '/assets/js/osetin-feature-autosuggest.js',  array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'osetin-feature-search',             get_template_directory_uri() . '/assets/js/osetin-feature-search.js',       array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'osetin-lib-countdown-timer-plugin', get_template_directory_uri() . '/assets/js/lib/jquery.plugin.min.js',       array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'jquery-countdown',                  get_template_directory_uri() . '/assets/js/lib/jquery.countdown.min.js',    array( 'jquery', 'osetin-lib-countdown-timer-plugin' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'isotope',                           get_template_directory_uri() . '/assets/js/lib/isotope.pkgd.min.js',        array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'packery-mode',                      get_template_directory_uri() . '/assets/js/lib/packery-mode.pkgd.min.js',   array( 'jquery', 'isotope'), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'owl-carousel',                      get_template_directory_uri() . '/assets/js/lib/owl.carousel.min.js',        array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'chosen',                            get_template_directory_uri() . '/assets/js/lib/chosen.jquery.min.js',       array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'jquery-barrating',                  get_template_directory_uri() . '/assets/js/lib/jquery.barrating.js',        array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'waitforimages',                     get_template_directory_uri() . '/assets/js/lib/waitforimages.min.js',       array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'perfect-scrollbar',                 get_template_directory_uri() . '/assets/js/lib/perfect-scrollbar.js',       array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'jquery-mousewheel',                 get_template_directory_uri() . '/assets/js/lib/jquery.mousewheel.js',       array( 'jquery' ), OSETIN_THEME_VERSION, true );
    wp_enqueue_script( 'osetin-feature-ingredients',        get_template_directory_uri() . '/assets/js/osetin-feature-ingredients.js',  array( 'jquery' ), OSETIN_THEME_VERSION, true );


    // GIF ANIMATION SCRIPTS
    if(true){
      wp_enqueue_script( 'imagesloaded',                    get_template_directory_uri() . '/assets/js/lib/imagesloaded.min.js',                 array( 'jquery' ), OSETIN_THEME_VERSION, true );
      wp_enqueue_script( 'freezeframe',                     get_template_directory_uri() . '/assets/js/lib/freezeframe.js',                 array( 'jquery', 'imagesloaded' ), OSETIN_THEME_VERSION, true );
      wp_enqueue_script( 'jquery.gifplayer',                get_template_directory_uri() . '/assets/js/lib/jquery.gifplayer.js',                 array( 'jquery' ), OSETIN_THEME_VERSION, true );
    }
    
    wp_enqueue_script( 'osetin-functions',                  get_template_directory_uri() . '/assets/js/functions.js',                   array( 'jquery', 'isotope'), OSETIN_THEME_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
    }

  }
endif;

add_action( 'wp_enqueue_scripts', 'osetin_scripts' );




if ( ! function_exists( 'osetin_dequeue_css_from_plugins' ) ) :
  function osetin_dequeue_css_from_plugins()  {
    // remove styles that are no need
    wp_dequeue_style('sb_instagram_icons');
  }
endif;

add_action('wp_print_styles', 'osetin_dequeue_css_from_plugins');



/**
 * TypeKit Fonts
 *
 */
if ( ! function_exists( 'osetin_load_typekit' ) ) :
  function osetin_load_typekit() { 
    // NON-ASYNCRON LOAD
    if ( wp_script_is( 'osetin_typekit', 'done' ) ) {
      echo '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>';
    }
  }
endif;

/**
 * myFonts.com Fonts
 *
 */
if ( ! function_exists( 'osetin_load_myfonts_script' ) ) :
  function osetin_load_myfonts_script() {
    if ( osetin_get_field('myfonts_code', 'option') ) {
      osetin_the_field('myfonts_code', 'option');
    }
  }
endif;



// Get google fonts names inputted in admin, or use default google fonts if nothign is selected
if ( ! function_exists( 'osetin_get_google_font_names' ) ) :
  function osetin_get_google_font_names(){
    $font_selected_in_admin = osetin_get_field('google_fonts_href', 'option');
      if(!empty($font_selected_in_admin)){
        // clean the input in order to get font names=
        $font_selected_in_admin = str_replace("<link href='http://fonts.googleapis.com/css?family=", '', $font_selected_in_admin);
        $font_selected_in_admin = str_replace("<link href='https://fonts.googleapis.com/css?family=", '', $font_selected_in_admin);
        $font_selected_in_admin = str_replace("<link href='", '', $font_selected_in_admin);
        $font_selected_in_admin = str_replace("http://fonts.googleapis.com/css?family=", '', $font_selected_in_admin);
        $font_selected_in_admin = str_replace("https://fonts.googleapis.com/css?family=", '', $font_selected_in_admin);
        $font_selected_in_admin = str_replace("' rel='stylesheet' type='text/css'>", '', $font_selected_in_admin);
        $font_names = $font_selected_in_admin;
      }else{
        // default font to use in case nothing is selected in admin
        $font_names = 'Droid+Serif:400,400italic%7CYanone+Kaffeesatz';
      }
      return $font_names;
  }
endif;


add_filter('style_loader_tag', 'osetin_preload_in_css_fonts_filter', 10, 2);

function my_theme_updater() {
  require get_template_directory() . '/inc/updater/theme-updater.php'; // Kelias iki updater failo
}
add_action('after_setup_theme', 'my_theme_updater');

function osetin_preload_in_css_fonts_filter($html, $handle) {
  if($handle === 'osetin-font-in-css') {
    $html = str_replace("type='text/css'", '', $html);
    return str_replace("rel='stylesheet'", "rel='preload' as='font' type='font/woff2' crossorigin='anonymous'", $html);
  }
  return $html;
}


// This is done to make sure acf fields are loaded in a child theme 
// More info http://support.advancedcustomfields.com/forums/topic/acf-json-fields-not-loading-from-parent-theme/

add_filter('acf/settings/save_json', function() {
  return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function($paths) {
  $paths = array(get_template_directory() . '/acf-json');

  if(is_child_theme()){
    $paths[] = get_stylesheet_directory() . '/acf-json';
  }

  return $paths;
});