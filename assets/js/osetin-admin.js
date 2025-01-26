( function( $ ) {
  "use strict";



  // DOCUMENT READY
  $( function() {
    if($('.osetin-intro-box.activate-theme-box').length){
      $(document).off('submit', 'form');
      $(document).off('click', 'input[type="submit"]');
      $('.cerberus-trigger-code-btn').on('click', function(){
        $(this).text('Loading...');
        $('.cerberus-code-btn-w .spinner').fadeIn();
        var code = $('.cerberus_code').val();
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            "action": "osetin_cerberus_submit_code",
            "key" : $('input.cerberus_code').val()
          },
          dataType: "json",
          success: function(data){
            $('.cerberus-trigger-code-btn').text('Activate');
            $('.cerberus-code-btn-w .spinner').fadeOut();
            $('.theme-activation-status').remove();
            if(data.status == 200){
              $('.cerberus-code-w').before('<div class="theme-activation-status theme-activated">' + data.message + '</div>');
            }else{
              $('.cerberus-code-w').before('<div class="theme-activation-status theme-disactivated">' + data.message + '</div>');
            }
          }
        });
        return false;
      });
    }
  } );


} )( jQuery );