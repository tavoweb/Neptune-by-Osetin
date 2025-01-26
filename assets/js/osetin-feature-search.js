( function( $ ) {
  "use strict";
  $( function() {

    var timeoutId = 0;
    $('.trigger-ingredient-search').on('click', function () {
      $('.load-more-infinite').remove();
      var $btn = $(this);
      if($(".ingredients-multi-select").val() != null){
        $('.ingredients-search-box-w').addClass('search-in-progress');
        $btn.data('label-default', $btn.text()).text($btn.data('label-loading'));
        osetinGetIngredientsSearchResults();
      }else{
        $('.chosen-choices .search-field input[type="text"]').addClass('animated animation-shake');
      }
      return false;
    });

    $('.ingredients-search-results-w').on('click', '.load-more-infinite', function () {
      var $btn = $(this);
      if($(".ingredients-multi-select").val() != null){
        $('.ingredients-search-box-w').addClass('search-in-progress');
        $btn.data('label-default', $btn.text()).find('span').text($btn.data('label-loading'));
        osetinGetIngredientsSearchResults();
      }else{
        $('.chosen-choices .search-field input[type="text"]').addClass('animated animation-shake');
      }
      return false;
    });

    function osetinGetIngredientsSearchResults(){
      var paged = $('.load-more-infinite').length ? $('.load-more-infinite').data('paged') : 1;
      $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            "action": "osetin_search_process_request",
            "search_ingredient_ids" : $(".ingredients-multi-select").val(),
            "paged" : paged
          },
          dataType: "json",
          success: function(data){
            if(data.is_last_page == 'yes'){
              $('.load-more-infinite').remove();
            }
            $('.ingredients-search-box-w').addClass('compacted');
            var $btn = $('.trigger-ingredient-search');
            $btn.text($btn.data('label-default'));
            $('.ingredients-search-box-w').removeClass('search-in-progress');
            if($('.load-more-infinite').length){
              var $load_more_btn = $('.load-more-infinite');
              $load_more_btn.find('span').text($load_more_btn.data('label-default'));
            }

            if(paged > 1){
              $('.ingredients-search-results-w .masonry-grid').append(data.message);
            }else{
              $('.ingredients-search-results-w').html(data.message);
            }
            paged = paged + 1;
            $('.load-more-infinite').data('paged', paged);
          }
      });
    }
  });

} )( jQuery );
