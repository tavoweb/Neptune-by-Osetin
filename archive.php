<?php
/**
 * The template for displaying archive pages
 *
 */

get_header(); 

$layout_type_for_index = osetin_get_settings_field('layout_type_for_index');
$archive_title = get_the_archive_title();
$archive_description = get_the_archive_description();
?>
<div class="os-container top-bar-w">
  <div class="top-bar bordered">
    <?php osetin_output_breadcrumbs(); ?>
    <?php osetin_social_share_icons('header'); ?>
  </div>
</div>
          
<div class="os-container">
  <?php global $wp_query; ?>
  <?php echo build_index_posts($layout_type_for_index, 'sidebar-index', $wp_query, false, array('title' => $archive_title, 'description' => $archive_description)); ?>
</div>

<?php get_footer(); ?>