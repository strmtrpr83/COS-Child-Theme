<?php get_header(); the_post(); 

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

?>

<div class="container mb-5 mt-3 mt-lg-5">
	<article class="<?php echo $post->post_status; ?> post-list-item">
		
		<?php echo cos_show_courses( array('level' => $term->slug) ); ?>
		
	</article>
</div>
<?php get_footer(); ?>
