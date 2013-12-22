<?php
get_header();
global $woo_options;

/* Settings for this template. Setup defaults and make sure the theme options override them, if applicable. */
$settings = array( 'woo_thumb_single' => 'false', 'woo_single_w' => 100, 'woo_single_h' => 100 );
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
 
		<div id="main" class="col-left">
		           
            <?php if ( have_posts() ) { $count = 0; ?>
            <?php while ( have_posts() ) { the_post(); $count++; ?>
            
				<div <?php post_class(); ?>>

                    <?php if ( $settings['woo_thumb_single'] == 'true' ) { woo_image('width=' . $settings['woo_single_w'] . '&height=' . $settings['woo_single_h'] . '&class=thumbnail alignright' ); } ?>

                    <h1 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                    
	        		<?php if( get_post_type() == 'post' ){ ?>
                    <p class="post-meta">
                    	<span class="small"><?php _e( 'by', 'woothemes' ); ?></span> <span class="post-author"><?php the_author_posts_link(); ?></span>
                    	<span class="small"><?php _e( 'on', 'woothemes' ); ?></span> <span class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
                    	<span class="small"><?php _e( 'in', 'woothemes' ); ?></span> <span class="post-category"><?php the_category( ', ' ); ?></span>
   	                    <?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>
                    </p>
                    <?php } ?>
                    
                    <div class="entry">
                    	<?php the_content(); ?>
					</div>
										
					<?php the_tags('<p class="tags">Tags: ', ', ', '</p>'); ?>

                    <?php // woo_postnav(); ?>
                    
                </div><!-- /.post -->
                
                <?php woo_subscribe_connect(); ?>
                
                <?php
                	$comm = get_option( 'woo_comments' );
                	if ( 'open' == $post->comment_status && ( $comm == 'post' || $comm == 'both' ) ) {
                		comments_template('', true);
                	}		
                ?>
                                                    
			<?php
					}
				} else {
			?>
				<div class="post">
                	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
  				</div><!-- /.post -->             
           	<?php } ?>  
        
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

	</div><!-- /#col-full -->
    </div><!-- /#content -->
		
<?php get_footer(); ?>