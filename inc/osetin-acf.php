<?php
  class osetin_acf {

    private $default_vars = array();

    function __construct() {
      $this->load_defaults();
    }

    public function load_defaults()
    {
      $acf_vars = array();
      // load default color scheme settings
      require_once( get_template_directory() . "/inc/default-acf-vars.php");
      $this->default_vars = $acf_vars;
    }

    public function get_default_var($field_name)
    {
      if(isset($this->default_vars[$field_name])){
        return $this->default_vars[$field_name];
      }else{
        return '';
      }
    }
  }

  $my_osetin_acf = new osetin_acf();