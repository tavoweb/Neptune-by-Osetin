<?php
/**
 * The template for displaying all pages.
 *
 * @package Neptune
 */

get_header(); ?>

  <div class="os-container">

    
    <div class="page-w bordered">
      <div class="page-content">
        <article>
          <div class="not-found-icon"><i class="os-icon os-icon-frown-o"></i></div>
          <h1 class='page-title'><?php esc_html_e('No results found', 'neptune-by-osetin'); ?></h1>
          <p><?php esc_html_e('Make sure the url is spelled correctly and try again:', 'neptune-by-osetin'); ?></p>
        </article>
      </div>
    </div>

  </div>

<?php get_footer(); ?>