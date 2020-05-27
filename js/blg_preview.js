jQuery(document).ready(function($) {





    // Trigger if input is changed with delay of 1.2 seconds
  function delay(callback, ms) {
      var timer = 0;
      return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
          callback.apply(context, args);
        }, ms || 0);
      };
    }
    
    $('#post_number_display').keyup(delay(function (e) {

    // get max page
    // max_page = ajax_object.max_page,
    //console.log(max_page);  


    // get new value of input post number
    post_number_value = this.value;
    console.log('Post per page :', post_number_value);

    total_num_post = ajax_object.total_post_count;
    console.log('Total Post Count :', total_num_post);

    max_page = total_num_post/post_number_value;
    console.log('Max Page :', max_page);

    

    col_layout = jQuery('select#blg_col_num').val();

    if (col_layout == 1) {	    		
      col_layout = 12;
    } 
    else if (col_layout == 4) {
      col_layout = 3;
    }
    else if (col_layout ==3) {
      col_layout = 4;
    }
    else if ($col_layout = 2) {
      col_layout = 6;
    }

    var data = {
        'action': 'blg_preview_action',          'blg_ajax_data': ajax_object.we_value,    // We pass php values differently!
        'query': ajax_object.posts, // that's how we get params from wp_localize_script() function
        'page' : ajax_object.current_page,
        'current_page' : ajax_object.ajaxurl2,
        'col_layout' :  col_layout,
        'num_of_post' : post_number_value
    };

    


    // hide existing preview and remove then display loading message
    $(".blg_preview_container .row [class^=col]").remove();

    // ajax posting data to php
    $.ajax({ // you can also use $.post here
        url : ajax_object.ajax_url, // AJAX handler
        data : data,
        type : 'POST',
        beforeSend : function ( xhr ) {
          //  $('.blg_prev_loading').text('Preparing data....'); // change the button text, you can also add a preloader image
            $('.blg_prev_loading').css('display','block'); 
        },
        success : function( data ){
            $('.blg_prev_loading').css('display','none'); 

            // remoev button if max page is more than 1
            if (max_page < 1) { $('.misha_loadmore').hide() }
            else { $('.misha_loadmore').show() }
            
            if( data ) { 
            //    console.log('Got this from the server: ' + data);
                $('.blg_preview_container .row').append(data);
            }
        }
    });    
 }, 1200));



     // Trigger if 'select' is changed
    $('#blg_col_num').change(function(){ 


      max_page = ajax_object.max_page,
      console.log(max_page);

      var data = {
        'action': 'blg_preview_action',
            'blg_ajax_data': ajax_object.we_value,    // We pass php values differently!
            'query': ajax_object.posts, // that's how we get params from wp_localize_script() function
            'page' : ajax_object.current_page,
            'current_page' : ajax_object.ajaxurl2,
            'col_layout' :  ajax_object.column_layout,
            'num_of_post' : jQuery('input#post_number_display').val()
        };


       // hide existing preview and remove then display loading message
        $(".blg_preview_container .row [class^=col]").remove();
        // get new value of select field
        var data_select= $(this).val();

        // data grid col converted to 12 col bootstrap system
        if (data_select == 1) {	    		
          data_select = 12;
        } 
        else if (data_select == 4) {
          data_select = 3;
        }
        else if (data_select ==3) {
          data_select = 4;
        }
        else if ($data_select = 2) {
          data_select = 6;
        }
      
        else { data_select = 12; }	
        
        data.col_layout =  data_select;
        // ajax posting data to php
        $.ajax({ // you can also use $.post here
            url : ajax_object.ajax_url, // AJAX handler
            data : data,
            type : 'POST',
            beforeSend : function ( xhr ) {
              //  $('.blg_prev_loading').text('Preparing data....'); // change the button text, you can also add a preloader image
                $('.blg_prev_loading').css('display','block'); 
            },
            success : function( data ){
                $('.blg_prev_loading').css('display','none'); 

                if (max_page < 1) { $('.misha_loadmore').hide() }
                else { $('.misha_loadmore').show() }
                
                
                if( data ) { 
                 //   console.log('Got this from the server: ' + data);
                    $('.blg_preview_container .row').append(data);
                }
            }
        });    
    });


});