<?php
/**
 * Template Name: Recipe Index
 *
 */
?>


<?php get_header(); ?>
<?php 
if ( have_posts() ) : while ( have_posts() ) : the_post();
  $content_location = osetin_get_field('content_location');
  $content_field_value = get_the_content();
  $css_extra_class = (has_post_thumbnail()) ? 'with-background' : 'without-background';
  $osetin_current_page_id = get_the_ID();
  if($content_location == 'as_header'){ ?>
  <div class="os-container">
    <div class="page-intro-header <?php echo esc_attr($css_extra_class); ?>" style="<?php echo osetin_get_css_prop('background-image', wp_get_attachment_url( get_post_thumbnail_id() ), false, 'background-repeat: repeat; background-position: top left;'); ?>">
      <h2><?php echo osetin_get_the_title(get_the_ID()); ?></h2>
      <?php 
        if ( $content_field_value ) { ?>
          <div class="page-intro-description"><?php echo do_shortcode($content_field_value); ?></div>
        <?php } ?>
    </div>
  </div>
  <?php } ?>

  <?php 
    echo osetin_get_hero_recipes_slider(); 
    osetin_show_featured_recipes_slider(); 
    $bordered = osetin_get_field('show_featured_recipes_slider') ? false : true;
  ?>
  <?php
  $layout_type_for_index = osetin_get_settings_field('layout_type_for_index');
  if(get_query_var('page')){
    $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
  }else{
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
  }
  if($paged == 1){
    $sticky_posts = osetin_get_field('sticky_posts', false, false, true);
  }
  $os_posts_per_page = osetin_get_number_of_posts_per_page();
  $args = array(
    'orderby' => 'position',
    'order' => 'DESC',
    'posts_per_page' => $os_posts_per_page,
    'paged' => $paged,
    'tax_query' => array()
  );


  // ---------------
  // FILTERS
  // ---------------
  // FILTER SELECTED CATEGORIES
  if( osetin_get_field('show_posts_from_selected_categories', false, false, true) ) $args['category__in'] = osetin_get_field('show_posts_from_selected_categories', false, false, true);
  // FILTER SELECTED TAGS
  if( osetin_get_field('show_posts_from_selected_tags', false, false, true) ) $args['tag__in'] = osetin_get_field('show_posts_from_selected_tags', false, false, true);
  // FILTER SELECTED POSTS
  if( osetin_get_field('show_only_specific_posts', false, false, true) ) $args['post__in'] = osetin_get_field('show_only_specific_posts', false, false, true);
  // FILTER SELECTED CUISINES
  if( osetin_get_field('show_posts_from_selected_cuisines', false, false, true) ){
    array_push($args['tax_query'], array(
                                      'taxonomy' => 'recipe_cuisine',
                                      'field' => 'term_id',
                                      'terms' => osetin_get_field('show_posts_from_selected_cuisines', false, false, true)
                                   ));
  }
  // FILTER SELECTED FEATURES
  if( osetin_get_field('show_posts_from_selected_features', false, false, true) ){
    array_push($args['tax_query'], array(
                                      'taxonomy' => 'recipe_feature',
                                      'field' => 'term_id',
                                      'terms' => osetin_get_field('show_posts_from_selected_features', false, false, true)
                                   ));
  }

  switch(osetin_get_field('post_types_to_show')){
    case 'only_posts':
      $args['post_type'] = array('post');
    break;
    case 'only_recipes':
      $args['post_type'] = array('osetin_recipe');
    break;
    default:
      $args['post_type'] = array('osetin_recipe', 'post');
    break;
  }
  $osetin_recipes_query = new WP_Query( $args ); 

  $sidebar_name = (osetin_get_field('hide_sidebar') == true) ? false : 'sidebar-index';
  ?>

  <div class="os-container">
    <?php echo build_index_posts($layout_type_for_index, $sidebar_name, $osetin_recipes_query, $sticky_posts, false, $content_field_value, $content_location, $bordered); ?>
  </div>
  <?php endwhile; endif; ?>
<?php get_footer(); ?>