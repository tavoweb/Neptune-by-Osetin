<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); 

$layout_type_for_index = osetin_get_settings_field('layout_type_for_index', 'magazine_v1'); ?>

<div class="os-container top-bar-w">
  <div class="top-bar bordered">
    <?php osetin_output_breadcrumbs(); ?>
    <?php osetin_social_share_icons('header'); ?>
  </div>
</div>

<div class="os-container">
  <?php global $wp_query; ?>
  <?php echo build_index_posts($layout_type_for_index, 'sidebar-index', $wp_query); ?>
</div>
<?php get_footer(); ?>
