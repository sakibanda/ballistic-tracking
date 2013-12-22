<?php

add_action('init','woo_global_options');
function woo_global_options() {
	// Populate WooThemes option in array for use in theme
	global $woo_options;
	$woo_options = get_option('woo_options');
}

add_action( 'admin_head','woo_options' );  
if (!function_exists('woo_options')) {
function woo_options() {
	
// VARIABLES
$themename = "Inspire";
$manualurl = 'http://www.woothemes.com/support/theme-documentation/inspire/';
$shortname = "woo";

$GLOBALS['template_path'] = get_bloginfo('template_directory');

//Access the WordPress Categories via an Array
$woo_categories = array();  
$woo_categories_obj = get_categories('hide_empty=0');
foreach ($woo_categories_obj as $woo_cat) {
    $woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;}
$categories_tmp = array_unshift($woo_categories, "Select a category:");    
       
//Access the WordPress Pages via an Array
$woo_pages = array();
$woo_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($woo_pages_obj as $woo_page) {
    $woo_pages[$woo_page->ID] = $woo_page->post_name; }
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");       

// Image Alignment radio box
$options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 

// Image Links to Options
$options_image_link_to = array("image" => "The Image","post" => "The Post"); 

//Testing 
$options_select = array("one","two","three","four","five"); 
$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five"); 

//URL Shorteners
if (_iscurlinstalled()) {
	$options_select = array("Off","TinyURL","Bit.ly");
	$short_url_msg = 'Select the URL shortening service you would like to use.'; 
} else {
	$options_select = array("Off");
	$short_url_msg = '<strong>cURL was not detected on your server, and is required in order to use the URL shortening services.</strong>'; 
}

//Stylesheets Reader
$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//More Options


$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

// THIS IS THE DIFFERENT FIELDS
$options = array();   

$options[] = array( "name" => "General Settings",
                    "icon" => "general",
                    "type" => "heading");

$options[] = array( 'name' => __( 'Quick Start', 'woothemes' ),
    				'type' => 'subheading' );
                 
$options[] = array( "name" => "Theme Stylesheet",
					"desc" => "Select your theme's alternative color scheme.",
					"id" => $shortname."_alt_stylesheet",
					"std" => "default.css",
					"type" => "select",
					"options" => $alt_stylesheets);

$options[] = array( "name" => "Custom Logo",
					"desc" => "Upload a logo for your theme, or specify an image URL directly.",
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload");    
                                                                                     
$options[] = array( "name" => "Text Title",
					"desc" => "Enable if you want Blog Title and Tagline to be text-based. Setup title/tagline in WP -> Settings -> General.",
					"id" => $shortname."_texttitle",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px <a href='http://www.faviconr.com/'>ico image</a> that will represent your website's favicon.",
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload"); 
                                               
$options[] = array( "name" => "Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");        

$options[] = array( 'name' => __( 'Subscription Settings', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( "name" => "RSS URL",
					"desc" => "Enter your preferred RSS URL. (Feedburner or other)",
					"id" => $shortname."_feed_url",
					"std" => "",
					"type" => "text");
                    
$options[] = array( "name" => "E-Mail URL",
					"desc" => "Enter your preferred E-mail subscription URL. (Feedburner or other)",
					"id" => $shortname."_subscribe_email",
					"std" => "",
					"type" => "text");

$options[] = array( 'name' => __( 'Display Options', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( "name" => "Contact Form E-Mail",
					"desc" => "Enter your E-mail address to use on the Contact Form Page Template. Add the contact form by adding a new page and selecting 'Contact Form' as page template.",
					"id" => $shortname."_contactform_email",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => "Custom CSS",
                    "desc" => "Quickly add some CSS to your theme by adding it to this block.",
                    "id" => $shortname."_custom_css",
                    "std" => "",
                    "type" => "textarea");

$options[] = array( "name" => "Post/Page Comments",
					"desc" => "Select if you want to enable/disable comments on posts and/or pages. ",
					"id" => $shortname."_comments",
					"type" => "select2",
					"options" => array("post" => "Posts Only", "page" => "Pages Only", "both" => "Pages / Posts", "none" => "None") );                                                          
    
$options[] = array( "name" => "Styling Options",
                    "icon" => "styling",
					"type" => "heading");

$options[] = array( 'name' => __( 'General Styling', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( "name" =>  "Use Google Font",
					"desc" => "Use <a target='_blank' href='http://code.google.com/webfonts/family?family=Droid+Sans'>Google font replacement</a>. Fonts used are 'Droid Sans' and 'Droid Serif'. ",
					"id" => "woo_google_fonts",
					"std" => "true",
					"type" => "checkbox");  

$options[] = array( 'name' => __( 'Background', 'woothemes' ),
    				'type' => 'subheading' );
					
$options[] = array( "name" =>  "Body Background Color",
					"desc" => "Pick a custom color for background color of the theme e.g. #697e09",
					"id" => "woo_body_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" => "Body Background Image",
					"desc" => "Upload an image for the theme's background.",
					"id" => $shortname."_body_img",
					"std" => "",
					"type" => "upload");
					
$options[] = array( "name" => "Background Image Repeat",
                    "desc" => "Select how you would like to repeat the background-image.",
                    "id" => $shortname."_body_repeat",
                    "std" => "no-repeat",
                    "type" => "select",
                    "options" => $body_repeat);

$options[] = array( "name" => "Background Image Position",
                    "desc" => "Select how you would like to position the background.",
                    "id" => $shortname."_body_pos",
                    "std" => "top",
                    "type" => "select",
                    "options" => $body_pos);

$options[] = array( 'name' => __( 'Links', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( "name" =>  "Link Color",
					"desc" => "Pick a custom color for links or add a hex color code e.g. #697e09",
					"id" => "woo_link_color",
					"std" => "",
					"type" => "color");   

$options[] = array( "name" =>  "Link Hover Color",
					"desc" => "Pick a custom color for links hover or add a hex color code e.g. #697e09",
					"id" => "woo_link_hover_color",
					"std" => "",
					"type" => "color");  

$options[] = array( "name" =>  "Button Color",
					"desc" => "Pick a custom color for buttons or add a hex color code e.g. #697e09",
					"id" => "woo_button_color",
					"std" => "",
					"type" => "color");                    

$options[] = array( "name" => "Homepage",
                    "icon" => "homepage",
					"type" => "heading");    

$options[] = array( 'name' => __( 'Homepage Setup', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array(	"name" => "Disable Featured Area",
					"desc" => "Check this if you don't want to use the featured area.",
					"id" => $shortname."_featured_disable",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => "Mini-Features Area",
          "desc" => "Enable the front page Mini-Features features area.",
          "id" => $shortname."_main_pages",
          "std" => "true",
          "type" => "checkbox");

if ( is_woocommerce_activated() ) {					
$options[] = array( 'name' => __( 'Display featured products', 'woothemes' ),
					'desc' => __( 'Display features products on the homepage?', 'woothemes' ),
					'id' => $shortname.'_homepage_featured_products',
					'std' => 'false',
					"class" => "",
					'type' => 'checkbox' );
}

$options[] = array( 'name' => __( 'Featured Slider', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array(	"name" => "Featured Fade Speed",
					"desc" => "Enter a time in seconds for the fade transition",
					"id" => $shortname."_featured_speed",
                    "std" => "0.6",
					"type" => "select",
					"options" => array( '0.0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1.0', '1.1', '1.2', '1.3', '1.4', '1.5', '1.6', '1.7', '1.8', '1.9', '2.0' ) );

$options[] = array(	"name" => "Featured Timeout",
					"desc" => "Enter a time in seconds to wait between page transitions. Set to 0 to disable auto fade.",
					"id" => $shortname."_featured_timeout",
                    "std" => "6",
					"type" => "select",
					"options" => array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' ) );
					
$options[] = array(	"name" => "Featured Auto Resize",
					"desc" => "Enable automatic resizing of the featured area with animation to fit the featured page content.",
					"id" => $shortname."_featured_resize",
					"std" => "false",
					"type" => "checkbox");
					
$options[] = array(	"name" => "Custom Height",
					"desc" => "Change the default featured height from 325px to your own preferred height. <strong>Example: '300'</strong>.",
					"id" => $shortname."_featured_height",
					"std" => "",
					"type" => "text");

$options[] = array( 'name' => __( 'Mini-Features', 'woothemes' ),
					'type' => 'subheading' );

if ( !is_features_activated() ) {
	$options[] = array( 'name' => __( 'Deprecated Notice', 'woothemes' ),
	                    'id' => $shortname . '_features_deprecated_notice',
	                    'std' => sprintf ( __( 'This feature will be removed in the next version of %1$s in favor of %2$s. Please download and install the plugin.', 'woothemes' ), $themename, '<a href="http://wordpress.org/plugins/features-by-woothemes/">' . __( 'Features by WooThemes', 'woothemes' ) . '</a>' ),
	                    'type' => 'info' );
}

$options[] = array( 'name' => __( 'Display how many mini-features?', 'woothemes' ),
					'desc' => __( 'Specify how many mini-features should appear on the homepage.', 'woothemes' ),
					'id' => $shortname . '_homepage_mini_features_perpage',
					'std' => '4',
					'type' => 'select2',
					"class" => "",
					'options' => $other_entries);

if ( !is_features_activated() ) {

	$options[] = array(	"name" => "Mini-Features More Link Text",
						"desc" => "Enter a text for the more features link under mini-features.",
						"id" => $shortname."_main_pages_link_text",
						"std" => "View all features",
						"type" => "text");
						
	$options[] = array( "name" => "Custom permalink",
	          "desc" => "This option allows you to change the permalink on the individual mini-features pages. (e.g /infobox/pagename to /features/pagename/). Please update <a href='". admin_url('options-permalink.php')."'>Permalinks</a> after any changes.",
	          "id" => $shortname."_infobox_rewrite",
	          "std" => "infobox",
	          "type" => "text");                          

}

$options[] = array( 'name' => __( 'Content Areas', 'woothemes' ),
					'type' => 'subheading' );				

$options[] = array( "name" => "Homepage content #1",
          "desc" => "(Optional) Select a page that you'd like to display on the front page <strong>above the mini features area</strong>.",
          "id" => $shortname."_main_page1",
          "std" => "Select a page:",
			"type" => "select",
			"options" => $woo_pages);   

$options[] = array( "name" => "Homepage content #2",
          "desc" => "(Optional) Select a page that you'd like to display on the front page <strong>below the mini features area.</strong>",
          "id" => $shortname."_main_page2",
          "std" => "Select a page:",
			"type" => "select",
			"options" => $woo_pages);   

/* Homepage Featured Products */
if ( is_woocommerce_activated() ) {
$options[] = array( 'name' => __( 'Featured Products', 'woothemes' ),
					'type' => 'subheading' );                      
$options[] = array( 'name' => __( 'Display how many featured products?', 'woothemes' ),
					'desc' => __( 'Specify how many featured products should appear on the homepage.', 'woothemes' ),
					'id' => $shortname . '_homepage_featured_products_perpage',
					'std' => '4',
					'type' => 'select2',
					"class" => "",
					'options' => $other_entries);
}

$options[] = array( "name" => "Portfolio",
                    "icon" => "portfolio",
					"type" => "heading");    

$options[] = array(	"name" => "Use Lightbox?",
					"desc" => "Show the portfolio URL or large image in a javascript lightbox.",
					"id" => $shortname."_portfolio_lightbox",
					"std" => "true",
					"type" => "checkbox");

$options[] = array(	"name" => "Use Dynamic Image Resizer?",
					"desc" => "Use the dynamic image resizer (thumb.php) to resize the portfolio thumbnail. Remember to CHMOD your cache folder to 777. <a href='http://www.woothemes.com/2008/10/troubleshooting-image-resizer-thumbphp/'>Need help?</a>",
					"id" => $shortname."_portfolio_resize",
					"std" => "false",
					"type" => "checkbox");		

$options[] = array( "name" => "Portfolio Tags",
					"desc" => "Enter comma seperated tags for portfolio sorting (e.g. web, print, icons). You must add these tags to the portfolio items you want to sort.",
					"id" => $shortname."_portfolio_tags",
					"std" => "",
					"type" => "text");    

$options[] = array( "name" => "Layout Options",
                    "icon" => "layout",
					"type" => "heading");  

$options[] = array(	"name" => "Show Breadcrumbs bar?",
					"desc" => "Check this box if you'd like to show breadcrumbs at the top of your pages, posts and archives. <a href='http://yoast.com/wordpress/breadcrumbs/'>Yoast Breadcrumbs Plugin</a> is supported in the breadcrumbs bar.",
					"id" => $shortname."_breadcrumbs",
					"std" => "true",
					"type" => "checkbox");

$options[] = array(	"name" => "Blog Excerpt",
					"desc" => "Show only the excerpt in the blog section. ",
					"id" => $shortname."_excerpt",
					"std" => "true",
					"type" => "checkbox");
 					                   
$options[] = array( "name" => "Dynamic Images",
					"type" => "heading",
					"icon" => "image");    

$options[] = array( 'name' => __( 'Resizer Settings', 'woothemes' ),
    				'type' => 'subheading' );
			    				   
$options[] = array( "name" => 'Dynamic Image Resizing',
					"desc" => "",
					"id" => $shortname."_wpthumb_notice",
					"std" => 'There are two alternative methods of dynamically resizing the thumbnails in the theme, <strong>WP Post Thumbnail</strong> or <strong>TimThumb - Custom Settings panel</strong>. We recommend using WP Post Thumbnail option.',
					"type" => "info");					

$options[] = array( "name" => "WP Post Thumbnail",
					"desc" => "Use WordPress post thumbnail to assign a post thumbnail. Will enable the <strong>Featured Image panel</strong> in your post sidebar where you can assign a post thumbnail.",
					"id" => $shortname."_post_image_support",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox" );

$options[] = array( "name" => "WP Post Thumbnail - Dynamic Image Resizing",
					"desc" => "The post thumbnail will be dynamically resized using native WP resize functionality. <em>(Requires PHP 5.2+)</em>",
					"id" => $shortname."_pis_resize",
					"std" => "true",
					"class" => "hidden",
					"type" => "checkbox" );

$options[] = array( "name" => "WP Post Thumbnail - Hard Crop",
					"desc" => "The post thumbnail will be cropped to match the target aspect ratio (only used if 'Dynamic Image Resizing' is enabled).",
					"id" => $shortname."_pis_hard_crop",
					"std" => "true",
					"class" => "hidden last",
					"type" => "checkbox" );

$options[] = array( "name" => "TimThumb - Custom Settings Panel",
					"desc" => "This will enable the <a href='http://code.google.com/p/timthumb/'>TimThumb</a> (thumb.php) script which dynamically resizes images added through the <strong>custom settings panel below the post</strong>. Make sure your themes <em>cache</em> folder is writable. <a href='http://www.woothemes.com/2008/10/troubleshooting-image-resizer-thumbphp/'>Need help?</a>",
					"id" => $shortname."_resize",
					"std" => "true",
					"type" => "checkbox" );

$options[] = array( "name" => "Automatic Image Thumbnail",
					"desc" => "If no thumbnail is specifified then the first uploaded image in the post is used.",
					"id" => $shortname."_auto_img",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( 'name' => __( 'Thumbnail Settings', 'woothemes' ),
    				'type' => 'subheading' );
				                    
$options[] = array( "name" => "Thumbnail Image Dimensions",
					"desc" => "Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.",
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_thumb_w',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_thumb_h',
											'type' => 'text',
											'std' => 100,
											'meta' => 'Height')
								  ));
                                                                                                
$options[] = array( "name" => "Thumbnail Image alignment",
					"desc" => "Select how to align your thumbnails with posts.",
					"id" => $shortname."_thumb_align",
					"std" => "alignleft",
					"type" => "radio",
					"options" => $options_thumb_align); 

$options[] = array( "name" => "Show thumbnail in Single Posts",
					"desc" => "Show the attached image in the single post page.",
					"id" => $shortname."_thumb_single",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => "Single Image Dimensions",
					"desc" => "Enter an integer value i.e. 250 for the image size. Max width is 576.",
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array( 
									array(  'id' => $shortname. '_single_w',
											'type' => 'text',
											'std' => 200,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_single_h',
											'type' => 'text',
											'std' => 200,
											'meta' => 'Height')
								  ));

$options[] = array( "name" => "Add thumbnail to RSS feed",
					"desc" => "Add the the image uploaded via your Custom Settings to your RSS feed",
					"id" => $shortname."_rss_thumb",
					"std" => "false",
					"type" => "checkbox");  
					
//Footer
$options[] = array( "name" => "Footer Customization",
                    "icon" => "footer",
                    "type" => "heading");
					
					
$options[] = array( "name" => "Custom Affiliate Link",
					"desc" => "Add an affiliate link to the WooThemes logo in the footer of the theme.",
					"id" => $shortname."_footer_aff_link",
					"std" => "",
					"type" => "text");	
									
$options[] = array( "name" => "Enable Custom Footer (Left)",
					"desc" => "Activate to add the custom text below to the theme footer.",
					"id" => $shortname."_footer_left",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => "Custom Text (Left)",
					"desc" => "Custom HTML and Text that will appear in the footer of your theme.",
					"id" => $shortname."_footer_left_text",
					"std" => "<p></p>",
					"type" => "textarea");
						
$options[] = array( "name" => "Enable Custom Footer (Right)",
					"desc" => "Activate to add the custom text below to the theme footer.",
					"id" => $shortname."_footer_right",
					"std" => "false",
					"type" => "checkbox");    

$options[] = array( "name" => "Custom Text (Right)",
					"desc" => "Custom HTML and Text that will appear in the footer of your theme.",
					"id" => $shortname."_footer_right_text",
					"std" => "<p></p>",
					"type" => "textarea");
					
/* Subscribe & Connect */
$options[] = array( "name" => "Subscribe & Connect",
					"type" => "heading",
					"icon" => "connect" );

$options[] = array( 'name' => __( 'Setup', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( "name" => "Enable Subscribe & Connect - Single Post",
					"desc" => "Enable the subscribe & connect area on single posts. You can also add this as a <a href='".home_url()."/wp-admin/widgets.php'>widget</a> in your sidebar.",
					"id" => $shortname."_connect",
					"std" => 'false',
					"type" => "checkbox" );

$options[] = array( "name" => "Subscribe Title",
					"desc" => "Enter the title to show in your subscribe & connect area.",
					"id" => $shortname."_connect_title",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Text",
					"desc" => "Change the default text in this area.",
					"id" => $shortname."_connect_content",
					"std" => '',
					"type" => "textarea" );

$options[] = array( "name" => "Enable Related Posts",
					"desc" => "Enable related posts in the subscribe area. Uses posts with the same <strong>tags</strong> to find related posts. Note: Will not show in the Subscribe widget.",
					"id" => $shortname."_connect_related",
					"std" => 'true',
					"type" => "checkbox" );

$options[] = array( 'name' => __( 'Subscribe Settings', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( "name" => "Subscribe By E-mail ID (Feedburner)",
					"desc" => "Enter your <a href='http://www.woothemes.com/tutorials/how-to-find-your-feedburner-id-for-email-subscription/'>Feedburner ID</a> for the e-mail subscription form.",
					"id" => $shortname."_connect_newsletter_id",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => 'Subscribe By E-mail to MailChimp', 'woothemes',
					"desc" => 'If you have a MailChimp account you can enter the <a href="http://woochimp.heroku.com" target="_blank">MailChimp List Subscribe URL</a> to allow your users to subscribe to a MailChimp List.',
					"id" => $shortname."_connect_mailchimp_list_url",
					"std" => '',
					"type" => "text");

$options[] = array( 'name' => __( 'Connect Settings', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( "name" => "Enable RSS",
					"desc" => "Enable the subscribe and RSS icon.",
					"id" => $shortname."_connect_rss",
					"std" => 'true',
					"type" => "checkbox" );

$options[] = array( "name" => "Twitter URL",
					"desc" => "Enter your  <a href='http://www.twitter.com/'>Twitter</a> URL e.g. http://www.twitter.com/woothemes",
					"id" => $shortname."_connect_twitter",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Facebook URL",
					"desc" => "Enter your  <a href='http://www.facebook.com/'>Facebook</a> URL e.g. http://www.facebook.com/woothemes",
					"id" => $shortname."_connect_facebook",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "YouTube URL",
					"desc" => "Enter your  <a href='http://www.youtube.com/'>YouTube</a> URL e.g. http://www.youtube.com/woothemes",
					"id" => $shortname."_connect_youtube",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Flickr URL",
					"desc" => "Enter your  <a href='http://www.flickr.com/'>Flickr</a> URL e.g. http://www.flickr.com/woothemes",
					"id" => $shortname."_connect_flickr",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "LinkedIn URL",
					"desc" => "Enter your  <a href='http://www.www.linkedin.com.com/'>LinkedIn</a> URL e.g. http://www.linkedin.com/in/woothemes",
					"id" => $shortname."_connect_linkedin",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Delicious URL",
					"desc" => "Enter your <a href='http://www.delicious.com/'>Delicious</a> URL e.g. http://www.delicious.com/woothemes",
					"id" => $shortname."_connect_delicious",
					"std" => '',
					"type" => "text" );

$options[] = array( "name" => "Google+ URL",
					"desc" => "Enter your <a href='http://plus.google.com/'>Google+</a> URL e.g. https://plus.google.com/104560124403688998123/",
					"id" => $shortname."_connect_googleplus",
					"std" => '',
					"type" => "text" );

							                                       
// Add extra options through function
if ( function_exists("woo_options_add") )
  $options = woo_options_add($options);                                              

if ( get_option('woo_template') != $options) update_option('woo_template',$options);      
if ( get_option('woo_themename') != $themename) update_option('woo_themename',$themename);   
if ( get_option('woo_shortname') != $shortname) update_option('woo_shortname',$shortname);
if ( get_option('woo_manual') != $manualurl) update_option('woo_manual',$manualurl);

                                     
// Woo Metabox Options
$woo_metaboxes = array();

if( get_post_type() == 'post' || !get_post_type()){

$woo_metaboxes[] = array (	"name" => "image",
							"label" => "Image",
							"type" => "upload",
							"desc" => "Upload image for use with blog posts");
} // End post

if( get_post_type() == 'slide' || !get_post_type()){

  $woo_metaboxes["slide_image"] = array (
              "name" => "slide_image",
              "label" => "Slider Image",
              "type" => "upload",
              "desc" => "Upload image for use in the featured area on the homepage"
          );        

  $woo_metaboxes["slide_url"] = array (
              "name" => "slide_url",
              "label" => "Slider Link out",
              "type" => "text",
              "desc" => "Enter a custom URL for the featured image on homepage"
          );
  $woo_metaboxes["slide_embed"] = array (
              "name" => "slide_embed",
              "label" => "Slider Embed Code",
              "type" => "textarea",
              "desc" => "Enter a video embed code to use in featured area."
          );

} // End slide

if( get_post_type() == 'infobox' || !get_post_type()){
	

$woo_metaboxes['mini'] = array (	
				"name" => "mini",
				"label" => "Mini-features Icon",
				"type" => "upload",
				"desc" => "Upload icon for use with the Mini-Feature on the homepage (optimal size: 32x32px) (optional)"
			);
 
$woo_metaboxes['mini_excerpt'] = array (	
				"name" => "mini_excerpt",
				"label" => "Mini-features Excerpt",
				"type" => "textarea",
				"desc" => "Enter the text to show in your Mini-Feature. "
			);

$woo_metaboxes['mini_readmore'] = array (	
				"name" => "mini_readmore",
				"std" => "",
				"label" => "Mini-features URL",
				"type" => "text",
				"desc" => "Add an URL for your Read More button in your Mini-Feature on homepage (optional)"
			);

} // End mini

if( get_post_type() == 'portfolio' || !get_post_type()){

$woo_metaboxes['portfolio'] = array (	"name" => "portfolio",
							"label" => "Portfolio Thumbnail",
							"type" => "upload",
							"desc" => "Upload an image for use in the portfolio (optimal size: 450x210)");

$woo_metaboxes['portfolio-large'] = array (	"name" => "portfolio-large",
							"label" => "Portfolio Large",
							"type" => "upload",
							"desc" => "Add an URL OR upload an image for use as the large portfolio image");

} // End portfolio

if( get_post_type() == 'feedback' || !get_post_type()){

$woo_metaboxes['feedback_citation'] = array ( "name" => "feedback_citation",
							"label" => "Citation",
							"type" => "text",
							"desc" => "Enter a citation for this feedback post.");

} // End portfolio

// Add extra metaboxes through function
if ( function_exists("woo_metaboxes_add") )
	$woo_metaboxes = woo_metaboxes_add($woo_metaboxes);
    
if ( get_option('woo_custom_template') != $woo_metaboxes) update_option('woo_custom_template',$woo_metaboxes);

}
}
?>