<?php
/*
Template Name: Image Gallery
*/
?>

<?php get_header(); ?>

	<?php woo_crumbs(); ?>
	</div><!-- /#top -->
       
    <div id="content">
	<div class="col-full">   
		<div id="main" class="col-left">
                                                                            
            <div class="post">

                <h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
                
				<div class="entry">
                <?php
                    $query = new WP_Query( 'showposts=60' );
                ?>
                <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                <?php woo_get_image( 'image', 100, 100, 'thumbnail alignleft' ); ?>
                <?php endwhile; endif; ?>	
                </div>

            </div><!-- /.post -->
            <div class="fix"></div>                
                                                            
		</div><!-- /#main -->
		
        <?php get_sidebar(); ?>

	</div><!-- /#col-full -->
    </div><!-- /#content -->
		
<?php get_footer(); ?>