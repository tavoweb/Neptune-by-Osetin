<?php
  class my_less extends lessc {

    private $default_vars = array();

    function __construct() {
      parent::__construct();

      // Images path
      define('CSS_IMAGES_PATH', get_template_directory_uri()."/images/");
    }

    public function load_defaults()
    {
      $scheme_vars = array();
      // load default color scheme settings
      require( get_template_directory() . "/inc/default-less-vars.php");
      // load custom variables you want to override
      require( get_template_directory() . '/extend/php-variables.php' );
      $this->default_vars = $scheme_vars;
    }



    // **************************************************
    // **************************************************
    // **************************************************

    // SETTING LESS CSS VARIABLES FROM THE ADMIN OPTIONS

    // **************************************************
    // **************************************************
    // **************************************************


    function my_less_vars( $vars, $handle ) {
      $this->load_defaults();
      $vars = $this->default_vars;
      $vars[ 'plutoFolderPath' ] = "'".get_template_directory_uri()."'";
      $vars[ 'imagesPath' ] = "'".get_template_directory_uri()."/assets/img'";
      $vars[ 'fontsPath' ] = "'".get_template_directory_uri()."/assets/fonts'";
      

      // FONTS
      $vars[ 'headingsFontFamily' ]         = $this->custom_or_default('headings_font_family' , 'headingsFontFamily');
      $vars[ 'headingsFontWeightNormal' ]   = $this->custom_or_default('headings_font_weight_normal' , 'headingsFontWeightNormal');
      $vars[ 'headingsFontWeightBold' ]     = $this->custom_or_default('headings_font_weight_bold' , 'headingsFontWeightBold');

      $vars[ 'textFontFamily' ]             = $this->custom_or_default('text_font_family' , 'textFontFamily');
      $vars[ 'textFontWeightNormal' ]       = $this->custom_or_default('text_font_weight_normal' , 'textFontWeightNormal');
      $vars[ 'textFontWeightBold' ]         = $this->custom_or_default('text_font_weight_bold' , 'textFontWeightBold');

      $vars[ 'menuFontFamily' ]             = $this->custom_or_default('menu_font_family' , 'menuFontFamily');
      $vars[ 'menuFontWeightNormal' ]       = $this->custom_or_default('menu_font_weight_normal' , 'menuFontWeightNormal');
      $vars[ 'menuFontWeightBold' ]         = $this->custom_or_default('menu_font_weight_bold' , 'menuFontWeightBold');

      $vars[ 'alternativeFontFamily' ]      = $this->custom_or_default('alternative_font_family' , 'alternativeFontFamily');
      $vars[ 'alternativeFontWeightNormal'] = $this->custom_or_default('alternative_font_weight_normal' , 'alternativeFontWeightNormal');
      $vars[ 'alternativeFontWeightBold']   = $this->custom_or_default('alternative_font_weight_bold' , 'alternativeFontWeightBold');

      $vars[ 'baseFontSize' ]               = $this->add_px($this->custom_or_default('base_font_size' , 'baseFontSize'));
      $vars[ 'headingsBaseFontSize' ]       = $this->add_px($this->custom_or_default('headings_base_font_size' , 'headingsBaseFontSize'));
      $vars[ 'mainMenuBaseFontSize' ]       = $this->add_px($this->custom_or_default('main_menu_base_font_size' , 'mainMenuBaseFontSize'));
      $vars[ 'alternativeBaseFontSize' ]    = $this->add_px($this->custom_or_default('alternative_base_font_size' , 'alternativeBaseFontSize'));

      $vars[ 'h1FontSize' ]                 = $this->add_px($this->custom_or_default('h1_font_size' , 'h1FontSize'));
      $vars[ 'h2FontSize' ]                 = $this->add_px($this->custom_or_default('h2_font_size' , 'h2FontSize'));
      $vars[ 'h3FontSize' ]                 = $this->add_px($this->custom_or_default('h3_font_size' , 'h3FontSize'));
      $vars[ 'h4FontSize' ]                 = $this->add_px($this->custom_or_default('h4_font_size' , 'h4FontSize'));
      $vars[ 'h5FontSize' ]                 = $this->add_px($this->custom_or_default('h5_font_size' , 'h5FontSize'));
      $vars[ 'h6FontSize' ]                 = $this->add_px($this->custom_or_default('h6_font_size' , 'h6FontSize'));
    
      $vars[ 'textColor' ]                  = $this->custom_or_default('text_color', 'textColor');
      $vars[ 'linkColor' ]                  = $this->custom_or_default('link_color', 'linkColor');
      $vars[ 'headingsColor' ]              = $this->custom_or_default('headings_color', 'headingsColor');

      $vars[ 'dropdownMenuBg' ]             = $this->custom_or_default('menu_dropdown_background_color', 'dropdownMenuBg');

      return $vars;
    }

    // Convert RGBA color to 6 digit HEX
    public function my_rgba_to_hex($rgba_arr){
      return "#".substr(parent::lib_rgbahex($rgba_arr), -6);
    }

    // Mix 2 colors
    public function my_mix($color1, $color2, $percent){
      return $this->my_rgba_to_hex(parent::lib_mix(array("list", ",", array( array("raw_color", $color1),  array("raw_color", $color2), array("number", $percent, "%")))));
    }


    function adjustBrightness($hex, $steps) {
      $hex = str_replace('#','',$hex);
      // Steps should be between -255 and 255. Negative = darker, positive = lighter
      $steps = max(-255, min(255, $steps));

      // Format the hex color string
      $hex = str_replace('#', '', $hex);
      if (strlen($hex) == 3) {
          $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
      }

      // Get decimal values
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));

      // Adjust number of steps and keep it inside 0 to 255
      $r = max(0,min(255,$r + $steps));
      $g = max(0,min(255,$g + $steps));
      $b = max(0,min(255,$b + $steps));

      $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
      $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
      $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
      return '#'.$r_hex.$g_hex.$b_hex;
    }

    function getContrastYIQ($hexcolor, $dark = 'black', $light = 'white'){
      $r = hexdec(substr($hexcolor,0,2));
      $g = hexdec(substr($hexcolor,2,2));
      $b = hexdec(substr($hexcolor,4,2));
      $yiq = (($r*299)+($g*587)+($b*114))/1000;
      return ($yiq >= 128) ? $dark : $light;
    }

    function add_px($value = '14')
    {
      $value = str_replace('px', '', $value);
      $value = $value.'px';
      return $value;
    }

    function custom_or_default($custom_key, $default_key, $default_value = '#aaa'){
      $option_value = osetin_get_field("{$custom_key}", 'option');
      if(!empty($option_value)){
        return $option_value;
      }else{
        if(isset($this->default_vars["{$default_key}"]))
          return $this->default_vars["{$default_key}"];
        else
          return $default_value;
      }
    }

    function custom_or_default_image_url($custom_key, $default_key, $default_value = 'none'){
      $option_value = osetin_get_field("{$custom_key}", 'option');
      if(!empty($option_value)){
        return $this->wrap_in_url($option_value);
      }else{
        if(isset($this->default_vars["{$default_key}"]))
          return $this->default_vars["{$default_key}"];
        else
          return $default_value;
      }
    }

    public function wrap_in_url($value='none')
    {
      if($value == 'none'){
        return 'none';
      }else{
        return 'url('.$value.')';
      }
    }

    function custom_merged_or_default($custom_key, $default_key, $merge_string, $default_value = "4px solid #fff"){
      $option_value = osetin_get_field("{$custom_key}", 'option');
      if(!empty($option_value)){
        return $merge_string.$option_value;
      }else{
        if(isset($this->default_vars["{$default_key}"]))
          return $this->default_vars["{$default_key}"];
        else
          return $default_value;
      }
    }

    function adjust_custom_or_use_default($custom_key, $default_key, $steps){
      $option_value = osetin_get_field("{$custom_key}", 'option');
      if(!empty($option_value)){
        return $this->adjustBrightness($option_value, $steps);
      }else{
        return $this->default_vars["{$default_key}"];
      }
    }

    function adjust_mix_custom_or_use_default($custom_key, $default_key, $steps, $mix_color, $mix_value){
      $option_value = osetin_get_field("{$custom_key}", 'option');
      if(!empty($option_value)){
        $adjusted_color = $this->adjustBrightness($option_value, $steps);
        $mixed_color = $this->my_mix($mix_color, $adjusted_color, $mix_value);
        return $mixed_color;
      }else{
        return $this->default_vars["{$default_key}"];
      }
    }

  }


  // Hook to the ACF and set a variable to recompile a less css if options have been saved
  function my_acf_save_post( $post_id ) {
    // stop function if not "options" page
    if( $post_id != "options" ) {
      return;
    }
    // Set a flag to recompile LESS on the next front end request.
    update_option( 'prefix_force_recompile', 'yes' );
  }
  // run after ACF saves the $_POST['fields'] data
  add_action('acf/save_post', 'my_acf_save_post', 20);

  $my_less = new my_less;
  // pass variables into all .less files
  add_filter( 'less_vars', array($my_less, 'my_less_vars'), 10, 2 );

?>