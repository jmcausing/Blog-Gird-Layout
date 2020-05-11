<?php
	
/*
Plugin Name: Blog Grid Layout
Plugin URI: http://causingdesignscom.kinsta.cloud/
Description: Similar to nginx access log where it get get the IP, user-agent, access path and display analytics chart.
Author: John Mark Causing
Author URI:  http://causingdesignscom.kinsta.cloud/
*/


// START - Metaboxes / fields / html for custom post type called 'causing_forms'
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
	$blg_column = (!empty( get_post_meta($post_id, 'blg_column', true))) ? get_post_meta($post_id, 'blg_column', true) : '';
	$blg_post_number = (!empty( get_post_meta($post_id, 'blg_post_number', true))) ? get_post_meta($post_id, 'blg_post_number', true) : '';
	$blg_category = (!empty( get_post_meta($post_id, 'blg_category', true))) ? get_post_meta($post_id, 'blg_category', true) : '';

	$post_list = get_posts( array(
		'numberposts' => 10,
		'orderby'    => 'menu_order',
		'sort_order' => 'asc'
	) );
	 
	$posts = array();

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<script type="text/javascript">
var field_id =  "post_number_display";    // <----------------- CHANGE THIS

var SubmitButton = document.getElementById("save-post") || false;
var PublishButton = document.getElementById("publish")  || false; 
if (SubmitButton)   {SubmitButton.addEventListener("click", SubmCLICKED, false);}
if (PublishButton)  {PublishButton.addEventListener("click", SubmCLICKED, false);}
function SubmCLICKED(e){   
  var passed= false;
  if(!document.getElementById(field_id)) { alert("I cant find that field ID !!"); }
  else {
      var Enabled_Disabled= document.getElementById(field_id).value;
      if (Enabled_Disabled == "" ) { 
		  
		 // alert("Field is Empty");  
		 jQuery('.post_number_display').append('<br><p style="color: red;">This field is required!')
		  
		  
		   }  else{passed=true;}
  }
  if (!passed) { e.preventDefault();  return false;  }
}
</script>

<style>
	.blog_grid_container { 
		border: 1px solid red;
		width: 250px;
		height: 100px;
		margin: auto;
		margin-bottom: 20px;
	}

	.blog_grid_container {
    background-size: cover;
	}
</style>


<div class="container">
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

			<select name="blg_column[]" id="blog_post_grid_cat" >

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
<div class="container">
  <div class="row">



<?php

if ($blg_column == null && $blg_post_number == null && $blg_category == null) {
	echo '<h2 style="color: red;"> Nothing yet to preview.. Set some settings first then save it!</h2>';
	return;	
}


if ( isset($blg_column) )  {
	foreach($blg_column as $col_num) {

		if ($col_num == 4) {
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
		
		</div>
	  
	</div>


	<?php
	} // end loop

	?>


  </div>
</div>
<!-- #################### -->
<!-- Bootstrap grid END -->


<?php
}
// ###############################
// END -- HTML meta box function


// ############################################################################
// END - Metaboxes / fields / html for custom post type called 'causing_forms'



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
 	$blg_column = ( isset($_POST['blg_column']) ) ? $_POST['blg_column'] : '';
	$blg_post_number = ( isset($_POST['blg_post_number']) ) ? sanitize_text_field( $_POST['blg_post_number']) : '';
	$blg_category = ( isset($_POST['blg_category']) ) ?  $_POST['blg_category'] : '';


	// update / insert post meta

	update_post_meta($post_id, 'blg_column', array_map( 'strip_tags', $blg_column) );
	update_post_meta($post_id, 'blg_post_number', $blg_post_number);
	update_post_meta($post_id, 'blg_category', array_map( 'strip_tags', $blg_category) );
	
}
add_action('save_post', 'save_blog_grid_layouts_meta', 10, 2); 


// END -- save data from custom post type 'causing_forms'
// ###############################






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

 


