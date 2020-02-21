<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="container mt-3 mt-sm-4 mt-md-5 mb-5">
	<article class="<?php echo $post->post_status; ?> post-list-item">
		<h2 class="mb-3">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<div class="meta mb-2">
			<?php /*<span class="author">By <?php echo get_author_name(); ?></span>
			| */?>
			<span class="date"><?php the_time( 'F j, Y' ); ?></span>
		</div>
		<div class="summary row">
			<?php 
			if( has_post_thumbnail() ){ ?>	
                <div class=" col-12 col-sm-12 col-md-3 col-lg-2"><a href='<?php echo get_the_permalink(); ?>'>
                <?php the_post_thumbnail( 'thumbnail', array('class' => 'img-fluid aligncenter mb-3' )); ?>
                </a></div><div class=" col-12 col-sm-12 col-md-9 col-lg-10"><?php the_excerpt(); ?></div>
            <?php 
            } else {
            	echo '<div class="col-12 mb-3">'.get_the_excerpt().'</div>';
            }
			?>
		</div>
		<div class="categories-tags">
			<?php 
				if ( count( get_the_category() ) )
					echo "Categories: ".get_the_category_list(' ')." ";
				
				$tag_list = "";
				$tag_list = get_the_tag_list( '', ' ' );
				if ( $tag_list )
					echo "Tags: $tag_list";
			?>
		</div>
	</article>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>
