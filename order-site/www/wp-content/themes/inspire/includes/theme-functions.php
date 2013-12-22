<?php 
/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Check if WooCommerce is activated
- Check if Features by WooThemes is activated
- Check if Testimonials by WooThemes is activated

- Page / Post navigation
- WooTabs - Popular Posts
- WooTabs - Latest Posts
- WooTabs - Latest Comments
- Woo Breadcrumbs
- Misc
  - WordPress 3.0 New Features Support

- Custom Post Type - Slides
- Custom Post Type - Mini-Features
- Custom Post Type - Portfolio
- Get Post image attachments
- Subscribe & Connect

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Check if WooCommerce is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
    function is_woocommerce_activated() {
        if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
    }
}

/*-----------------------------------------------------------------------------------*/
/* Check if Features by WooThemes is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'is_features_activated' ) ) {
    function is_features_activated() {
        if ( class_exists( 'Woothemes_Features' ) ) { return true; } else { return false; }
    }
}

/*-----------------------------------------------------------------------------------*/
/* Check if Testimonials by WooThemes is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'is_testimonials_activated' ) ) {
    function is_testimonials_activated() {
        if ( class_exists( 'Woothemes_Testimonials' ) ) { return true; } else { return false; }
    }
}


/*-----------------------------------------------------------------------------------*/
/* Page / Post navigation */
/*-----------------------------------------------------------------------------------*/
function woo_pagenav() { 

	if (function_exists('wp_pagenavi') ) { ?>
    
<?php wp_pagenavi(); ?>
    
	<?php } else { ?>    
    
		<?php if ( get_next_posts_link() || get_previous_posts_link() ) { ?>
        
            <div class="nav-entries">
                <div class="nav-prev fl"><?php previous_posts_link(__('&laquo; Newer Entries ', 'woothemes')) ?></div>
                <div class="nav-next fr"><?php next_posts_link(__(' Older Entries &raquo;', 'woothemes')) ?></div>
                <div class="fix"></div>
            </div>	
        
		<?php } ?>
    
	<?php }   
}                	

function woo_postnav() { 

	?>
        <div class="post-entries">
            <div class="post-prev fl"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ) ?></div>
            <div class="post-next fr"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ) ?></div>
            <div class="fix"></div>
        </div>	

	<?php 
}                	



/*-----------------------------------------------------------------------------------*/
/* WooTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('woo_tabs_popular')) {
	function woo_tabs_popular( $posts = 5, $size = 35 ) {
		global $post;
		$popular = get_posts('orderby=comment_count&posts_per_page='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) woo_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<div class="fix"></div>
	</li>
	<?php endforeach;
	}
}



/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Posts */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('woo_tabs_latest')) {
	function woo_tabs_latest( $posts = 5, $size = 35 ) {
		global $post;
		$latest = get_posts('showposts='. $posts .'&orderby=post_date&order=desc');
		foreach($latest as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) woo_image('height='.$size.'&width='.$size.'&class=thumbnail&single=true'); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<div class="fix"></div>
	</li>
	<?php endforeach; 
	}
}



/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Comments */
/*-----------------------------------------------------------------------------------*/

function woo_tabs_comments( $posts = 5, $size = 35 ) {
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
	comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
	comment_type,comment_author_url,
	SUBSTRING(comment_content,1,50) AS com_excerpt
	FROM $wpdb->comments
	LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
	$wpdb->posts.ID)
	WHERE comment_approved = '1' AND comment_type = '' AND
	post_password = ''
	ORDER BY comment_date_gmt DESC LIMIT ".$posts;
	
	$comments = $wpdb->get_results($sql);
	
	foreach ($comments as $comment) {
	?>
	<li>
		<?php echo get_avatar( $comment, $size ); ?>
	
		<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php _e('on ', 'woothemes'); ?> <?php echo $comment->post_title; ?>">
			<?php echo strip_tags($comment->comment_author); ?>: <?php echo strip_tags($comment->com_excerpt); ?>...
		</a>
		<div class="fix"></div>
	</li>
	<?php 
	}
}


/*-----------------------------------------------------------------------------------*/
/* Woo Breadcrumbs */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_crumbs') ) {
function woo_crumbs() {
	$bc = get_option( 'woo_breadcrumbs' );
	if ( $bc == 'true' ) {

// Setup RSS Feed URL
$feedurl = get_option( 'woo_feed_url' ); 
if ( empty( $feedurl ) ) {
    $feedurl = get_feed_link();
}
if ( is_ssl() ) { $feedurl = str_replace( 'http://', 'https://', $feedurl ); }

wp_reset_query(); // Make sure we're at the original query prior to displaying the breadcrumbs.
?>

     <div id="breadcrumb">
        <div class="col-full">
            <div class="fl"><?php if ( function_exists( 'yoast_breadcrumb' ) ) { yoast_breadcrumb( '', '' ); } else { woo_breadcrumbs(); } ?></div>
            <a class="subscribe fr" href="<?php echo esc_url( $feedurl ); ?>">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/ico-rss.png" alt="Subscribe" class="rss" />
            </a>        
        </div>
    </div> 
    
<?php
	}
}
} 

/*-----------------------------------------------------------------------------------*/
/* MISC */
/*-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu' ) ) );
} 


/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Slides */
/*-----------------------------------------------------------------------------------*/

add_action('init', 'woo_add_slides');
function woo_add_slides() 
{
  $labels = array(
    'name' => _x('Slides', 'post type general name', 'woothemes', 'woothemes'),
    'singular_name' => _x('Slide', 'post type singular name', 'woothemes'),
    'add_new' => _x('Add New', 'slide', 'woothemes'),
    'add_new_item' => __('Add New Slide', 'woothemes'),
    'edit_item' => __('Edit Slide', 'woothemes'),
    'new_item' => __('New Slide', 'woothemes'),
    'view_item' => __('View Slide', 'woothemes'),
    'search_items' => __('Search Slides', 'woothemes'),
    'not_found' =>  __('No slides found', 'woothemes'),
    'not_found_in_trash' => __('No slides found in Trash', 'woothemes'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_icon' => get_template_directory_uri() .'/includes/images/slides.png',
    'menu_position' => null,
    'supports' => array('title','editor',/*'author','thumbnail','excerpt','comments'*/)
  ); 
  register_post_type('slide',$args);
}



/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Info Boxes */
/*-----------------------------------------------------------------------------------*/

if ( !is_features_activated() )
    add_action('init', 'woo_add_infoboxes');

function woo_add_infoboxes() 
{
  $labels = array(
    'name' => _x('Mini-Features', 'post type general name', 'woothemes'),
    'singular_name' => _x('Mini-Feature', 'post type singular name', 'woothemes'),
    'add_new' => _x('Add New', 'infobox', 'woothemes'),
    'add_new_item' => __('Add New Mini-Feature', 'woothemes'),
    'edit_item' => __('Edit Mini-Feature', 'woothemes'),
    'new_item' => __('New Mini-Feature', 'woothemes'),
    'view_item' => __('View Mini-Feature', 'woothemes'),
    'search_items' => __('Search Mini-Features', 'woothemes'),
    'not_found' =>  __('No Mini-Features found', 'woothemes'),
    'not_found_in_trash' => __('No Mini-Features found in Trash', 'woothemes'), 
    'parent_item_colon' => ''
  );
  
  $infobox_rewrite = get_option('woo_infobox_rewrite');
  if(empty($infobox_rewrite)) $infobox_rewrite = 'infobox';
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => array('slug'=> $infobox_rewrite),
    'capability_type' => 'post',
    'hierarchical' => false,
    'has_archive' => true,
    'menu_icon' => get_template_directory_uri() .'/includes/images/box.png',
    'menu_position' => null,
    'supports' => array('title','editor',/*'author','thumbnail','excerpt','comments'*/)
  ); 
  register_post_type('infobox',$args);
}


/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Portfolio */
/*-----------------------------------------------------------------------------------*/

add_action('init', 'woo_add_portfolio');
function woo_add_portfolio() 
{
  $labels = array(
    'name' => _x('Portfolio', 'post type general name', 'woothemes'),
    'singular_name' => _x('Portfolio Item', 'post type singular name', 'woothemes'),
    'add_new' => _x('Add New', 'slide', 'woothemes'),
    'add_new_item' => __('Add New Portfolio Item', 'woothemes'),
    'edit_item' => __('Edit Portfolio Item', 'woothemes'),
    'new_item' => __('New Portfolio Item', 'woothemes'),
    'view_item' => __('View Portfolio Item', 'woothemes'),
    'search_items' => __('Search Portfolio Items', 'woothemes'),
    'not_found' =>  __('No Portfolio Items found', 'woothemes'),
    'not_found_in_trash' => __('No Portfolio Items found in Trash', 'woothemes'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => true,
	'_builtin' => false,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_icon' => get_template_directory_uri() .'/includes/images/portfolio.png',
    'menu_position' => null,
    'supports' => array('title','editor','thumbnail'/*'author','excerpt','comments'*/),
	'taxonomies' => array('post_tag') // add tags so portfolio can be filtered
  ); 
  register_post_type('portfolio',$args);

}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Feedback */
/*-----------------------------------------------------------------------------------*/

if ( !is_testimonials_activated() )
    add_action('init', 'woo_add_feedback');

function woo_add_feedback() 
{
  $labels = array(
    'name' => _x('Feedback', 'post type general name', 'woothemes'),
    'singular_name' => _x('Feedback Item', 'post type singular name', 'woothemes'),
    'add_new' => _x('Add New', 'slide', 'woothemes'),
    'add_new_item' => __('Add New Feedback Item', 'woothemes'),
    'edit_item' => __('Edit Feedback Item', 'woothemes'),
    'new_item' => __('New Feedback Item', 'woothemes'),
    'view_item' => __('View Feedback Item', 'woothemes'),
    'search_items' => __('Search Feedback Items', 'woothemes'),
    'not_found' =>  __('No Feedback Items found', 'woothemes'),
    'not_found_in_trash' => __('No Feedback Items found in Trash', 'woothemes'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => true,
	'_builtin' => false,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_icon' => get_template_directory_uri() .'/includes/images/feedback.png',
    'menu_position' => null,
    'supports' => array('title','editor',/*'author','thumbnail','excerpt','comments'*/),
  ); 
  register_post_type('feedback',$args);

}

/*-----------------------------------------------------------------------------------*/
/* Get Post image attachments */
/*-----------------------------------------------------------------------------------*/
/* 

Description:

This function will get all the attached post images that have been uploaded via the 
WP post image upload and return them in an array. 

*/
function woo_get_post_images($offset = 1) {
	
	// Arguments
	$output = '';
	$exclude = '';
	$repeat = 100; 				// Number of maximum attachments to get 
	$photo_size = 'large';		// The WP "size" to use for the large image

	global $post;

	$id = get_the_id();
	$attachments = get_children( array(
	'post_parent' => $id,
	'numberposts' => $repeat,
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'order' => 'ASC', 
	'orderby' => 'menu_order date')
	);
	if ( !empty($attachments) ) :
		$output = array();
		$count = 0;
		foreach ( $attachments as $att_id => $attachment ) {
			$count++;  
			if ($count <= $offset) continue;
			$url = wp_get_attachment_image_src($att_id, $photo_size, true);	
			if ( $url[0] != $exclude )
				$output[] = array( "url" => $url[0], "caption" => $attachment->post_excerpt );
		}  
	endif; 
	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_subscribe_connect')) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		global $woo_options;

		// Setup title
		if ( $widget != 'true' )
			$title = $woo_options[ 'woo_connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $woo_options[ 'woo_connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $woo_options[ 'woo_connect' ] == "true" OR $widget == 'true' ) : ?>
	<div id="connect">
		<h3><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else _e('Subscribe','woothemes'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ($woo_options[ 'woo_connect_content' ] != '') echo stripslashes($woo_options[ 'woo_connect_content' ]); else _e( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ); ?></p>

			<?php if ( $woo_options[ 'woo_connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'woothemes' ); ?>';}" />
				<input type="hidden" value="<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit button" type="submit" name="submit" value="<?php _e( 'Submit', 'woothemes' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $woo_options['woo_connect_mailchimp_list_url'] != "" AND $form != 'on' AND $woo_options['woo_connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $woo_options[ 'woo_connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $woo_options[ 'woo_connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $woo_options['woo_feed_url'] ) { echo esc_url( $woo_options['woo_feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-rss.png" title="<?php _e('Subscribe to our RSS feed', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_twitter'] ); ?>" class="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-twitter.png" title="<?php _e('Follow us on Twitter', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_facebook'] ); ?>" class="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-facebook.png" title="<?php _e('Connect on Facebook', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_youtube'] ); ?>" class="youtube"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-youtube.png" title="<?php _e('Watch on YouTube', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_flickr'] ); ?>" class="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-flickr.png" title="<?php _e('See photos on Flickr', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_linkedin'] ); ?>" class="linkedin"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-linkedin.png" title="<?php _e('Connect on LinkedIn', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_delicious' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_delicious'] ); ?>" class="delicious"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-delicious.png" title="<?php _e('Discover on Delicious', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_googleplus'] ); ?>" class="googleplus"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-googleplus.png" title="<?php _e('View Google+ profile', 'woothemes'); ?>" alt=""/></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $woo_options[ 'woo_connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Posts:', 'woothemes' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

        <div class="fix"></div>
	</div>
	<?php endif; ?>
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
  
?>