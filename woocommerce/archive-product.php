<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

get_header( 'shop' );
$shop_page_id = get_option( 'woocommerce_shop_page_id' );
?>

  <div class="os-container top-bar-w">
    <div class="top-bar <?php if(!osetin_is_imaged_header($shop_page_id)) echo 'bordered'; ?>">
      <?php woocommerce_breadcrumb(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
  </div>
  <?php  
  if(osetin_is_imaged_header($shop_page_id)){
    $page_bg_image_url = wp_get_attachment_url( get_post_thumbnail_id($shop_page_id) ); ?>
    <div class="os-container">
      <div class="page-intro-header with-background" style="<?php echo osetin_get_css_prop('background-image', $page_bg_image_url, false, 'background-repeat: repeat; background-position: top left; '); ?>">
        <h2><?php echo osetin_get_the_title($shop_page_id); ?></h2>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="os-container">
    
    <div class="page-w <?php if ( osetin_is_active_sidebar( 'sidebar-shop', $shop_page_id ) ) echo 'with-sidebar sidebar-location-right'; ?>">
      <div class="page-content">
        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php
            /**
             * woocommerce_before_main_content hook
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action( 'woocommerce_before_main_content' );
          ?>
          <?php 
          if(osetin_is_regular_header(get_the_ID())){
            if ( apply_filters( 'woocommerce_show_page_title', true ) ){
              echo '<h1 class="page-title">'.woocommerce_page_title().'</h1>';
            }
          }
          $sub_title = osetin_get_field('sub_title');
          if ( ! empty( $sub_title ) ) { ?>
            <h2 class="page-content-sub-title"><?php echo esc_html($sub_title); ?></h2>
          <?php } ?>

          <?php
            /**
             * woocommerce_archive_description hook
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action( 'woocommerce_archive_description' );
          ?>

          <?php if ( have_posts() ) : ?>

            <?php
              /**
               * woocommerce_before_shop_loop hook
               *
               * @hooked woocommerce_result_count - 20
               * @hooked woocommerce_catalog_ordering - 30
               */
              do_action( 'woocommerce_before_shop_loop' );
            ?>

            <?php woocommerce_product_loop_start(); ?>

              <?php woocommerce_product_subcategories(); ?>

              <?php while ( have_posts() ) : the_post(); ?>

                <?php wc_get_template_part( 'content', 'product' ); ?>

              <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

            <?php
              /**
               * woocommerce_after_shop_loop hook
               *
               * @hooked woocommerce_pagination - 10
               */
              do_action( 'woocommerce_after_shop_loop' );
            ?>

          <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

            <?php wc_get_template( 'loop/no-products-found.php' ); ?>

          <?php endif; ?>

        <?php
          /**
           * woocommerce_after_main_content hook
           *
           * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
           */
          do_action( 'woocommerce_after_main_content' );
        ?>
        </article>
      </div>

        <?php
          if ( osetin_is_active_sidebar( 'sidebar-shop', $shop_page_id ) ){
            echo '<div class="page-sidebar">';
            /**
             * woocommerce_sidebar hook
             *
             * @hooked woocommerce_get_sidebar - 10
             */
            do_action( 'woocommerce_sidebar' );
            echo '</div>';
          }
        ?>
      </div>
    </div>
<?php get_footer( 'shop' ); ?>
