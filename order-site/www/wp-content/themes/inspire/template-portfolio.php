<?php
/*
Template Name: Portfolio
*/

global $woo_options;

$counter = 0;
$caption = '';
$gallery = '';

$feed_url = get_feed_link();
if ( isset( $woo_options['woo_feed_url'] ) && '' != $woo_options['woo_feed_url'] ) { $feed_url = esc_url( $woo_options['woo_feed_url'] ); }

get_header();
?>

	<!-- Breadcrumb & Tags -->
	<?php if ( isset( $woo_options['woo_portfolio_tags'] ) && '' != $woo_options['woo_portfolio_tags'] ) { ?>
		<div id="breadcrumb">
        	<div class="col-full">
                <div class="fl">
                	<?php
					$tags = explode( ',', $woo_options['woo_portfolio_tags'] ); // Tags to be shown
					foreach ( $tags as $tag ) {
						$tag = trim($tag); 
						$displaytag = $tag;
						$tag = str_replace (" ", "-", $tag);	
						$tag = str_replace ("/", "-", $tag);
						$tag = strtolower ( $tag );
						$link_tags[] = '<a href="#' . esc_attr( $tag ) . '" rel="' . esc_attr( $tag ) . '">' . $displaytag . '</a>'; 
					}
					$new_tags = implode( ' ', $link_tags );
					?>
                    <span class="port-cat"><?php _e( 'Select a category:', 'woothemes' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" rel="all"><?php _e( 'All', 'woothemes' ); ?></a>&nbsp;<?php echo $new_tags; ?></span>
                </div>
                <a class="subscribe fr" href="<?php echo esc_url( $feed_url ); ?>">
                    <img src="<?php bloginfo('template_directory'); ?>/images/ico-rss.png" alt="Subscribe" class="rss" />
                </a>        
                <div class="<?php if ( 'true' == $bc ) echo 'fr'; else echo 'fl'; ?>">
					<span class="fr catrss"><a class="subscribe fr" href="<?php echo esc_url( $feed_url ); ?>"></a></span>
                </div>
			</div>
        </div>   
	<?php } else { ?>
		<?php woo_crumbs(); ?>   
	<?php } ?>
	<!-- /Breadcrumb & Tags -->
    
	</div><!-- /#top -->
       
    <div id="content">
    <div class="col-full">
    
		<div id="portfolio">		
	        <?php
	        	$paged = get_query_var( 'paged' );
	        	$args = array( 'post_type' => 'portfolio', 'posts_per_page' => '-1', 'paged' => $paged );
	        	$query = new WP_Query( $args );

	        	if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); $counter++; ?>		        					

				<?php 
					// Portfolio tags class
					$porttag = ''; 
					$posttags = get_the_tags(); 
					if ($posttags) { 
						foreach( $posttags as $tag ) { 
							$tag = $tag->name;
							$tag = str_replace ( ' ', '-', $tag );	
							$tag = str_replace ( '/', '-', $tag );
							$tag = strtolower ( $tag );
							$porttag .= $tag . ' '; 
						} 
					} 
				?>                                                                        
                <!-- Post Starts -->
                <div class="post block fl <?php echo esc_attr( $porttag ); ?>">

					<?php 
						// Check if there is a gallery in post
                    	$gallery = woo_get_post_images(0);
                    	if ( $gallery ) {
                    	
                    		// Get first uploaded image in gallery
                    		$large = $gallery[0]['url'];
                    		$caption = $gallery[0]['caption'];
                    		
	                    } else {
	
							// Grab large portfolio image
						 	$large = get_post_meta($post->ID, 'portfolio-large', $single = true); 
	                    
	                    } // End If Statement
					    
	                    // Set rel on anchor to show lightbox
	                    $rel = '';
	                    if ( isset( $woo_options['woo_portfolio_lightbox'] ) && 'true' == $woo_options['woo_portfolio_lightbox'] ) {
                    		$rel = 'rel="prettyPhoto[' . esc_attr( $post->ID ) . ']"';
                    	} // End If Statement
					 ?>
	                    
                    <a <?php echo $rel; ?> title="<?php echo esc_attr( $caption ); ?>" href="<?php echo esc_url( $large ); ?>" class="thumb">
						<?php 
						if ( isset( $woo_options['woo_portfolio_resize'] ) && 'true' == $woo_options['woo_portfolio_resize'] ) {
							woo_image( 'key=portfolio&width=440&height=210&class=portfolio-img&link=img&noheight=true' ); 
						} else { ?>
                        	<img class="portfolio-img" src="<?php echo esc_url( get_post_meta( $post->ID, 'portfolio', true ) ); ?>" alt="" />
                        <?php } ?>
                    </a>
                    
                    <?php 
                    	// Output image gallery for lightbox
                    	if ( $gallery && is_array( $gallery ) ) {
                    	
	                    	foreach ( $gallery as $img => $attachment ) { 
                				if ( $attachment['url'] != $large ) { 
	      		              		echo '<a ' . $rel . ' title="' . esc_attr( $attachment['caption'] ) . '" href="' . esc_url( $attachment['url'] ) . '" class="gallery-image"></a>';	     
	                    		} // End If Statement               
	                    	} // End For Loop
	                    	unset( $gallery );
	                    }
                    ?>

                    <h2 class="title"><?php the_title(); ?></h2>

                    <div class="entry">
	                    <?php the_content(); ?>                        
	                </div><!-- /.entry -->

                </div><!-- /.post -->
                                                    
			<?php } // End While Loop
			} else { ?>
				<div class="post">
				     <p class="note"><?php _e( 'You need to setup the "Portfolio" options and select a category for your portfolio posts.', 'woothemes' ); ?></p>
                </div><!-- /.post -->
            <?php } // End If Statement ?>  

            <div class="fix"></div>
        
            <?php woo_pagenav(); ?>
                
		</div><!-- /#portfolio -->
        

    </div><!-- /.col-full -->
    </div><!-- /#content -->    
        
<?php get_footer(); ?>