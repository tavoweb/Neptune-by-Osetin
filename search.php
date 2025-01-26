<?php
/**
 * The template for displaying search results page
 */

get_header(); 

$layout_type_for_index = osetin_get_settings_field('layout_type_for_index');

?>
<div class="os-container">
  <div class="page-intro-header without-background">
    <h2><?php printf( __( '"%s" <span class="smaller-text">Query Search Results</span>', 'neptune-by-osetin' ), ucwords(get_search_query()) ); ?></h2>
  </div>
</div>

          
<div class="os-container">
  <?php global $wp_query; ?>
  <?php echo build_index_posts($layout_type_for_index, 'sidebar-index', $wp_query); ?>
</div>

<?php get_footer(); ?>