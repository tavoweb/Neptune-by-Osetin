<?php
/**
 * Template Name: Recipe Publisher
 *
 */
?>
<?php 

function osetin_can_user_post_recipe(){
  $return = false;
  if ( ( is_user_logged_in() && current_user_can('read') ) ) {
    $return = true;
  }
  return $return;
} 

// function to filter every field that gets submitted, but before that check if its array or not
function osetin_acf_wp_kses_post($data) {
  if (!is_array($data)) {
    return wp_kses_post($data);
  }
  $return = array();
  foreach ($data as $index => $value) {
    $return[$index] = osetin_acf_wp_kses_post($value);
  }
  return $return;
}

// allow contributor users upload media
if ( current_user_can('contributor') && !current_user_can('upload_files') ){ 
  add_action('get_header', 'allow_contributor_uploads', 1);
}

function allow_contributor_uploads() {
     $contributor = get_role('contributor');
     $contributor->add_cap('upload_files');
}


// allow contributor users edit categories (in order for them to add ingredients)
if ( current_user_can('contributor') && !current_user_can('manage_categories') ){ 
  add_action('get_header', 'allow_contributor_category_editing', 1);
}

function allow_contributor_category_editing() {
     $contributor = get_role('contributor');
     $contributor->add_cap('manage_categories');
}

$new_recipe = true;
$user_allowed_to_edit_this_post = true;
if(osetin_can_user_post_recipe()){
  $new_post = array(
    'id' => 'frontend-publisher',
    'post_id'   => 'new_post',
    'post_title'  => true,
    'post_content'  => true,
    'submit_value' => __('Submit Recipe', 'neptune-by-osetin'),
    'updated_message' => __("Your recipe has been submitted. We will review it and publish it shortly. In the meantime you can either publish another one using a form below or just continue browsing our website.", 'neptune-by-osetin'),
    'new_post'    => array(
      'post_type'   => 'osetin_recipe',
      'post_status' => 'pending'
    )
  );

  if(get_query_var('recipe_id_to_edit')){
    // EDITING RECIPE
    $new_recipe = false;
    if(!current_user_can('edit_post', get_query_var('recipe_id_to_edit'))){
      $user_allowed_to_edit_this_post = false;
    }
    $new_post['post_id'] = get_query_var('recipe_id_to_edit');
    $new_post['new_post'] = false;
    $new_post['post_status'] = 'pending';
    $new_post['submit_value'] = __('Update Recipe', 'neptune-by-osetin');
    $new_post['updated_message'] = __("Your recipe has been updated. We will review it and publish it shortly. In the meantime you can either submit/edit another one using a form below or just continue browsing our website.", 'neptune-by-osetin');
  }
  //* Add required acf_form_head() function to head of page
  add_action( 'get_header', 'osetin_do_acf_form_head', 1 );
  function osetin_do_acf_form_head() {
    add_filter('acf/update_value', 'osetin_acf_wp_kses_post', 10, 1);
    acf_form_head();
  }
}

function tsm_deregister_admin_styles() {

  if ( ! ( is_user_logged_in() || current_user_can('publish_posts') ) ) {
    return;
  }
  wp_deregister_style( 'wp-admin' );
}


  get_header();

  ?>

  <?php while ( have_posts() ) : the_post(); ?>
  
  <div class="os-container top-bar-w">
    <div class="top-bar <?php if(!has_post_thumbnail()) echo 'bordered'; ?>">
      <?php osetin_output_breadcrumbs(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
  </div>

  <?php  
  if(osetin_is_imaged_header(get_the_ID())){
    if(osetin_is_bbpress()){
      $page_bg_image_url = get_template_directory_uri().'/assets/img/patterns/flowers_light.jpg';
    }else{
      $page_bg_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
    } ?>
    <div class="os-container">
      <div class="page-intro-header with-background" style="<?php echo osetin_get_css_prop('background-image', $page_bg_image_url, false, 'background-repeat: repeat; background-position: top left; '); ?>">
        <h2><?php echo osetin_get_the_title(get_the_ID()); ?></h2>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="os-container">
    
    <div class="page-w <?php if ( osetin_is_active_sidebar( 'sidebar-index' ) ) echo 'with-sidebar sidebar-location-right'; ?>">
      <div class="page-content">
        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php 
          if(osetin_is_regular_header(get_the_ID())){
            echo '<h1 class="page-title">'.osetin_get_the_title(get_the_ID()).'</h1>';
          }
          ?>
          <?php 
          the_content();
          if ($user_allowed_to_edit_this_post){
            if ( osetin_can_user_post_recipe() ) {
              echo '<div class="frontend-publisher-w">';
              if($new_recipe){
                echo '<h2 class="form-header">'.__('Submit your recipe', 'neptune-by-osetin').'</h2>';
              }else{
                echo '<h2 class="form-header">'.__('Edit your recipe', 'neptune-by-osetin').'</h2>';
              }
              include_once(ABSPATH.'wp-admin/includes/plugin.php');
              acf_form($new_post);
              echo '</div>';
            }else{
              if(is_user_logged_in()){
                echo '<h3>'.__('You do not have required permissions to submit recipes', 'neptune-by-osetin').'</h3>';
              }else{
                echo do_shortcode('[userpro template=login login_heading="'.__('Login to submit your recipe', 'neptune-by-osetin').'"]');
              }
            }
          }else{
            echo '<h3>'.__('You do not have required permissions to edit this recipe.', 'neptune-by-osetin').'</h3>';
          }
          ?>
        </article>
      </div>

      <?php if ( osetin_is_active_sidebar( 'sidebar-index' ) ) { ?>

        <div class="page-sidebar">
        
          <?php dynamic_sidebar( 'sidebar-index' ); ?>

        </div>
          
      <?php } ?>
    </div>
  </div>

  <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>