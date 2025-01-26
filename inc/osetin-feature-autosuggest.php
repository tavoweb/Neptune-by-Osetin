<?php

if (!defined('OSETIN_FEATURE_AUTOSUGGEST_VERSION')) define('OSETIN_FEATURE_AUTOSUGGEST_VERSION', '1.0');

// --------------------------
// VOTING FUNCTIONS BY OSETIN
// --------------------------


function osetin_autosuggest_init(){
  add_action( 'wp_ajax_osetin_autosuggest_process_request', 'osetin_autosuggest_process_request' );
  add_action( 'wp_ajax_nopriv_osetin_autosuggest_process_request', 'osetin_autosuggest_process_request' );
}


function osetin_autosuggest_process_request(){
  if(isset($_POST['search_query_string'])){

    $args = array(  
    'post_status' => 'publish',
    'post_type' => array('osetin_recipe', 'post'),
    's' => $_POST['search_query_string']);

    $response_html = '';

    $osetin_recipes_query = new WP_Query( $args );
    if ( $osetin_recipes_query->have_posts() ) {
      $response_html.= '<div class="autosuggest-items-shadow"></div><div class="autosuggest-items">';
      while ( $osetin_recipes_query->have_posts() ) : $osetin_recipes_query->the_post();
        $response_html.= '<a href="'.get_the_permalink().'" class="autosuggest-item">';
          $response_html.= '<div class="autosuggest-item-media-w">';
            $response_html.= '<div class="autosuggest-item-media-thumbnail fader-activator" style="background-image:url('.osetin_output_post_thumbnail_url("small", false).'); background-size: cover;">';
            $response_html.= '<span class="image-fader"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>';
            $response_html.= '</div>';
          $response_html.= '</div>';
          $response_html.= '<h3 class="autosuggest-item-title">'.get_the_title().'</h3>';
        $response_html.= '</a>';
      endwhile;
      $response_html.= '</div>';
    }
    
    if($response_html){
      echo wp_send_json(array('status' => 200, 'message' => $response_html));
    }else{
      echo wp_send_json(array('status' => 404, 'message' => esc_html__('No recipes found', 'neptune-by-osetin')));
    }

  }else{
    echo wp_send_json(array('status' => 422, 'message' => esc_html__('Invalid data supplied', 'neptune-by-osetin')));
  }
}
