<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

  <div class="os-container top-bar-w">
    <div class="top-bar bordered">
      <?php woocommerce_breadcrumb(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
  </div>
  <div class="os-container">
    
    <div class="page-w <?php if ( osetin_is_active_sidebar( 'sidebar-shop', $shop_page_id ) ) echo 'with-sidebar sidebar-location-right'; ?>">
      <div class="page-content">
  <?php
    /**
     * woocommerce_before_main_content hook
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action( 'woocommerce_before_main_content' );
  ?>

    <?php while ( have_posts() ) : the_post(); ?>

      <?php wc_get_template_part( 'content', 'single-product' ); ?>

    <?php endwhile; // end of the loop. ?>

  <?php
    /**
     * woocommerce_after_main_content hook
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );
  ?>
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