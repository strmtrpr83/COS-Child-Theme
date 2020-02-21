<?php
/**
 * Template Name: Basic
 */
?>
<?php get_header(); the_post(); ?>

<div class="container mb-5 mt-3 mt-lg-5">
	<article class="<?php echo $post->post_status; ?> post-list-item">		
		<?php 
		if ( has_post_thumbnail() )		
			the_post_thumbnail('medium', array( 'class' => 'rounded float-left mr-3' )); 
		?>
		<div class="meta mb-3"><span class="date"><?php the_time( 'F j, Y' ); ?></span></div>
		<?php   the_content();	?>
	</article>
</div>

<?php get_footer(); ?>
