<?php
/**
 * The template for displaying Tag pages
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @since Neptune 1.0
 */
?>

<?php
get_header(); 
$layout_type_for_index = osetin_get_settings_field('layout_type_for_index');
$tag_title = ucwords(single_tag_title( '', false ));
$tag_description = term_description();

?>
<div class="os-container top-bar-w">
  <div class="top-bar bordered">
    <?php osetin_output_breadcrumbs(); ?>
    <?php osetin_social_share_icons('header'); ?>
  </div>
</div>

<div class="os-container">
  <?php global $wp_query; ?>
  <?php echo build_index_posts($layout_type_for_index, 'sidebar-index', $wp_query, false, array('title' => $tag_title, 'description' => $tag_description)); ?>
</div>
<?php get_footer(); ?>