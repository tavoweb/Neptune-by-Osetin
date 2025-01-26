<?php
/**
 * Template Name: Ingredients Search
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

  <div class="os-container">
    <div class="ingredients-search-box-w">
      <div class="ingredients-search-box-i">
        <h2 class="box-heading"><span><?php _e('Search by ingredients', 'neptune-by-osetin'); ?></span></h2>
        <div class="ingredients-select-box-w">
          <div class="ingredient-search-icon">
            <i class="os-icon os-icon-thin-search"></i>
          </div>
          <select class="ingredients-multi-select" name="" id="" data-placeholder="<?php esc_attr_e('Click to select Ingredients for search...', 'neptune-by-osetin'); ?>" multiple>
            <?php 
            $args = array( 'hide_empty' => 0 );

            $terms = get_terms( 'recipe_ingredient', $args );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
              foreach ( $terms as $term ) {
                echo '<option value="'.$term->term_id.'">'.$term->name.'</option>';
              }
            }
            ?>
          </select>
          <div class="ingredient-search-btn-w">
            <span class="ingredient-loading-icon-w"><img src="<?php echo get_template_directory_uri().'/assets/img/ajax-loader.gif' ?>" alt="<?php _e('Loading', 'neptune-by-osetin'); ?>"></span>
            <div class="trigger-ingredient-search" data-label-loading="<?php esc_attr_e('Searching...', 'neptune-by-osetin'); ?>"><?php _e('Find Recipes','neptune-by-osetin'); ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="ingredients-search-results-w"></div>
  </div>
<?php


endwhile; endif; ?>
<?php get_footer(); ?>