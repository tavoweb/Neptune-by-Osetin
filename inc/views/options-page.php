<?php 

// extract
//extract($args);
		
?>
<div class="wrap about-wrap">
	<?php 
  $current_theme = wp_get_theme();
  if(isset($current_theme)){
    $welcome_str = 'Welcome to Neptune v'.$current_theme->get( 'Version' );
  }else{
    $welcome_str = 'Welcome';
  }
  echo '<h1>'.$welcome_str.'</h1>';
  ?>

	<div class="about-text">Congratulations! You are using the most powerful food recipe blog theme on the market built for you by a great team at <a href="https://pinsupreme.com">Pinsupreme.com</a></div>
  <?php if ( !defined('ENVATO_HOSTED_SITE') ) { ?>
  <div class="osetin-intro-box activate-theme-box">
    <h3><span class="dashicons dashicons-admin-network"></span> Activate your theme</h3>
    <?php 
    $cerberus = new osetin_cerberus(); ?>
    <div class="theme-activation-status <?php if($cerberus->last_status_code == 200) echo 'theme-activated'; ?>"><?php echo $cerberus->last_status_message;?></div>

    <div class="cerberus-code-w">
      <input placeholder="Enter your license key here..." type="text" value="<?php echo $cerberus->license_key; ?>" class="cerberus_code">
      <div class="cerberus-code-btn-w">
        <a href="#" class="button button-primary button-large cerberus-trigger-code-btn"><?php _e("Activate",'neptune-by-osetin'); ?></a>
        <div class="spinner"></div>
      </div>
    </div>
    <div class="how-to-find-license">
      <a href="https://pinsupreme.com/posts/how-to-find-your-themeforest-item-purchase-license-code" target="_blank">How to find your license key?</a>
    </div>
  </div>

  <?php } ?>

  <div class="osetin-intro-box">
    <h3><span class="dashicons dashicons-editor-help"></span> Questions?</h3>
    <ul>
      <li><a href="http://neptune.pinsupreme.com/docs/index.html" target="_blank">Read Documentation</a></li>
      <?php if ( !defined('ENVATO_HOSTED_SITE') ) { ?>
      <li><a href="https://pinsupreme.com/users/sign_up" target="_blank">Manage Activations &amp; Get Latest Plugins</a></li>
      <?php } ?>
      <li><a href="http://neptune.pinsupreme.com/docs/index.html#headingFaqs" target="_blank">Read FAQ</a></li>
      <li><a href="https://osetin.ticksy.com/" target="_blank">Open Support Ticket</a></li>
    </ul>
  </div>

  <div class="osetin-intro-box">
    <h3><span class="dashicons dashicons-admin-media"></span> Import Demo Data</h3>
    <a href="http://neptune.pinsupreme.com/docs/index.html#headingImportingDemoDataXML" target="_blank">Learn How to Import Demo Data</a>
  </div>


  <div class="osetin-intro-box osetin-intro-newsletter-box">
    <h3><span class="dashicons dashicons-email"></span> Our Newsletter</h3>
    <ul>
      <li>Receive update notifications</li>
      <li>Free Goodies</li>
      <li>New Product Announcements</li>
    </ul>
    <!-- Begin MailChimp Signup Form -->
    <!-- <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css"> -->
    <div id="mc_embed_signup">
    <form action="//pinsupreme.us1.list-manage.com/subscribe/post?u=efaa9f358315f63ca0a1e981d&amp;id=d289368bc2" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
        <div id="mc_embed_signup_scroll">
      
    <div class="mc-field-group">
      <label for="mce-EMAIL">Enter your email address</label>
      <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Your email address">
    </div>
      <div id="mce-responses" class="clear">
        <div class="response" id="mce-error-response" style="display:none"></div>
        <div class="response" id="mce-success-response" style="display:none"></div>
      </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_efaa9f358315f63ca0a1e981d_d289368bc2" tabindex="-1" value=""></div>
        <div class="clear"><input type="submit" value="Subscribe" class="button button-primary button-large" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
        </div>
    </form>
    </div>
    <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
    <!--End mc_embed_signup-->
  </div>
</div>


<script type="text/javascript">
(function($){
	
	acf.options_page = acf.model.extend({
		
		events: {
			'click .postbox .handlediv, .postbox .hndle':	'toggle',
		},
				
		toggle : function( e ){
			
			var postbox = e.$el.closest('.postbox');
		
			if( postbox.hasClass('closed') ) {
			
				postbox.removeClass('closed');
				
			} else {
			
				postbox.addClass('closed');
				
			}
			
			
			// get all closed postboxes
			var closed = $('.postbox').filter('.closed').map(function() { return this.id; }).get().join(',');

			$.post(ajaxurl, {
				action: 'closed-postboxes',
				closed: closed,
				closedpostboxesnonce: $('#closedpostboxesnonce').val(),
				page: 'acf_options_page'
			});
			
		}
		
	});
	
	
})(jQuery);
</script>
