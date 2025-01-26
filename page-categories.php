<?php
/**
 * Template Name: Categories
 *
 */
?>
<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


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
    
    <div class="page-w">
      <div class="page-content">
        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php 
          if(osetin_is_regular_header(get_the_ID())){
            echo '<h1 class="page-title">'.osetin_get_the_title(get_the_ID()).'</h1>';
          }
          ?>
          <?php $sub_title = osetin_get_field('sub_title');
          if ( ! empty( $sub_title ) ) { ?>
            <h2 class="page-content-sub-title"><?php echo esc_html($sub_title); ?></h2>
          <?php } ?>
          <div class="category-tiles columns-3">


            <?php
              if(osetin_get_field('which_categories_to_show') == 'all'){
                $categories = get_categories(array('type' => 'osetin_recipe')); ?>
                <?php 
                foreach($categories as $category){
                  $category_bg_image_url = osetin_get_field('category_tile_bg', "category_{$category->cat_ID}"); ?>
                  <a href="<?php echo get_category_link($category->cat_ID); ?>" class="category-tile" style="<?php echo osetin_get_css_prop('background-image', $category_bg_image_url, false, 'background-repeat: repeat; background-position: center center;  background-size: cover;'); ?>">
                    <div class="category-fader"></div>
                    <div class="category-recipes-count"><?php echo esc_html($category->category_count).' '.esc_html__('Recipes', 'neptune-by-osetin'); ?></div>
                    <h3><?php echo esc_html($category->cat_name); ?></h3>
                  </a>
                  <?php
                }
              }else{

                if( osetin_have_rows('category_items') ){
                  while ( osetin_have_rows('category_items') ) { the_row(); 
                    $category_bg_image_url = get_sub_field('image'); 
                    if(get_sub_field('what_to_show') == 'category'){
                      $tile_link = get_category_link(get_sub_field('category'));
                    }elseif(get_sub_field('what_to_show') == 'page'){
                      $tile_link = get_sub_field('page');
                    }else{
                      $tile_link = get_sub_field('custom_link');
                    }

                    ?>
                    <a href="<?php echo esc_attr($tile_link); ?>" class="category-tile" style="<?php echo osetin_get_css_prop('background-image', $category_bg_image_url, false, 'background-repeat: repeat; background-position: center center;  background-size: cover;'); ?>">
                      <div class="category-fader"></div>
                      <h3><?php echo get_sub_field('title'); ?></h3>
                    </a><?php
                  }
                }
              } ?>
          </div>
        </article>
      </div>
    </div> 
    
  </div>
  <?php


endwhile; endif; ?>
<?php get_footer(); ?>