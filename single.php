<?php
/**
 * The template for displaying all single posts.
 *
 * @package Neptune
 */

get_header(); ?>

  <?php while ( have_posts() ) : the_post(); ?>

  <?php osetin_show_featured_recipes_slider(); ?>
  <div class="os-container top-bar-w">
    <div class="top-bar <?php if(!has_post_thumbnail()) echo 'bordered'; ?>">
      <?php osetin_output_breadcrumbs(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
  </div>




  <div class="os-container">
    
    <div class="page-w bordered <?php if ( is_active_sidebar( 'sidebar-index' ) ) echo 'with-sidebar sidebar-location-right'; ?>">
      <div class="page-content">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php if(osetin_get_field('hide_title', get_the_ID()) != true){ ?>
            <h1 class="page-title"><?php echo osetin_get_the_title(get_the_ID()); ?></h1>
            <?php $sub_title = osetin_get_field('sub_title');
            if ( ! empty( $sub_title ) ) { ?>
              <h2 class="page-content-sub-title"><?php echo esc_html($sub_title); ?></h2>
            <?php } ?>
          <?php } ?>
          <div class="single-post-meta">
            <div class="post-date"><?php esc_html_e('Posted on', 'neptune-by-osetin'); ?> <time class="entry-date updated" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date('M jS, Y'); ?></time></div>
            <div class="post-author"><?php esc_html_e('by', 'neptune-by-osetin'); ?> <strong class="author vcard"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) )) ; ?>" class="url fn n" rel="author"><?php echo get_the_author(); ?></a></strong></div>
            <div class="post-categories-w">
              <div class="post-categories-label"><?php esc_html_e('Categories:', 'neptune-by-osetin'); ?></div>
              <?php echo get_the_category_list(); ?>
            </div>
          </div>
          <div class="single-featured-image-w">
          <?php 
          if(('video' == get_post_format()) && osetin_get_field('video_shortcode')){
            echo do_shortcode(osetin_get_field('video_shortcode')); 
          }elseif(('gallery' == get_post_format()) && osetin_get_field('gallery_images', false, false, true)){
            $gallery_images = osetin_get_field('gallery_images', false, false, true);
            $html = '';
            $html.= '<div class="single-main-media">';
            foreach( $gallery_images as $key => $image ){
              $active_class = ($key == 0) ? 'active' : '';
              $html.= '<div class="single-main-media-image-w has-gallery osetin-lightbox-trigger fader-activator '.$active_class.'" id="singleMainMedia'.$key.'" 
              data-lightbox-caption="'.$image['caption'].'" 
              data-lightbox-img-src="'.$image['sizes']['osetin-full-width'].'" 
              data-lightbox-thumb-src="'.$image['sizes']['osetin-medium-square-thumbnail'].'">
              <span class="image-fader lighter"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
              <img src="'.$image['sizes']['osetin-full-width'].'"/></div>';
            }
            $html.= '<div class="single-post-gallery-images"><div class="single-post-gallery-images-i">';
            foreach( $gallery_images as $key => $image ){
              $active_class = ($key == 0) ? 'active' : '';
              $html.= '<div class="gallery-image-source '.$active_class.' fader-activator" data-image-id="singleMainMedia'.$key.'">
              <span class="image-fader"><span class="hover-icon-w"><i class="os-icon os-icon-plus"></i></span></span>
              <img src="'.$image['sizes']['osetin-medium-square-thumbnail'].'"/></div>';
            }
            $html.= '</div></div></div>';
            echo $html;
          }else{
            the_post_thumbnail('osetin-full-width');
          } ?>
          </div>
          <?php the_content(); ?>
          <?php wp_link_pages(array('before' => '<div class="content-link-pages">', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
          <div class="single-post-tags">
            <i class="os-icon os-icon-tags"></i>
            <?php the_tags('<ul class="post-tags"><li>','</li><li>','</li></ul>'); ?>
          </div>
          <div class="single-post-about-author">
            <div class="author-avatar-w">
              <?php echo get_avatar(get_the_author_meta( 'ID' )); ?>
            </div>
            <div class="author-details">
              <h3 class="author-name"><?php the_author_meta( 'display_name' ); ?></h3>
              <?php if ( get_the_author_meta('description') ) : ?>
                <p><?php the_author_meta('description'); ?></p>
              <?php endif; ?>
              <div class="author-social-links">
                <?php if ( get_the_author_meta('google_profile') ) : ?>
                  <a href="<?php esc_url( the_author_meta('google_profile')); ?>" class="profile-url"><i class="os-icon os-icon-social-googleplus"></i></a>
                <?php endif; ?>
                <?php if ( get_the_author_meta('twitter_profile') ) : ?>
                  <a href="<?php esc_url( the_author_meta('twitter_profile')); ?>" class="profile-url"><i class="os-icon os-icon-social-twitter"></i></a>
                <?php endif; ?>
                <?php if ( get_the_author_meta('facebook_profile') ) : ?>
                  <a href="<?php esc_url( the_author_meta('facebook_profile')); ?>" class="profile-url"><i class="os-icon os-icon-social-facebook"></i></a>
                <?php endif; ?>
                <?php if ( get_the_author_meta('linkedin_profile') ) : ?>
                  <a href="<?php esc_url( the_author_meta('linkedin_profile')); ?>" class="profile-url"><i class="os-icon os-icon-social-linkedin"></i></a>
                <?php endif; ?>
                <?php if ( get_the_author_meta('instagram_profile') ) : ?>
                  <a href="<?php esc_url( the_author_meta('instagram_profile')); ?>" class="profile-url"><i class="os-icon os-icon-social-instagram"></i></a>
                <?php endif; ?>
                <?php if ( get_the_author_meta('flickr_profile') ) : ?>
                  <a href="<?php esc_url( the_author_meta('flickr_profile')); ?>" class="profile-url"><i class="os-icon os-icon-social-flickr"></i></a>
                <?php endif; ?>
                <?php if ( get_the_author_meta('rss_url') ) : ?>
                  <a href="<?php esc_url( the_author_meta('rss_url')); ?>" class="profile-url"><i class="os-icon os-icon-social-rss"></i></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php
            // If comments are open or we have at least one comment, load up the comment template
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;
          ?>
        </article>
      </div>
      <?php if ( osetin_is_active_sidebar( 'sidebar-index' ) ) { ?>

        <div class="page-sidebar">
        
          <?php dynamic_sidebar( 'sidebar-index' ); ?>

        </div>
          
      <?php } ?>



    </div>
  </div>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>