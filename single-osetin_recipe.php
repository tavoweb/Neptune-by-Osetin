<?php
/**
 * The template for displaying all single posts.
 *
 * @package _s
 */

if ( is_user_logged_in() ) {
  //* Add required acf_form_head() function to head of page
  add_action( 'get_header', 'osetin_do_acf_form_head', 1 );
  function osetin_do_acf_form_head() {
    add_filter('acf/update_value', 'wp_kses_post', 10, 1);
    acf_form_head();
  }
}
get_header(); ?>
  <?php while ( have_posts() ) : the_post(); 
  $recipe_sub_title = osetin_get_field('sub_title');
  $layout_type_for_recipe = osetin_get_settings_field('layout_type_for_single_recipe', 'half_left_image');
  $big_header_titled_image = false;
  $recipe_cooking_time = osetin_get_field('recipe_cooking_time');
  $recipe_serves = osetin_get_field('recipe_serves');
  $recipe_difficulty_string = osetin_get_difficulty_string(osetin_get_field('recipe_difficulty'));
  $cooking_temperature = osetin_get_field('recipe_cooking_temperature');
  $quick_description = osetin_get_field('quick_description');
  $details_position = osetin_get_settings_field('recipe_details_position', 'split');
  $reviews_info = osetin_recipe_rating_average_and_total(get_the_ID()); 
  $previous_post = get_previous_post();
  $next_post = get_next_post();

  if($layout_type_for_recipe == 'big_image_titled'){
    $custom_image_for_header = osetin_get_field('custom_image_for_header', false, false, true);
    if(is_array($custom_image_for_header)){
      $big_header_titled_image = $custom_image_for_header['sizes']['osetin-full-width'];
    }else{
      $big_header_titled_image = osetin_output_post_thumbnail_url('osetin-full-width');
    }
  }
  osetin_show_featured_recipes_slider(); ?>
  <div class="os-container top-bar-w">
    <div class="top-bar">
      <?php osetin_output_breadcrumbs(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
  </div>
  <?php if($big_header_titled_image){ ?>
  <div class="os-container">
    <div class="recipe-big-titled-header-w">
      <div class="recipe-big-titled-header-box">
        <div class="recipe-big-titled-header-image" data-original-height="500" style="height: 500px;<?php echo osetin_get_css_prop('background-image', $big_header_titled_image, false, 'background-repeat: repeat; background-position: center center; background-size: cover;'); ?>">
          <div class="recipe-big-titled-header-fader"></div>
          <h1>
            <div><?php the_title(); ?></div>
            <?php 
            if($recipe_sub_title){ 
              echo '<div class="recipe-header-image-sub-title">'.$recipe_sub_title.'</div>'; 
            } ?>
          </h1>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
  <div class="recipe-floating-box">
    <div class="cooking-mode-btn-w">
      <a href="#" class="cooking-mode-toggler cooking-mode-btn"><i class="os-icon os-icon-ui-34"></i> <span><?php esc_html_e('Start Reading Mode', 'neptune-by-osetin'); ?></span></a>
    </div>
    <div class="print-share-recipe-btn-w">
      <a href="#" class="print-recipe-btn">
        <i class="os-icon os-icon-tech-11"></i> 
        <span><?php esc_html_e('Print', 'neptune-by-osetin'); ?></span>
      </a>
      <a href="#" class="share-recipe-btn">
        <i class="os-icon os-icon-ui-35"></i> 
        <span><?php esc_html_e('Share', 'neptune-by-osetin'); ?></span>
      </a>
    </div>
    <div class="timer-w">
      <i class="os-icon os-icon-clock2"></i>
      <div class="timer-counter"></div>
      <button id="pause-resume-timer-btn" data-label-resume="<?php esc_attr_e('Resume', 'neptune-by-osetin'); ?>" data-label-pause="<?php esc_attr_e('Pause', 'neptune-by-osetin'); ?>"><?php esc_html_e('Pause', 'neptune-by-osetin'); ?></button>
      <input id="timer-minutes" name="timer-minutes" type="text" value="10">
      <label id="timer-minutes-label" for="timer-minutes"><?php esc_html_e('min', 'neptune-by-osetin'); ?></label>
      <button id="stop-timer-btn" data-label-stop="<?php esc_attr_e('Stop', 'neptune-by-osetin'); ?>" data-label-start="<?php esc_attr_e('Start Timer', 'neptune-by-osetin'); ?>"><?php esc_html_e('Stop', 'neptune-by-osetin'); ?></button>
      <button id="start-timer-btn"><?php esc_html_e('Start Timer', 'neptune-by-osetin'); ?></button>
      <audio id="timer-alarm-media" preload>
        <source src="<?php echo get_template_directory_uri(); ?>/assets/audio/ticktac.mp3" type="audio/mpeg" />
        <source src="<?php echo get_template_directory_uri(); ?>/assets/audio/ticktac.ogg" type="audio/ogg" />
      </audio>
    </div>
    <div class="thumbs-votes-w">
      <?php osetin_generate_votes(); ?>
    </div>
    <?php if(class_exists('osetinMealPlanner')){
      echo '<div class="add-to-meal-plan-btn-w">'. do_shortcode('[os_add_to_meal_plan]') . '</div>';
      } ?>
  </div>
    <?php if($layout_type_for_recipe == 'big_image'){ ?>
      <div class="os-container single-big-media">
        <div class="single-main-media">
          <?php echo output_recipe_media(get_the_ID(), 'osetin-full-width'); ?>
        </div>
      </div>
    <?php } ?>
  <?php if($details_position == 'single'){ ?>
    <div class="os-container big-meta-box-w">
      <div class="big-meta-box">
        <ul>
          <li class="single-meta-share">
            <a href="#" class="trigger-share-recipe-lightbox">
              <i class="os-icon os-icon-ui-35"></i>
              <span><?php esc_html_e('Share', 'neptune-by-osetin'); ?></span>
            </a>
          </li>
          <?php if(function_exists('echo_tptn_post_count')){ 
            echo '<li class="single-meta-views">';
            echo '<span>'. do_shortcode('[tptn_views daily="0"]'). ' ' . esc_html__('Views', 'neptune-by-osetin') .'</span>';
            
            echo '</li>'; ?>
          <?php } ?>
          <li class="single-meta-likes">
            <?php osetin_vote_build_button($post->ID, 'slide-button slide-like-button'); ?>
          </li>
          <?php if($recipe_cooking_time){ ?><li class="single-meta-cooking-time tooltip-trigger" data-tooltip-header="<?php echo esc_attr(sprintf( __( 'Cooking Time: %s', 'neptune-by-osetin' ), $recipe_cooking_time )); ?>"><i class="os-icon os-icon-thin-clock-busy"></i> <span><?php echo $recipe_cooking_time; ?></span></li><?php } ?>
          <?php if($recipe_serves){ ?><li class="single-meta-serves"><i class="os-icon os-icon-thin-serve"></i> <span><?php printf( __( 'Serves %d', 'neptune-by-osetin' ), $recipe_serves ); ?></span></li><?php } ?>
          <?php if($recipe_difficulty_string){ ?><li class="single-meta-difficulty tooltip-trigger" data-tooltip-header="<?php echo esc_attr(sprintf( __( 'Recipe Difficulty: %s', 'neptune-by-osetin' ), $recipe_difficulty_string )); ?>"><i class="os-icon os-icon-thin-cook"></i> <span><?php echo $recipe_difficulty_string; ?></span></li><?php } ?>
          <?php if($cooking_temperature){ ?><li class="single-meta-temperature tooltip-trigger" data-tooltip-header="<?php echo esc_attr(sprintf( __( 'Cooking Temperature: %s', 'neptune-by-osetin' ), $cooking_temperature )); ?>"><i class="os-icon os-icon-thin-temperature"></i> <span><?php echo $cooking_temperature; ?></span></li><?php } ?>
          <?php if($reviews_info){
                  echo '<li class="single-meta-rating"><a href="#osetinRecipeReviews">';
                  if($reviews_info->avg_rating && $reviews_info->total_reviews) {
                    $stars_html = osetin_build_stars($reviews_info->avg_rating);
                    echo $stars_html.'<span class="single-meta-total-reviews">('.$reviews_info->total_reviews.')</span>';
                  }
                  echo '</a></li>';
                } ?>
        </ul>
      </div>
    </div>
    <?php } ?>
    <div class="single-panel os-container">
      <div class="single-panel-details <?php if(osetin_get_field('do_not_move_single_sidebar', 'option') != true) echo 'move-on-scroll'; ?>">
        <div class="single-panel-details-i">
          <?php if(in_array($layout_type_for_recipe, array('half_left_image', 'big_image_titled'))){ ?>
            <div class="single-main-media">
              <?php echo output_recipe_media(get_the_ID(), 'large'); ?>
            </div>
          <?php } ?>
          <?php if($details_position == 'split'){ ?>
            <div class="side-meta-box">
              <ul>
              <li class="single-meta-share">
                <a href="#" class="post-control-share">
                  <i class="os-icon os-icon-ui-35"></i>
                  <span><?php esc_html_e('Share', 'neptune-by-osetin'); ?></span>
                </a>
              </li>
              <?php if(function_exists('echo_tptn_post_count')){ 
                echo '<li class="single-meta-views">';
                echo '<span>'. do_shortcode('[tptn_views daily="0"]'). ' ' . esc_html__('Views', 'neptune-by-osetin') .'</span>';
                
                echo '</li>'; ?>
              <?php } ?>
              <li class="single-meta-likes">
                <?php osetin_vote_build_button($post->ID, 'slide-button slide-like-button'); ?>
              </li>
              </ul>
            </div>
          <?php } ?>
          <?php osetin_post_share_box(); ?>
          <div class="single-recipe-ingredients-nutritions">
            <?php 
            // check if ingredients exist
            if( osetin_have_rows('ingredients') ){ ?>
              <?php $searchable_ingredients = osetin_get_field('searchable_ingredients'); ?>
              <?php $show_ingredients_on_mobile = osetin_get_field('ingredients_open_on_mobile', 'option'); ?>

              <div class="single-ingredients <?php echo ($show_ingredients_on_mobile) ? 'visible-on-mobile' : 'hidden-on-mobile'; ?>">
                <div class="close-btn"><i class="os-icon os-icon-plus"></i></div>
                <h3><i class="os-icon os-icon-thin-paper-holes-text"></i> <?php esc_html_e('Ingredients', 'neptune-by-osetin'); ?></h3>
  
                <?php if(osetin_get_field('recipe_serves')){ ?>
                  <div class="ingredient-serves">
                    <div class="ingredient-serves-label"><?php esc_html_e('Adjust Servings:', 'neptune-by-osetin'); ?></div>
                    <div class="servings-adjuster-control">
                      <div class="ingredient-serves-decr"><i class="os-icon os-icon-basic2-273_remove_delete_minus"></i></div>
                      <input class="ingredient-serves-num" type="text" data-initial-service-num="<?php echo get_field( 'recipe_serves' ); ?>" data-current-serves-num="<?php echo get_field( 'recipe_serves' ); ?>" value="<?php echo get_field( 'recipe_serves' );?>" />
                      <div class="ingredient-serves-incr"><i class="os-icon os-icon-basic2-272_add_new_plus"></i></div>
                    </div>
                  </div>
                <?php } ?>
                <table class="ingredients-table">
                  <?php
                  // loop through the rows of ingredients
                  while ( osetin_have_rows('ingredients') ) { 
                    the_row();

                    ?>
                    <tr>
                      <?php if(get_sub_field('separator')){ ?>
                        <td></td>
                        <td><div class="ingredient-heading"><?php the_sub_field('ingredient_name'); ?></div></td>
                      <?php }else{ ?>
                        <td class="ingredient-action">
                          <span class="ingredient-mark-icon"><i class="os-icon os-icon-circle-o"></i></span>
                        </td>
                        <td>
                          <span class="ingredient-amount" data-initial-amount="<?php the_sub_field('ingredient_amount'); ?>"><?php the_sub_field('ingredient_amount'); ?></span> 
                          <?php 
                          if($searchable_ingredients){ ?>
                            <?php 
                            $ingredient_obj = get_sub_field('ingredient_obj');
                            $ingredient_link_html = get_sub_field('ingredient_name');

                            if($ingredient_obj){
                              $ingredient_link_html = '<a href="'.get_term_link($ingredient_obj).'" target="_blank">'.$ingredient_obj->name.'</a>';
                            }
                            if(get_sub_field('custom_link')){
                              $ingredient_link_html = '<a href="'.get_sub_field('custom_link').'" target="_blank">'.$ingredient_obj->name.'</a>';
                            }
                            if($ingredient_obj || $ingredient_link_html){ ?>
                              <span class="ingredient-name"><?php echo $ingredient_link_html; ?></span>
                            <?php } ?>
                            <?php 
                          }else{ ?>
                            <?php 
                            if(get_sub_field('custom_link')){
                              echo '<span class="ingredient-name"><a href="'.get_sub_field('custom_link').'" target="_blank">'.get_sub_field('ingredient_name').'</a></span>';
                            }else{
                              echo '<span class="ingredient-name">'.get_sub_field('ingredient_name').'</span>';
                            } ?>
                            <?php 
                          } ?>

                          <?php 
                          if(get_sub_field('ingredient_note')){ 
                            echo '<span class="ingredient-info-icon"><i class="os-icon os-icon-info-round"></i><span class="ingredient-info-popup">'.esc_attr(get_sub_field('ingredient_note')).'</span></span>';
                          } ?>
                        </td>
                      <?php } ?>
                    </tr>
                    <?php
                  }
                  ?>
                </table>
              </div>
              <?php
            } ?>

            <?php 
            // check if ingredients exist
            if( osetin_have_rows('nutritions') ){ ?>
              <?php $show_nutritions_on_mobile = osetin_get_field('nutritions_open_on_mobile', 'option'); ?>

              <div class="single-nutritions <?php echo ($show_nutritions_on_mobile) ? 'visible-on-mobile' : 'hidden-on-mobile'; ?>">
                <div class="close-btn"><i class="os-icon os-icon-plus"></i></div>
                <h3><i class="os-icon os-icon-thin-info"></i> <?php esc_html_e('Nutritional information', 'neptune-by-osetin'); ?></h3>
                <div class="single-nutritions-list">
                  <?php
                  // loop through the rows of nutritions
                  while ( osetin_have_rows('nutritions') ) { 
                    the_row(); ?>
                    <div class="single-nutrition">
                      <div class="single-nutrition-value"><?php the_sub_field('nutrition_value'); ?></div>
                      <div class="single-nutrition-name"><?php the_sub_field('nutrition_name'); ?></div>
                    </div>
                  <?php 
                  } ?>
                </div>
              </div>
            <?php 
            } ?>
          </div>
          <?php 
          if((osetin_get_field('allow_bookmarks', 'option') != 'no') && osetin_is_userpro_installed()){ ?>
            <div class="single-recipe-bookmark-box">
              <div class="close-btn"><i class="os-icon os-icon-plus"></i></div>
              <h3><i class="os-icon os-icon-thin-book-bookmarked"></i> <?php esc_html_e('Bookmark this recipe', 'neptune-by-osetin'); ?></h3>
              <?php echo do_shortcode('[userpro_bookmark width="100%"]'); ?>
            </div>
          <?php } ?>
          <div class="sidebar-single-w">
            <?php if ( is_active_sidebar( 'sidebar-single' ) ) { ?>
              <?php dynamic_sidebar( 'sidebar-single' ); ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="single-panel-main">

        <?php if($layout_type_for_recipe == 'half_right_image'){ ?>
          <div class="single-main-media">
            <?php echo output_recipe_media(get_the_ID(), 'osetin-full-width'); ?>
          </div>
        <?php } ?>
        <?php if(!$big_header_titled_image){ ?>
          <div class="single-title">
            <h1><?php the_title(); ?></h1>
            <?php 
            if($recipe_sub_title){ 
              echo '<h3>'.$recipe_sub_title.'</h3>'; 
            } ?>
          </div>
        <?php } ?>

        <?php 
          $this_recipe_features_with_icons = osetin_get_recipe_features_with_icons(get_the_ID());
          $this_recipe_cuisines_with_icons = osetin_get_recipe_cuisines_with_icons(get_the_ID());
          $recipe_cuisine_and_features_html = '';
          if((is_array($this_recipe_features_with_icons) || is_object($this_recipe_features_with_icons)) || (is_array($this_recipe_cuisines_with_icons) || is_object($this_recipe_cuisines_with_icons))){
            $recipe_cuisine_and_features_html.= '<div class="single-recipe-custom-taxonomies position-'.$details_position.'">';

              // FEATURES
              if (is_array($this_recipe_features_with_icons) || is_object($this_recipe_features_with_icons)){
                $recipe_cuisine_and_features_html.= '<div class="single-recipe-features"><div class="single-recipe-features-header">'.__('Features:', 'neptune-by-osetin').'</div><ul>';
                foreach($this_recipe_features_with_icons as $feature){
                  $feature_term = $feature['term'];
                  $recipe_cuisine_and_features_html.= '<li><a href="'.esc_url(get_term_link($feature_term, 'feature')).'"><span class="tooltip-trigger" data-tooltip-header="'.esc_attr($feature_term->name).'"><img src="'.$feature['icon_url'].'" alt="'.esc_attr($feature_term->name).'"/></span></a></li>';
                }
                $recipe_cuisine_and_features_html.= '</ul></div>';
              }

              // CUISINES
              if (is_array($this_recipe_cuisines_with_icons) || is_object($this_recipe_cuisines_with_icons)){
                $recipe_cuisine_and_features_html.= '<div class="single-recipe-cuisines"><div class="single-recipe-cuisines-header">'.__('Cuisine:', 'neptune-by-osetin').'</div><ul>';
                foreach($this_recipe_cuisines_with_icons as $cuisine){
                  $cuisine_term = $cuisine['term'];
                  $recipe_cuisine_and_features_html.= '<li>
                          <a href="'.get_term_link($cuisine_term).'" class="single-recipe-cuisine-label-w tooltip-trigger" data-tooltip-header="'.esc_attr($cuisine_term->name).'">
                            <span class="single-recipe-cuisine-label">'.esc_html($cuisine_term->name).'</span>
                            <span class="single-recipe-cuisine-image"><img src="'.$cuisine['icon_url'].'" alt="'.esc_attr($cuisine_term->name).'"/></span>
                          </a>
                        </li>';
                }
                $recipe_cuisine_and_features_html.= '</ul></div>';
              }

            $recipe_cuisine_and_features_html.= '</div>';
          }
        ?>
        <?php 
        if($details_position == 'split'){
          echo $recipe_cuisine_and_features_html;
        }
        if($quick_description){
          echo '<div class="quick-description-quote position-'.$details_position.'">'.$quick_description.'</div>';
        }
        if($details_position == 'single'){
          echo $recipe_cuisine_and_features_html;
        }
        ?>

        <?php if($details_position == 'split'){ ?>
          <div class="single-meta">
            <ul>
              <?php if($recipe_cooking_time){ ?><li class="single-meta-cooking-time"><i class="os-icon os-icon-thin-clock-busy"></i> <span><?php echo $recipe_cooking_time; ?></span></li><?php } ?>
              <?php if($recipe_serves){ ?><li class="single-meta-serves"><i class="os-icon os-icon-thin-serve"></i> <span><?php printf( __( 'Serves %d', 'neptune-by-osetin' ), $recipe_serves ); ?></span></li><?php } ?>
              <?php if($recipe_difficulty_string){ ?><li class="single-meta-difficulty"><i class="os-icon os-icon-thin-cook"></i> <span><?php echo $recipe_difficulty_string; ?></span></li><?php } ?>
            </ul>
          </div>
        <?php } ?>




        <div class="single-content" data-font-change-count="0">
          <div class="cooking-mode-close-btn-w">
            <a href="#" class="cooking-mode-toggler cooking-mode-close-btn"><i class="os-icon os-icon-thin-close-round"></i></a>
          </div>
  

          <?php // PRINTABLE INGREDIENTS: ?>


          <?php 
          // check if ingredients exist
          if( osetin_have_rows('ingredients') ){ ?>
            <?php $searchable_ingredients = osetin_get_field('searchable_ingredients'); ?>

            <div class="single-print-ingredients">
              <h2 class="bordered-title"><i class="os-icon os-icon-thin-paper-holes-text"></i> <span><?php esc_html_e('Ingredients', 'neptune-by-osetin'); ?></span></h2>
              <ul>
                <?php
                // loop through the rows of ingredients
                while ( osetin_have_rows('ingredients') ) { 
                  the_row(); ?>
                  <li>
                    <?php if(get_sub_field('separator')){ ?>
                      <h3><?php the_sub_field('ingredient_name'); ?></h3>
                    <?php }else{ ?>
                      <div class="print-ingredient">
                        <span class="ingredient-amount"><?php the_sub_field('ingredient_amount'); ?></span> 
                        <?php if($searchable_ingredients){ ?>
                          <?php 
                          $ingredient_obj = get_sub_field('ingredient_obj');
                          if($ingredient_obj){ ?>
                            <span class="ingredient-name"><?php echo $ingredient_obj->name; ?></span>
                          <?php } ?>
                        <?php }else{ ?>
                          <span class="ingredient-name"><?php the_sub_field('ingredient_name'); ?></span>
                        <?php } ?>
                        <?php if(get_sub_field('ingredient_note')){ 
                          echo '<div class="ingredient-print-note">'. get_sub_field('ingredient_note'). '</div>';
                        } ?>
                      </div>
                    </li>
                    <?php 
                  }
                } ?>
              </ul>
            </div>
            <?php
          } ?>

          <?php // END PRINTABLE INGREDIENTS ?>


          <h2 class="bordered-title"><i class="os-icon os-icon-thin-paper-list"></i> <span><?php esc_html_e('Directions', 'neptune-by-osetin'); ?></span></h2>
          <div class="single-content-self">
            <div class="single-sharing-box">
              <?php osetin_get_post_sharing_icons(); ?>
            </div>
            <?php the_content(); ?>
            <?php // echo do_shortcode('[os_add_to_meal_plan]'); ?>
            <?php wp_link_pages(array('before' => '<div class="content-link-pages">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
          </div>

          <?php 
          // check if steps exist
          if( osetin_have_rows('steps') ){ ?>

            <div class="single-steps">
              <h2 class="bordered-title"><span><?php esc_html_e('Steps', 'neptune-by-osetin'); ?></span></h2>
              <table class="recipe-steps-table">
                <?php
                $step_number = 0;
                // loop through the rows of steps
                while ( osetin_have_rows('steps') ) { 
                  the_row(); 
                  $step_number++;
                  ?>
                  <tr class="single-step">
                    <td class="single-step-number">
                      <div class="single-step-number-i">
                        <div class="single-step-number-value"><?php echo esc_html($step_number) ?></div>
                        <div class="single-step-control">
                          <i class="os-icon os-icon-circle-o"></i>
                          <div class="single-step-complete-label"><?php esc_html_e('Done', 'neptune-by-osetin') ?></div>
                        </div>
                      </div>
                      <?php if(get_sub_field('step_duration')){ ?>
                        <div class="single-step-duration"><i class="os-icon os-icon-clock"></i> <?php echo get_sub_field('step_duration') ?></div>
                      <?php } ?>
                    </td>
                    <td class="single-step-description">
                      <?php if(get_sub_field('step_title')){ ?>
                        <h4 class="single-step-title"><?php echo get_sub_field('step_title') ?></h4>
                      <?php } ?>
                      <div class="single-step-description-i">
                        <?php if(get_sub_field('step_images')){ 
                          // step images
                          $step_images = get_sub_field('step_images');
                          $media_vars = osetin_get_step_media_vars(count($step_images));
                          echo '<ul class="single-step-media '.$media_vars['extra_class'].'">';
                          foreach($step_images as $step_image){
                            if(isset($step_image['sizes'])){
                              $thumbnail_post_title = $step_image['caption'];
                              $thumbnail_url = (isset($step_image['sizes'][$media_vars['img_size']])) ? $step_image['sizes'][$media_vars['img_size']] : '';
                              $lightbox_thumbnail_url = (isset($step_image['sizes']['osetin-medium-square-thumbnail'])) ? $step_image['sizes']['osetin-medium-square-thumbnail'] : '';
                              $full_image_url = (isset($step_image['sizes']['osetin-full-width'])) ? $step_image['sizes']['osetin-full-width'] : ''; ?>
                              <li>
                                <a class="osetin-lightbox-trigger-step-images" href="<?php echo $full_image_url; ?>" data-lightbox-thumb-src="<?php echo $lightbox_thumbnail_url; ?>" data-lightbox-img-src="<?php echo $full_image_url; ?>">
                                  <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($thumbnail_post_title); ?>">
                                </a>
                              </li>
                              <?php
                            }
                          }
                          echo '</ul>';
                        ?>
                          
                        <?php } ?>
                        <?php echo do_shortcode(get_sub_field('step_description')); ?>
                        <div class="step-off-fader"></div>
                      </div>
                    </td>
                  </tr>
                <?php 
                }
                wp_reset_postdata(); ?>
              </table>
            </div>
          <?php 
          } ?>
        </div>
        <div class="single-meta single-meta-at-bottom">
          <ul>
            <li class="social-bottom-comments">
              <i class="os-icon os-icon-thin-comments"></i> 
              <a href="#singlePostComments"><?php comments_number( __('Comment', 'neptune-by-osetin'), '1 '.__('Comment', 'neptune-by-osetin'), '% '.__('Comments', 'neptune-by-osetin') ); ?></a>
            </li>
            <li class="social-links">
              <?php echo get_user_social_links(get_the_author_meta( 'ID' )); ?>
            </li>
            <li class="social-bottom-author"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar(get_the_author_meta( 'ID' ), 40); ?></a> <?php the_author_posts_link(); ?></li>
          </ul>
        </div>
        <div class="single-post-about-author">
          <div class="author-avatar-w">
            <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar(get_the_author_meta( 'ID' )); ?></a>
          </div>
          <div class="author-details">
            <h3 class="author-name"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta( 'display_name' ); ?></a></h3>
            <div style="display:none;">
              <div class="post-date"><time class="entry-date updated" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date('M jS, Y'); ?></time></div>
              <div class="post-author"><strong class="author vcard"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) )) ; ?>" class="url fn n" rel="author"><?php echo get_the_author(); ?></a></strong></div>
            </div>
            <?php if ( get_the_author_meta('description') ) : ?>
              <p><?php the_author_meta('description'); ?></p>
            <?php endif; ?>
            <div class="author-social-links">
              <?php echo get_user_social_links(get_the_author_meta( 'ID' ), 'profile-url'); ?>
            </div>
          </div>
        </div>
        <?php 
        if((osetin_get_field('allow_recipe_reviews', 'option') != 'no') && osetin_is_userpro_installed()){ ?>
          <div id="osetinRecipeReviews" class="single-post-reviews-w">
            <div class="existing-reviews-w">
              <h3 class="box-heading"><i class="os-icon os-icon-thin-comment"></i> <?php _e('Recipe Reviews', 'neptune-by-osetin'); ?></h3>
              <?php 
              $reviews_args = array(
                'nopaging' => true,
                'meta_key'    => 'recipe',
                'meta_value'  => get_the_ID(),
                'post_type' => 'osetin_review');
              $osetin_reviews_query = new WP_Query( $reviews_args );
              if ( $osetin_reviews_query->have_posts() ) {
                if($reviews_info){
                  echo '<div class="reviews-summary">';
                  if($reviews_info->avg_rating) {
                    $stars_html = osetin_build_stars($reviews_info->avg_rating);
                    echo '<div class="review-summary-average"><span class="review-summary-label">'.esc_html__('Average Rating: ', 'neptune-by-osetin').'</span>'.$stars_html.'<span class="review-summary-average-stars">('.$reviews_info->avg_rating.')</span></div>';
                  }
                  if($reviews_info->total_reviews) echo '<div class="review-summary-total"><span class="review-summary-label">'.esc_html__('Total Reviews: ', 'neptune-by-osetin').'</span><span class="review-summary-value">'.$reviews_info->total_reviews.'</span></div>';
                  echo '</div>';
                }
                while ( $osetin_reviews_query->have_posts() ) : $osetin_reviews_query->the_post(); ?>
                  <div class="review-box-w">
                    <div class="review-head">
                      <div class="review-author-avatar">
                        <?php echo get_avatar(get_the_author_meta( 'ID' )); ?>
                      </div>
                      <div class="review-author-meta">
                        <div class="review-author-name"><?php the_author_meta( 'display_name' ); ?></div>
                        <div class="review-post-date"><?php echo get_the_date(); ?></div>
                      </div>
                      <div class="review-rating">
                        <select class="review-rating-select">
                          <?php 
                            $rating = osetin_get_field('rating');
                            for($i = 1; $i <= 5; $i++){
                              if($i == $rating) $selected = 'selected';
                              else $selected = '';
                              echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="review-body">
                      <p><?php echo osetin_get_field('body'); ?></p>
                    </div>
                    <div class="review-images">
                      <?php  
                      $review_images = osetin_get_field('review_images', false, false, true);
                      if($review_images){
                        $html = '';
                        foreach( $review_images as $key => $image ){
                          $active_class = ($key == 0) ? 'active' : '';
                          $html.= '<div class="review-image osetin-lightbox-trigger" 
                          data-lightbox-caption="'.$image['caption'].'" 
                          data-lightbox-img-src="'.$image['sizes']['osetin-full-width'].'" 
                          data-lightbox-thumb-src="'.$image['sizes']['osetin-medium-square-thumbnail'].'">
                          <img src="'.$image['sizes']['osetin-medium-square-thumbnail'].'"/></div>';
                        }
                        echo $html;
                      }
                      ?>
                    </div>  
                  </div>
                  <?php
                endwhile;
              }else{
                echo '<div class="no-review-results">'.__('There are no reviews for this recipe yet, use a form below to write your review', 'neptune-by-osetin').'</div>';
              }
              wp_reset_postdata(); 
              ?>
            </div>
            <?php
              if ( is_user_logged_in() ) {

                $user_reviews_args = array(
                  'nopaging' => true,
                  'meta_key'    => 'recipe',
                  'meta_value'  => get_the_ID(),
                  'author' => get_current_user_id(),
                  'post_status' => 'any',
                  'post_type' => 'osetin_review');
                $osetin_user_reviews_query = new WP_Query( $user_reviews_args );

                if($osetin_user_reviews_query->have_posts()){
                  // this user has already posted review for this recipe
                  echo '<div class="thanks-for-review">'.__('Thank you for submitting your review, you can find it in a list above as soon as we process it.', 'neptune-by-osetin').'</div>';
                }else{
                  echo '<div class="recipe-review-form-w" data-post-id="'.get_the_ID().'">';
                  echo '<h3 class="form-header"><i class="os-icon os-icon-thin-comment"></i> '.__('Write your own review', 'neptune-by-osetin').'</h3>';
                  include_once(ABSPATH.'wp-admin/includes/plugin.php');
                  acf_form(array(
                    'post_id'   => 'new_post',
                    'post_title'  => false,
                    'post_content'  => false,
                    'submit_value' => __('Submit Review', 'neptune-by-osetin'),
                    'new_post'    => array(
                      'post_type'   => 'osetin_review',
                      'post_status' => 'pending'
                    )
                  ));
                  echo '</div>';
                }
              }else{
                echo '<div class="review-login-w">';
                echo do_shortcode('[userpro template="login" login_heading="'.__('Login to write a review', 'neptune-by-osetin').'"]');
                echo '</div>';
              }
            ?>
          </div>
        <?php } ?>
        <div class="single-post-navigation">
          <?php
          if ( is_a( $previous_post , 'WP_Post' ) ) { ?>
            <a href="<?php echo get_permalink( $previous_post->ID ); ?>">
              <figure>
                <?php echo osetin_get_post_thumbnail($previous_post->ID, 'osetin-medium-square-thumbnail'); ?>
                <div class="fader"><span class="fader-label"><i class="os-icon os-icon-chevron-left"></i> <span><?php esc_html_e('previous', 'neptune-by-osetin'); ?></span></span></div>
              </figure>
              <span><?php echo get_the_title($previous_post->ID); ?></span>
            </a>
          <?php } ?>
          <?php
          if ( is_a( $next_post , 'WP_Post' ) ) { ?>
            <a href="<?php echo get_permalink( $next_post->ID ); ?>">
              <figure>
                <?php echo osetin_get_post_thumbnail($next_post->ID, 'osetin-medium-square-thumbnail'); ?>
                <div class="fader"><span class="fader-label"><i class="os-icon os-icon-chevron-right"></i> <span><?php esc_html_e('next', 'neptune-by-osetin'); ?></span></span></div>
              </figure>
              <span><?php echo get_the_title($next_post->ID); ?></span>
            </a>
          <?php } ?>
        </div>
      </div>
    </div>
    
    <?php

      $osetin_current_post_id = get_the_ID();
      $related_recipes_args = array( 
        'paged' => 1, 
        'posts_per_page' => 5, 
        'post_type' => 'osetin_recipe', 
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'post_password' => '',
        'meta_query' => array(
          array( 
            'key' => '_thumbnail_id',
            'value' => 0,
            'type' => 'NUMERIC',
            'compare' => '>'
          ),
        )
      );

      $specific_related_posts = osetin_get_field('show_selected_posts_as_relative', false, false, true);
      if($specific_related_posts){
        // specific related posts were selected, show those
        $related_recipes_args['post__in'] = $specific_related_posts;
      }else{
        $related_recipes_selecting_by = osetin_get_field('related_recipes_selecting_by', 'option', 'random');
        switch ($related_recipes_selecting_by) {
          case 'category':
            $current_post_categories = wp_get_post_categories($osetin_current_post_id, array('fields' => 'all'));
            // post has categories
            if($current_post_categories){
              $category_ids = array();
              foreach($current_post_categories as $individual_category){
                $category_ids[] = $individual_category->term_id;
              }
              $related_recipes_args['category__in'] = $category_ids;
            }
            break;
          case 'tag':
            $current_post_tags = wp_get_post_tags($osetin_current_post_id);
            // post has tags
            if($current_post_tags){
              $tag_ids = array();
              foreach($current_post_tags as $individual_tag){
                $tag_ids[] = $individual_tag->term_id;
              }
              $related_recipes_args['tag__in'] = $tag_ids;
            }
            break;
          default:
            $related_recipes_args['orderby'] = 'rand';
            break;
        }
        $related_recipes_args['post__not_in'] = array($osetin_current_post_id);
      }
      $osetin_related_recipes_query = new WP_Query( $related_recipes_args );

      $related_recipes_bg = osetin_get_field('related_posts_background_image', 'option');
      if(empty($related_recipes_bg)){
        $related_recipes_bg = get_template_directory_uri().'/assets/img/patterns/flowers_light.jpg';
      }
    ?>
    <?php
    if ( is_a( $previous_post , 'WP_Post' ) ) { ?>
      <div class="floating-prev-post">
        <a href="<?php echo get_permalink( $previous_post->ID ); ?>">
          <figure>
            <?php echo osetin_get_post_thumbnail($previous_post->ID, 'osetin-medium-square-thumbnail'); ?>
            <div class="fader"><span class="fader-label"><i class="os-icon os-icon-chevron-left"></i> <span><?php esc_html_e('previous', 'neptune-by-osetin'); ?></span></span></div>
          </figure>
          <span><?php echo get_the_title($previous_post->ID); ?></span>
        </a>
      </div>
    <?php } ?>
    <?php
    if ( is_a( $next_post , 'WP_Post' ) ) { ?>
      <div class="floating-next-post">
        <a href="<?php echo get_permalink( $next_post->ID ); ?>">
          <figure>
            <?php echo osetin_get_post_thumbnail($next_post->ID, 'osetin-medium-square-thumbnail'); ?>
            <div class="fader"><span class="fader-label"><i class="os-icon os-icon-chevron-right"></i> <span><?php esc_html_e('next', 'neptune-by-osetin'); ?></span></span></div>
          </figure>
          <span><?php echo get_the_title($next_post->ID); ?></span>
        </a>
      </div>
    <?php } ?>
    <div class="os-container">
      <div class="related-recipes-w" style="<?php echo osetin_get_css_prop('background-image', $related_recipes_bg, false, 'background-repeat: repeat;'); ?>">
        <div class="related-recipes-heading">
          <h2 class="bordered-title"><span><?php esc_html_e('Related Recipes:', 'neptune-by-osetin'); ?></span></h2>
          <?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
        </div>
        <ul class="related-recipes">
          <?php while ($osetin_related_recipes_query->have_posts()) : $osetin_related_recipes_query->the_post(); ?>

          <li>
            <a href="<?php the_permalink(); ?>" class="fader-activator">
              <figure><?php the_post_thumbnail('osetin-medium-square-thumbnail'); ?><span class="image-fader"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span></figure>
              <span><?php the_title(); ?></span>
            </a>
          </li>

          <?php endwhile; 
            wp_reset_postdata();
          ?>
        </ul>
      </div>
    </div>
    <div class="os-container">
      <div class="single-post-comments-w with-ads">
        <div class="single-post-comments" id="singlePostComments">
          <?php
            // If comments are open or we have at least one comment, load up the comment template
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;
          ?>
        </div>
        <?php if ( is_active_sidebar( 'sidebar-single-comments' ) ) { ?>
          <div class="single-post-comments-sidebar">
            <?php dynamic_sidebar( 'sidebar-single-comments' ); ?>
          </div>
        <?php } ?>
      </div>
    </div>

  <?php endwhile; // end of the loop. ?>


<?php get_footer(); ?>