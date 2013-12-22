<?php
	global $woo_options;
	
	// Set some variables, depending on theme options settings.
	$css_class = 'fixed-height';
	$fixed_height = '';
	
	if ( isset( $woo_options['woo_featured_height'] ) && ( $woo_options['woo_featured_height'] != '' ) ) {
		$fixed_height = ' style="min-height: ' . $woo_options['woo_featured_height'] . 'px;"';
	}
	
	if ( isset( $woo_options['woo_featured_resize'] ) && ( $woo_options['woo_featured_resize'] == 'true' ) ) {
		$css_class = 'dynamic-height';
	}
	
	query_posts( 'suppress_filters=0&post_type=slide&order=ASC&orderby=date&posts_per_page=20' );
	
	if ( have_posts() ) { $count = 0; 
?>

<div id="woofader">
    <div id="featured" class="col-full featured <?php echo $css_class; ?>"<?php echo $fixed_height; ?>>
    
        <?php if ( have_posts() ) { while ( have_posts() ) { the_post(); $count++; ?>		        					
        <div class="slide <?php echo $css_class; ?>"<?php echo $fixed_height; ?>>
            <?php if ( get_post_meta($post->ID, 'slide_image', true) ) { ?>
            <?php
            	// Get image dimensions.
            	$image_url = get_post_meta( $post->ID, 'slide_image', true );
            	$image_url = esc_url( $image_url );
            ?>
            <div class="featured-image">
                <a href="<?php echo get_post_meta($post->ID, 'slide_url', $single = true); ?>"><img src="<?php echo get_post_meta($post->ID, 'slide_image', true ); ?>" alt="" /></a>
            </div>
            <?php } elseif ( get_post_meta( $post->ID, 'slide_embed', true ) ) { ?>  
            	<?php echo woo_embed( 'key=slide_embed&width=460&height=320&class=video' ); ?>
            <?php } ?>
            
            <div class="wrap">
                <?php the_content(); ?>
            </div>
            <div class="fix"></div><!--/.fix-->
        </div><!-- /.slide -->
        <?php } } ?>
        
    </div><!-- /#featured -->
    <div class="fix"></div>
    
	<?php if ( $count > 1 ) { ?>
    <div id="breadcrumb">
        
        <div class="col-full">
            <div class="col">
                <div class="fl">
                    <a href="#" class="left"></a>
                    <a href="#" class="right"></a>
                </div>
                <div class="fr">
                    <ul class="pagination">
                        <?php $count_nav = 1; while ( $count_nav <= $count ) { ?>
                        <li <?php if ( $count_nav == 1 ) echo 'class="active"'; ?>><a href="#"></a></li>
						<?php $count_nav++; } ?>
                    </ul>
                </div>
                <div class="fix"></div>
            </div><!-- /.col -->
        </div><!-- /.col-full -->
        
    </div><!-- /#breadcrumb -->
	<?php } ?>       
    
</div><!-- /#woofader -->

<?php } ?>
<?php 
if ( is_home() && ( isset( $woo_options['woo_featured_disable'] ) && $woo_options['woo_featured_disable'] != 'true' ) ) { 

if( $count > 0 ) {

	$speed = $woo_options['woo_featured_speed'] * 1000; if ( ! $speed ) $speed = 500;
	$timeout = $woo_options['woo_featured_timeout'] * 1000; if ( ! $timeout ) $timeout = 0;
	$resize = $woo_options['woo_featured_resize']; if ( ! $resize ) $resize = 'true';
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	if ( jQuery( '#woofader .slide' ).length > 1 ) {
		jQuery('#woofader').slides({
			container: 'featured', 
			slideSpeed: <?php echo $speed; ?>, 
			play: <?php echo $timeout; ?>,
			autoHeight: <?php echo $resize; ?>,  
			effect: 'fade', 
			generatePagination: false, 
			next: 'right', 
			prev: 'left', 
			paginationClass: 'pagination', 
			currentClass: 'active', 
			hoverPause: true, 
			pause: 2500,
			animationComplete: function () { jQuery( this ).stop(); }, 
			slidesLoaded: function () {
							if ( jQuery( '#woofader .dynamic-height' ).length ) {
								jQuery( '#woofader .slides_control' ).css( 'height', jQuery( '#woofader .slide:first' ).height() );
							}
						}
		});
	} else {
	
		jQuery( '#woofader #featured' ).fadeIn();
	}
});
</script>
<?php } 
} ?>