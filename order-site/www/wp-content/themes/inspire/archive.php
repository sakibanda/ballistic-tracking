<?php
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
 
		<div id="main" class="col-left">
            
		<?php if (have_posts()) : $count = 0; ?>
        
            <?php if (is_category()) { ?>
            <span class="archive_header"><span class="fl cat"><?php _e( 'Archive', 'woothemes' ); ?> &rsaquo; <?php echo single_cat_title(); ?></span></span>        
        
            <?php } elseif (is_day()) { ?>
            <span class="archive_header"><?php _e( 'Archive', 'woothemes' ); ?> &rsaquo; <?php the_time( get_option( 'date_format' ) ); ?></span>

            <?php } elseif (is_month()) { ?>
            <span class="archive_header"><?php _e( 'Archive', 'woothemes' ); ?> &rsaquo; <?php the_time( 'F, Y' ); ?></span>

            <?php } elseif (is_year()) { ?>
            <span class="archive_header"><?php _e( 'Archive', 'woothemes' ); ?> &rsaquo; <?php the_time( 'Y' ); ?></span>

            <?php } elseif (is_author()) { ?>
            <span class="archive_header"><?php _e( 'Archive by Author', 'woothemes' ); ?></span>

            <?php } elseif (is_tag()) { ?>
            <span class="archive_header"><?php _e( 'Tag Archives:', 'woothemes' ); ?> <?php echo single_tag_title( '', true ); ?></span>
            
            <?php } ?>
            
            <div class="fix"></div>
        
        <?php while (have_posts()) : the_post(); $count++; ?>
                                                                    
            <!-- Post Starts -->
            <div class="post">

                <?php woo_image('width=' . intval( $settings['woo_thumb_w'] ) . '&height=' . intval( $settings['woo_thumb_h'] ) . '&class=thumbnail ' . esc_attr( $settings['woo_thumb_align'] ) ); ?> 

                <?php
                    $post_link = get_permalink();
                    $feature_readmore = get_post_meta( $post->ID, 'mini_readmore', true );
                    if ( 'infobox' == get_post_type() && '' != $feature_readmore ) {
                        $post_link = get_post_meta( $post->ID, 'mini_readmore', true );
                    }
                ?>

                <h2 class="title"><a href="<?php echo esc_url( $post_link ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                
                <p class="post-meta">
                    <span class="small"><?php _e( 'by', 'woothemes' ); ?></span> <span class="post-author"><?php the_author_posts_link(); ?></span>
                    <span class="small"><?php _e( 'on', 'woothemes' ); ?></span> <span class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
                    <span class="small"><?php _e( 'in', 'woothemes' ); ?></span> <span class="post-category"><?php the_category( ', ' ); ?></span>
                </p>
                
                <div class="entry">
					<?php if ( 'true' == get_option( 'woo_excerpt' ) ) the_excerpt(); else the_content(); ?>                        
                </div><!-- /.entry -->

                <span class="comments"><?php comments_popup_link( __( 'Comments ( 0 )', 'woothemes' ), __( 'Comments ( 1 )', 'woothemes' ), __( 'Comments ( % )', 'woothemes' ) ); ?></span>

            </div><!-- /.post -->
            
            
        <?php endwhile; else: ?>
            <div class="post">
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </div><!-- /.post -->
        <?php endif; ?>  
    
			<?php woo_pagenav(); ?>
                
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

	</div><!-- /#col-full -->
    </div><!-- /#content -->

<?php get_footer(); ?>