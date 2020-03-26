<?php get_header(); ?>

<div class="container mt-4 mt-sm-4 mt-md-5 mb-5">
	<?php the_archive_description( '<div class="lead mb-4 mb-sm-4 mb-md-5">', '</div>' ); ?>
	<?php while ( have_posts() ) : the_post(); ?>
	<article class="<?php echo $post->post_status; ?> post-list-item pt-2 pb-2">
		<h3 class="mb-2">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<div class="meta mb-2">			
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
					echo "<span class='post-categories'>Categories: ".get_the_category_list(' ')."</span>";
				
				$tag_list = "";
				$tag_list = get_the_tag_list( '', ' ' );
				if ( $tag_list )
					echo "<span class='post-tags'>Tags: $tag_list</span>";
			?>
		</div>
	</article>
	<hr class="col-7">
	<?php endwhile; ?>
	<?php 
		$args = array(
			'mid_size'  => 1,
			'prev_next' => true,
			'prev_text' => 'Previous',
			'next_text' => 'Next',
			'type'      => 'plain',
			'screen_reader_text' => __( 'Posts navigation' ),
		);
		// Generate paginated link markup
		$links = paginate_links( $args );

		if( $links ) echo $links;
	?>
</div>

<?php get_footer(); ?>
