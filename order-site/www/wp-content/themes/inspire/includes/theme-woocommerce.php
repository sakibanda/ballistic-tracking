<?php

// If WooCommerce is active, do all the things
if ( is_woocommerce_activated() )

add_theme_support( 'woocommerce' );

// Load WooCommerce stylsheet
if ( ! is_admin() ) { add_action( 'get_header', 'woo_load_woocommerce_css', 20 ); }

if ( ! function_exists( 'woo_load_woocommerce_css' ) ) {
	function woo_load_woocommerce_css () {
		wp_register_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
		wp_enqueue_style( 'woocommerce' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Hook in on activation */
/*-----------------------------------------------------------------------------------*/

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action('init', 'woo_install_theme', 1);

/*-----------------------------------------------------------------------------------*/
/* Install */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_install_theme' ) ) {
	function woo_install_theme() {

		update_option( 'woocommerce_thumbnail_image_width', '200' );
		update_option( 'woocommerce_thumbnail_image_height', '200' );
		update_option( 'woocommerce_single_image_width', '500' ); // Single
		update_option( 'woocommerce_single_image_height', '500' ); // Single
		update_option( 'woocommerce_catalog_image_width', '400' ); // Catalog
		update_option( 'woocommerce_catalog_image_height', '400' ); // Catlog

	}
}

// Insert HTML5 Shiv
add_action('wp_head', 'woocommerce_html5');

if ( ! function_exists( 'woocommerce_html5' ) ) {
	function woocommerce_html5() {
		echo '<!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
	}
}

// Disable WooCommerce styles
if ( version_compare( WOOCOMMERCE_VERSION, '2.1' ) >= 0 ) {
    // WooCommerce 2.1 or above is active
    add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
    // WooCommerce less than 2.1 is active
    define( 'WOOCOMMERCE_USE_CSS', false );
}

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// WooCommerce overrides
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'woocommerce_inspire_before_content', 20);
add_action('woocommerce_after_main_content', 'woocommerce_inspire_after_content', 30);

if ( ! function_exists( 'woocommerce_inspire_before_content' ) ) {
	function woocommerce_inspire_before_content() {
		?>
		<!-- #content Starts -->
		<?php woo_crumbs(); ?>
		</div><!--/#top-->

		<?php woo_content_before(); ?>
	    <div id="content">

	    	<div class="col-full">

		    	<div id="main-sidebar-container">

		            <!-- #main Starts -->
		            <?php woo_main_before(); ?>
		            <div id="main" class="col-left">
	    <?php
	}
}

if ( ! function_exists( 'woocommerce_inspire_after_content' ) ) {
	function woocommerce_inspire_after_content() {
		?>
					</div><!-- /#main -->
		            <?php woo_main_after(); ?>

				</div><!-- /#main-sidebar-container -->

			</div><!--/.col-full-->

	    </div><!-- /#content -->
		<?php woo_content_after(); ?>
	    <?php
	}
}

// Add the WC sidebar in the right place
add_action( 'woo_main_after', 'woocommerce_get_sidebar', 10);

// Remove breadcrumb (we're using the WooFramework default breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

add_action( 'woocommerce_after_main_content', 'inspire_commerce_pagination', 01, 0);

remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

if ( ! function_exists( 'inspire_commerce_pagination' ) ) {
	function inspire_commerce_pagination() {
		if ( is_search() && is_post_type_archive() ) {
			add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
		}
		woo_pagenav();
	}
}

if ( ! function_exists( 'woocommerceframework_add_search_fragment' ) ) {
	function woocommerceframework_add_search_fragment ( $settings ) {
		$settings['add_fragment'] = '&post_type=product';
		return $settings;
	} // End woocommerceframework_add_search_fragment()
}

// Change columns in related products output to 3
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
if ( ! function_exists( 'woocommerce_output_related_products' ) ) {
	function woocommerce_output_related_products() {
		woocommerce_related_products(3,3); // 3 products, 3 columns
	}
}

if ( ! function_exists( 'loop_columns' ) ) {
	// Change columns in product loop to 3
	function loop_columns() {
		return 3;
	}
}

add_filter('loop_shop_columns', 'loop_columns');

// Remove pagination - we're using WF pagination.
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );

// Display 12 products per page
add_filter('loop_shop_per_page', create_function('$cols', 'return 12;'));

// Fix sidebar on shop page
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Adjust the star rating in the sidebar
add_filter('woocommerce_star_rating_size_sidebar', 'woostore_star_sidebar');

if ( ! function_exists( 'woostore_star_sidebar' ) ) {
	function woostore_star_sidebar() {
		return 12;
	}
}

// Adjust the star rating in the recent reviews
add_filter('woocommerce_star_rating_size_recent_reviews', 'woostore_star_reviews');
if ( ! function_exists( 'woostore_star_reviews' ) ) {
	function woostore_star_reviews() {
		return 12;
	}
}