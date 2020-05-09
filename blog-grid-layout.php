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

add_action('add_meta_boxes_blog_grid_layouts', 'meta_box_blog_grid_layout')gi;

// START -- HTML meta box functionl
// #################################
function blog_grid_layout_page_function() {

	$post_list = get_posts( array(
		'numberposts' => 10,
		'orderby'    => 'menu_order',
		'sort_order' => 'asc'
	) );
	 
	$posts = array();

//	var_dump($post_list);

?>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<h1>Blog Grid Layout Preview</h1>

<!-- Bootstrap grid start -->
<!-- #################### -->
<div class="container">
  <div class="row">


<?php

	// start loop
	foreach ( $post_list as $post ) {
	   $posts[] += $post->ID;

	  $post_title =  $post->post_title. '<br>';

	 $featured_image =  get_the_post_thumbnail_url( $post->ID );

	 if ( !$featured_image)  {
		 $featured_image = '';
	 }

	?>


  <div class="col-sm-4">
      <h4> <?php echo $post_title; ?>  </h4>


	  <img src="<?php echo $featured_image; ?>" alt="" width="352px" height="220px">
    </div>


	<?php
	} // end loop

	?>


  </div>
</div>
<!-- #################### -->
<!-- Bootstrap grid END -->

	<?php



/* 
	global $post;
	$post_id = $post->ID;
	$shortcode_string = 'cforms the_post_id=' . $post_id . '';
	$my_shortcode =  '['.  $shortcode_string .']';

	wp_nonce_field( basename( __FILE__), 'causing_forms_nonce'); // insert hidden field to verify later when sumitting the post

	// storing variables from the form name and html fields
	$form_name = (!empty( get_post_meta($post_id, 'cforms_name', true))) ? get_post_meta($post_id, 'cforms_name', true) : '';
	$from_html = (!empty( get_post_meta($post_id, 'cforms_html', true))) ? get_post_meta($post_id, 'cforms_html', true) : '';
 */



	?>


<!-- HTML source of this metabox -->		


<!--

		<div class="slb-field-row">
			<div class="slb-field-container">
				<label for="">Form Name <span>*</span> </label>
				<input type="name" name="cforms_name" require="" class="widefat" value="" />
			</div>
		</div>
		</br>
		<div class="slb-field-row">
			<div class="slb-field-container">
				<label for="">HTML Text Box <span>*</span></label><br>

			</div>
			<div class="slb-field-container">
				<label for="">HTML Preview </label>
				<div class="html_preview"> 
				
				</div>
			</div>
		</div>

	<h1>This is your shortcode for this form: <b>  </h1>
-->


<?php

}
// ###############################
// END -- HTML meta box function


// ############################################################################
// END - Metaboxes / fields / html for custom post type called 'causing_forms'




















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
		"supports" => false,
	];

	register_post_type( "blog_grid_layouts", $args );
}

add_action( 'init', 'cptui_register_my_cpts_blog_grid_layouts' );

 