<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Neptune
 * @since Neptune
 */

get_header(); 
$cat_id =  get_query_var('cat');
$category_bg_image_url = osetin_get_field('category_header_bg', "category_{$cat_id}");
$css_extra_class = ($category_bg_image_url) ? 'with-background' : 'without-background';
$term_description = term_description();

$layout_type_for_term_archive = osetin_get_field('layout_type_for_term_archive', "category_{$cat_id}");
if($layout_type_for_term_archive && ($layout_type_for_term_archive != 'default')){
  $layout_type_for_index = $layout_type_for_term_archive;
}else{
  $layout_type_for_index = osetin_get_settings_field('layout_type_for_index');
}
if(empty($category_bg_image_url)){
  $header_arr['description'] = $term_description;
  $header_arr['title'] = ucwords(single_cat_title( '', false ));
}else{
  $header_arr = false;
}

?>
  <div class="os-container top-bar-w">
    <div class="top-bar <?php if(empty($category_bg_image_url)) echo 'bordered'; ?>">
      <?php osetin_output_breadcrumbs(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
    <?php if(!empty($category_bg_image_url)){ ?>
      <div class="page-intro-header <?php echo esc_attr($css_extra_class); ?>" style="<?php echo osetin_get_css_prop('background-image', $category_bg_image_url, false, 'background-repeat: repeat; background-position: top left;'); ?>">
        <h2><?php echo ucwords(single_cat_title( '', false )); ?></h2>
        <?php 
          if ( ! empty( $term_description ) ) { ?>
            <div class="page-intro-description"><?php echo $term_description; ?></div>
          <?php } ?>
      </div>
    <?php } ?>
  </div>

  <div class="os-container">
    <?php global $wp_query; ?>
    <?php echo build_index_posts($layout_type_for_index, 'sidebar-index', $wp_query, false, $header_arr); ?>
  </div>
<?php get_footer(); ?>