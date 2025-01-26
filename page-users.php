<?php
/**
 * Template Name: List of Users
 *
 */
?>
<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


  <div class="os-container top-bar-w">
    <div class="top-bar <?php if(!has_post_thumbnail()) echo 'bordered'; ?>">
      <?php osetin_output_breadcrumbs(); ?>
      <?php osetin_social_share_icons('header'); ?>
    </div>
  </div>

  <?php  
  if(osetin_is_imaged_header(get_the_ID())){
    if(osetin_is_bbpress()){
      $page_bg_image_url = get_template_directory_uri().'/assets/img/patterns/flowers_light.jpg';
    }else{
      $page_bg_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
    } ?>
    <div class="os-container">
      <div class="page-intro-header with-background" style="<?php echo osetin_get_css_prop('background-image', $page_bg_image_url, false, 'background-repeat: repeat; background-position: top left; '); ?>">
        <h2><?php echo osetin_get_the_title(get_the_ID()); ?></h2>
      </div>
    </div>
    <?php
  }
  ?>

  <div class="os-container">
    
    <div class="page-w <?php if ( osetin_is_active_sidebar( 'sidebar-index' ) ) echo 'with-sidebar sidebar-location-right'; ?>">
      <div class="page-content">
        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php 
          if(osetin_is_regular_header(get_the_ID())){
            echo '<h1 class="page-title">'.osetin_get_the_title(get_the_ID()).'</h1>';
          }
          ?>
          <?php $sub_title = osetin_get_field('sub_title');
          if ( ! empty( $sub_title ) ) { ?>
            <h2 class="page-content-sub-title"><?php echo esc_html($sub_title); ?></h2>
          <?php } ?>
          <div class="list-of-users">


            <?php
              $args = array();
              $selected_users = osetin_get_field('selected_user_ids', false, false, true);
              if($selected_users){
                $selected_user_ids = array();
                foreach($selected_users as $temp_user){
                  array_push($selected_user_ids, $temp_user['ID']);
                }
                if(!empty($selected_user_ids)) $args['include'] = $selected_user_ids;
              }else{
                if( osetin_get_field('only_users_with_posts') ){
                  global $wpdb;
                  $user_ids = $wpdb->get_col("SELECT `post_author` FROM
                          (SELECT `post_author`, COUNT(*) AS `count` FROM {$wpdb->posts}
                          WHERE `post_status`='publish' GROUP BY `post_author`) AS `stats`
                          WHERE `count` >= 1 ORDER BY `count` DESC;");
                  $args['include'] = $user_ids;
                }
              } 
              $users = get_users($args);
              foreach($users as $user){
                echo '<div class="user-item">';
                  echo '<div class="user-item-data">';
                    echo '<div class="user-item-avatar"><a href="'.get_author_posts_url($user->ID).'">'.get_avatar($user->ID, 200).'</a></div>';
                    echo '<div class="user-item-description-w">';
                      echo '<h2 class="user-item-name"><a href="'.get_author_posts_url($user->ID).'">'.$user->display_name.'</a></h2>';
                      echo '<div class="user-item-social">'.get_user_social_links($user->ID).'</div>';
                      echo '<div class="user-item-description">'.$user->description.'</div>';
                    echo '</div>';
                  echo '</div>';

                  $post_args = array(
                    'orderby' => 'position',
                    'order' => 'DESC',
                    'posts_per_page' => 3,
                    'post_type' => array('osetin_recipe', 'post'),
                    'author' => $user->ID,
                    'ignore_sticky_posts' => true,
                    'paged' => 1
                  );

                  $author_recent_posts_query = new WP_Query( $post_args );

                  if ( $author_recent_posts_query->have_posts() ) {
                    echo '<div class="user-item-recent-posts">';
                      echo '<h4 class="user-item-recent-posts-header">'.sprintf( esc_html__( 'Recent Posts by %s:', 'neptune-by-osetin' ), $user->display_name ).'</h4>';
                      while ( $author_recent_posts_query->have_posts() ) : $author_recent_posts_query->the_post();
                        echo '<a href="'.get_the_permalink().'" class="user-item-recent-post">';
                          echo '<span class="user-item-recent-post-img">'.osetin_get_post_thumbnail(get_the_ID(), 'osetin-medium-square-thumbnail').'</span>';
                          echo '<h4 class="user-item-recent-post-title">'.get_the_title().'</h4>';
                        echo '</a>';
                      endwhile;
                      
                    echo '</div>';
                    wp_reset_postdata();
                  }
                echo '</div>';
              }
              ?>
          </div>
        </article>
      </div>
      <?php if ( osetin_is_active_sidebar( 'sidebar-index' ) ) { ?>

        <div class="page-sidebar">
        
          <?php dynamic_sidebar( 'sidebar-index' ); ?>

        </div>
          
      <?php } ?>
    </div> 

  </div>
  <?php


endwhile; endif; ?>
<?php get_footer(); ?>