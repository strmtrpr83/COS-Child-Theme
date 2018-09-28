<?php get_header(); the_post(); ?>
<?php
    
  $crs_semesters    = get_field('crs_semester_offered');
  $crs_preReqs      = get_field('crs_prerequisite');
  $crs_textbooks    = get_field('crs_textbooks');
  $crs_description  = get_field('crs_description');
  
  $crs_notes        = get_field('crs_notes');
  $crs_section      = get_field('crs_current_sections');

?>
<div class="container mb-5 mt-3 mt-lg-5">
	<article class="<?php echo $post->post_status; ?> post-list-item">				
		<?php 
		if(!empty($crs_semesters)): ?>
		<div class="row mb-4">
			<div class="col-md-4">
	    		<h4>Semester(s) Offered: </h4>
			</div>
			<div class="col-lg-7 col-md-8 pl-md-5">
				<?php 
				foreach ($crs_semesters as $semester => $value) {
		      		echo '<a class="btn btn-primary mb-0 pb-3 mr-3 disabled" aria-disabled="true">'.ucfirst($value)."</a>";
		    	}
				?>
			</div>
		</div>
		<?php endif; 

		if(!empty($crs_preReqs)): ?>
		<div class="row mb-4">
			<div class="col-md-4">
	    		<h4>Prerequisite(s): </h4>
			</div>
			<div class="col-lg-7 col-md-8 pl-md-5 lead">
				<?php echo $crs_preReqs; ?>
			</div>
		</div>
		<?php endif;

		if(!empty($crs_textbooks)): ?>
		<div class="row mb-4">
			<div class="col-md-4">
	    		<h4>Textbook(s): </h4>
			</div>
			<div class="col-lg-7 col-md-8 pl-md-5 lead">
				<?php echo $crs_textbooks; ?>
			</div>
		</div>
		<?php endif;		

		if(!empty($crs_description)): ?>
		<div class="row mb-4">
			<div class="col-md-4">
	    		<h4>Description: </h4>
			</div>
			<div class="col-lg-7 col-md-8 pl-md-5 lead">
				<?php echo $crs_description; ?>
			</div>
		</div>
		<?php endif;	

		if(!empty($crs_notes)): ?>
		<div class="row mb-4">
			<div class="col-md-4">
	    		<h4>Notes: </h4>
			</div>
			<div class="col-lg-7 col-md-8 pl-md-5 lead">
				<?php echo $crs_notes; ?>
			</div>
		</div>
		<?php endif;	

		if(!empty($crs_section)): ?>
		<div class="row mb-4">
			<div class="col-md-4">
	    		<h4>Current Section(s): </h4>
			</div>
			<div class="col-lg-7 col-md-8 pl-md-5 lead">
				<?php echo $crs_section; ?>
			</div>
		</div>
		<?php endif; ?>

	</article>
</div>
<?php get_footer(); ?>
