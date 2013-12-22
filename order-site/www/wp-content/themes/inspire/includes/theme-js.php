<?php
if ( !is_admin() ) { add_action( 'wp_print_scripts', 'woothemes_add_javascript' ); }

function woothemes_add_javascript() {	
	$template_directory = get_template_directory_uri();

	wp_enqueue_script('jquery');    
	wp_enqueue_script( 'superfish', $template_directory . '/includes/js/superfish.js', array( 'jquery' ) );
	if ( is_home() && get_option( 'woo_featured_disable' ) != 'true' ) {
		wp_enqueue_script( 'slides', $template_directory . '/includes/js/slides.min.jquery.js', array( 'jquery' ) );
	}
	wp_enqueue_script( 'general', $template_directory . '/includes/js/general.js', array( 'jquery' ) );
	wp_enqueue_script( 'prettyPhoto', $template_directory . '/includes/js/jquery.prettyPhoto.js', array( 'jquery' ) );
} // End woothemes_add_javascript()
?>