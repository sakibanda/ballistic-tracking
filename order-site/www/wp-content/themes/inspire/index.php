<?php
	get_header();
	global $woo_options;
	$counter = 0;
?>
<?php if ( isset( $woo_options['woo_featured_disable'] ) && ( $woo_options['woo_featured_disable'] != 'true' ) ) { get_template_part( 'includes/featured' ); } ?>

	</div><!-- /#top -->


    <div id="content">
	<div class="col-full">

		<!-- Featured Products -->
        <?php
	        if ( is_woocommerce_activated() && ( 'true' == $woo_options['woo_homepage_featured_products'] ) ) {
		        echo '<h1>' . __( 'Featured Products', 'woothemes' ) . '</h1>';
				$featuredproductsperpage = $woo_options['woo_homepage_featured_products_perpage'];
				echo do_shortcode('[featured_products per_page="' . $featuredproductsperpage . '" columns="4"]');
		    }
        ?>

		<div id="main" class="col-left">

	        <?php if ( isset( $woo_options['woo_main_page1'] ) && $woo_options['woo_main_page1'] && $woo_options['woo_main_page1'] != 'Select a page:' ) { ?>
	        <div id="main-page1">

	            	<?php
	            	$main_page_1 = get_page_id($woo_options['woo_main_page1']);
					$args = array( 'page_id' => $main_page_1 );
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); ?>

							<div class="entry"><?php the_content(); ?></div>
							<div class="fix"></div>

					<?php endwhile; ?>

	        </div><!-- /#main-page1 -->

	        <?php } ?>

	        <?php if ( isset( $woo_options['woo_main_pages'] ) && $woo_options['woo_main_pages'] == 'true' ) { ?>
            <div id="mini-features">
		        <?php

			     	$minifeaturesperpage = $woo_options['woo_homepage_mini_features_perpage'];

		        	if ( is_features_activated() ) {
		        		do_action( 'woothemes_features', array( 'title' => '', 'limit' => $minifeaturesperpage, 'before' => '', 'after' => '', 'size' => 50, 'per_row' => 2 ) );
		        	} else {

			        	$args = array( 'post_type' => 'infobox', 'order' => 'ASC', 'posts_per_page' => $minifeaturesperpage );
			        	$query = new WP_Query( $args );
			        
			        	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); $counter++;

				        	$feature_image = get_post_meta($post->ID, 'mini', true );
				        	$feature_readmore = get_post_meta( $post->ID, 'mini_readmore', true );

		        ?>
			                <div class="block<?php if ( $counter == 2 ) echo ' last'; ?>">
			                    <?php if ( '' != $feature_image ) { ?>
			                    <img src="<?php echo esc_url( $feature_image ); ?>" alt="" class="home-icon" />
			                    <?php } ?>

			                    <div class="<?php if ( '' != $feature_image ) echo 'feature'; ?>">
			                       <h3><?php echo get_the_title(); ?></h3>
			                       <p><?php echo get_post_meta( $post->ID, 'mini_excerpt', true ); ?></p>
			                       <?php if ( '' != $feature_readmore ) { ?><a href="<?php echo esc_url( $feature_readmore ); ?>" class="btn"><span><?php _e( 'Read More', 'woothemes' ); ?></span></a><?php } ?>
			                    </div>
			                </div>
			                <?php if ( $counter == 2 ) { $counter = 0; echo '<div class="fix"></div>'; } ?>

		            	<?php endwhile; endif; ?>

		        <?php } //WOO_HAS_FEATURES ?> 

                <div class="fix"></div>

                <div class="more-features">

                	<?php $post_archive_link = is_features_activated() ? get_post_type_archive_link( 'feature' ) : get_post_type_archive_link( 'infobox' ); ?>

                	<a href="<?php echo esc_url( $post_archive_link ); ?>"><span><?php echo $woo_options['woo_main_pages_link_text']; ?></span><img src="<?php echo esc_url( get_template_directory_uri() . '/images/btn-more.png' ); ?>" alt="" /></a>
                
                </div>

            </div><!-- /#mini-features -->
            <?php } ?>

            <div class="fix"></div>

	        <?php if ( isset( $woo_options['woo_main_page2'] ) && $woo_options['woo_main_page2'] && $woo_options['woo_main_page2'] != 'Select a page:' ) { ?>
	        <div id="main-page2">

				<?php
	            	$main_page_2 = get_page_id($woo_options['woo_main_page2']);
					$args = array( 'page_id' => $main_page_2 );
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); ?>

							<div class="entry"><?php the_content(); ?></div>
							<div class="fix"></div>

					<?php endwhile; ?>

	        </div><!-- /#main-page2 -->
	        <?php } ?>
		</div><!-- /#main -->
		<?php wp_reset_postdata(); ?>
		<?php get_sidebar(); ?>

	</div><!-- /#col-full -->
    </div><!-- /#content -->

<?php get_footer(); ?>