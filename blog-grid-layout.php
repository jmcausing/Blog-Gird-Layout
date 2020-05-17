<?php
	
/*
Plugin Name: Blog Grid Layout
Plugin URI: http://causingdesignscom.kinsta.cloud/
Description: Similar to nginx access log where it get get the IP, user-agent, access path and display analytics chart.
Author: John Mark Causing
Author URI:  http://causingdesignscom.kinsta.cloud/
*/


/* 

Ajax load more reference

https://artisansweb.net/load-wordpress-post-ajax/

https://rudrastyh.com/wordpress/load-more-posts-ajax.html

 */

 

// START - Metaboxes / fields / html for custom post type called 'blog_grid_layouts'
// ############################################################################
function meta_box_blog_grid_layout( $post ) {

	add_meta_box(
		'blog_grid_layout_metabox_id',
		'Blog Grid Layout Settings',
		'blog_grid_layout_page_function',
		'blog_grid_layouts', // Slug of custom post type
		'normal',
		'default'
	);
}

add_action('add_meta_boxes_blog_grid_layouts', 'meta_box_blog_grid_layout');

// START -- HTML meta box function
// #################################
function blog_grid_layout_page_function() {

	global $post;

	$post_id = $post->ID;

	wp_nonce_field( basename( __FILE__), 'blog_layout_grid_nonce'); // insert hidden field to verify later when sumitting the post

	// storing variables from the form name and html fields
	$blg_name = (!empty( get_post_meta($post_id, 'blg_name', true))) ? get_post_meta($post_id, 'blg_name', true) : '';
	$blg_column = (!empty( get_post_meta($post_id, 'blg_column', true))) ? get_post_meta($post_id, 'blg_column', true) : '';
	$blg_post_number = (!empty( get_post_meta($post_id, 'blg_post_number', true))) ? get_post_meta($post_id, 'blg_post_number', true) : '';
	$blg_category = (!empty( get_post_meta($post_id, 'blg_category', true))) ? get_post_meta($post_id, 'blg_category', true) : '';

	$post_list = get_posts( array(
		'numberposts' => $blg_post_number,
		'orderby'    => 'date',
		'sort_order' => 'desc',
		'max_num_pages' => 1,
		'paged' => 1,
	) );
	 
	$posts = array();

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<script type="text/javascript">


var field_id =  "post_number_display";    // <----------------- CHANGE THIS
var blg_name =  "blg_name";

var SubmitButton = document.getElementById("save-post") || false;
var PublishButton = document.getElementById("publish")  || false; 
if (SubmitButton)   {SubmitButton.addEventListener("click", SubmCLICKED, false);}
if (PublishButton)  {PublishButton.addEventListener("click", SubmCLICKED, false);}


function SubmCLICKED(e){   


  var passed= false;
  
  if(  !document.getElementById(field_id) || !document.getElementById(blg_name)  ) { alert("I cant find that field ID !!"); }


  else {

	  var Enabled_Disabled_Name = document.getElementById(blg_name).value;
	  var Enabled_Disabled = document.getElementById(field_id).value;

      if (Enabled_Disabled_Name == "" || Enabled_Disabled == "" ) { 
		  
		if (Enabled_Disabled_Name == "" ) { 
			
			jQuery('span.name_required').text('');
			jQuery('span.name_required').append('Name is required!');
		}
		if (Enabled_Disabled == "" ) { 
			jQuery('span.post_number_display').text('');
			jQuery('span.post_number_display').append('<br><p style="color: red;">This field is required!');
			
		}
	
		  
		} 
		
		else{passed=true;}
		
  }
  if (!passed) { e.preventDefault();  return false;  }


}
</script>



<style>



	/* bootsrap height columns */
	.blg_preview_container.container .col-12 .blog_grid_container {
		height: 350px;
	}

	.blg_preview_container.container .col-6 .blog_grid_container {
		height: 250px;
	}

	.blg_preview_container.container .col-4 .blog_grid_container {
		height: 200px;
	}

	.blg_preview_container.container .col-3 .blog_grid_container {
		height: 170px;
	}
	/* bootsrap height columns */


	.blg_field_settings_container.container .row {
		padding: 5px;
	}
	.blog_grid_container { 
		border: 1px solid red;
		width: 100%;
		height: 100px;
		margin: auto;
		margin-bottom: 20px;
	}

	.blog_grid_container {
    background-size: cover;
	}



.misha_loadmore{
	background-color: #ddd;
	border-radius: 2px;
	display: block;
	text-align: center;
	font-size: 14px;
	font-size: 0.875rem;
	font-weight: 800;
	letter-spacing:1px;
	cursor:pointer;
	text-transform: uppercase;
	padding: 10px 0;
	transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out, color 0.3s ease-in-out;  
}
.misha_loadmore:hover{
	background-color: #767676;
	color: #fff;
}


.post_title_grid {
    background: #ffff0073;
    padding: 10px;
}

	
</style>


<div class="blg_field_settings_container container">
	<div class="row">
		<div class="col-sm-12">
			<div class="blg_input_label_name">
				Blog Grid Layout Name: 
				<input id="blg_name" type="name" name="blg_name"  class="" value="<?php if ($blg_name == null) echo "Grid Name " . $post_id; else echo $blg_name ?>" />
				<span style="color: red;" class="name_required"></span>
			</div>
			
		</div>
	</div>
  	<div class="row">
   		<div class="col-sm-4">
		Number of columns
		<br>
		</div>
		<div class="col-sm-4">How many post to display?</div>
		<div class="col-sm-4">Select a category</div>
 	</div>
  	<div class="row">
    	<div class="col-sm-4">

			<select name="blg_column[]" id="blg_col_num" >

				<?php

				// check if field is empty then add default values
				if($blg_column == null) { 
					echo '
						<option selected value="1"> 1 </option>
						<option selected value="2"> 2 </option>
						<option selected value="3"> 3 </option>
						<option selected value="4"> 4 </option>
					';
				}

				// if not empty, get from DB post meta and mark selected items
				else {

					// number of columns for selection
					$column_numbers = array(1, 2, 3, 4);
					foreach ($column_numbers as $value_col) {

						// check if item in selected value is in the array (or selected) for column in post_meta then mark as 'selected'
						$selected_col = ( in_array($value_col, $blg_column) ) ? 'selected' : '';
	
						echo '<option ' . $selected_col . ' value="' . $value_col . '">'. $value_col .'</option>';

						
					}
				}

				?>
				
			</select>	
	
		</div>
		<div class="post_number_display col-sm-4">
			<input id="post_number_display" type="number" name="blg_post_number"  class="" value="<?php if ($blg_post_number == null) echo "10"; else echo $blg_post_number ?>" />
			<span style="color: red;" class="post_number_display"></span>
		</div>
		<div class="col-sm-4"> 
			<select name="blg_category[]" id="blog_post_grid_cat" multiple>

				<?php

				// check if field is empty then add default values
				if($blg_category == null) { 
					echo '<option selected value="all">All</option>';

					$categories = get_categories(); // get all categories as object
					foreach($categories as $category) {			
						echo '<option  value="' . $category->name . '">' . $category->name . '</option>';
					}
				}

				// if not empty, get from DB post meta and mark selected items
				else {

					echo '<option value="all">All</option>';

					$categories = get_categories(); // get all categories as object
		
					// Start loop - get all categories
					foreach($categories as $category) {
						// check if items in selected values iares in array (or selected) for category list then mark as 'selected'
						$selected = ( in_array($category->name, $blg_category) ) ? 'selected' : '';
					
						echo '<option '.  $selected  .' value="' . $category->name . '">' . $category->name . '</option>';
					}
					// End loop - get all categories

				}

				?>

			</select>
		</div>	
  </div>
</div>

<br>

<h1>Blog Grid Layout Preview</h1>


<!-- Bootstrap grid start  / Display blog grid preview -->
<!-- #################### -->


<style>
.blg_prev_loading {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
</head>
<body>



<div class="blg_prev_loading" style="display: none;"></div>

<div class="blg_preview_container container">
		

  		<div class="row">

		<?php

		if ($blg_column == null && $blg_post_number == null && $blg_category == null) {
			echo '<h2 style="color: red;"> Nothing yet to preview.. Set some settings first then save it!</h2>';
			return;	
		}



		if ( isset($blg_column) )  {
			foreach($blg_column as $col_num) {

				if ($col_num == 1) {
					$set_column = 12;
				} 
				elseif ($col_num == 4) {
					$set_column = 3;
				}
				elseif ($col_num ==3) {
					$set_column = 4;
				}
				elseif ($col_num = 2) {
					$set_column = 6;
				}
			
				else { $set_column = 12; }	
			}	
		}


		// ##########
		// START
		// Link jquery custom.js file and call ajax
		// This is to pass data from this function -> to jquery -> and get it from misha_loadmore_ajax_handler() function

		// query cpt
		$args = array(
			'post_status'       => 'publish',
			'posts_per_page'    => $blg_post_number,
			'post_type'         => 'blog_grid_layouts',
		);

		// set variable for query cpt
		$query_pages = new WP_Query( $args );

		// get current max pages. if 50 post to display and there are only 16 entries it will get '1' value for max pages
		$max_num_pages = $query_pages->max_num_pages;

		// if there are more page to load, load ajax and jquery script
		if ($max_num_pages != 1) {

			// In most cases it is already included on the page and this line can be removed
			wp_enqueue_script('jquery');

			// register our main script but do not enqueue it yet
			wp_register_script( 'my_loadmore', plugin_dir_url(__FILE__).'js/custom.js', array('jquery') );


			// now the most interesting part
			// we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
			// you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
			wp_localize_script( 'my_loadmore', 'misha_loadmore_params', array(
				'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
				'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
				'max_page' => $max_num_pages,
				'column_layout' => $set_column,
				'number_of_post' => $blg_post_number

			) );
			wp_enqueue_script( 'my_loadmore' );

		}

		// Link jquery custom.js file and call ajax
		// This is to pass data from this function -> to jquery -> and get it from misha_loadmore_ajax_handler() function
		// END
		// ##########



	// start loop
	foreach ( $post_list as $post ) {

	   $posts[] += $post->ID;
	   

	 	 $post_title =  $post->post_title. '<br>';

		 $featured_image =  get_the_post_thumbnail_url( $post->ID );

		if ( !$featured_image)  {
			$featured_image = '';
		}

	?>

	<!-- Set number of columns. 4 is 3 columns. 3 is 4 columns Bootstrap 12 column grid. -->
	<div class="col-<?php echo $set_column;  ?>">

		<div class="blog_grid_container" style="background-image: url('<?php echo $featured_image; ?> '); "  >
		<div class="post_title_grid"> 	<?php echo $post->post_title; ?>  </div>
		</div>
	  
	</div>


	<?php
	} // end loop

	?>

  </div>


<?php 


// check if there are more entries to display
if ($max_num_pages != 1) { 
	echo '<div class="misha_loadmore">More posts</div>';
}

?>

</div>
<!-- #################### -->
<!-- Bootstrap grid END -->





<?php

	// This is ajax preview for blog grid layouy
	// ######
	// START

	wp_enqueue_script('jquery');
		
	wp_enqueue_script( 'blg_preview_action', plugins_url( '/js/blg_preview.js', __FILE__ ), array('jquery') );

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'blg_preview_action', 'ajax_object',
			array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'blg_ajax_data' => 1234,
				'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
				'max_page' => $max_num_pages,
				'column_layout' => $set_column,
				'number_of_post' => $blg_post_number

				) 
				
	);
	wp_enqueue_script( 'blg_preview_action' );

	// This is ajax preview for blog grid layouy
	// END
	// ######




}
// ###############################
// END -- HTML meta box function


// ############################################################################
// END - Metaboxes / fields / html for custom post type called 'blog_grid_layouts'



// ###############################
// START -- save data from custom post type 'blog_grid_layouts'
// This is triggered when the post is saved

function save_blog_grid_layouts_meta( $post_id, $post ) {

	// Verify nonce
	if ( !isset($_POST['blog_layout_grid_nonce']) || !wp_verify_nonce( $_POST['blog_layout_grid_nonce'], basename( __FILE__) ) ) {
		return $post_id;
	}

	// get the post type object
	$post_type = get_post_type_object( $post->post_type );

	// check if the current user has perssion to edit the post
	if ( !current_user_can( $post_type->cap->edit_post, $post_id) ) {
		return $post_id;
	}

	// Get the posted data and sanitize it
	$blg_name = ( isset($_POST['blg_name']) ) ? $_POST['blg_name'] : '';
 	$blg_column = ( isset($_POST['blg_column']) ) ? $_POST['blg_column'] : '';
	$blg_post_number = ( isset($_POST['blg_post_number']) ) ? sanitize_text_field( $_POST['blg_post_number']) : '';
	$blg_category = ( isset($_POST['blg_category']) ) ?  $_POST['blg_category'] : '';


	// update / insert post meta
	update_post_meta($post_id, 'blg_name', $blg_name );
	update_post_meta($post_id, 'blg_column', array_map( 'strip_tags', $blg_column) );
	update_post_meta($post_id, 'blg_post_number', $blg_post_number);
	update_post_meta($post_id, 'blg_category', array_map( 'strip_tags', $blg_category) );
	
}
add_action('save_post', 'save_blog_grid_layouts_meta', 10, 2); 


// END -- save data from custom post type 'blog_grid_layouts'
// ###############################






// START -  Admin column title and data
// ####################################
//
// hint: register custom admin column headers
// 'blog_grid_layouts' = custom post type name


	// START 
	// ####
	// This is to edit the title column data in custom post type 'blog_grid_layouts'
	// It will only change the column data of HTML Name.
	add_action('admin_head-edit.php', 'blg_register_custom_admin_titles');

	function blg_register_custom_admin_titles() {

		add_filter(
			'the_title',
			'blg_custom_admin_titles',
			99,
			2
		);
	}
	function blg_custom_admin_titles( $title, $post_id) {
		

		global $post_type;

		// check if post type name is correct
		if ($post_type == 'blog_grid_layouts') {
			$html_name = get_post_meta( $post_id , 'blg_name', true );
			$output = $html_name ;
			return $output;
		}
		else return $title;

	}
	
	// This is to edit the title column data in custom post type 'blog_grid_layouts'
	// It will only change the data of Subscriber Column.
	// ####
	// END
	
	
	// this is column title header in the custom post type 'blog_grid_layouts'
	// manage_edit-blog_grid_layouts_columns <-- 'blog_grid_layouts' is the slug of custom post type
	add_filter('manage_edit-blog_grid_layouts_columns', 'blg_column_headers');
	
	function blg_column_headers( $columns ) {
		// creating custom column header data
		// __( ) is for language so WP can detect if you are using a different language like Spanish
		$columns = array(
			'cb' => '<input type="checkbox">',
			'title' => __('Blog Grid Layout Name'),
			'shortcodes' => __('Shortcodes')
		);
	
		// returning new columns
		return $columns;
	}
	
	
	// This is the data column of custom post type 'blog_grid_layouts'
	// You can put other data here like ID, email, shortcode, etc.
	// 'blog_grid_layouts' = custom post type name
	add_filter('manage_blog_grid_layouts_posts_custom_column', 'blg_subscriber_column_data', 1, 2);
	
	function blg_subscriber_column_data( $column, $post_id ) {
	
		// setup our return text
		$output1 = '';
	
		switch ( $column ) {
				
					case 'shortcodes':
						// get the custom email data
						
					$shortcode_string = 'blg the_post_id=' . $post_id . '';
						$my_shortcode =  '['.  $shortcode_string .']';
						$output1 .= $my_shortcode;
	
					break;				
				
		}
	
		// print the column data
		echo $output1;
	}
	
	// ##################################
	// END -  Admin column title and data






// Using Ajax to pass parameters and create load more for blog grid layout
// ### START
// #####################

function blg_loadmore_ajax_handler() {

	// Retrieve data from ajax
	$col_layout = $_POST['col_layout'];
	$number_of_post_to_display = $_POST['num_of_post'];
	$cur_page = $_POST['page'] + 1;

	$current_page = 1;

	$current_page++;

	global $post;

	$post_id = $post->ID;

	$post_list = get_posts( array(
		'numberposts' => $number_of_post_to_display,
		'orderby'    => 'date',
		'sort_order' => 'desc',
		'max_num_pages' => 1,
		'paged' => $cur_page,
		
	) );
	 
	$posts = array();
	foreach ( $post_list as $post ) {

		$posts[] += $post->ID;
		
		$post_title =  $post->post_title. '<br>';

		$featured_image =  get_the_post_thumbnail_url( $post->ID );

		if ( !$featured_image)  {
			$featured_image = '';
		}

		?>

		<div class="col-<?php echo $col_layout ?>">

		  <div class="blog_grid_container" style="background-image: url('<?php echo $featured_image; ?> '); "  >
		  <div class="post_title_grid"> 	<?php echo $post->post_title; ?>  </div>
		  </div>
		
	 	 </div> 

	  <?php
		
	 } 
	  die; // here we exit the script and even no wp_reset_query() required!
}

add_action('wp_ajax_loadmore', 'blg_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'blg_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

// Using Ajax to pass parameters and create load more for blog grid layout
// ### END
// #####################












// From CPT UI
// Post Type: Blog Grid Layouts.
function cptui_register_my_cpts_blog_grid_layouts() {

     $labels = [
		"name" => __( "Blog Grid Layouts", "twentytwenty" ),
		"singular_name" => __( "Blog Grid Layout", "twentytwenty" ),
		"menu_name" => __( "Blog Grid Layout", "twentytwenty" ),
		"all_items" => __( "All Blog Grid Layouts", "twentytwenty" ),
		"add_new" => __( "Add New", "twentytwenty" ),
		"add_new_item" => __( "Add New Blog Gird Layout", "twentytwenty" ),
	];

	$args = [
		"label" => __( "Blog Grid Layouts", "twentytwenty" ),
		"labels" => $labels,
		"description" => "Select a blog grid layout then insert it to any post or page using shortcodes.",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "blog_grid_layouts", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-grid-view",
		"supports" => false,
	];

	register_post_type( "blog_grid_layouts", $args );
}

add_action( 'init', 'cptui_register_my_cpts_blog_grid_layouts' );

 















// This is ajax preview for blog grid layouy
// ######
// START		
add_action( 'wp_ajax_blg_preview_action', 'blg_preview_action' );

function blg_preview_action() {

	// Retrieve data from ajax
	$col_layout = $_POST['col_layout'];
	$number_of_post_to_display = $_POST['num_of_post'];
	$cur_page = $_POST['page'];

	$current_page = 1;

	$current_page++;

	global $post;

	$post_id = $post->ID;

	$post_list = get_posts( array(
		'numberposts' => $number_of_post_to_display,
		'orderby'    => 'date',
		'sort_order' => 'desc',
		'max_num_pages' => 1,
		'paged' => $cur_page,
		
	) );
	 
	$posts = array();
	foreach ( $post_list as $post ) {

		$posts[] += $post->ID;
		
		$post_title =  $post->post_title. '<br>';

		$featured_image =  get_the_post_thumbnail_url( $post->ID );

		if ( !$featured_image)  {
			$featured_image = '';
		}

		?>

		<div class="col-<?php echo $col_layout ?>">

		  <div class="blog_grid_container" style="background-image: url('<?php echo $featured_image; ?> '); "  >
		  <div class="post_title_grid"> 	<?php echo $post->post_title; ?>  </div>
		  </div>
		
	 	 </div> 

	  <?php
		
	 } 
	die; // here we exit the script and even no wp_reset_query() required!

	wp_die();

}

// This is ajax preview for blog grid layouy
// END
// ######