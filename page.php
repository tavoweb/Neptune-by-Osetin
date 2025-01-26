<?php
/**
 * The template for displaying all pages.
 *
 * @package Neptune
 */

get_header(); ?>

  <?php while ( have_posts() ) : the_post(); ?>


  <div class="os-container top-bar-w">
    <div class="top-bar <?php if(!osetin_is_imaged_header(get_the_ID())) echo 'bordered'; ?>">
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
          <?php $sub_title = osetin_get_field('sub_title');
          if ( ! empty( $sub_title ) ) { ?>
            <h2 class="page-content-sub-title"><?php echo esc_html($sub_title); ?></h2>
          <?php } ?>
          <?php the_content(); ?>
          <?php
            // If comments are open or we have at least one comment, load up the comment template
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;
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