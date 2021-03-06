
jQuery(function($){
	
	// use jQuery code inside this to avoid "$ is not defined" error
	$('.misha_loadmore').click(function(){

		console.log( misha_loadmore_params.current_page );
 
		var button = $(this),
		    data = {
			'action': 'loadmore',
			'query': misha_loadmore_params.posts, // that's how we get params from wp_localize_script() function
			'page' : misha_loadmore_params.current_page,
		//	'current_page' : misha_loadmore_params.ajaxurl2,
		//	'col_layout' :  misha_loadmore_params.column_layout,
			'col_layout' : jQuery('select#blg_col_num').val(),
			'num_of_post' : misha_loadmore_params.num_of_post
		//	'num_of_post' : jQuery('input#post_number_display').val()
		};


	//	if ( misha_loadmore_params.current_page == misha_loadmore_params.max_page ) 
	//					button.remove(); // if last page, remove the button
		
		$.ajax({ // you can also use $.post here
			url : misha_loadmore_params.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Loading...'); // change the button text, you can also add a preloader image
			},
			success : function( data ){
				
				if( data ) { 
					button.text( 'More posts' ).prev().append(data); // insert new posts
					misha_loadmore_params.current_page++;
					console.log(data);


		
 
					if ( misha_loadmore_params.current_page == misha_loadmore_params.max_page ) 
						button.remove(); // if last page, remove the button
 
					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
					button.remove(); // if no data, remove the button as well
				}
			}
		});
	});
});