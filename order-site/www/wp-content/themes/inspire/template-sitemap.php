<?php
/*
Template Name: Sitemap
*/

get_header();
global $post;
?>

	<?php woo_crumbs(); ?>
	</div><!-- /#top -->
       
    <div id="content">
	<div class="col-full">   
		<div id="main" class="col-left">
					
	        <div class="post">
	        
	        	<h2 class="title"><?php the_title(); ?></h2>
	        	
	        	<div class="entry">
	            
	            	<h3><?php _e( 'Pages', 'woothemes' ); ?></h3>
	
	            	<ul>
	           	    	<?php wp_list_pages( 'depth=0&sort_column=menu_order&title_li=' ); ?>		
	            	</ul>				
	    
		            <h3><?php _e( 'Categories', 'woothemes' ); ?></h3>
	
		            <ul>
	    	            <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1' ); ?>	
	        	    </ul>
			        
			        <h3><?php _e( 'Posts per category', 'woothemes' ); ?></h3>
			        
			        <?php
			            $cats = get_categories();
			            foreach ( $cats as $c ) {
			            $query = new WP_Query( 'cat=' . intval( $c->cat_ID ) );
			        ?>
	        			<h4><?php echo $c->cat_name; ?></h4>
			        	<ul>	
	    	        	    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
	        	    	    <li style="font-weight:normal !important;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php _e( 'Comments', 'woothemes' ); ?> (<?php echo $post->comment_count; ?>)</li>
	            		    <?php endwhile;  ?>
			        	</ul>
	    
	    		    <?php } ?>
	    		
	    			<div class="fix"></div>
	    			
	    			<?php if ( is_woocommerce_activated() ): ?>
	    		    <div class="fl" style="width:50%;">												  	    
		            
		        	    <h3><?php _e( 'Product Categories', 'woothemes' ); ?></h3>
		        	    <ul>
		        	    	<?php wp_list_categories( 'taxonomy=product_cat&pad_counts=1&title_li=' ); ?>
					    </ul>
		    		</div>				
	    
					<div class="fl" style="width:50%;">	    
		    		    <h3><?php _e( 'Products', 'woothemes' ); ?></h3>
		    		    <ul>
			    		    <?php
			    		    	$args = array( 'post_type' => 'product', 'posts_per_page' => 100 );
								$loop = new WP_Query( $args );
								while ( $loop->have_posts() ) : $loop->the_post();
							?>
							<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
							<?php endwhile; ?>
						</ul>
	        	    </div>
	        		<?php endif; ?>
	    			
	    			<div class="fix"></div>
	        	    
	    		</div><!-- /.entry -->
	    						
	        </div><!-- /.post -->                    
	                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

	</div><!-- /#col-full -->
    </div><!-- /#content -->
		
<?php get_footer(); ?>