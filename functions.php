<?php
include_once 'includes/child-header-functions.php';

/**
 * Functions and definitions
 *
 * @package WordPress
 */

/**
 * Register Footer Menu
 *
 * @author Jonathan Hendricker
 **/
function register_footer_menu(){
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ));
}
add_action('init', 'register_footer_menu');


/**
 * Update the default thumbnail image to crop from top left.
 *
 * @since 1.0.11
 * @author Jonathan Hendricker
 **/
update_option( 'thumbnail_crop', array('left','top') );


/** 
 * Add custom image size for People CPT for better 
 * efficiency in the event people upload large images 
 *
 * @author Jonathan Hendricker
 **/
add_image_size( 'person-headshot', 300, 350, array( 'left', 'top' ) );


/**
 * Display college address information
 *
 * @author Jonathan Hendricker
 **/
function get_contact_address_markup_child() {
	$address = get_theme_mod( 'organization_address' );
	if ( !empty( $address ) ) {
		ob_start();
	?>
	<p class="ucf-footer-address">
		<?php echo wptexturize( nl2br( $address ) ); ?>		
	</p>
	<?php
		return ob_get_clean();
	}
	return;
}


/**
 * Display the footer information that's either College or Dept
 *
 * @author Jonathan Hendricker
 **/
function get_cos_footer_content() {

	$footer_type = get_theme_mod( 'footer_type_option' );

	if ( $footer_type === 'college' ){
		ob_start();
		?>

			<div class="row">
				<div class="col-lg-6">
					<section class="primary-footer-section-center"><?php dynamic_sidebar( 'footer-col-1' ); ?></section>
				</div>
				<div class="col-lg-6">
					<section class="primary-footer-section-right"><?php dynamic_sidebar( 'footer-col-2' ); ?></section>
				</div>					
			</div>
			<div class="row">
				<div class="col-lg-12">
					<section class="primary-footer-section-bottom <?php if( is_active_sidebar('footer-col-1') || is_active_sidebar('footer-col-2') ){
					echo "footer-cols";
			}?>">
						<h2 class="h5 text-primary mb-2 text-transform-none"><?php echo get_sitename_formatted(); ?>, UCF</h2>							
						<?php
						if ( shortcode_exists( 'ucf-social-icons' ) ) {
							echo do_shortcode( '[ucf-social-icons color="grey"]' );
						}
						?>
						<?php
							wp_nav_menu( array(
								'theme_location'  => 'footer-menu',
								'depth'           =>  1,									
								'menu_id'    	  => 'footer-menu',
								'menu_class'      => 'menu list-unstyled list-inline text-center',
								'fallback_cb'     => 'false'
							) );
						?>
						<?php echo get_contact_address_markup_child(); ?>
						<span class="ucf-footer-address">© <a href="https://www.ucf.edu/">University of Central Florida</a></span>
					</section>
				</div>
			</div>
		<?php 
		return ob_get_clean();

	} elseif ( $footer_type === 'department' ){
		ob_start();
		?>
			<div class="row">
				<div class="col-lg-4">
					<section class="primary-footer-section-left">
						<h2 class="h5 text-primary mb-2 text-transform-none"><?php echo get_sitename_formatted(); ?></h2>
						<?php echo get_contact_address_markup(); ?>
						<?php
						if ( shortcode_exists( 'ucf-social-icons' ) ) {
							echo do_shortcode( '[ucf-social-icons color="grey"]' );
						}
						?>
					</section>
				</div>
				<div class="col-lg-4">
					<section class="primary-footer-section-center"><?php dynamic_sidebar( 'footer-col-1' ); ?></section>
				</div>
				<div class="col-lg-4">
					<section class="primary-footer-section-right"><?php dynamic_sidebar( 'footer-col-2' ); ?></section>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<section class="primary-footer-section-bottom footer-cols">
						<h2 class="h5 text-primary mb-2 text-transform-none"><a href="https://sciences.ucf.edu">COLLEGE OF SCIENCES</a>, UCF</h2>					
						<?php
							wp_nav_menu( array(
								'theme_location'  => 'footer-menu',
								'depth'           =>  1,									
								'menu_id'    	  => 'footer-menu',
								'menu_class'      => 'menu list-unstyled list-inline text-center',
								'fallback_cb'     => 'false'
							) );
						?>
						<span class="ucf-footer-address">© <a href="https://www.ucf.edu/">University of Central Florida</a></span>
					</section>
				</div>
			</div>
		<?php 
		return ob_get_clean();
	} elseif ( $footer_type === 'faculty_lab' ){
		ob_start();
		?>
			<div class="row">
				<div class="col-lg-4">
					<section class="primary-footer-section-left">
						<h2 class="h5 text-primary mb-2 text-transform-none"><?php echo get_sitename_formatted(); ?></h2>
						<?php echo get_contact_address_markup(); ?>
						<?php
						if ( shortcode_exists( 'ucf-social-icons' ) ) {
							echo do_shortcode( '[ucf-social-icons color="grey"]' );
						}
						?>
					</section>
				</div>
				<div class="col-lg-4">
					<section class="primary-footer-section-center"><?php dynamic_sidebar( 'footer-col-1' ); ?></section>
				</div>
				<div class="col-lg-4">
					<section class="primary-footer-section-right"><?php dynamic_sidebar( 'footer-col-2' ); ?></section>
				</div>
			</div>
		<?php 
		return ob_get_clean();
	}
	return;
}

/**
 * Customizer Options
 *
 * @author Jonathan Hendricker
 **/
add_action( 'customize_register', 'cos_child_theme_customize_register');
function cos_child_theme_customize_register( $wp_customize ) {
	$wp_customize->add_section( 
	    'cos_child_theme_footer_options', 
	    array(
	        'title'       => __( 'Footer Options', 'COS-Child-Theme' ), 
	        'description' => __( 'Select the type of footer you would like to use for this website. An example of the College footer can be found on <a href="https://sciences.ucf.edu" target="_blank">sciences.ucf.edu</a>, COS Department footer at <a href="https://sciences.ucf.edu/statistics" target="_blank">sciences.ucf.edu/statistics</a> and the Faculty / Other footer at <a href="https://sciences.ucf.edu/geeo" target="_blank">sciences.ucf.edu/geeo</a>' ),
	        'capability'  => 'edit_theme_options',
	    ) 
	);
	$wp_customize->add_setting( 'footer_type_option', 
		array(
	  		'default'	=> 'college',
	  		'transport' => 'refresh', 
	  		'sanitize_callback' => 'cos_child_theme_sanitize_radio' 		
		) 
	);

	$wp_customize->add_control( 'footer_type_option', 
		array(
			'label' => __( 'Footer Type' ),			
			'section' => 'cos_child_theme_footer_options', 
			'type' => 'radio',
			'capability' => 'edit_theme_options',
			'choices'	=> array(
				'college'		=> __( 'College' ),
				'department'	=> __( 'COS Department' ),
				'faculty_lab'	=> __( 'Faculty / Lab' )
			)		
		)
	);

	// Creates a Gravity Forms section that has a checkbox to allow Gravity Form entries to be saved in the DB
	$wp_customize->add_section( 
	    'cos_child_theme_gravity_form_dont_save_entries', 
	    array(
	        'title'       => __( 'Gravity Forms', 'COS-Child-Theme' ), 
	        'description' => __( 'The College of Sciences&#39; hosting agreement prevents the storing of sensitive information on the SMCA servers. <strong>By default, all Gravity Forms entries will be deleted after processing</strong>.<br/><br/>To override this setting, please check the box below. Only check this box if you will NOT be storing sensitive information on the server, including but not limited to student email addresses.<br/><br/><strong>Any public facing form with an email field could potentially breach this agreement</strong>.' ),
	        'capability'  => 'edit_theme_options',
	    ) 
	);
	$wp_customize->add_setting(	'cos_child_gravity_save_entries',
		array(
			'default' => 0,
			'transport'	=> 'refresh',
			'sanitize_callback' => 'cos_child_theme_sanitize_checkbox'
		)
	);
	$wp_customize->add_control( 'cos_child_gravity_save_entries', 
		array(
			'label' 			=> __( 'Save Entries Upon Submission' ),			
			'section' 			=> 'cos_child_theme_gravity_form_dont_save_entries', 
			'type' 				=> 'checkbox',
			'capability' 		=> 'edit_theme_options',			
		)
	);

	// Creates a Posts section that has a checkbox to allow individual post pages to display the featured image in the page in addition to the_content()
	$wp_customize->add_section( 
	    'cos_child_theme_display_posts_options', 
	    array(
	        'title'       => __( 'Post Options', 'COS-Child-Theme' ), 	        
	        'capability'  => 'edit_theme_options',
	    ) 
	);
	$wp_customize->add_setting(	'cos_child_show_post_featured_image',
		array(
			'default' => 0,
			'transport'	=> 'refresh',
			'sanitize_callback' => 'cos_child_theme_sanitize_checkbox'
		)
	);
	$wp_customize->add_control( 'cos_child_show_post_featured_image', 
		array(
			'type' 				=> 'checkbox',
			'label' 			=> __( 'Display a Post&#39;s Featured Image' ),
			'description' => __( 'Option to display a post&#39;s featured image at the beginning of the post before the post content.' ),			
			'section' 			=> 'cos_child_theme_display_posts_options', 			
			'capability' 		=> 'edit_theme_options',			
		)
	);


	function cos_child_theme_sanitize_radio( $input, $setting ){
     
        //input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
        $input = sanitize_key($input);

        //get the list of possible radio box options 
        $choices = $setting->manager->get_control( $setting->id )->choices;
                         
        //return input if valid or return default option
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
    }

    function cos_child_theme_sanitize_checkbox( $input ) {
	   
    	return ( true === $input ? 1 : 0 );

	}
}


/**
 * Disable the storing of Gravity Form entries in the database
 *
 *@author Jonathan Hendricker
 *
 **/
add_action( 'gform_after_submission', 'cos_child_remove_gf_form_entry', 100, 1 );
function cos_child_remove_gf_form_entry( $entry ) {
	// Get value from the theme customizer
	$save_gf_entries = get_theme_mod( 'cos_child_gravity_save_entries' );

	// If not checked, delete the entry
	if ( $save_gf_entries !== 1 ){
		GFAPI::delete_entry( $entry['id'] );
	}
}

/**
 * Enqueue Parent and Child theme styles
 *
 * @author Jonathan Hendricker
 **/
function my_theme_enqueue_styles() {
    // Parent theme name
    $parent_style = 'Colleges-Theme'; 

    // Enqueue Parent Theme Style
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/static/css/style.min.css' );
    // Enqueue Child Theme Style
    wp_enqueue_style( 'style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/**
 * Re-Enqueue Parent Scripts
 * 
 * @author Jonathan Hendricker
 **/
function themeslug_enqueue_script() { 
	// Re-enqueue the tether script because it's a dependency in the parent theme script below
	wp_enqueue_script( 'tether', 'https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js', null, null, true );
	// Re-enqueue the parent theme script so it looks in the right directory
	wp_enqueue_script( 'script', get_template_directory_uri() . '/static/js/script.min.js', array( 'jquery', 'tether' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_script' );


/**
 * Display's a person's website in a condensed table-like format.
 * For use on single-person.php
 *
 * @author Jonathan Hendricker
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function get_person_website_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
	$website_title = get_field( 'peson_website_title', $post->ID );
	$website_url = get_field( 'person_website_url', $post->ID );

	if ( !empty($website_title) && !empty($website_url)  ):
?>
	<div class="row">
		<div class="col-xl-4 col-md-12 col-sm-4 person-label">
			Website
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 person-attr">
			<a href="<?php echo $website_url; ?>" class="person-email">
				<?php echo $website_title; ?>
			</a>
		</div>
	</div>
	<hr class="my-2">
<?php
	endif;
	return ob_get_clean();
}

/**
 * Display's a person's tabbed content using the tabs in the Athena framework .
 * For use on single-person.php
 *
 * @author Jonathan Hendricker
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function get_person_tabs_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();

	$tab_nav 	 = "";
	$tab_content = "";
	$tab_first	 = '0';

	if ( have_rows( 'person_tabbed_content', $post->ID ) ):
		// Loop through user generated tab content
		while ( have_rows( 'person_tabbed_content', $post->ID ) ): the_row();

			$tab_title  = get_sub_field('person_tab_title');
			$tab_anchor = sanitize_title_with_dashes( $tab_title );
			
			if ( $tab_title ):
				$tab_nav .= '<li class="nav-item"><a class="nav-link flex-sm-fill ';
				$tab_content .= '<div class="tab-pane fade ';
				
				if ( $tab_first === '0' ){
					$tab_nav .= 'active';
					$tab_content .= 'active show" aria-expanded="true"';
				} elseif ( $tab_first !== '0' ){
					$tab_content .= '" aria-expanded="false"';
				}
				$tab_nav .= '" href="#'.$tab_anchor.'" data-toggle="tab" role="tab" aria-controls="'.$tab_anchor.'">'.$tab_title.'</a></li>';
				$tab_content .= ' role="tabpanel" id="'.$tab_anchor.'" ><h3>'.$tab_title.'</h3>'.get_sub_field('person_tab_content').'</div>';
				++$tab_first;
			endif;							

		endwhile;

	endif;

	// Add tab for news articles from the COS News blog
	if ( get_field('person_show_cos_news_feed') === 'yes'  ){

		$custom_news_slug = get_field('person_custom_news_tag');
		// Check for custom news tag and sanitize it
		if ( !empty( $custom_news_slug ) )
			$news_slug = sanitize_title_with_dashes( trim( $custom_news_slug ) );	
		else
			$news_slug = $post->post_name;

		// Get news stories using the UCF News Modded plugin
		$news_articles = do_shortcode( '[ucf-news-feed limit="3" layout="modern" title="" topics="'.$news_slug.'"]') ; 

		// If articles are returned
		if ( !empty($news_articles) && $news_articles !== 'There are no news stories at this time.' ){
			// Check to see if News is the first tab
			if ( $tab_first === '0' ){
				$tab_nav .= '<li class="nav-item"><a class="nav-link flex-sm-fill active" href="#news" data-toggle="tab" role="tab" aria-controls="news">News</a></li>';	
				$tab_content .= '<div class="tab-pane fade active show" role="tabpanel" id="news" aria-expanded="true"><h3>In the News</h3>'.$news_articles.'</div>';
				++$tab_first;
			} elseif ( $tab_first !== '0' ){
				$tab_nav .= '<li class="nav-item"><a class="nav-link flex-sm-fill" href="#news" data-toggle="tab" role="tab" aria-controls="news">News</a></li>';
				$tab_content .= '<div class="tab-pane fade " role="tabpanel" id="news" aria-expanded="false"><h3>In the News</h3>'.$news_articles.'</div>';
			}
		}
	}

	// Display Public Office Hours
	if ( get_field('person_office_hours') === 'public' ){

		$mon_hours   = get_field('person_office_hours_mon');
		$tues_hours  = get_field('person_office_hours_tues');
		$wed_hours   = get_field('person_office_hours_wed');
		$thurs_hours = get_field('person_office_hours_thurs');
		$fri_hours   = get_field('person_office_hours_fri');

		$has_hours = '0';

		if ( !empty($mon_hours) || !empty($tues_hours) || !empty($wed_hours) || !empty($thurs_hours) || !empty($fri_hours) )
			$has_hours = '1';

		if ( $has_hours === '1' ){

			$tab_nav .= '<li class="nav-item"><a class="nav-link flex-sm-fill ';
			$tab_content .= '<div class="tab-pane fade ';
			
			if ( $tab_first === '0' ){
				$tab_nav .= 'active';
				$tab_content .= 'active show" aria-expanded="true"';
				++$tab_first;
			} elseif ( $tab_first !== '0' ){
				$tab_content .= '" aria-expanded="false"';
			}
			$tab_nav .= '" href="#office_hours" data-toggle="tab" role="tab" aria-controls="office_hours">Office Hours</a></li>';
			$tab_content .= ' role="tabpanel" id="office_hours" ><h3>Office Hours</h3>';

			if ( !empty($mon_hours) ){
				$tab_content .= '<div class="row"><div class="col-xl-4 col-md-12 col-sm-4 person-label">Monday</div>';
				$tab_content .= '<div class="col-xl-8 col-md-12 col-sm-8 person-attr" style="text-align:left;">'.$mon_hours.'</div></div><hr class="my-2">';
			}
			if ( !empty($tues_hours) ){
				$tab_content .= '<div class="row"><div class="col-xl-4 col-md-12 col-sm-4 person-label">Tuesday</div>';
				$tab_content .= '<div class="col-xl-8 col-md-12 col-sm-8 person-attr" style="text-align:left;">'.$tues_hours.'</div></div><hr class="my-2">';
			}
			if ( !empty($wed_hours) ){
				$tab_content .= '<div class="row"><div class="col-xl-4 col-md-12 col-sm-4 person-label">Wednesday</div>';
				$tab_content .= '<div class="col-xl-8 col-md-12 col-sm-8 person-attr" style="text-align:left;">'.$wed_hours.'</div></div><hr class="my-2">';
			}
			if ( !empty($thurs_hours) ){
				$tab_content .= '<div class="row"><div class="col-xl-4 col-md-12 col-sm-4 person-label">Thursday</div>';
				$tab_content .= '<div class="col-xl-8 col-md-12 col-sm-8 person-attr" style="text-align:left;">'.$thurs_hours.'</div></div><hr class="my-2">';
			}
			if ( !empty($fri_hours) ){
				$tab_content .= '<div class="row"><div class="col-xl-4 col-md-12 col-sm-4 person-label">Friday</div>';
				$tab_content .= '<div class="col-xl-8 col-md-12 col-sm-8 person-attr" style="text-align:left;">'.$fri_hours.'</div></div><hr class="my-2">';
			}
			
			$tab_content .= '</div>';			
		}			
		
	}
	// Display Private Office Hours 
	elseif ( get_field('person_office_hours') === 'private' ){

			$tab_nav .= '<li class="nav-item"><a class="nav-link flex-sm-fill ';
			$tab_content .= '<div class="tab-pane fade ';

			if ( $tab_first === '0' ){
				$tab_nav .= 'active';
				$tab_content .= 'active show" aria-expanded="true"';
				++$tab_first;
			} elseif ( $tab_first !== '0' ){
				$tab_content .= '" aria-expanded="false"';
			}
			$tab_nav .= '" href="#office_hours" data-toggle="tab" role="tab" aria-controls="office_hours">Office Hours</a></li>';
			$tab_content .= ' role="tabpanel" id="office_hours" ><h3>Office Hours</h3><div class="col-sm-12 person-label">Office hours are by appointment only</div></div>';	
	}
	if (!empty($tab_nav) && !empty($tab_content) ){
		?>
		<ul class="nav nav-tabs flex-column flex-sm-row" role="tablist"><?php echo $tab_nav; ?></ul>
		<div class="tab-content "><?php echo $tab_content; ?></div>
		<?php 
	}
	return ob_get_clean();
}

/**
 * Display individual cos_courses CPT entries for a specific taxonomy .
 * For use on single-person.php
 *
 * @author Jonathan Hendricker
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function cos_show_courses( $atts ){

	$courseCat = shortcode_atts( array(
	  'level' => 'all'
	), $atts );

	$taxonomy = $courseCat['level']; 	

	if( $taxonomy != 'all' ){  

		$taxonomyArgs = array( 
		  'posts_per_page' 	=> -1,
		  'post_type' 		=> 'cos_course',
		  'tax_query' 		=> array(
		    array(
		      'taxonomy' 	=> 'cos_course_tax',
		      'field' 		=> 'slug',
		      'terms' 		=> $taxonomy,          
		      'include_children' => FALSE, 
		    )
		  ),
		  'orderby' => 'title',
		  'order'   => 'ASC',
		);		
	} else {		
		$taxonomyArgs = array( 
		  'post_type'       => 'cos_course',
		  'posts_per_page'  => -1,
		  'orderby'         => 'title',
		  'order'           => 'ASC',
		);
	}

	$stuff_to_return = "";

	$my_query = new WP_Query($taxonomyArgs);

	if($my_query->have_posts()) : 

		$multiple_prefix = "";
		$previous_prefix = "";  

		$stuff_to_return .= "<div class='row'>";
		$num_posts = $my_query->found_posts;

		$stuff_to_return .= "<div class='col-12 col-sm-6'>";
		$cur_post = 0; 

		while ($my_query->have_posts()) : $my_query->the_post();      
		  
			$thisID = get_the_ID();

			$course_prefix 	= get_field("crs_prefix");
			$course_number 	= get_field("crs_number");
			$course_title 	= get_field("crs_title");
			$course_link 	= get_permalink();

			if( $cur_post == (int)($num_posts/2) && $num_posts > 10 ) {
				$stuff_to_return .= "</div><div class='col-12 col-sm-6'>";

				if ( $course_prefix === $previous_prefix && $multiple_prefix === '1')
					$stuff_to_return .= "<h3>$course_prefix (continued)</h3>";
				elseif ($multiple_prefix === '1')
					$stuff_to_return .= "<h3>$course_prefix</h3>";
				else
					$stuff_to_return .= "<h3>&nbsp;</h3>";
			}

			if($course_prefix !== $previous_prefix && $previous_prefix !== ""){
				$multiple_prefix = "1";
				$stuff_to_return .= "<br/><h3>$course_prefix</h3>";
			}
			elseif($previous_prefix === "")
			$stuff_to_return .= "<h3>$course_prefix</h3>";


			$stuff_to_return .=  "<p class='lead'><a href='$course_link'>$course_prefix $course_number: $course_title</a></p>";

			$previous_prefix = $course_prefix; 
			$cur_post++; 
		endwhile; 

		if($previous_prefix !== "") $stuff_to_return .= "";

		$stuff_to_return .= "</div>"; 

	else:
		$stuff_to_return .= "<h4>There are no courses active for this prefix. Please refer to the UCF Course Catalog for more information</h4>";
	endif; wp_reset_query();

	return $stuff_to_return;

}
add_shortcode( 'cos_show_courses', 'cos_show_courses' );


/**
 * Overriding the default get_person_thumbnail function that displays a person's thumbnail image.
 *
 * @author Jo Dickson | Mod: Jonathan Hendricker
 * @since 1.0.0
 * @param $post object | Person post object
 * @param $css_classes str | Additional classes to add to the thumbnail wrapper
 * @return Mixed | thumbnail HTML or void
 **/
function get_person_thumbnail_medium( $post, $css_classes='' ) {

	if ( !$post->post_type == 'person' ) { return; }

	$thumbnail = get_the_post_thumbnail_url( $post, 'person-headshot' ) ?: get_theme_mod_or_default( 'person_thumbnail' );
	// Account for attachment ID being returned by get_theme_mod_or_default():
	if ( is_numeric( $thumbnail ) ) {		
		$thumbnail = wp_get_attachment_url( $thumbnail );
	}

	ob_start();
	if ( $thumbnail ):
?>
	<div class="media-background-container person-photo mx-auto <?php echo $css_classes; ?>">
		<img src="<?php echo $thumbnail; ?>" alt="<?php $post->post_title; ?>" title="<?php $post->post_title; ?>" class="media-background object-fit-cover">
	</div>
<?php
	endif;
	return ob_get_clean();
}

/**
 * Removing the default 'ucf_post_list_display_people' filter
 * and replacing it with a new one that uses a new custom 
 * image size for more efficiency and faster loading
 *
 * @author Jonathan Hendricker
 * @since 1.0.9
 **/
// Remove the old filter first
function remove_parent_filters(){ 
	remove_filter( 'ucf_post_list_display_people', 'colleges_post_list_display_people');
}
add_action( 'after_setup_theme', 'remove_parent_filters' );

// Redefine the function with the new image size
function colleges_post_list_display_people_modded( $content, $items, $atts ) {
	if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }
	ob_start();
?>
	<?php if ( $items ): ?>
	<ul class="list-unstyled row ucf-post-list-items">
		<?php foreach ( $items as $item ): ?>
		<li class="col-6 col-sm-4 col-md-3 col-xl-2 mt-3 mb-2 ucf-post-list-item">
			<a class="person-link" href="<?php echo get_permalink( $item->ID ); ?>">
				<?php echo get_person_thumbnail_medium( $item ); ?>
				<h3 class="mt-2 mb-1 person-name"><?php echo get_person_name( $item ); ?></h3>
				<?php if ( $job_title = get_field( 'person_jobtitle', $item->ID ) ): ?>
				<div class="font-italic person-job-title">
					<?php echo $job_title; ?>
				</div>
				<?php endif; ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<div class="ucf-post-list-error mb-4">No results found.</div>
	<?php endif; ?>
<?php
	return ob_get_clean();
}
add_filter( 'ucf_post_list_display_people', 'colleges_post_list_display_people_modded', 10, 3 );


/**
 * Shortcode for displaying a search field to search for Posts from multiple Categories and Tags
 * For use on single-person.php
 *
 * @author Jonathan Hendricker
 * @since 1.0.0
 * @param $atts 
 * @return Mixed | Dropdown list of current categories or tags or void
 **/
function cos_display_category_tag_content($atts){

  $baseURL = get_site_url();

  ob_start(); 

  ?>
  <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/static/css/sumoselect.min.css" media="all">
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/static/js/jquery.sumoselect.min.js"></script>
  <script type="text/javascript">
      jQuery(document).ready(function ($) {
          window.asd = $('.SlectBox').SumoSelect({ csvDispCount: 3 });
      
          $("#form_submit").click(function(){
            var baseURL = "<?php echo $baseURL; ?>/";
            var iccaeCats = $("#iccae-categories").val();
            var iccaeTags = $("#iccae-tags").val();

            if((iccaeCats != null) && (iccaeCats.length)){
              iccaeCats = iccaeCats.toString().replace(",", "+");
              baseURL = baseURL + "category/" + iccaeCats + "/";
            }
            if((iccaeTags != null) && (iccaeTags.length)){
              iccaeTags = iccaeTags.toString().replace(",", ",");
              baseURL = baseURL + "?tag=" + iccaeTags + "/";
            }

            window.location.href = baseURL;
                        
          });

      });
  </script>
  
	<div class="row mb-4">
	<?php 			
		$categories = get_categories();

		if(!empty($categories)):
	?>
		<div class=" col-12 col-sm-6">
			<label class="col-form-label d-block">Select Your Categories:</label>
			<select name="iccae-categories[]" id="iccae-categories" class="SlectBox" multiple="multiple"> 
			<?php 
				 
				foreach ($categories as $category) {
				  $option = '<option value="'.$category->slug.'">';
				  $option .= $category->cat_name;
				  $option .= ' ('.$category->category_count.')';
				  $option .= '</option>';
				echo $option;
			}
			?>
			</select>
    	</div>
  	<?php  
  		endif;

  		$tags = get_tags();   

    	if( !empty($tags) ):
    ?>
    	<div class=" col-12 col-sm-6">
    		<label class="col-form-label d-block">Select Your Tags (Optional):</label> 
    		<select name='iccae-tags' id="iccae-tags" class="SlectBox" multiple="multiple" >
			<?php 
			foreach( $tags as $tag) {
			  $slug = $tag->slug;
			  echo "<option  value='".$tag->slug."'>".$tag->name."</option>";
			}
			?></select>
		</div>
		<?php endif; ?>
	</div>

  	<input type="submit" value="Submit" id="form_submit" class="btn btn-primary" />

	<?php

	return ob_get_clean();
  
}
add_shortcode('cos-category-and-tags', 'cos_display_category_tag_content');


/**
 * Dashboard Cusomtizations 
 **/
// Change Log-In Screen Logo
function my_custom_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a { 
        	background-image: url('<?php echo get_stylesheet_directory(); ?>/static/img/logo2018.png');
        	width: 283px; 
        	height: 77px; 
        	background-size: cover; }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_custom_login_logo');

// Change Log-In Screen Logo URL
function put_my_url(){
	// putting my URL in place of the WordPress one
	return ('https://sciences.ucf.edu'); 
}
add_filter('login_headerurl', 'put_my_url');

// Change Log-In Screen Logo Hover State
function put_my_title(){
    // Change the title from "Powered by WordPress"
    return ('College of Sciences');     
}
add_filter('login_headertitle', 'put_my_title');


/**
 * Change 'Username or Email' login text to 'UCF NID'
 **/
add_filter( 'gettext', 'register_text'  );
add_filter( 'gettext', 'remove_lostpassword_text' );

function register_text( $translated ) {
     $translated = str_ireplace(  'Username or Email Address',  'UCF NID Credentials (No @ucf.edu)',  $translated );
     return $translated;
}
/**
 * Remove the 'Lost your password?' option
 **/
function remove_lostpassword_text ( $text ) {
     if ($text === 'Lost your password?'){$text = '';}
            return $text;
 }

 /**
  * Allow Feedzy Pro plugin to link imported  
  * articles to their original sources
  *
  * @author Jonathan Hendricker (Original Author Feedzy Pro)
  * @since 1.0.12
  **/
 add_filter('post_link', function( $url, $id ){

    $feed_url = get_post_meta( $id->ID, 'feedzy_item_url', true );

    if ( !empty( $feed_url) )
        $url = $feed_url;

    return $url;

}, 99, 2);

