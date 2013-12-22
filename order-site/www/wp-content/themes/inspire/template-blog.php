<?php
/*
Template Name: Blog
*/

get_header();
global $woo_options;

/* Settings for this template. Setup defaults and make sure the theme options override them, if applicable. */
$settings = array( 'woo_thumb_w' => 100, 'woo_thumb_h' => 100, 'woo_thumb_align' => 'alignleft' );
foreach ( $settings as $k => $v ) {
	if ( isset( $woo_options[$k] ) && ( $woo_options[$k] != '' ) ) {
		$settings[$k] = $woo_options[$k];
	}
}
?>
	<?php woo_crumbs(); ?>
	</div><!-- /#top -->
       

   <div id="content">
	<div class="col-full">  
      
        <!-- #main Starts -->
        <div id="main" class="col-left">      

		<?php
		// WP 3.0 PAGED BUG FIX
		if ( get_query_var('paged') )
			$paged = get_query_var('paged');
		elseif ( get_query_var('page') ) 
			$paged = get_query_var('page');
		else 
			$paged = 1;
		?>
		<?php query_posts("post_type=post&paged=$paged"); ?>
        <?php if (have_posts()) : $count = 0; ?>
        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <!-- Post Starts -->
            <div <?php post_class(); ?>>

                <?php woo_image('width=' . $settings['woo_thumb_w'] . '&height=' . $settings['woo_thumb_h'] . '&class=thumbnail ' . $settings['woo_thumb_align'] ); ?> 
                
                <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                
                <p class="post-meta">
                    <span class="small"><?php _e( 'by', 'woothemes' ); ?></span> <span class="post-author"><?php the_author_posts_link(); ?></span>
                    <span class="small"><?php _e( 'on', 'woothemes' ); ?></span> <span class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
                    <span class="small"><?php _e( 'in', 'woothemes' ); ?></span> <span class="post-category"><?php the_category( ', ' ); ?></span>
                </p>
                
                <div class="entry">
					<?php global $more; $more = 0; ?>	                
					<?php if ( get_option( 'woo_excerpt' ) == 'true' ) { the_excerpt(); } else { the_content(); } ?>                        
                </div>
    
                <span class="comments"><?php comments_popup_link( __( 'Comments ( 0 )', 'woothemes' ), __( 'Comments ( 1 )', 'woothemes' ), __( 'Comments ( % )', 'woothemes' ) ); ?></span>

            </div><!-- /.post -->
                                                
        <?php endwhile; else: ?>
            <div class="post">
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </div><!-- /.post -->
        <?php endif; ?>  
    
            <?php woo_pagenav(); ?>
                
        </div><!-- /#main -->
        <?php woo_main_after(); ?>

        <?php get_sidebar(); ?>

	</div><!-- /#col-full -->
    </div><!-- /#content -->
		
<?php get_footer(); ?>