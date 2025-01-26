<?php
namespace osetin\cerberus
{
if ( ! defined( 'ABSPATH' ) ) exit;


  class Notices 
  {

    private $core_notices = array(
      'theme_license_deactivate'  => 'theme_license_deactivate',
      'theme_license_activate'  => 'theme_license_activate',
      'theme_license_connection_error' => 'theme_license_connection_error',
      'theme_license_status_422'=> 'theme_license_status_422'
    );

    static $option_notes = 'osetin_admin_notices';

    public function __construct() 
    { 
      //add_action( 'wp_loaded', array( $this, 'hide_notices' ) );
      //if ( current_user_can( '' ) )
      add_action( 'admin_print_styles', array( $this, 'show_notices' ) );
      //$this->add_notices();
    }

    public static function add_notice( $name ) 
    {
      $notices = array_unique( array_merge( get_option( self::$option_notes, array() ), array( $name ) ) );

      update_option( self::$option_notes, $notices );
    }

    public static function remove_all_notices() 
    {
      delete_option(  self::$option_notes );
    }

    public static function remove_notice( $name ) 
    {
      $notices = array_diff( get_option(  self::$option_notes, array() ), array( $name ) );
      update_option(  self::$option_notes, $notices );
    }

    public static function has_notice( $name ) 
    {
      return in_array( $name, get_option( self::$option_notes, array() ) );
    }   

    /**
     * Add notices + styles if needed.
     */
    public function show_notices() 
    {
      $notices = get_option( self::$option_notes, array() );

      if ( $notices ) 
      {       
        foreach ( $notices as $notice ){
          if ( ! empty( $this->core_notices[ $notice ] ) && apply_filters( 'osetin_show_admin_notice', true, $notice ) ) {
            add_action( 'admin_notices', array( $this, $this->core_notices[ $notice ] ) );
          }
        }
      }
    }

    public function enqueue_noties_style()
    {
      // wp_enqueue_style( 'osetin_noties_style', plugins_url( '/assets/css/activation.css', __FILE__ ));     
    }
    
    public function theme_license_deactivate() 
    {
      if (!self::has_notice('theme_license_activate')){
        include( 'views/html-notice-theme-deactivate.php' );
      }
      else{
        self::remove_notice('theme_license_deactivate');
      }
    } 

    public function theme_license_activate() 
    {
      include( 'views/html-notice-theme-activate.php' );
      self::remove_notice('theme_license_activate');
    } 

    public function theme_license_connection_error() 
    {
      include( 'views/html-notice-theme-activate.php' );
      self::remove_notice('theme_license_connection_error');
    } 

    public function theme_license_status_422() 
    {
      include( 'views/html-notice-theme-activate.php' );
      self::remove_notice('theme_license_status_422');
    } 
  }
  new Notices();
}