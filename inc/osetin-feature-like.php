<?php

if (!defined('OSETIN_FEATURE_VOTE_VERSION')) define('OSETIN_FEATURE_VOTE_VERSION', '1.0');

// --------------------------
// VOTING FUNCTIONS BY OSETIN
// --------------------------


function osetin_vote_init(){
  add_action( 'wp_ajax_osetin_vote_process_request', 'osetin_vote_process_request' );
  add_action( 'wp_ajax_nopriv_osetin_vote_process_request', 'osetin_vote_process_request' );
}


function osetin_vote_build_button($post_id, $extra_class = ''){
  $vote_data = osetin_vote_get_post_vote_data($post_id);
  ?>
  <a href="#" class="<?php echo esc_attr($extra_class); ?> osetin-vote-trigger <?php echo ($vote_data['has_voted']) ? 'osetin-vote-has-voted' : 'osetin-vote-not-voted'; ?>" data-has-voted-label="<?php _e('Liked', 'neptune-by-osetin'); ?>" data-not-voted-label="<?php _e('Like', 'neptune-by-osetin'); ?>" data-post-id="<?php echo esc_attr($post_id); ?>" data-vote-action="<?php echo ($vote_data['has_voted']) ? 'unvote' : 'vote'; ?>" data-votes-count="<?php echo esc_attr($vote_data['count']); ?>">
    <span class="slide-button-i">
      <?php if($vote_data['has_voted']){ ?>
        <i class="os-icon os-icon-ui-03"></i>
      <?php }else{ ?>
        <i class="os-icon os-icon-ui-03"></i>
      <?php } ?>
      <span class="slide-button-label osetin-vote-action-label">
        <?php echo ($vote_data['has_voted']) ? esc_html__("Liked", 'neptune-by-osetin') : esc_html__("Like", 'neptune-by-osetin'); ?>
      </span>
      <span class="slide-button-sub-label osetin-vote-count <?php if(!$vote_data['count']) echo 'hidden'; ?>">
        <?php echo esc_html($vote_data['count']);  ?>
      </span>
    </span>
  </a><?php
}

function osetin_vote_process_request(){
  $post_id = $_POST['vote_post_id'];
  $vote_action = $_POST['vote_action'];


  if($post_id && $vote_action){
    switch($vote_action){
      case 'vote':
        echo wp_send_json(array('status' => 200, 'message' => osetin_vote_do_vote($post_id)));
      break;
      case 'unvote':
        echo wp_send_json(array('status' => 200, 'message' => osetin_vote_do_unvote($post_id)));
      break;
      case 'read':
        echo wp_send_json(array('status' => 200, 'message' => osetin_vote_get_post_vote_data($post_id)));
      break;
    }
  }else{
    echo wp_send_json(array('status' => 422, 'message' => 'Invalid data supplied'));
  }
}

// --------------------------
// GET VOTE INFO ABOUT A POST
// --------------------------

function osetin_vote_get_post_vote_data($post_id = false){
  $votes_count = get_post_meta($post_id, '_osetin_vote', true);

  // create a post meta if the field does not exist yet
  if(!$votes_count) add_post_meta($post_id, '_osetin_vote', 0, true);

  $has_voted = osetin_vote_has_voted($post_id);
  $vote_data = array('count' => $votes_count, 'has_voted' => $has_voted);



  return $vote_data;
}






// -------------------------------
// CHECK IF USER HAS ALREADY VOTED
// -------------------------------

function osetin_vote_has_voted($post_id = false){
  return isset($_COOKIE['osetin_vote_'. $post_id]);
}







// ----------
// ADD A VOTE 
// ----------

function osetin_vote_do_vote($post_id = false){
  $vote_data = osetin_vote_get_post_vote_data($post_id);

  // if user has already voted - exit
  if($vote_data['has_voted']) return $vote_data;

  update_post_meta($post_id, '_osetin_vote', $vote_data['count'] + 1);
  $cookie_expire_on = time()+60*60*24*30;
  setcookie('osetin_vote_'. $post_id, $post_id, $cookie_expire_on, '/');




  return osetin_vote_get_post_vote_data($post_id);
}





// -------------
// REMOVE A VOTE 
// -------------

function osetin_vote_do_unvote($post_id = false){
  $vote_data = osetin_vote_get_post_vote_data($post_id);

  // check if user has voted for this post and there are any votes on this post 
  if($vote_data['has_voted'] && ($vote_data['count'] > 0)){

    update_post_meta($post_id, '_osetin_vote', $vote_data['count'] - 1);
    setcookie('osetin_vote_'. $post_id, $post_id, 1, '/');



    return osetin_vote_get_post_vote_data($post_id);

  }else{

    return $vote_data; 

  }

} 


