<?php 
if(!isset($layout_type_for_index)){
  global $layout_type_for_index;
}
if(!isset($current_step_class)){
  global $current_step_class;
}
if(!isset($limit)){
  global $limit;
}
if(!isset($hidden_elements_array)){
  $hidden_elements_array = osetin_get_hidden_elements_array();
}
?>
<article <?php post_class('archive-item any '.$current_step_class); ?>>
  <div class="archive-item-i">
    <div class="extra-styling-box"></div>
    <?php if(!in_array('share', $hidden_elements_array)){ ?>
      <div class="archive-item-share-w active">
        <div class="archive-item-share-trigger">
          <div class="archive-item-share-plus"><i class="os-icon os-icon-plus"></i></div>
          <div class="archive-item-share-label"><?php esc_html_e('Share', 'neptune-by-osetin'); ?></div>
          <div class="archive-item-share-icons">
            <?php osetin_get_sharing_icons(); ?>
          </div>
        </div>
      </div>
    <?php } ?>
    <div class="archive-item-media">
      <?php 
      // ------ MEDIA

      if((get_post_format() == 'gallery') && osetin_get_field('gallery_images', false, false, true) && !isset($only_image)){
        // GALLERY
        $gallery_images = osetin_get_field('gallery_images', false, false, true);
        $thumb_name = osetin_get_archive_thumb_name($current_step_class);
        if(isset($gallery_images[0])){ ?>
          <a href="<?php the_permalink(); ?>" class="archive-item-media-thumbnail fader-activator" style="background-image:url(<?php echo $gallery_images[0]['sizes'][$thumb_name]; ?>); background-size: cover;">
            <span class="image-fader"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
          </a><?php
        }
        foreach( $gallery_images as $key => $image ){
          $active_class = ($key == 0) ? 'active' : '';
          echo '<div class="gallery-image-source '.$active_class.'" data-gallery-image-url="'.$image['sizes'][$thumb_name].'"></div>';
        }
        echo '<div class="gallery-image-previous"><i class="os-icon os-icon-angle-left"></i></div>';
        echo '<div class="gallery-image-next"><i class="os-icon os-icon-angle-right"></i></div>';
      }elseif(get_post_format() == 'video' && osetin_get_field('video_shortcode') && !isset($only_image)){
        // VIDEO
        echo do_shortcode(osetin_get_field('video_shortcode'));
      }else{ ?>
        <?php 
          $gif_html = '';
          if($current_step_class == 'hero'){ 
            $custom_image_for_header = osetin_get_field('custom_image_for_header', false, false, true);
            if(is_array($custom_image_for_header)){
              $item_bg_image = $custom_image_for_header['sizes']['osetin-full-width'];
            }else{
              $item_bg_image = osetin_output_post_thumbnail_url('osetin-full-width');
            }
          }else{
            $item_bg_image = osetin_output_post_thumbnail_url(osetin_get_archive_thumb_name($current_step_class), false);
          }
          if(pathinfo($item_bg_image, PATHINFO_EXTENSION) == 'gif' && osetin_get_field('is_gif_media')){
            $gif_html = '<span class="gif-label"><i class="os-icon os-icon-basic1-082_movie_video_camera"></i><span>'.__('GIF', 'neptune-by-osetin').'</span></span>';
            if(osetin_get_field('preview_image_for_lazy_gif')){
              $preview_img_src = wp_get_attachment_image_src(osetin_get_field('preview_image_for_lazy_gif'), 'osetin-full-width');
              if(!empty($preview_img_src[0])){
                $item_bg_image = $preview_img_src[0];
              }
            }
          }
        ?>
        <a href="<?php the_permalink(); ?>" class="archive-item-media-thumbnail fader-activator" style="background-image:url(<?php echo $item_bg_image; ?>); background-size: cover;">
          <span class="image-fader"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
          <?php echo $gif_html; ?>
        </a>
      <?php } ?>
    </div>
    <div class="archive-item-content">
      <?php if(!in_array('title', $hidden_elements_array)){ ?>
        <header class="archive-item-header">
          <?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
        </header>
      <?php } ?>
      <?php if(array_diff(array('cooking_time', 'categories', 'cuisines'), $hidden_elements_array)){ ?>
        <div class="archive-item-meta">
          <?php if(!in_array('cooking_time', $hidden_elements_array)){ ?>
            <?php $cooking_time = osetin_get_field('recipe_cooking_time'); ?>
            <?php if(!empty($cooking_time)){ ?>
              <div class="archive-item-meta-cooking-time">
                <?php esc_html_e('Cooking Time: ', 'neptune-by-osetin'); ?><?php echo esc_html($cooking_time); ?>
              </div>
            <?php } ?>  
          <?php } ?>
          <?php if(!in_array('categories', $hidden_elements_array)){ ?>
            <div class="archive-item-meta-categories">
              <?php echo get_the_category_list(); ?>
            </div>
          <?php } ?>
          <?php 
            if(!in_array('cuisines', $hidden_elements_array)){
              $this_recipe_cuisines_with_icons = osetin_get_recipe_cuisines_with_icons(get_the_ID());
              // CUISINES
              if (is_array($this_recipe_cuisines_with_icons) || is_object($this_recipe_cuisines_with_icons)){
                echo '<ul class="archive-item-meta-cuisines">';
                foreach($this_recipe_cuisines_with_icons as $cuisine){
                  $cuisine_term = $cuisine['term'];
                  echo '<li>
                          <a href="'.get_term_link($cuisine_term).'" class="single-recipe-cuisine-label-w tooltip-trigger" data-tooltip-header="'.esc_attr($cuisine_term->name).'">
                            <span class="single-recipe-cuisine-label">'.esc_html($cuisine_term->name).'</span>
                            <span class="single-recipe-cuisine-image"><img src="'.$cuisine['icon_url'].'" alt="'.esc_attr($cuisine_term->name).'"/></span>
                          </a>
                        </li>';
                }
                echo '</ul>';
              }
            }
          ?>

        </div>
      <?php } ?>
      <?php if(!in_array('excerpt', $hidden_elements_array)){ ?>
        <div class="archive-item-content-text">
          <?php echo osetin_excerpt($limit, false); ?>
        </div>
      <?php } ?>
      <?php if($current_step_class != 'full_third'){ ?>
        <?php if(array_diff(array('author_avatar', 'author_name', 'date', 'like'), $hidden_elements_array)){ ?>
          <div class="archive-item-author-meta">
            <?php if(!in_array('author_avatar', $hidden_elements_array)){ ?>
              <div class="author-avatar-w">
                <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar(get_the_author_meta( 'ID' )); ?></a>
              </div>
            <?php } ?>
            <?php if(array_diff(array('author_name', 'date'), $hidden_elements_array)){ ?>
              <div class="author-details">
                <?php if(!in_array('author_name', $hidden_elements_array)){ ?>
                <h4 class="author-name"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta( 'display_name' ); ?></a></h4>
                <?php } ?>
                <?php if(!in_array('date', $hidden_elements_array)){ ?>
                  <div class="archive-item-date-posted"><?php echo get_the_date(); ?></div>
                <?php } ?>
              </div>
            <?php } ?>
            <?php if(!in_array('like', $hidden_elements_array)){ ?>
              <div class="archive-item-views-count-likes">
                <?php osetin_vote_build_button(get_the_ID(), 'slide-button slide-like-button'); ?>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>
      <?php if($current_step_class == 'full_third'){  echo '</div><div class="clear"></div><div>'; } ?>
      <?php if(array_diff(array('rating', 'read_more', 'comments', 'views'), $hidden_elements_array)){ ?>
        <div class="archive-item-deep-meta">
          <?php if(array_diff(array('rating', 'read_more'), $hidden_elements_array)){ ?>
            <div class="archive-item-rating-and-read-more">
              <?php  
                if(!in_array('rating', $hidden_elements_array)){
                  $reviews_info = osetin_recipe_rating_average_and_total(get_the_ID()); 
                  if($reviews_info){
                    if($reviews_info->avg_rating) {
                      echo '<div class="archive-item-rating">';
                      $stars_html = osetin_build_stars($reviews_info->avg_rating);
                      echo '<div class="review-summary-average">'.$stars_html.'</div>';
                      if($reviews_info->total_reviews) echo '<a href="'.get_permalink().'#osetinRecipeReviews" class="review-summary-total">'.$reviews_info->total_reviews.'</a>';
                      echo '</div>';
                    }
                  }
                }
              ?>
              <?php if(!in_array('read_more', $hidden_elements_array) && $current_step_class != 'full_third'){ ?>
                <div class="archive-item-read-more-btn">
                  <div class="read-more-link"><a href="<?php the_permalink(); ?>"><?php _e('Read More', 'neptune-by-osetin'); ?></a></div>
                </div>
              <?php } ?>
            </div>
          <?php } ?>

          <?php if(!in_array('comments', $hidden_elements_array)){ ?>
            <div class="archive-item-comments">
              <a href="<?php the_permalink() ?>#singlePostComments"><i class="os-icon os-icon-thin-comment"></i> <span><?php comments_number( __('Comment', 'neptune-by-osetin'), '1 '.__('Comment', 'neptune-by-osetin'), '% '.__('Comments', 'neptune-by-osetin') ); ?></span></a>
            </div>
          <?php } ?>
          <?php if(!in_array('views', $hidden_elements_array)){ ?>
            <?php if(function_exists('echo_tptn_post_count')){ 
              echo '<div class="archive-item-views-count">';
              echo '<i class="os-icon os-icon-eye"></i> <span>'. do_shortcode('[tptn_views daily="0"]'). ' ' . esc_html__('Views', 'neptune-by-osetin') .'</span>';
              
              echo '</div>'; ?>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
    <div class="clear"></div>
    <?php  // edit_post_link( esc_html__( 'Edit', 'neptune-by-osetin' ), '<span class="edit-link">', '</span>' ); ?>
  </div>
</article>