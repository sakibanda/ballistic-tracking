<?php
/*
Template Name: Archives Page
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
			    
				    <h3><?php _e('The Last 30 Posts', 'woothemes') ?></h3>
																	  
				    <ul>											  
				        <?php $query = new WP_Query( 'posts_per_page=30' ); ?>		  
				        <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>  
				            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php echo $post->comment_count; ?> <?php _e( 'comments', 'woothemes' ); ?></li>
				        <?php endwhile; endif; ?>	
				        <?php wp_reset_postdata(); ?>
				    </ul>											  
																	  
				    <h3><?php _e( 'Categories', 'woothemes' ); ?></h3>	  
																	  
				    <ul>											  
				        <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1' ); ?>	
				    </ul>											  
				     												  
				    <h3><?php _e( 'Monthly Archives', 'woothemes' ); ?></h3>
																	  
				    <ul>											  
				        <?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>	
				    </ul>

				</div><!-- /.entry -->
			    			
			</div><!-- /.post -->                 
                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

	</div><!-- /#col-full -->
    </div><!-- /#content -->
		
<?php get_footer(); ?>