<?php
/**
 * Functions and definitions
 *
 * @package WordPress
 */

// ************************
// * Register Footer Menu
// ************************
function register_footer_menu(){
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ));
}
add_action('init', 'register_footer_menu');

/**
 * Display college address information
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
 **/
add_action( 'customize_register', 'cos_child_theme_customize_register');
function cos_child_theme_customize_register( $wp_customize ) {
	$wp_customize->add_section( 
	    'cos_child_theme_footer_options', 
	    array(
	        'title'       => __( 'Footer Options', 'COS-Child-Theme' ), 
	        //'priority'    => 6, 
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

	function cos_child_theme_sanitize_radio( $input, $setting ){
     
        //input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
        $input = sanitize_key($input);

        //get the list of possible radio box options 
        $choices = $setting->manager->get_control( $setting->id )->choices;
                         
        //return input if valid or return default option
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
         
    }


}

// *********************************************
// * Enqueue Parent and Child theme styles
// *********************************************
function my_theme_enqueue_styles() {
    // Parent theme name
    $parent_style = 'Colleges-Theme-master'; 

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

// *********************************************
// * Re-Enqueue Parent Scripts
// *********************************************
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
			endif;							
			++$tab_first;

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
			
			$tab_nav .= '<li class="nav-item"><a class="nav-link flex-sm-fill" href="#news" data-toggle="tab" role="tab" aria-controls="news">News</a></li>';	

			$tab_content .= '<div class="tab-pane fade " role="tabpanel" id="news" aria-expanded="false"><h3>In the News</h3>'.$news_articles.'</div>';
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
	?>
		<ul class="nav nav-tabs flex-column flex-sm-row" role="tablist"><?php echo $tab_nav; ?></ul>
		<div class="tab-content "><?php echo $tab_content; ?></div>
	<?php 
	return ob_get_clean();
}