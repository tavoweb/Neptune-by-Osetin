<?php
// --------------------------
// VOTING FUNCTIONS BY strongVine
// --------------------------
add_action('wp_ajax_my_vote_like', 'sv_user_vote_like');
add_action('wp_ajax_nopriv_my_vote_like', 'sv_user_vote_like');
function sv_user_vote_like() {
  //! $vote i $unvote takje odnovremenno sozdaet v baze dannij metakey s imenami $vote i $unvote, sotvetstvenno
  //sv_user_vote($vote, $unvote)
  sv_user_vote('like','dislike');
}

add_action('wp_ajax_my_vote_dislike', 'sv_user_vote_dislike');
add_action('wp_ajax_nopriv_my_vote_dislike', 'sv_user_vote_dislike');
function sv_user_vote_dislike() 
{
  //!$vote i $unvote takje odnovremenno sozdaet v baze dannij metakey s imenami $vote i $unvote, sotvetstvenno
  //sv_user_vote($vote, $unvote)
  sv_user_vote('dislike','like');
}

function osetin_generate_votes(){
  $nonce = wp_create_nonce("my_vote_nonce");
  $likes_count = get_post_meta(get_the_ID(), "sv_recipe_like", true);
  $likes_count = ($likes_count == "") ? 0 : $likes_count;
  $dislikes_count = get_post_meta(get_the_ID(), "sv_recipe_dislike", true);
  $dislikes_count = ($dislikes_count == "") ? 0 : $dislikes_count;
  $is_liked = isset($_COOKIE['like_'.get_the_ID()]) ? $_COOKIE['like_'.get_the_ID()] : '';
  $is_disliked = isset($_COOKIE['dislike_'.get_the_ID()]) ? $_COOKIE['dislike_'.get_the_ID()] : '';
  
  ?>

    
  <div id = "vote_box" data-voting-in-progress="no">                
    <a class="user_vote_like" data-nonce="<?php echo esc_attr($nonce)?>" data-post_id="<?php echo esc_attr(get_the_ID())?>" href="#" >
      <i class="os-icon os-icon-ui-05"></i>
      <span id="vote_like_counter" data-votes="<?php echo esc_attr($likes_count)?>" data-vote-status="<?php echo esc_attr($is_liked)?>"> <?php echo esc_html($likes_count)?> </span>
    </a>
    <a class="rotated user_vote_dislike" data-nonce="<?php echo esc_attr($nonce)?>" data-post_id="<?php echo esc_attr(get_the_ID())?>" href="#" >
      <i class="os-icon os-icon-ui-06"></i>
      <span id="vote_dislike_counter" data-votes="<?php echo esc_attr($dislikes_count)?>" data-vote-status="<?php echo esc_attr($is_disliked)?>"><?php echo esc_html($dislikes_count)?></span>
    </a>
    <span style="display:none;" id="user_vote_label" data-loading-label=<?php esc_attr_e('Loading…','neptune-by-osetin')?> data-label=<?php esc_attr_e('Vote','neptune-by-osetin')?>><?php esc_html_e('Vote','neptune-by-osetin')?></span>
  </div>
  <?php
}

// $vote i $unvote takje odnovremenno sozdaet v baze dannij metakey s imenami $vote i $unvote, sotvetstvenno
function sv_user_vote($vote, $unvote){

  //proveraen prishli li dannie on sgenerirovanoi serverom stranitsi ili c levogo mesta
  if ( !wp_verify_nonce( $_POST['nonce'], "my_vote_nonce")) 
  {
    exit("No naughty business please");
  } 
  
  // tut doljna bit' proverka na dopustimost' imen $vote i $unvote
  //...

  //esli user otmenaet svoe mnenie
  if(isset($_COOKIE[$vote.'_'.$_POST['post_id']]))
  {
    $result[$vote] = update_post_meta_unvote('sv_recipe_'.$vote);  
    if($result[$vote] === false) // ecli ne udalos' sdelat' update bazedannih (voitUP - 1)
    {
      $result['type'] = 'error';
    }
    else //esli baza udachno obnovilas' i golos udalen iz bazi
    {      
      setcookie($vote.'_'.$_POST['post_id'], 'voted', time()-3100, '/'); //udalaet informatsiu o vote UP iz kukov
      $result['type'] = 'success';    
    }     
  }
  else // esli user stavit svoe mnenie pervii raz, ili menaet ego 
  {    
    if(isset($_COOKIE[$unvote.'_'.$_POST['post_id']])) // esli user menaet svoe mnenie na protivopoloznoe to ono udalaetsa iz bazi
    {
      $result[$unvote] = update_post_meta_unvote('sv_recipe_'.$unvote); // udalenie protivopolojnogo mnenia iz bazi
      setcookie($unvote.'_'.$_POST['post_id'], 'voted', time()-3100, '/'); //udalaet informatsiu o dislaikah
    }
    $result[$vote] = update_post_meta_vote('sv_recipe_'.$vote); //dobavlenie novogo mnenia v basu
    if($result[$vote] === false) // esli vote ne proshol v baze
    {
      $result['type'] = 'error';
    }
    else 
    {
      //tut nejno uveli4it' vrema jizni kukov
      setcookie($vote.'_'.$_POST['post_id'], 'voted', time()+60*60*24*30, '/'); //ustanavlivautsa kuki voteUP, esli update bazi bil uda4nim
      $result['type'] = 'success';
    }
  }
  $result = json_encode($result);
  echo $result;
  wp_die();  
}

function update_post_meta_vote($meta_field){
  $vote_count = get_post_meta($_POST['post_id'], $meta_field, true);
  $vote_count = ($vote_count == '') ? 0 : $vote_count;
  $vote_count++;
  $vote = update_post_meta($_POST['post_id'], $meta_field, $vote_count);
  if($vote === false)
    return $vote; //
  return $vote_count;

}
function update_post_meta_unvote($meta_field){  
  $vote_count = get_post_meta($_POST['post_id'], $meta_field, true);
  $vote_count = ($vote_count == '') ? 0 : $vote_count;
  if($vote_count == 0)      //
    return $vote_count;    
  $vote_count--;
  $vote = update_post_meta($_POST['post_id'], $meta_field, $vote_count);
  if($vote === false)
    return $vote; //
  return $vote_count;
}
?>