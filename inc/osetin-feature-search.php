<?php

if (!defined('OSETIN_FEATURE_SEARCH_VERSION')) define('OSETIN_FEATURE_SEARCH_VERSION', '1.0');

// --------------------------
// VOTING FUNCTIONS BY OSETIN
// --------------------------


function osetin_search_init(){
  add_action( 'wp_ajax_osetin_search_process_request', 'osetin_search_process_request' );
  add_action( 'wp_ajax_nopriv_osetin_search_process_request', 'osetin_search_process_request' );
}


function osetin_search_process_request(){
  if(isset($_POST['search_ingredient_ids'])){

    if(isset($_POST['paged']) && is_numeric($_POST['paged'])){
      $paged = $_POST['paged'];
    }else{
      $paged = 1;
    }
    $args = array(  
    'post_status' => 'publish',
    'post_type' => 'osetin_recipe',
    'posts_per_page' => 1,
    'paged' => $paged,
    'tax_query' => array(
        array(
           'taxonomy' => 'recipe_ingredient',
           'field' => 'term_id',
           'terms' => $_POST['search_ingredient_ids'],
           'operator' => 'IN'
        )
     ));
    $osetin_recipes_query = new WP_Query( $args );

    $response_html = '';
    

    if($paged == 1){
      $response_html = '<div class="archive-posts-w">
                          <div class="archive-posts masonry-grid-w per-row-4">
                            <div class="masonry-grid" data-layout-mode="fitRows">';
    }

    $layout_type_for_index = 'masonry_4';
    $limit = 20;
    $current_step_class = 'full_full';
    
    while ( $osetin_recipes_query->have_posts() ) : $osetin_recipes_query->the_post();
      $response_html.= '<div class="masonry-item any fourth">';
        ob_start();
        include(locate_template('content.php'));
        $response_html.= ob_get_clean();
      $response_html.= '</div>';
    endwhile;

    $is_last_page = ($osetin_recipes_query->max_num_pages > $paged) ? 'no' : 'yes';

    if($paged == 1){
      $paged = 2;
      $response_html.=      '</div>
                          </div>
                          <div class="load-more-infinite" data-label-loading="'.esc_attr__('Searching...', 'neptune-by-osetin').'" data-paged="'.$paged.'"><span>'.__('Load More', 'neptune-by-osetin').'</span></div>
                        </div>';
    }

    echo wp_send_json(array('status' => 200, 'message' => $response_html, 'is_last_page' => $is_last_page));

  }else{
    echo wp_send_json(array('status' => 422, 'message' => esc_html__('Invalid data supplied', 'neptune-by-osetin')));
  }
  exit();  
}
