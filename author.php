<?php
/**
 * The template for displaying author pages
 *
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); 
$cat_id =  get_query_var('cat');
$category_bg_image_url = osetin_get_field('category_header_bg', "category_{$cat_id}");
$css_extra_class = ($category_bg_image_url) ? 'with-background' : 'without-background';

$layout_type_for_index = osetin_get_settings_field('layout_type_for_index');
$archive_class = osetin_get_archive_class($layout_type_for_index);
$masonry_layout_mode = osetin_get_masonry_layout_mode($layout_type_for_index);
$term_description = term_description();
$not_my_posts = true;
?>
  <div class="os-container top-bar-w">
    <div class="top-bar bordered">
      <?php osetin_output_breadcrumbs(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
  </div>

  <div class="os-container">
    <div class="archive-posts-w <?php if ( is_active_sidebar( 'sidebar-index' ) ) echo 'with-sidebar sidebar-location-right'; ?>">
      <?php if(is_user_logged_in()){ ?>
        <?php 
        global $userpro;
        if(isset($userpro)){
          $os_current_user = wp_get_current_user(); 
          $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
          if($curauth->id == get_current_user_id()){
            $not_my_posts = false;
          } ?>
        <?php } ?>
      <?php } ?>



      <?php if($not_my_posts){ ?>
      <div class="archive-posts <?php echo osetin_get_archive_wrapper_class() ?>">
        <div class="archive-title-w with-avatar">
          <div class="page-title-avatar-self">
              <?php echo get_avatar(get_the_author_meta('ID'), 200); ?>
          </div>
          <div class="page-title-avatar-side">
            <h1 class="page-title"><?php printf( esc_html__( 'All posts by %s', 'neptune-by-osetin' ), get_the_author() ); ?></h1>
            <div class="page-title-social-icons"><?php echo get_user_social_links(get_the_author_meta( 'ID' )); ?></div>
            <?php if ( get_the_author_meta( 'description' ) ) : ?>
              <h2 class="page-content-sub-title"><?php the_author_meta( 'description' ); ?></h2>
            <?php endif; ?>
          </div>
        </div>

        <div class="<?php echo esc_attr($archive_class); ?>" data-layout-mode="<?php echo esc_attr($masonry_layout_mode); ?>">
            <?php


            $layout_settings = osetin_get_layout_settings_arr($layout_type_for_index);

            $show_ads = osetin_get_field('ad_between_posts_type');
            $ads_code = osetin_generate_ads_code($show_ads, $layout_settings['ad_wrapper_class']);

            
            $wrapper_step = 0;
            $item_step = 1;
            $counter = 1;



              if ( have_posts() ) { ?>
                <?php
                // Start the Loop.
                while ( have_posts() ) : the_post();
                  // show ad if we have any here
                  if($ads_code != ''){
                    if($layout_settings['ad_position'] == $counter){
                      echo $ads_code;
                    }
                  }
                  if(($item_step == 1) || in_array(($item_step - 1), $layout_settings['wrapper_ends'])){
                    echo '<div class="masonry-item any '.$layout_settings['wrapper_classes'][$wrapper_step].'">';
                    $wrapper_step++;
                    if($wrapper_step >= count($layout_settings['wrapper_classes'])){
                      $wrapper_step = $layout_settings['loop_start_from_wrapper_step'];
                    }
                  }
                  $current_step_class = $layout_settings['item_classes'][$item_step - 1];
                  $limit = osetin_get_limit_by_item_type($current_step_class, $layout_settings['wrapper_classes'][$wrapper_step], $archive_class);
                  $cooking_time = osetin_get_field('recipe_cooking_time');
                  get_template_part( 'content', get_post_format() );
                  

                  if(in_array($item_step, $layout_settings['wrapper_ends']) || ($counter == $wp_query->post_count)){
                    echo '</div>';
                  }

                  if($item_step >= count($layout_settings['item_classes'])){
                    $item_step = $layout_settings['loop_start_from_item_step'];
                  }
                  $item_step++;
                  $counter++;
                endwhile;
            } else {
              get_template_part( 'content', 'none' ); 
            } ?>
        </div>

        <?php
        osetin_output_navigation();
        ?>
      </div>
      <?php }else{





            // POSTS BELONG TO CURRENT USER




              echo '<h2>'.esc_html__('My Recipes', 'neptune-by-osetin').'</h2>';

              $frontend_publisher_page_link = false;

              $frontend_publisher_page = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-publish-recipe.php',
                'number' => 1
              ));
              if($frontend_publisher_page && isset($frontend_publisher_page[0]) && isset($frontend_publisher_page[0]->ID)){
                $frontend_publisher_page_link = get_page_link($frontend_publisher_page[0]->ID);
              }

              $my_recipes_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
              $user_recipes_query_args = array(
                  'post_type' => 'osetin_recipe',
                  'author' => $current_user->ID,
                  'post_status' => get_post_stati(),
                  'paged' => $my_recipes_paged,
                  'posts_per_page' => 10
              );
              $user_recipes_query = new WP_Query($user_recipes_query_args);


              if ( $user_recipes_query->have_posts() ) { ?>
                <?php
                echo '<ul class="my-recipes-list">';
                // Start the user_recipes_query.
                while ( $user_recipes_query->have_posts() ) : $user_recipes_query->the_post();
                  echo '<li>';
                  echo '<div class="my-recipe-img">'.get_the_post_thumbnail(get_the_ID(), 'osetin-medium-square-thumbnail').'</div>';
                  echo '<div class="my-recipe-info">';
                    echo '<h3><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
                    echo '<div class="my-recipe-actions-bar">';
                    echo '<div class="my-recipe-status status-'.get_post_status().'">'.esc_html__('Status: ', 'neptune-by-osetin').'<span>'.get_post_status().'</span></div>';
                    if($frontend_publisher_page_link){
                      echo '<a href="'.esc_url( add_query_arg( 'recipe_id_to_edit', get_the_ID(), $frontend_publisher_page_link)).'" class="edit-my-recipe-link"><i class="os-icon os-icon-write"></i> '.esc_html__('Edit Recipe', 'neptune-by-osetin').'</a>';
                    }
                    echo '</div>';
                  echo '</div>';
                  echo '</li>';
                endwhile;
                echo '</ul>';

                global $wp_query;
                $temp_query = $wp_query;
                $wp_query = $user_recipes_query;
                osetin_output_navigation();
                $wp_query = $temp_query;
                wp_reset_postdata();
            } else {
              get_template_part( 'content', 'none' ); 
            } ?>
      <?php  } ?>
        
      <?php if ( is_active_sidebar( 'sidebar-index' ) ) { ?>
        <div class="archive-sidebar">
          <?php dynamic_sidebar( 'sidebar-index' ); ?>
        </div>
      <?php } ?>

    </div>
  </div>
<?php get_footer(); ?>