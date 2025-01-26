( function( $ ){
    "use strict"; 


    $( function(){

      function osetin_isInt(value) {
        if (isNaN(value)) {
          return false;
        }
        var x = parseFloat(value);
        return (x | 0) === x;
      }

      function osetin_float2rat(x) {
          var tolerance = 1.0E-6;
          var h1=1; var h2=0;
          var k1=0; var k2=1;
          var b = x;
          do {
              var a = Math.floor(b);
              var aux = h1; h1 = a*h1+h2; h2 = aux;
              aux = k1; k1 = a*k1+k2; k2 = aux;
              b = 1/(b-a);
          } while (Math.abs(x-h1/k1) > x*tolerance);
          
          if(h1 > 9){
            return x;
          }else{
            return h1+"/"+k1;
          }
      }

      function osetin_toDeci(fraction) {
        var result, wholeNum = 0, frac, deci = 0;
        if(fraction.search('/') >= 0){
            if(fraction.search('-') >= 0){
                var wholeNum = fraction.split('-');
                frac = wholeNum[1];
                wholeNum = parseInt(wholeNum, 10);
            }else{
                frac = fraction;
            }
            if(fraction.search('/') >=0){
                frac =  frac.split('/');
                deci = parseInt(frac[0], 10) / parseInt(frac[1], 10);
            }
            result = wholeNum + deci;
        }else{
            result = +fraction;
        }
        return result.toFixed(2);
      }

      // function that handles recalculation of ingredient amounts after servings change
      function osetin_update_ingredient_amounts($elements, initial_serves, new_serves){
        $elements.each(function(){
          var ingredient_amount_text = $(this).text();
          var ingredient_initial_amount_text = String($(this).data('initial-amount'));
          if(ingredient_initial_amount_text != '' && initial_serves > 0){
            // extract only the numbers and dot before the first letter
            var amount_value = ingredient_initial_amount_text.match(/([0-9\.\/]+)[^0-9]*/);
            if(amount_value != null && typeof amount_value[1] !== 'undefined'){
              // extract letters from the amount text so we can use it later and join it with the number amount
              var amount_non_value = ingredient_initial_amount_text.replace(amount_value[1], '');
              // Fraction
              var is_fraction = false;
              if(amount_value[1].indexOf("/") > -1){
                amount_value[1] = osetin_toDeci(amount_value[1]);
                is_fraction = true;
              }
              var per_amount = parseFloat(amount_value[1])/initial_serves;
              // round the value properly so we dont have .00 if the value is plain number
              var new_amount = Math.round((per_amount * new_serves) * 100) / 100;
              if(is_fraction && !osetin_isInt(new_amount) && new_amount < 1) new_amount = osetin_float2rat(new_amount);
              $(this).text(new_amount + amount_non_value);
            }
          }
        });
      }

      // Servings increment button click
      $(".ingredient-serves-incr").click(function(){        
        var current_serves = parseInt($(".ingredient-serves-num").val());
        var initial_serves = parseInt($(".ingredient-serves-num").data('initial-service-num'));
        // check if current serves is proper number and not zero
        if (Number.isInteger(current_serves) && (current_serves > 0) && (initial_serves > 0) && Number.isInteger(initial_serves)){
          var new_serves = current_serves + 1;
          // set input box values to new serving value
          $(".ingredient-serves-num").val( new_serves ).data('current-serves-num', new_serves);
          // update ingredient amounts based on new servings
          osetin_update_ingredient_amounts($(".ingredient-amount"), initial_serves, new_serves);
        }
      }); 


      // Servings decrement button click
      $(".ingredient-serves-decr").click(function(){ 
        var current_serves = parseInt($(".ingredient-serves-num").val());
        var initial_serves = parseInt($(".ingredient-serves-num").data('initial-service-num'));
        // check if current serves is proper number and more than 1 so we can substract something from it
        if (Number.isInteger(current_serves) && (current_serves > 1) && Number.isInteger(initial_serves)){
          var new_serves = current_serves - 1;
          // set input box values to new serving value
          $(".ingredient-serves-num").val( new_serves ).data('current-serves-num', new_serves);
          // update ingredient amounts based on new servings
          osetin_update_ingredient_amounts($(".ingredient-amount"), initial_serves, new_serves);
        }
      }); 


      // Servings changing value
      $(".ingredient-serves-num").change(function(){
        
        var current_serves = parseInt( $(".ingredient-serves-num").data('current-serves-num') );
        var initial_serves = parseInt($(".ingredient-serves-num").data('initial-service-num'));
        var new_serves = parseInt($(".ingredient-serves-num").val());

        if (Number.isInteger(current_serves) && new_serves >= 1 && current_serves > 0){
          $(".ingredient-serves-num").val(new_serves).data('current-serves-num', new_serves);
          osetin_update_ingredient_amounts($(".ingredient-amount"), initial_serves, new_serves);
        }else{
          $(".ingredient-serves-num").val(current_serves);
        }
      });
    });
  } 
)( jQuery );