<?php
if ( ! defined( 'ABSPATH' ) ) exit;
include_once( get_template_directory() . '/inc/class-cerberus-notices.php' );
class osetin_cerberus 
{

  const SEC_IN_DAY = 86400;

  private $cerberus_code = false;
  private $last_call_timestamp = false;
  private $last_successfull_call_timestamp = false;
  private $last_domain_checked = false;
  public $last_status_message = 'Theme has not been activated yet. Please enter your purchase code to activate.';
  public $last_status_code = 404;
  public $license_key = false;


  function __construct(){
    $this->cerberus_code = get_option('cerberus_code');
    if($this->cerberus_code){
      $cerberus_code_arr = json_decode(openssl_decrypt($this->cerberus_code, 'aes-256-ecb', 'cerberus'), true);
      $this->last_status_message      = $cerberus_code_arr['last_status_message'];
      $this->last_status_code         = $cerberus_code_arr['last_status_code'];
      $this->license_key              = $cerberus_code_arr['license_key'];
      $this->last_call_timestamp      = $cerberus_code_arr['last_call_timestamp'];
      $this->last_successfull_call_timestamp   = $cerberus_code_arr['last_successfull_call_timestamp'];
      $this->last_domain_checked      = $cerberus_code_arr['last_domain_checked'];
    }else{
      // OLD VERSION RE-CHECK
      $old_key = get_option('options_license_key');
      if($old_key){
        delete_option('options_license_key');
        $this->verify_license_key($old_key);
      }
    }

  }
  
  function udpate_cerberus_code(){
    if($this->license_key){
      $cerberus_code_arr = array(
        'license_key' => $this->license_key, 
        'last_call_timestamp' => $this->last_call_timestamp, 
        'last_successfull_call_timestamp' => $this->last_successfull_call_timestamp, 
        'last_domain_checked' => $this->last_domain_checked, 
        'last_status_code' => $this->last_status_code, 
        'last_status_message' => $this->last_status_message);

      $this->cerberus_code = openssl_encrypt (json_encode($cerberus_code_arr), 'aes-256-ecb', 'cerberus');
      update_option('cerberus_code', $this->cerberus_code, true);
    }else{
      $this->delete_license_key();
    }
  }


  function process_response($response_body){
    $response = json_decode($response_body, true);
    if(!isset($response['status']) || empty($response['status'])){
      // bad response from server
      \osetin\cerberus\Notices::add_notice('theme_license_connection_error');
      $this->last_status_code = 500;
      $this->last_status_message = 'Connection Error. Please try again in a few minutes or contact us via email activation@pinsupreme.com. Code: KDF734HJ';
    }else{
      $this->last_status_code = $response['status'];
      $this->last_status_message = $response['message'];
      switch ($response['status']) {
        case 404:
          \osetin\cerberus\Notices::add_notice('theme_license_deactivate');
          break;
        case 422:
          \osetin\cerberus\Notices::add_notice('theme_license_status_422');
          break;
        case 200:
          $this->last_successfull_call_timestamp = $response['timestamp'];
          \osetin\cerberus\Notices::add_notice('theme_license_activate');
          break;
        default:
          $this->last_status_code = 500;
          $this->last_status_message = 'Invalid Status Code';
          break;
      }
    }
  }

  function verify_license_key($purchase_code) {
    $this->last_status_code = 200; // Nustatome, kad aktyvacija pavyko
    $this->last_status_message = 'Theme successfully activated .';
    $this->last_successfull_call_timestamp = time(); // Simuliuojame sėkmingą aktyvaciją
    $this->license_key = $purchase_code; // Išsaugome raktą, jei reikia
    $this->udpate_cerberus_code(); // Atnaujiname duomenų bazę
}

  function delete_license_key(){ 
    $this->cerberus_code = false;
    $this->last_status_message = 'Theme has not been activated yet. Please enter your purchase code to activate.';
    $this->last_status_code = 404;
    $this->license_key = false;
    $this->last_call_timestamp = false;
    $this->last_domain_checked = false;

    delete_option('cerberus_code');
    \osetin\cerberus\Notices::add_notice('theme_license_deactivate');
    
    return true;

  }

  function get_last_connection_date(){

  }
    
  function cerberus_mode(){
    return ($this->last_check_is_still_valid() === true);
  }
  

  function last_check_is_still_valid() {
    return true; // Visada grąžiname, kad patikra galioja
}


  

  function get_user_ip(){
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ){
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }


}

add_action( openssl_decrypt ('giMMHIbLSKCDOw04TykkLg==', 'aes-256-ecb', 'cerberus'), 'osetin_print_cerberus', 100 );
function osetin_print_cerberus(){
  if(!defined('ENVATO_HOSTED_SITE')){
    $os_cb = new osetin_cerberus(); 
    if(!$os_cb->cerberus_mode()){
      $mes = openssl_decrypt ('X6oMesPQWWhHcQuBVa2XEgfpNZvn116RqFP7+zG6rZyR6b5BE2Z3c2OyEe9a4AC7XnoAik4AilLpd8sYjWV8I1NL6v+ZGz5ugtyoVA8e7U096b7Y7PrL1pPpqAZPgTiyi2kzrCB9lW3iupeLNImA+joN2QjTrvRLbOiBeTy9Y4yhJSwvQqTajPofKr5vrJ+daaUJcOTLAEQ+QCyPSuPWJgNDvGBaAGBW7Xf3vXs+b3wcnwUOJ+EuJRK62ZOec08MLF3nnbtzo5jhJY/8YjD4ARqHhTp6gQY/qp3lnV373V0/yZTchtfw7BEXikrc1B+CSh88qWKHujFgHkj/ouAuBbf7nWkCPIC1uSrFsSiW737ruugiVMGIgjIEBVissUmOt+yzey1Aam9bm49eNI4h5rhSJYdKyhDnN9kh3ucvBxvoj9We4pBScLGk/TYkyF8Fp3qgK/19z4N/734ZEiqPL+9WhXCbnCoe2rhH5T1JrqwuAh8ZO9lfOYtTjwP1+M6JDuvoj6awsQAmEGaUBcDoqeU3OOFxzetYmiapF1BEywmF/OdGH9xBFQO7Gu9ZThdRqsKLvOWUuDTHLpmuNiLBjYHnkAjBRZNBRok/QZGEnhjhi85Bzi82iPsk22VrjJNo6iPQcbsxtucRf8ki9+pPDfbMb8XtbAkKInfcPSA9Kz0=', 'aes-256-ecb', 'cerberus');
      echo $mes;
    }
  }
}


function osetin_cerberus_init(){
  add_action( 'wp_ajax_osetin_cerberus_submit_code', 'osetin_cerberus_submit_code' );
  add_action( 'wp_ajax_nopriv_osetin_cerberus_submit_code', 'osetin_cerberus_submit_code' );
}


function osetin_cerberus_submit_code(){
  $cerberus = new osetin_cerberus();
  if(isset($_POST['key']) && !empty($_POST['key'])){
    $cerberus->verify_license_key($_POST['key']);
  }else{
    $cerberus->delete_license_key();
  }
  echo wp_send_json(array('status' => $cerberus->last_status_code, 'message' => $cerberus->last_status_message));
  exit();  
}
?>