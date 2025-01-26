<?php



// title // excerpt // author_avatar // author_name // date // like // comments // views // cooking_time // categories // cuisines // share // read_more // rating
function osetin_get_hidden_elements_array(){
  global $osetin_current_page_id;
  if(!empty($osetin_current_page_id)){
    $elements_to_hide = osetin_get_field('elements_to_hide', $osetin_current_page_id, false, true);
  }else{
    $elements_to_hide = osetin_get_field('elements_to_hide_option', 'option', false, true);
  }
  if(is_array($elements_to_hide)){
    return $elements_to_hide;
  }else{
    return array();
  }
}

// title // excerpt // author_avatar // author_name // date // like // comments // views // cooking_time // categories // cuisines // share // read_more // rating
function osetin_is_element_visible($element_name){
  if($element_name){
    $elements_to_hide = osetin_get_hidden_elements_array();
    if(is_array($elements_to_hide)){
      return !in_array($element_name, $elements_to_hide);
    }else{
      return true;
    }
  }else{
    return true;
  }
}

function get_user_social_links($user_id, $link_class = ''){
  $html = '';

  if ( get_the_author_meta('google_profile', $user_id) ){
    $html.= '<a href="'.esc_url( get_the_author_meta('google_profile', $user_id)).'" class="'.$link_class.'"><i class="os-icon os-icon-social-googleplus"></i></a>';
  }
  if ( get_the_author_meta('twitter_profile', $user_id) ){
    $html.= '<a href="'.esc_url( get_the_author_meta('twitter_profile', $user_id)).'" class="'.$link_class.'"><i class="os-icon os-icon-social-twitter"></i></a>';
  }
  if ( get_the_author_meta('facebook_profile', $user_id) ){
    $html.= '<a href="'.esc_url( get_the_author_meta('facebook_profile', $user_id)).'" class="'.$link_class.'"><i class="os-icon os-icon-social-facebook"></i></a>';
  }
  if ( get_the_author_meta('linkedin_profile', $user_id) ){
    $html.= '<a href="'.esc_url( get_the_author_meta('linkedin_profile', $user_id)).'" class="'.$link_class.'"><i class="os-icon os-icon-social-linkedin"></i></a>';
  }
  if ( get_the_author_meta('instagram_profile', $user_id) ){
    $html.= '<a href="'.esc_url( get_the_author_meta('instagram_profile', $user_id)).'" class="'.$link_class.'"><i class="os-icon os-icon-social-instagram"></i></a>';
  }
  if ( get_the_author_meta('flickr_profile', $user_id) ){
    $html.= '<a href="'.esc_url( get_the_author_meta('flickr_profile', $user_id)).'" class="'.$link_class.'"><i class="os-icon os-icon-social-flickr"></i></a>';
  }
  if ( get_the_author_meta('rss_url', $user_id) ){
    $html.= '<a href="'.esc_url( get_the_author_meta('rss_url', $user_id)).'" class="'.$link_class.'"><i class="os-icon os-icon-social-rss"></i></a>';
  }

  return $html;
}

function output_recipe_media($post_id = false, $image_size = 'osetin-full-width'){
  $html = '';
  if(('video' == get_post_format()) && osetin_get_field('video_shortcode')){
    $html.= do_shortcode(osetin_get_field('video_shortcode'));
  }elseif(('gallery' == get_post_format()) && osetin_get_field('gallery_images', false, false, true)){
    $gallery_images = osetin_get_field('gallery_images', false, false, true);
    foreach( $gallery_images as $key => $image ){
      $active_class = ($key == 0) ? 'active' : '';
      $html.= '<div class="single-main-media-image-w has-gallery osetin-lightbox-trigger fader-activator '.$active_class.'" id="singleMainMedia'.$key.'" 
      data-lightbox-caption="'.$image['caption'].'" 
      data-lightbox-img-src="'.$image['sizes']['osetin-full-width'].'" 
      data-lightbox-thumb-src="'.$image['sizes']['osetin-medium-square-thumbnail'].'">
      <span class="image-fader lighter"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
      <img src="'.$image['sizes'][$image_size].'"/></div>';
    }
    $html.= '<div class="single-post-gallery-images"><div class="single-post-gallery-images-i">';
    foreach( $gallery_images as $key => $image ){
      $active_class = ($key == 0) ? 'active' : '';
      $html.= '<div class="gallery-image-source '.$active_class.' fader-activator" data-image-id="singleMainMedia'.$key.'">
      <span class="image-fader"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
      <img src="'.$image['sizes']['osetin-medium-square-thumbnail'].'"/></div>';
    }
    $html.= '</div></div>';
  }else{
    if(!$post_id){
      global $post;
      $post_id = $post->ID;
    }


    $img_src = osetin_output_post_thumbnail_url($image_size, false, $post_id);
    $gif_img_attr = '';

    if(pathinfo($img_src, PATHINFO_EXTENSION) == 'gif' && osetin_get_field('is_gif_media')){
      $img_src = osetin_output_post_thumbnail_url('full', false, $post_id);
      $gif_html = '<span class="gif-label"><i class="os-icon os-icon-basic1-082_movie_video_camera"></i><span>'.__('GIF', 'neptune-by-osetin').'</span></span>';

      $extra_img_class = 'gif-media freezeframe-responsive';
      $extra_wrapper_class = 'gif-media-w';
      $is_gif = true;
      if(osetin_get_field('disable_lazy_load_gif') != true){
        if(osetin_get_field('preview_image_for_lazy_gif')){
          $preview_img_src = wp_get_attachment_image_src(osetin_get_field('preview_image_for_lazy_gif'), $image_size);
        }else{
          $preview_img_src = wp_get_attachment_image_src(get_post_thumbnail_id(), $image_size);
        }
        if(!empty($preview_img_src[0])){
          $gif_img_attr = ' data-gif="'.$img_src.'" data-playon="hover" data-wait="true" ';
          $img_src = $preview_img_src[0];
          $extra_img_class = 'gif-media-lazy';
          $extra_wrapper_class = 'gif-media-lazy-w';
        }
      }
    }else{
      $is_gif = false;
      $extra_img_class = '';
      $extra_wrapper_class = ' osetin-lightbox-trigger';
      $gif_html = '';
    }

    if($img_src){
      $html.= '<div class="single-main-media-image-w active fader-activator '.$extra_wrapper_class.'" 
        data-lightbox-caption="'.esc_attr(get_the_title($post_id)).'" 
        data-lightbox-img-src="'.osetin_output_post_thumbnail_url('osetin-full-width', false, $post_id).'">
        <span class="image-fader lighter"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
        <img class="'.$extra_img_class.'" src="'.$img_src.'" alt="'.esc_attr(get_the_title($post_id)).'" '.$gif_img_attr.'/>'.$gif_html.'</div>';
    }
  }
  return $html;
}

function osetin_generate_recipe_rich_snippet(){

    global $post;
    $recipe_post_id = $post->ID;

    $google_meta = array();
    $google_meta['@context'] = "http://schema.org/";
    $google_meta['@type'] = "Recipe";
    $google_meta['name'] = get_the_title($recipe_post_id);
    $google_meta_thumbnail_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $recipe_post_id ), "osetin-full-width" );
    $google_meta['image'] = ($google_meta_thumbnail_arr) ? $google_meta_thumbnail_arr[0] : '';
    $google_meta['author'] = array();
      $google_meta['author']['@type'] = 'Person';
      $google_meta['author']['name'] = get_the_author_meta( 'display_name', $post->post_author );
    $google_meta['datePublished'] = get_the_date('Y-m-d', $recipe_post_id);
    $google_meta['description'] = strip_tags(osetin_get_field('quick_description', $recipe_post_id));

    $cuisines = get_the_terms( $recipe_post_id, 'recipe_cuisine' );
    if ( !empty($cuisines) && ! is_wp_error( $cuisines ) ) {
      $cuisines_names = [];
      foreach($cuisines as $cuisine){
        $cuisines_names[] = $cuisine->name;
      }
      $google_meta['recipeCuisine'] = implode(',', $cuisines_names);
    }

    $categories = get_the_category($recipe_post_id);
    if ( !empty($categories) && ! is_wp_error( $categories ) ) {
      $categories_names = [];
      foreach($categories as $category){
        $categories_names[] = $category->name;
      }
      $google_meta['recipeCategory'] = implode(',', $categories_names);
    }

    $tags = get_the_tags($recipe_post_id);
    if ( !empty($tags) && ! is_wp_error( $tags ) ) {
      $tags_names = [];
      foreach($tags as $tag){
        $tags_names[] = $tag->name;
      }
      $google_meta['keywords'] = implode(',', $tags_names);
    }


    $google_meta['aggregateRating'] = array();
    $recipe_reviews = osetin_recipe_rating_average_and_total($recipe_post_id);
    if($recipe_reviews){
      $google_meta['aggregateRating']['@type'] = 'aggregateRating';
      if($recipe_reviews->avg_rating) $google_meta['aggregateRating']['ratingValue'] = $recipe_reviews->avg_rating;
      if($recipe_reviews->total_reviews) $google_meta['aggregateRating']['reviewCount'] = $recipe_reviews->total_reviews;

      $reviews_args = array(
        'nopaging' => true,
        'meta_key'    => 'recipe',
        'meta_value'  => $recipe_post_id,
        'post_type' => 'osetin_review');
      $osetin_reviews_query = new WP_Query( $reviews_args );
      $reviews_arr = array();
      if ( $osetin_reviews_query->have_posts() ) {
        while ( $osetin_reviews_query->have_posts() ) : $osetin_reviews_query->the_post();
          $review_info = array();
          $review_info['@type'] = 'Review';
          $review_info['reviewRating'] = array();
            $review_info['reviewRating']['@type'] = 'Rating';
            $review_info['reviewRating']['ratingValue'] = osetin_get_field('rating');
          $review_info['author'] = array();
            $review_info['author']['@type'] = 'Person';
            $review_info['author']['name'] = get_the_author_meta( 'display_name' );
          $review_info['datePublished'] = get_the_date('Y-m-d');
          $review_info['reviewBody'] = osetin_get_field('body');
          array_push($reviews_arr, $review_info);
        endwhile;
        wp_reset_postdata(); 
      }
      if(count($reviews_arr) > 0){
        $google_meta['review'] = $reviews_arr;
      }
    }


    if( osetin_have_rows('nutritions', $recipe_post_id) ){
      $google_meta['nutrition'] = array();
      $google_meta['nutrition']['@type'] = 'NutritionInformation';
      while ( osetin_have_rows('nutritions', $recipe_post_id) ) {
        the_row();
        // check if nutrition is matched/assigned to a predefined list of google scheme nutritions
        if(get_sub_field('google_rich_meta_field')){
          $google_meta['nutrition'][get_sub_field('google_rich_meta_field')] = get_sub_field('nutrition_value');
        }else{
          // try to automatically find a mathcing nutrition name
          $default_nutritions = array(
            "calories" => "calories",
            "protein" => "proteinContent",
            "fat" => "fatContent",
            "carbohydrates" => "carbohydrateContent",
            "carbs" => "carbohydrateContent",
            "saturated fat" => "saturatedFatContent",
            "sat_fat" => "saturatedFatContent",
            "trans_fat" => "transFatContent",
            "cholesterol" => "cholesterolContent",
            "fiber" => "fiberContent",
            "sodium" => "sodiumContent",
            "sugar" => "sugarContent");

          $temp_nutrition_name = get_sub_field('nutrition_name');
          if($temp_nutrition_name){
            $cleaned_nutrition_name = strtolower(str_replace(' ', '_', $temp_nutrition_name));
            if(isset($default_nutritions[$cleaned_nutrition_name])){
              $google_meta['nutrition'][$default_nutritions[$cleaned_nutrition_name]] = get_sub_field('nutrition_value');
            }
          }
        }
      }
    }

    $prepTime = osetin_string_to_iso8601_duration(osetin_get_field('recipe_preparation_time', $recipe_post_id));
    $cookTime = osetin_string_to_iso8601_duration(osetin_get_field('recipe_just_cooking_time', $recipe_post_id));
    $totalTime = osetin_string_to_iso8601_duration(osetin_get_field('recipe_cooking_time', $recipe_post_id));
    if($prepTime) $google_meta['prepTime'] = $prepTime;
    if($cookTime) $google_meta['cookTime'] = $cookTime;
    if($totalTime) $google_meta['totalTime'] = $totalTime;

    $google_meta_serves = osetin_get_field('recipe_serves', $recipe_post_id);
    $google_meta['recipeYield'] = ($google_meta_serves) ? $google_meta_serves : '';
    // INGREDIENTS
    if( have_rows('ingredients', $recipe_post_id) ){
      $google_meta['recipeIngredient'] = array();
      $google_meta['recipeIngredient'] = array();
      $searchable_ingredients = osetin_get_field('searchable_ingredients', $recipe_post_id);
      while ( have_rows('ingredients', $recipe_post_id) ) { the_row(); 
        if (get_sub_field('separator')) continue;
        $ingredient = '';
        $ingredient_amount = get_sub_field('ingredient_amount');
        if($searchable_ingredients){
          $ingredient_obj = get_sub_field('ingredient_obj');
          $ingredient = '';
          if(isset($ingredient_obj->name)){
            $ingredient = $ingredient_obj->name;
          }
          if($ingredient_amount) $ingredient.= ': '.$ingredient_amount;
        }else{
          $ingredient_name = get_sub_field('ingredient_name');
          $ingredient = $ingredient_name;
          if($ingredient_amount) $ingredient.= ': '.$ingredient_amount;
        }
        if($ingredient){
          array_push($google_meta['recipeIngredient'], $ingredient);
        }
      }
    }

    // STEPS
    if( have_rows('steps', $recipe_post_id) ){
      $google_meta['recipeInstructions'] = array();
      while ( have_rows('steps', $recipe_post_id) ) { the_row();
        array_push($google_meta['recipeInstructions'], strip_tags(get_sub_field('step_description')));
      }
    }
    if(!empty($google_meta)){
      return '<script type="application/ld+json">' . json_encode($google_meta) . '</script>';
    }else{
      return '';
    }
}

function osetin_build_stars($rating = false){
  $stars_html = '';
  if($rating){
    $stars_html.= '<div class="review-stars-w">';
    for($i = 1; $i <= 5; $i++){
      if(round($rating) < $i) $star_state = 'rating-star-off';
      else $star_state = 'rating-star-on';
      $stars_html.= '<i class="os-icon os-icon-star-full '.$star_state.'"></i>';
    }
    $stars_html.= '</div>';
  }
  return $stars_html;
}

function osetin_get_sharing_icons(){
  $sharing_url = get_the_permalink();
  $img_to_pin = has_post_thumbnail() ? wp_get_attachment_url( get_post_thumbnail_id() ) : "";
  $osetin_current_title = is_front_page() ? get_bloginfo('description') : wp_title('', false);

  $facebook_share_link = 'http://www.facebook.com/sharer.php?u='.urlencode($sharing_url);
  $pinterest_share_link = '//www.pinterest.com/pin/create/button/?url='.$sharing_url.'&amp;media='.$img_to_pin.'&amp;description='.$osetin_current_title;
  ?>
  <a href="<?php echo esc_url($facebook_share_link); ?>" target="_blank" class="archive-item-share-link aisl-facebook"><i class="os-icon os-icon-facebook"></i></a>
  <a href="<?php echo 'http://twitter.com/share?url='.$sharing_url.'&amp;text='.urlencode($osetin_current_title); ?>" target="_blank" class="archive-item-share-link aisl-twitter"><i class="os-icon os-icon-twitter"></i></a>
  <a href="<?php echo esc_url($pinterest_share_link); ?>" target="_blank" class="archive-item-share-link aisl-pinterest"><i class="os-icon os-icon-pinterest"></i></a>
  <a href="<?php echo 'mailto:?Subject='.$osetin_current_title.'&amp;Body=%20'.$sharing_url ?>" target="_blank" class="archive-item-share-link aisl-mail"><i class="os-icon os-icon-basic-mail-envelope"></i></a>
  <?php
}

function osetin_recipe_rating_average_and_total($recipe_post_id){
  global $wpdb;
  $review_info = $wpdb->get_row( $wpdb->prepare( "
  SELECT      avg(pmr.meta_value) as avg_rating, 
              pm.meta_value as recipe_id,
              count(p.ID) as total_reviews
  FROM        $wpdb->posts as p 
  LEFT JOIN   $wpdb->postmeta as pm
              on pm.post_id = p.ID
  LEFT JOIN   $wpdb->postmeta as pmr
              on pmr.post_id = p.ID
  WHERE       p.post_status = 'publish' and 
              p.post_type = 'osetin_review' and
              pm.meta_key = 'recipe' and 
              pm.meta_value = %d and
              pmr.meta_key = 'rating'
  GROUP BY    pm.meta_value
  ", $recipe_post_id ));
  if ( $review_info ) {
    return $review_info;
  }else{
    return false;
  }
}

function osetin_string_to_iso8601_duration($string) {
  if($string){
    $converted_to_time = strtotime($string, 0);
    if($converted_to_time){
      
      $units = array(
        "Y" => 365*24*3600,
        "D" =>     24*3600,
        "H" =>        3600,
        "M" =>          60,
        "S" =>           1,
      );

      $str = "P";
      $istime = false;

      foreach ($units as $unitName => &$unit) {
        $quot  = intval($converted_to_time / $unit);
        $converted_to_time -= $quot * $unit;
        $unit  = $quot;
        if ($unit > 0) {
          if (!$istime && in_array($unitName, array("H", "M", "S"))) { // There may be a better way to do this
            $str .= "T";
            $istime = true;
          }
          $str .= strval($unit) . $unitName;
        }
      }

      return $str;
    }
  }
  return false;
}


function build_index_posts($layout_type = 'magazine_v1', $sidebar_name = false, $osetin_query = false, $sticky_posts = false, $header_arr = false, $content = false, $content_location = false, $bordered = false){
  $archive_class = osetin_get_archive_class($layout_type);
  $archive_wrapper_class = osetin_get_archive_wrapper_class($layout_type);
  $masonry_layout_mode = osetin_get_masonry_layout_mode($layout_type);
  $layout_settings = osetin_get_layout_settings_arr($layout_type);
  $show_ads = osetin_get_field('ad_between_posts_type', 'option');
  $ads_code = osetin_generate_ads_code($show_ads, $layout_settings['ad_wrapper_class']);
  // sidebar
  $sidebar_location = osetin_get_field('sidebar_position_for_index_option', 'option', 'right');
  $sidebar_html = '';

  $content_html = '';
  if($content_location && $content_location != 'none'){
    $content_html = do_shortcode($content);
  }

  if($sidebar_name && is_active_sidebar( $sidebar_name )){
    $sidebar_class = 'with-sidebar sidebar-location-'.$sidebar_location;
    $sidebar_html.= '<div class="archive-sidebar color-scheme-'.osetin_get_field('sidebar_background_color_type', 'option', 'light').' " style="'.osetin_get_css_prop('background-color', osetin_get_field('sidebar_background_color', 'option')) . osetin_get_css_prop('background-image', osetin_get_field('sidebar_background_image', 'option')).'">';
    ob_start();
    dynamic_sidebar( $sidebar_name );
    $sidebar_html.= ob_get_clean();
    $sidebar_html.= '</div>';
  }else{
    $sidebar_class = '';
  }

  if($bordered){
    $bordered_class = 'bordered';
  }else{
    $bordered_class = '';
  }
  
  $wrapper_step = 0;
  $item_step = 1;
  $counter = 1;

  $html = '';

  if(($content_location == 'before_all') && $content_html){
    $html.= '<div class="page-content-field-w">'.$content_html.'</div>';
  }
  $html.= '<div class="archive-posts-w '.$sidebar_class.' '.$bordered_class.'">';
    if($sidebar_location == 'left'){
      $html.= $sidebar_html;
    }
    $html.= '<div class="archive-posts '.$archive_wrapper_class.'">';
      if(($content_location == 'before_posts') && $content_html){
        $html.= $content_html;
      }
      $html.= '<div class="'.$archive_class.'" data-layout-mode="'.$masonry_layout_mode.'">';
        if($header_arr){

          $html.= '<div class="archive-title-w">';
            $html.= '<h1 class="page-title">'.$header_arr['title'].'</h1>';
            if ( ! empty( $header_arr['description'] ) ) {
              $html.= '<h2 class="page-content-sub-title">'.$header_arr['description'].'</h2>';
            }
          $html.= '</div>';
        }
        if ( $osetin_query->have_posts() ) {
          if($sticky_posts){
            $only_image = true;
            $html.= '<div class="sticky-roll-w"><div class="owl-carousel sticky-posts-owl-slider">';
            foreach($sticky_posts as $sticky_post){
              global $post;
              $post = $sticky_post;
              setup_postdata($post);
              $current_step_class = 'full_full_over';
              $limit = osetin_get_limit_by_item_type($current_step_class);
              ob_start();
              include(locate_template('content.php'));
              $html.= ob_get_clean();
              wp_reset_postdata();
            }
            $html.= '</div></div>';
            unset($only_image);
          }
          while ( $osetin_query->have_posts() ) : $osetin_query->the_post();
            if($ads_code != '' && ($layout_settings['ad_position'] == $counter)) {
              $html.= $ads_code;
            }
            if(($item_step == 1) || in_array(($item_step - 1), $layout_settings['wrapper_ends'])){
              $html.= '<div class="masonry-item any '.$layout_settings['wrapper_classes'][$wrapper_step].'">';
              $wrapper_step++;
              if($wrapper_step >= count($layout_settings['wrapper_classes'])){
                $wrapper_step = $layout_settings['loop_start_from_wrapper_step'];
              }
            }
            $current_step_class = $layout_settings['item_classes'][$item_step - 1];
            $limit = osetin_get_limit_by_item_type($current_step_class, $layout_settings['wrapper_classes'][$wrapper_step], $archive_class);
            ob_start();
            include(locate_template('content.php'));
            $html.= ob_get_clean();
                  

            if(in_array($item_step, $layout_settings['wrapper_ends']) || ($counter == $osetin_query->post_count)){
              $html.= '</div>';
            }

            if($item_step >= count($layout_settings['item_classes'])){
              $item_step = $layout_settings['loop_start_from_item_step'];
            }
            $item_step++;
            $counter++;
          endwhile;
        }else{
          $html.= osetin_load_template_part( 'content', 'none' ); 
        }
      $html.= '</div>';
      // pagination
      global $wp_query;
      $temp_query = $wp_query;
      $wp_query = $osetin_query;
      ob_start();
      osetin_output_navigation();
      $html.= ob_get_clean();
      $wp_query = $temp_query;
      wp_reset_postdata();
      if(($content_location == 'after_posts') && $content_html){
        $html.= $content_html;
      }
    $html.= '</div>';
    if($sidebar_location == 'right'){
      $html.= $sidebar_html;
    }
  $html.= '</div>';
  if(($content_location == 'after_all') && $content_html){
    $html.= '<div class="page-content-field-w">'.$content_html.'</div>';
  }
  return $html;
}

// INGREDIENTS
// -------------

function osetin_get_all_ingredients() {

    global $wpdb;

    $label_ingredient_name = 'ingredient_name';
    $label_total_recipes = 'total_recipes';
    $query_meta_key = 'ingredients_%_ingredient_name';
    $query_post_status = 'publish';
    $query_post_type = 'osetin_recipe';

    $r = $wpdb->get_results( $wpdb->prepare( "
        SELECT pm.meta_value as '%s', count(pm.post_id) as '%s'
        FROM $wpdb->postmeta pm 
        LEFT JOIN $wpdb->posts p
        ON p.ID = pm.post_id 
        WHERE pm.meta_key like '%s'
        AND p.post_status = '%s' 
        AND p.post_type = '%s'
        group by lower(pm.meta_value)
    ", $label_ingredient_name, $label_total_recipes, $query_meta_key, $query_post_status, $query_post_type ) );

    return $r;
}


function osetin_get_the_title($post_id){
  $custom_title = osetin_get_field('custom_title', $post_id);
  if($custom_title){
    return $custom_title;
  }else{
    return get_the_title($post_id);
  }
}

function osetin_is_imaged_header($post_id = false){
  if(!$post_id) return false;
  return (osetin_get_field('hide_title', $post_id) != true) && (has_post_thumbnail($post_id) || osetin_is_bbpress());
}

function osetin_top_bar_visible(){
  return ((osetin_get_field('show_top_member_bar', 'option') != 'no') && osetin_is_userpro_installed()) || (function_exists('WC') && osetin_is_woocommerce_installed() && ((osetin_get_field('show_cart_link_in_the_top_bar', 'option') != 'no') || (osetin_get_field('show_checkout_link_in_the_top_bar', 'option') != 'no')));
}

function osetin_top_bar_member_buttons_visible(){
  return (osetin_is_userpro_installed() && (osetin_get_field('show_top_member_bar', 'option') != 'no'));
}

function osetin_top_bar_cart_button_visible(){
  return (function_exists('WC') && osetin_is_woocommerce_installed() && (osetin_get_field('show_cart_link_in_the_top_bar', 'option') != 'no'));
}

function osetin_top_bar_checkout_button_visible(){
  return (function_exists('WC') && osetin_is_woocommerce_installed() && (osetin_get_field('show_checkout_link_in_the_top_bar', 'option') != 'no'));
}

function osetin_is_woocommerce_installed(){
  return class_exists( 'WooCommerce' );
}

function osetin_is_regular_header($post_id = false){
  if(!$post_id) return true;
  return (osetin_get_field('hide_title', $post_id) != true) && (!has_post_thumbnail($post_id) && !osetin_is_bbpress());
}

function osetin_is_active_sidebar($location = 'sidebar-index', $post_id = false){
  if($post_id){
    if(osetin_get_field('hide_sidebar', $post_id) == true){
      return false;
    } 
  }else{
    if(osetin_get_field('hide_sidebar') == true){
      return false;
    } 
  }
  switch ($location) {
    case 'sidebar-index':
      if(osetin_is_bbpress_userpage()){
        return false;
      }else{
        return is_active_sidebar($location);
      }
      break;
    
    default:
      return true;
      break;
  }
}

function osetin_is_bbpress_userpage(){
  if(osetin_is_bbpress()){
    return bbp_is_single_user();
  }else{
    return false;
  }
}

function osetin_get_hero_recipes_slider(){
  $hero_posts = osetin_get_field('hero_posts', false, false, true);
  $html = '';
  if($hero_posts){
    $only_image = true;
    $hidden_elements_array = array();
    $html.= '<div class="os-container hero-roll-w"><div class="owl-carousel hero-posts-owl-slider">';
    foreach($hero_posts as $hero_post){
      global $post;
      $post = $hero_post;
      setup_postdata($post);
      $current_step_class = 'hero';
      $limit = osetin_get_limit_by_item_type($current_step_class);
      ob_start();
      include(locate_template('content.php'));
      $html.= ob_get_clean();
      wp_reset_postdata();
    }
    $html.= '</div></div>';
    unset($only_image);
    unset($hidden_elements_array);
  }
  return $html;
}

function osetin_show_featured_recipes_slider(){
  $boxed = osetin_get_field('make_featured_recipes_fixed_width');
  $featured_recipes_layout_type = osetin_get_field('featured_recipes_layout_type');
  $featured_recipes = osetin_get_field('featured_recipes', false, false, true);
  if(osetin_get_field('show_featured_recipes_slider') && $featured_recipes){
    $featured_recipes_bg = osetin_get_field('featured_recipes_background_image');
    if(empty($featured_recipes_bg)){
      $featured_recipes_bg = get_template_directory_uri().'/assets/img/patterns/flowers_light.jpg';
    }
    switch($featured_recipes_layout_type){
      case 'small':
        $step_ends = array(1);
        $step_classes = array('half_half');
        $featured_layout_class = 'featured-layout-small';
      break;
      case 'full':
        $step_ends = array(1);
        $step_classes = array('full_full');
        $featured_layout_class = 'featured-layout-full';
      break;
      case 'wide':
        $step_ends = array(1);
        $step_classes = array('full_half');
        $featured_layout_class = 'featured-layout-wide';
      break;
      case 'mixed':
      default:
        $step_ends = array(1,4,5,8);
        $step_classes = array('full_full', 'half_half', 'half_half', 'full_half', 'full_full', 'full_half', 'half_half', 'half_half');
        $featured_layout_class = 'featured-layout-mixed';
      break;
    }
    $current_class_step = 1;
    $current_step = 1;
    if($boxed) echo '<div class="os-container">';
    echo '<div class="featured-recipes-slider-w '.$featured_layout_class.'" style="background-image: url('.$featured_recipes_bg.'); background-repeat: repeat;">';
      echo '<div class="featured-recipes-fade-left"><div class="icon-w"><i class="os-icon os-icon-chevron-left"></i></div></div>';
      echo '<div class="featured-recipes-fade-right"><div class="icon-w"><i class="os-icon os-icon-chevron-right"></i></div></div>';
      echo '<div class="featured-recipes-slider-i owl-carousel featured-recipes-owl-slider">';
        echo '<div class="featured-recipes-slider-item">';
          global $post;
          foreach($featured_recipes as $post){
            setup_postdata($post); 
            $cooking_time = osetin_get_field('recipe_cooking_time');
            ?>
              <div class="featured-recipes-item <?php echo esc_attr($step_classes[$current_class_step - 1]); ?>">
                <div class="featured-recipes-item-i">
                  <div class="featured-recipe-media-w">
                    <a href="<?php the_permalink(); ?>" class="featured-recipe-thumbnail fader-activator" style="background-image:url(<?php echo osetin_output_post_thumbnail_url('osetin-medium-square-thumbnail', false); ?>); background-size: cover;">
                      <span class="image-fader"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
                    </a>
                  </div>
                  <div class="featured-recipe-content-w">
                    <div class="featured-recipe-title-w">
                      <h3 class="featured-recipe-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    </div>
                    <div class="featured-recipe-categories-w">
                      <?php echo get_the_category_list(); ?>
                    </div>
                    <div class="featured-recipe-details-w">
                      <div class="featured-recipe-cooking-time">
                        <?php if(!empty($cooking_time)){ ?>
                          <?php esc_html_e('Cooking Time:', 'neptune-by-osetin'); ?> <?php echo osetin_get_field('recipe_cooking_time'); ?>
                        <?php }else{ ?>
                          <?php echo get_the_category_list(); ?>
                        <?php } ?>
                      </div>
                      <div class="featured-recipe-features">
                        <?php 
                        $this_recipe_features_with_icons = osetin_get_recipe_features_with_icons(get_the_ID());
                        if (is_array($this_recipe_features_with_icons) || is_object($this_recipe_features_with_icons)){
                          echo '<ul>';
                          foreach($this_recipe_features_with_icons as $feature){
                            $term = $feature['term'];
                            echo '<li><span class="tooltip-trigger" data-tooltip-header="'.esc_attr($term->name).'"><img src="'.$feature['icon_url'].'" alt="'.esc_attr($term->name).'"/></span></li>';
                          }
                          echo '</ul>';
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php if(in_array($current_class_step, $step_ends) && ($current_step < count($featured_recipes))) echo '</div><div class="featured-recipes-slider-item">'; ?>
            
            <?php
            // reset postdata to default values from a main loop
            if($current_class_step >= count($step_classes)){
              $current_class_step = 0;
            }
            $current_step++;
            $current_class_step++;
          }
        echo '</div>'; // close the slider item tag;
      echo '</div>';
    echo '</div>';
    if($boxed) echo '</div>';
    wp_reset_postdata();
  }
}

function osetin_get_layout_settings_arr($layout_type){
  $layout_settings = array();
  $layout_settings['loop_start_from_item_step'] = 0;
  $layout_settings['loop_start_from_wrapper_step'] = 0;

  switch($layout_type){

    case 'magazine_v1':
      $layout_settings['ad_position'] = 5;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1,4,7,8);
      $layout_settings['wrapper_classes'] = array('half first-in-row', 'half last-in-row', 'half first-in-row', 'half last-in-row');
      $layout_settings['item_classes'] = array('full_full', 'full_third', 'full_third', 'full_third', 'full_third', 'full_third', 'full_third', 'full_full');
    break;

    case 'magazine_v2':
      $layout_settings['ad_position'] = 5;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1,2,3,4,5,6);
      $layout_settings['wrapper_classes'] = array('half first-in-row', 'half last-in-row', 'half first-in-row', 'half last-in-row', 'half first-in-row', 'half last-in-row');
      $layout_settings['item_classes'] = array('full_full', 'full_full', 'full_third', 'full_third', 'full_third', 'full_third');
    break;

    case 'masonry_2':
      $layout_settings['ad_position'] = 5;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1,2);
      $layout_settings['wrapper_classes'] = array('first-in-row half', 'half last-in-row');
      $layout_settings['item_classes'] = array('full_full', 'full_full');
    break;

    case 'masonry_3':
      $layout_settings['ad_position'] = 1;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1,2,3);
      $layout_settings['wrapper_classes'] = array('third first-in-row', 'third', 'third last-in-row');
      $layout_settings['item_classes'] = array('full_full', 'full_full', 'full_full');
    break;

    case 'masonry_4':
      $layout_settings['ad_position'] = 5;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1,2,3,4);
      $layout_settings['wrapper_classes'] = array('fourth first-in-row', 'fourth', 'fourth', 'fourth last-in-row');
      $layout_settings['item_classes'] = array('full_full', 'full_full', 'full_full', 'full_full');
    break;

    case 'full_width':
      $layout_settings['ad_position'] = 5;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1);
      $layout_settings['wrapper_classes'] = array('full');
      $layout_settings['item_classes'] = array('full_full');
    break;

    case 'half_image':
      $layout_settings['ad_position'] = 5;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1);
      $layout_settings['wrapper_classes'] = array('full');
      $layout_settings['item_classes'] = array('full_half');
    break;

    case 'packery_3':
      $layout_settings['ad_position'] = false;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1);
      $layout_settings['wrapper_classes'] = array('third');
      $layout_settings['item_classes'] = array('full_full');
    break;

    case 'packery_2':
      $layout_settings['ad_position'] = false;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1);
      $layout_settings['wrapper_classes'] = array('half');
      $layout_settings['item_classes'] = array('full_full');
    break;

    default:
      $layout_settings['ad_position'] = 1;
      $layout_settings['ad_wrapper_class'] = 'full';
      $layout_settings['wrapper_ends'] = array(1);
      $layout_settings['wrapper_classes'] = array('third');
      $layout_settings['item_classes'] = array('full_full');
    break;
  }
  return $layout_settings;
}

function osetin_generate_ads_code($show_ads, $ad_wrapper_class){
  $ads_code = '';
  if($show_ads != 'none'){
    if($ad_wrapper_class){
      $ads_code = '<div class="masonry-item any '.esc_attr($ad_wrapper_class).'">';
    }
    if($show_ads == 'image'){
      $ads_code.= '<div class="magic-box-w"><a href="'.esc_url(osetin_get_field('ad_between_posts_url', 'option')).'"><img src="'.esc_url(osetin_get_field('ad_between_posts_image', 'option')).'" alt="magic-box"/></a></div>';
    }
    if($show_ads == 'html'){
      $ads_code.= '<div class="magic-box-w">'.osetin_get_field('ad_between_posts_html', 'option').'</div>';
    }
    if($ad_wrapper_class){
      $ads_code.= '</div>';
    }
  }
  return $ads_code;
}

function osetin_get_current_url(){
  $osetin_current_url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
  $osetin_current_url .= $_SERVER['SERVER_NAME'];
  $osetin_current_url .= $_SERVER['REQUEST_URI'];
  return $osetin_current_url;
}


function osetin_get_all_features_with_icons(){
  $features_icons = array();

  $all_recipes_features = get_terms( 'recipe_feature' );
  if (is_array($all_recipes_features) || is_object($all_recipes_features)){
    foreach($all_recipes_features as $feature){
      $feature_icon_url = osetin_get_field('category_icon', "recipe_feature_{$feature->term_id}");
      if($feature_icon_url){
        $features_icons["feature_".$feature->term_id] = $feature_icon_url;
      }
    }
  }
  return $features_icons;

}


function osetin_get_recipe_cuisines_with_icons($post_id){
  $cuisines = get_the_terms( $post_id, 'recipe_cuisine' );
  if ( $cuisines && ! is_wp_error( $cuisines ) ) {

    $cuisine_data_arr = array();

    foreach ( $cuisines as $term ) {
      $cuisine_data = array();
      $cuisine_data['term'] = $term;
      $cuisine_icon = osetin_get_field('category_icon', "recipe_cuisine_{$term->term_id}", false);
      // if cuisine has icon - add it to the list, if not - ignore
      if($cuisine_icon){
        $cuisine_data['icon_url'] = $cuisine_icon;
        $cuisine_data_arr[] = $cuisine_data;
      }
    }
              
    return $cuisine_data_arr;
  }
}

function osetin_get_recipe_features_with_icons($post_id){
  $features = get_the_terms( $post_id, 'recipe_feature' );
  if ( $features && ! is_wp_error( $features ) ) {

    $feature_data_arr = array();

    foreach ( $features as $term ) {
      $feature_data = array();
      $feature_data['term'] = $term;
      $feature_icon = osetin_get_field('category_icon', "recipe_feature_{$term->term_id}", false);
      // if feature has icon - add it to the list, if not - ignore
      if($feature_icon){
        $feature_data['icon_url'] = $feature_icon;
        $feature_data_arr[] = $feature_data;
      }
    }
              
    return $feature_data_arr;
  }
}

function osetin_get_limit_by_item_type($item_class, $wrapper_class = false, $archive_class = 'masonry-grid'){
  if($archive_class == 'masonry-grid'){
    switch($item_class){
      case 'hero':
        return 20;
      break;
      case 'full_full_over':
        return 30;
      break;
      case 'full_full':
        switch($wrapper_class){
          case 'half':
            return 30;
            break;
          case 'third':
            return 25;
            break;
          case 'fourth':
            return 20;
            break;
          default:
            return 30;
            break;
        }
      break;
      default:
        return false;
      break;
    }
  }else{
    return 50;
  }
}

function osetin_get_archive_thumb_name($item_class){
  switch($item_class){
    case 'full_full_over':
      return 'osetin-full-width';
    break;
    case 'hero':
      return 'osetin-full-width';
    break;
    case 'full_full':
      return 'large';
    break;
    default:
      return 'osetin-medium-square-thumbnail';
    break;
  }
}

function osetin_get_archive_wrapper_class($layout_type = false){
  if($layout_type == false) $layout_type = osetin_get_settings_field('layout_type_for_index', 'magazine_v1');
  switch($layout_type){
    case 'magazine_v1':
      return 'masonry-grid-w magazine-v1';
    break;
    case 'magazine_v2':
      return 'masonry-grid-w magazine-v2';
    break;
    case 'masonry_2':
      return 'masonry-grid-w per-row-2';
    break;
    case 'masonry_3':
      return 'masonry-grid-w per-row-3';
    break;
    case 'masonry_4':
      return 'masonry-grid-w per-row-4';
    break;
    case 'full_width':
      return 'list-items-w list-items-full-width';
    break;
    case 'half_image':
      return 'list-items-w list-items-half-image';
    break;
    case 'packery':
      return 'masonry-grid-w masonry-title-image';
    break;
    default:
      return 'list-items-w list-items-half-image';
    break;
  }
}

function osetin_get_archive_class($layout_type_for_index = 'magazine_v1'){
  if(in_array($layout_type_for_index, array('masonry_2', 'masonry_3', 'masonry_4', 'magazine_v1', 'magazine_v2', 'packery'))){
    return 'masonry-grid';
  }else{
    return 'list-items';
  }
}

function osetin_get_masonry_layout_mode($layout_type_for_index = 'magazine_v1'){
  if(in_array($layout_type_for_index, array('packery'))){
    return 'packery';
  }else{
    return 'fitRows';
  }
}

function osetin_get_step_media_vars($images_count){
  switch($images_count){
    case 1:
      $extra_class = 'single-media-1-image';
      $img_size = 'large';
    break;
    case 2:
      $extra_class = 'single-media-2-image';
      $img_size = 'osetin-medium-square-thumbnail';
    break;
    case 3:
      $extra_class = 'single-media-3-image';
      $img_size = 'osetin-medium-square-thumbnail';
    break;
    case 4:
      $extra_class = 'single-media-4-image';
      $img_size = 'thumbnail';
    break;
    case 5:
      $extra_class = 'single-media-5-image';
      $img_size = 'thumbnail';
    break;
    default:
      $extra_class = '';
      $img_size = 'thumbnail';
    break;
  }
  return array('extra_class' => $extra_class, 'img_size' => $img_size);
}

function osetin_get_difficulty_string($number){
  $difficulties = array('1' => __('Easy', 'neptune-by-osetin'), '2' => __('Medium', 'neptune-by-osetin'), '3' => __('Hard', 'neptune-by-osetin'));
  if($number){
    return $difficulties[$number];
  }else{
    return '';
  }
}

function osetin_count_sidebar_widgets( $sidebar_id, $echo = false ) {
    $the_sidebars = wp_get_sidebars_widgets();
    if( !isset( $the_sidebars[$sidebar_id] ) )
        return 4;
    $count = count( $the_sidebars[$sidebar_id] );
    if($count > 4) $count = 4;
    if( $echo )
        echo esc_attr($count);
    else
        return $count;
}

function osetin_get_css_prop($property, $field_value, $default = false, $extra = ''){
  if($field_value){
    if($property == 'background-image'){
      return $property.':url('.$field_value.'); '.$extra;
    }else{
      return $property.':'.$field_value.'; '.$extra;
    }
  }elseif($default){
    return $property.':'.$default.'; '.$extra;
  }else{
    return '';
  }
}

function osetin_get_default_value($field_name = ''){
  global $my_osetin_acf;
  return $my_osetin_acf->get_default_var($field_name);
}


function osetin_have_rows($field_name, $post_id = false){
  if(function_exists('have_rows')){
    return have_rows($field_name, $post_id);
  }else{
    return false;
  }
}

function osetin_output_navigation(){
  if(function_exists('wp_pagenavi')){ ?>
    <div class="archive-pagination pagenavi-pagination">
      <?php wp_pagenavi(); ?>
    </div>
  <?php }else{ ?>
    <?php if(get_next_posts_link() || get_previous_posts_link()){ ?>
      <div class="archive-pagination classic-pagination">
        <div class="archive-pagination-prev"><?php previous_posts_link( esc_html__('Previous Entries', 'neptune-by-osetin') ); ?></div>
        <div class="archive-pagination-next"><?php next_posts_link( esc_html__('Next Entries', 'neptune-by-osetin'), '' ); ?></div>
      </div>
    <?php } ?>
  <?php }
}

function osetin_get_field($field_name, $post_id = false, $default = '', $expecting_array = false){
  if(function_exists('get_field')){
    $field_value = get_field($field_name, $post_id);
    if(($expecting_array == false) && is_array($field_value)){
      if(reset($field_value)){
        $final_value = reset($field_value);
      }else{
        $final_value = $field_value;
      }
    }else{
      $final_value = $field_value;
    }
    if(empty($final_value)) $final_value = get_field($field_name, $post_id);
    if(empty($final_value) && $default != '') return $default;
    else return $final_value;
  }else{
    if($default == ''){
      return osetin_get_default_value($field_name);
    }else{
      return $default;
    }
  }
}


// Loads get_template_part() into variable
function osetin_load_template_part($template_name, $part_name=null) {
  ob_start();
  get_template_part($template_name, $part_name);
  $var = ob_get_clean();
  return $var;
}

function osetin_get_settings_field($field_name, $default = '', $post_id = false, $forse_single = false, $expecting_array = false)
{
  if(is_single() || is_page() || $forse_single){
    if(!$post_id){
      global $post;
      if(isset($post->ID)) $post_id = $post->ID;
    }
    $temp_val = osetin_get_field($field_name, $post_id, 'default', $expecting_array);
    if(($temp_val === 'default') || (null === $temp_val) || ($temp_val === '')){
      $val = osetin_get_field($field_name.'_option', 'option', '', $expecting_array);
    }else{
      $val = $temp_val;
    }
  }else{
    $val = osetin_get_field($field_name.'_option', 'option', '', $expecting_array);
  }
  if(null === $val){
    $val = $default;
  }
  return $val;
}


// ------------
// Customize default wordpress excerpt with a custom length and "more" text depending on user select in admin
// ------------

function osetin_excerpt($limit = false, $more = TRUE, $more_link_class ='read-more-link', $more_appendix = '...') {
  if(!$limit){
    $limit = osetin_get_field('excerpt_length_option', 'option', 20);
  }
  if($more){
    return wp_trim_words(get_the_excerpt(), $limit, osetin_excerpt_more($more_link_class));
  }else{
    return wp_trim_words(get_the_excerpt(), $limit, $more_appendix);
  }

}





// ------------
// Excerpt "more" text settings
// ------------

function osetin_excerpt_more($more_link_class = 'read-more-link') {
  if(get_post_format(get_the_ID()) == 'link'){
    return '...<div class="'.$more_link_class.'"><a href="'. osetin_get_field( 'external_link' ) . '">' . esc_html__('Read More', 'neptune-by-osetin') . '</a></div>';
  }else{
    return '...<div class="'.$more_link_class.'"><a href="'. get_permalink( get_the_ID() ) . '">' . esc_html__('Read More', 'neptune-by-osetin') . '</a></div>';
  }
}
add_filter( 'excerpt_more', 'osetin_excerpt_more' );



function osetin_get_post_thumbnail($post_id, $thumbnail_name = 'osetin-medium-square-thumbnail'){
  if(has_post_thumbnail($post_id)){
    return get_the_post_thumbnail($post_id, $thumbnail_name);
  }else{
    if($thumbnail_name == 'osetin-medium-square-thumbnail'){
      $placeholder_url = osetin_get_placeholder_image_url(true);
    }else{
      $placeholder_url = osetin_get_placeholder_image_url();
    }
    return '<img src="'.$placeholder_url.'" alt="'.__('Featured Image', 'neptune-by-osetin').'"/>';
  }
}


function osetin_output_post_thumbnail_url($size = 'post-thumbnail', $forse_single = false, $post_id = false)
{
  if(is_single() || $forse_single){
    if(has_post_thumbnail()) $img_arr = wp_get_attachment_image_src(get_post_thumbnail_id(), 'osetin-full-width');
    else return osetin_get_placeholder_image_url();
  }else{
    if(!$post_id){
      $post_id = get_the_ID();
    }
    $img_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
  }
  if(isset($img_arr[0])){
    return $img_arr[0];
  }else{
    return osetin_get_placeholder_image_url();
  }
}

function osetin_get_placeholder_image_url($squared = false){
  $placeholder_url = $squared ? get_template_directory_uri().'/assets/img/placeholder-square.jpg' : get_template_directory_uri().'/assets/img/placeholder.jpg';
  $placeholder_img_id = osetin_get_field('placeholder_image', 'option');
  if ($placeholder_img_id){
    $size_name = $squared ? 'osetin-medium-square-thumbnail' : 'osetin-full-width';
    $img_url_arr = wp_get_attachment_image_src($placeholder_img_id, $size_name);
    if($img_url_arr){
      $placeholder_url = $img_url_arr[0];
    }
  }
  return $placeholder_url;
}

function osetin_output_post_thumbnail_data_arr($size = 'post-thumbnail', $forse_single = false, $post_id = false)
{
  if(is_single() || $forse_single){
    if(has_post_thumbnail()) $img_arr = wp_get_attachment_image_src(get_post_thumbnail_id(), 'osetin-full-width');
  }else{
    if(!$post_id){
      $post_id = get_the_ID();
    }
    $img_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
  }
  if(isset($img_arr)){
    return $img_arr;
  }else{
    return false;
  }
}

function osetin_hex_to_rgb($hex, $tp) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b, $tp);
   return 'rgba('.implode(",", $rgb).')';
}

function osetin_get_number_of_posts_per_page(){
  if(osetin_get_field('override_posts_per_page')){
    return osetin_get_field('override_posts_per_page');
  }else{
    return get_option('posts_per_page');
  }
}

function osetin_is_userpro_installed(){
  return function_exists('userpro_is_logged_in');
}

function osetin_is_bbpress(){
  return (function_exists('is_bbpress') && is_bbpress());
}

function get_user_meal_plans_permalink(){
  $args = array(
            'post_type' => 'page',
            'fields' => 'ids',
            'nopaging' => true,
            'posts_per_page' => 1,
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-user-meal-plans.php'
        );
  $meal_plans_page = get_posts( $args );
  if($meal_plans_page && isset($meal_plans_page[0]))
    return get_permalink($meal_plans_page[0]);
  return false;
}

function osetin_post_share_box(){
  $sharing_url = osetin_get_current_url();
  $img_to_pin = has_post_thumbnail() ? wp_get_attachment_url( get_post_thumbnail_id() ) : "";
  $osetin_current_title = is_front_page() ? get_bloginfo('description') : wp_title('', false);

  $facebook_share_link = 'http://www.facebook.com/sharer.php?u='.urlencode($sharing_url);
  $pinterest_share_link = '//www.pinterest.com/pin/create/button/?url='.$sharing_url.'&amp;media='.$img_to_pin.'&amp;description='.$osetin_current_title;
  ?>
  <div class="post-share-box">
    <div class="psb-close"><i class="os-icon os-icon-thin-close-round"></i></div>
    <h3 class="post-share-header"><?php esc_html_e('Share it on your social network:', 'neptune-by-osetin'); ?></h3>
    <div class="psb-links">
      <a href="<?php echo esc_url($facebook_share_link); ?>" target="_blank" class="psb-link psb-facebook"><i class="os-icon os-icon-facebook"></i></a>
      <a href="<?php echo 'http://twitter.com/share?url='.$sharing_url.'&amp;text='.urlencode($osetin_current_title); ?>" target="_blank" class="psb-link psb-twitter"><i class="os-icon os-icon-twitter"></i></a>
      <a href="<?php echo esc_url($pinterest_share_link); ?>" target="_blank" class="psb-link psb-pinterest"><i class="os-icon os-icon-pinterest"></i></a>
      <a href="<?php echo 'mailto:?Subject='.$osetin_current_title.'&amp;Body=%20'.$sharing_url ?>" target="_blank" class="psb-link psb-mail"><i class="os-icon os-icon-basic-mail-send"></i></a>
    </div>
    <div class="psb-url">
      <div class="psb-url-heading"><?php esc_html_e('Or you can just copy and share this url', 'neptune-by-osetin'); ?></div>
      <input type="text" class="psb-url-input" value="<?php echo esc_url($sharing_url); ?>">
    </div>
  </div>
  <?php
}

function osetin_output_breadcrumbs(){
  if(osetin_is_bbpress()){
    bbp_breadcrumb();
  }else{
    echo '<ul class="bar-breadcrumbs">';
      if(is_home()){
        echo '<li>'.esc_html__('Home', 'neptune-by-osetin').'</li>';
      }elseif(is_category()){
        echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'neptune-by-osetin').'</a></li>';
        echo '<li>'.get_cat_name(get_query_var('cat')).'</li>';
      }elseif(is_archive()){
        echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'neptune-by-osetin').'</a></li>';
        echo '<li>'.get_the_archive_title().'</li>';
      }elseif((get_post_type() == 'osetin_meal_plan') && get_user_meal_plans_permalink()){
        echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'neptune-by-osetin').'</a></li>';
        echo '<li><a href="'.get_user_meal_plans_permalink().'">'.esc_html__('My Meal Plans', 'neptune-by-osetin').'</a></li>';
        echo '<li>'.get_the_title().'</li>';
      }else{
        echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'neptune-by-osetin').'</a></li>';
        $categories = get_the_category();
        if(!empty($categories)){
          $category = $categories[0];
          echo '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s", 'neptune-by-osetin' ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
        }
        echo '<li>'.get_the_title().'</li>';
      }
    echo '</ul>';
  }
}



function osetin_get_post_sharing_icons(){
  $sharing_url = get_the_permalink();
  $img_to_pin = has_post_thumbnail() ? wp_get_attachment_url( get_post_thumbnail_id() ) : "";
  $osetin_current_title = is_front_page() ? get_bloginfo('name') : wp_title('', false);
  $blog_name = get_bloginfo('name');
  $osetin_current_description = is_front_page() ? get_bloginfo('description') : get_the_excerpt();

  $facebook_share_link = 'http://www.facebook.com/sharer.php?u='.urlencode($sharing_url);
  $pinterest_share_link = '//www.pinterest.com/pin/create/button/?url='.$sharing_url.'&amp;media='.$img_to_pin.'&amp;description='.$osetin_current_title;
  $google_share_link = 'https://plus.google.com/share?url='.$sharing_url;
  $yummly_share_link = 'http://www.yummly.com/urb/verify?url='.$sharing_url.'&title='.$osetin_current_title.'&yumtype=button';
  ?>
  <div class="split-share">
  <a href="<?php echo 'mailto:?Subject='.$osetin_current_title.'&amp;Body=%20'.$sharing_url ?>" target="_blank" class="archive-item-share-link aisl-mail"><i class="os-icon os-icon-mail"></i></a>
  <a href="#" class="archive-item-share-link aisl-print"><i class="os-icon os-icon-printer"></i></a>
  <a href="#" target="_blank" class="archive-item-share-link aisl-font"><i class="os-icon os-icon-font"></i></a>
  </div>
  <span><?php _e('Share', 'neptune-by-osetin'); ?></span>
  <a href="<?php echo esc_url($facebook_share_link); ?>" target="_blank" class="archive-item-share-link aisl-facebook"><i class="os-icon os-icon-social-facebook"></i></a>
  <a href="<?php echo esc_url($yummly_share_link); ?>" target="_blank" class="archive-item-share-link aisl-linkedin"><img src="<?php echo get_template_directory_uri().'/assets/img/yum-small.png' ?>"/></a>
  <a href="<?php echo 'http://twitter.com/share?url='.$sharing_url.'&amp;text='.urlencode($osetin_current_title); ?>" target="_blank" class="archive-item-share-link aisl-twitter"><i class="os-icon os-icon-social-twitter"></i></a>
  <a href="<?php echo esc_url($pinterest_share_link); ?>" data-pin-custom="true" target="_blank" class="archive-item-share-link aisl-pinterest"><i class="os-icon os-icon-social-pinterest"></i></a>
  <a href="<?php echo esc_url($google_share_link); ?>" target="_blank" class="archive-item-share-link aisl-googleplus"><i class="os-icon os-icon-social-googleplus"></i></a>  
  <?php
}

function osetin_social_share_icons($location = 'footer', $background_color_css = ''){
  // if social icons are set to appear in footer or header - output them
  if(((osetin_get_field('show_footer_social_icons', 'option') == 'yes') && ($location == 'footer')) || ((osetin_get_field('show_header_social_icons', 'option') == 'yes') && ($location == 'header'))){
    if( osetin_have_rows('social_links', 'option') ){
      echo '<ul class="bar-social" style="'.$background_color_css.'">';

      // loop through the rows of data
      while ( osetin_have_rows('social_links', 'option') ) : the_row();
          echo '<li><a href="'.get_sub_field('social_page_url').'" target="_blank"><i class="os-icon os-icon-'.get_sub_field('social_network').'"></i></a></li>';
      endwhile;

      echo '</ul>';

    }
  }
}
















