<?php
/**
 * Header Related Functions
 **/


/**
 * Returns title text for use in the page header.
 **/
function child_get_header_title( $obj ) {
	$title = '';

	if ( is_home() || is_front_page() ) {
		$title = get_field( 'homepage_header_title', $obj->ID );

		if ( ! $title ) {
			$title = get_bloginfo( 'name' );
		}
	}
	elseif ( is_search() ) {
		$title = __( 'Search Results for:' );
		$title .= ' ' . esc_html( stripslashes( get_search_query() ) );
	}
	elseif ( is_404() ) {
		$title = __( '404 Not Found' );
	}
	elseif ( is_single() || is_page() ) {
		if ( $obj->post_type === 'person' ) {
			$title = get_theme_mod_or_default( 'person_header_title' ) ?: get_field( 'page_header_title', $obj->ID );
		}
		else {
			$title = get_field( 'page_header_title', $obj->ID );
		}

		if ( ! $title ) {
			$title = single_post_title( '', false );
		}
	}
	elseif ( is_category() ) {
		// $title = __( 'Category Archives:' );
		$title = single_term_title( '', false );
	}
	elseif ( is_tag() ) {
		$title = __( 'Tag:' );
		$title .= ' ' . single_term_title( '', false );
	}
	elseif ( is_tax() ) {
		$tax_name = '';
		$tax = get_taxonomy( $obj->taxonomy );
		if ( $tax ) {
			$tax_name = $tax->labels->singular_name . ' ';
		}
		$title = __( $tax_name . 'Archives:' );
		$title .= ' ' . single_term_title( '', false );
	}

	return $title;
}

/**
 * Returns markup for inner header contents for pages using the 'inline-block'
 * or 'block' content display type.
 **/
function child_get_header_media_content_markup( $post, $header_height, $header_content_display ) {
	$title            = child_get_header_title( $post );
	$subtitle         = get_header_subtitle( $post );
	$extra_content    = get_field( 'page_header_extra_content', $post->ID );
	$content_position = get_field( 'page_header_content_position', $post->ID );

	$content_cols = '';
	if ( $header_content_display === 'block' ) {
		switch ( $content_position ) {
			case 'center':
				$content_cols = 'col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-10 offset-md-1 header-title-align-center';
				break;
			case 'right':
				$content_cols = 'col-xl-6 offset-xl-6 col-lg-8 offset-lg-4 col-md-10 offset-md-2 header-title-align-right';
				break;
			case 'left':
			default:
				$content_cols = 'col-xl-6 col-lg-8 col-md-10';
				break;
		}
	}
	else {
		switch ( $content_position ) {
			case 'center':
				$content_cols = 'col-xl-10 offset-xl-1 header-title-align-center';
				break;
			case 'right':
				$content_cols = 'col-xl-10 offset-xl-2 header-title-align-right';
				break;
			case 'left':
			default:
				$content_cols = 'col-xl-10';
				break;
		}
	}

	$align_classes = 'align-items-center';
	if ( $header_height !== 'header-media-fullscreen' ) {
		$align_classes .= ' align-items-sm-end';
	}

	ob_start();
?>
	<div class="container d-flex <?php echo $align_classes; ?>">
		<div class="row no-gutters w-100">
			<div class="<?php echo $content_cols; ?>">
				<div class="header-title-wrapper">
					<?php
					// Don't print multiple h1's on the page for person templates
					if ( $post->post_type === 'person' ):
					?>
					<strong class="h1 d-block header-title"><?php echo $title; ?></strong>
					<?php else: ?>
					<h1 class="header-title"><?php echo $title; ?></h1>
					<?php endif; ?>

					<?php if ( $subtitle ): ?>
					<p class="header-subtitle"><?php echo $subtitle; ?></p>
					<?php endif; ?>

					<?php if ( $extra_content ): ?>
					<div class="header-extra mt-3 mb-4 mb-sm-0"><?php echo $extra_content; ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}


/**
 * Returns the markup for page headers with an image or video background.
 **/
function child_get_header_media_markup( $post, $videos=null, $images=null ) {
	$header_height          = child_get_header_height( $post );
	$header_content_display = get_field( 'page_header_content_display', $post->ID );

	$videos     = $videos ?: get_header_videos( $post );
	$images     = $images ?: child_get_header_images( $post );
	$video_loop = get_field( 'page_header_video_loop', $post->ID );

	ob_start();

	if ( $images || $videos ) :
?>
		<div class="header-media <?php echo ( is_front_page() || $header_content_display === 'block' ) ? 'header-media-content-block' : ''; ?> <?php echo $header_height ?: ''; ?> media-background-container mb-0 d-flex flex-column">
			<?php
			if ( $videos ) {
				echo get_media_background_video( $videos, $video_loop );
			}
			if ( $images ) {
				$bg_image_srcs = array();
				switch ( $header_height ) {
					case 'header-media-fullscreen':
						$bg_image_srcs = child_get_media_background_picture_srcs( null, $images['header_image'], 'bg-img' );
						$bg_image_src_xs = child_get_media_background_picture_srcs( $images['header_image_xs'], null, 'header-img' );

						if ( isset( $bg_image_src_xs['xs'] ) ) {
							$bg_image_srcs['xs'] = $bg_image_src_xs['xs'];
						}
						break;
					case 'header-media-short':
						$bg_image_srcs = child_get_media_background_picture_srcs( null, $images['header_image'], 'header-img-short' );
						$bg_image_src_xs = child_get_media_background_picture_srcs( $images['header_image_xs'], null, 'header-img' );

						if ( isset( $bg_image_src_xs['xs'] ) ) {
							$bg_image_srcs['xs'] = $bg_image_src_xs['xs'];
						}
						break;
					case 'header-media-default':
					default:
						$bg_image_srcs = child_get_media_background_picture_srcs( $images['header_image_xs'], $images['header_image'], 'header-img' );
						break;
				}
				echo child_get_media_background_picture( $bg_image_srcs );
			}
			?>
			<div class="header-content">

			<?php
			if ( is_front_page() ) {
				echo get_homepage_header_markup( $post );
			}
			else {
				echo child_get_header_media_content_markup( $post, $header_height, $header_content_display );
			}
			?>

			</div>
		</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns the default markup for page headers.
 **/
function child_get_header_default_markup( $obj ) {
	$title         = child_get_header_title( $obj );
	$subtitle      = get_header_subtitle( $obj );
	$extra_content = '';

	if ( is_single() || is_page() ) {
		$extra_content = get_field( 'page_header_extra_content', $obj->ID );
	}

	ob_start();
?>
<div class="container">
	<?php
	// Don't print multiple h1's on the page for person templates
	if ( is_single() && $obj->post_type === 'person' ):
	?>
	<strong class="h1 d-block mt-3 mt-sm-4 mt-md-5 mb-3"><?php echo $title; ?></strong>
	<?php else: ?>
	<h1 class="mt-3 mt-sm-4 mt-md-5 mb-3"><?php echo $title; ?></h1>
	<?php endif; ?>

	<?php if ( $subtitle ): ?>
	<p class="lead mb-4 mb-md-5"><?php echo $subtitle; ?></p>
	<?php endif; ?>

	<?php if ( $extra_content ): ?>
	<div class="mb-4 mb-md-5"><?php echo $extra_content; ?></div>
	<?php endif; ?>
</div>
<?php
	return ob_get_clean();
}


function child_get_header_markup() {
	$videos = $images = null;
	$obj = get_queried_object();

	if ( is_single() || is_page() || is_category() ) {
		$videos = get_header_videos( $obj );
		$images = child_get_header_images( $obj );
	}

	echo get_nav_markup();

	if ( $videos || $images ) {
		echo child_get_header_media_markup( $obj, $videos, $images );
	}
	else {
		echo child_get_header_default_markup( $obj );
	}
}

/**
 * Gets the header image for pages.
 **/
function child_get_header_images( $post ) {
	$retval = array(
		'header_image' => '',
		'header_image_xs' => ''
	);

	if ( $post->post_type === 'person' || $post->post_type === 'degree' ) {
		$retval['header_image']    = get_theme_mod( $post->post_type . '_header_image' );
		$retval['header_image_xs'] = get_theme_mod( $post->post_type . '_header_image_xs' );
	}

	if ( $post_header_image = get_field( 'page_header_image', $post->ID ) ) {
		$retval['header_image'] = $post_header_image;
	}
	if ( $post_header_image_xs = get_field( 'page_header_image_xs', $post->ID ) ) {
		$retval['header_image_xs'] = $post_header_image_xs;
	}

	if ( is_category() ){		
		if ( $post_header_image = get_field( 'page_header_image', $post ) ){		
			$retval['header_image'] = $post_header_image;
		}
		if ( $post_header_image_xs = get_field( 'page_header_image_xs', $post ) ) {
			$retval['header_image_xs'] = $post_header_image_xs;
		}
	}

	if ( $retval['header_image'] ) {
		return $retval;
	}
	return false;
}

/**
 * Returns the header height meta value for the page header.
 **/
function child_get_header_height( $post ) {
	$retval = 'header-media-default';

	if ( $post->post_type === 'person' || $post->post_type === 'degree' ) {
		$retval = 'header-media-short';
	}

	if ( $post_header_height = get_field( 'page_header_height', $post->ID ) ) {
		$retval = $post_header_height;
	}

	if( is_category() ){
		if ( $post_header_height = get_field( 'page_header_height', $post ) ) {
			$retval = $post_header_height;
		}
	}

	return $retval;
}

/**
 * NOTE: This is a duplication and modification of the original get_media_background_picture() 
 * function from the Colleges Theme
 * ----------------------------------
 * Returns an array of src's for a media background <picture>'s <source>s by
 * breakpoint.
 * 
 * Including a new image size to accomodate 1920 pixel wide images
 *
 * $img_size_prefix is expected to be a prefix for a set of registered image
 * sizes, which has dimensions defined for each of Athena's responsive
 * breakpoints.  For example, if given a prefix 'bg-img', it is expected that
 * bg-img, bg-img-sm, bg-img-md, bg-img-lg, and bg-img-xl are valid registered
 * image sizes.
 *
 * @param int $attachment_xs_id Attachment ID for the image to be used at the -xs breakpoint
 * @param int $attachment_sm_id Attachment ID for the image to be used at the -sm breakpoint and up
 * @param string $img_size_prefix Prefix for a set of image sizes
 * @return array
 **/
function child_get_media_background_picture_srcs( $attachment_xs_id, $attachment_sm_id, $img_size_prefix ) {
	$bg_images = array();

	if ( $attachment_sm_id ) {
		$bg_images = array_merge(
			$bg_images,
			array(
				//Inclusion of new higher resolution size
				'xxl' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-xxl' ),
				'xl' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-xl' ),
				'lg' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-lg' ),
				'md' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-md' ),
				'sm' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-sm' )
			)
		);

		// Try to get a fallback -xs image if needed
		if ( !$attachment_xs_id ) {
			$bg_images = array_merge(
				$bg_images,
				array( 'xs' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix ) )
			);
		}

		// Remove duplicate image sizes, in case an old image isn't pre-cropped
		$bg_images = array_unique( $bg_images );

		// Use the largest-available image as the fallback <img>
		$bg_images['fallback'] = reset( $bg_images );
	}
	if ( $attachment_xs_id ) {
		$bg_images = array_merge(
			$bg_images,
			array( 'xs' => get_attachment_src_by_size( $attachment_xs_id, $img_size_prefix ) )
		);
	}

	// Strip out false-y values (in case an attachment failed to return somewhere)
	$bg_images = array_filter( $bg_images );

	return $bg_images;
}

/**
 * NOTE: This is a duplication and modification of the original get_media_background_picture() 
 * function from the Colleges Theme
 * ----------------------------------
 * Returns markup for a media background <picture>, given an array of media
 * background picture <source> src's from get_media_background_picture_srcs().
 *
 * @param array $srcs Array of image urls that correspond to <source> src vals
 * @return string
 **/
function child_get_media_background_picture( $srcs ) {
	ob_start();

	if ( isset( $srcs['fallback'] ) ) :
?>
	<?php
	// Define classes for the <picture> element
	$picture_classes = 'media-background-picture ';
	if ( !isset( $srcs['xs'] ) ) {
		// Hide the <picture> element at -xs breakpoint when no mobile image
		// is available
		$picture_classes .= 'hidden-xs-down';
	}
	?>
	<picture class="<?php echo $picture_classes; ?>">
		<?php 
		// Checks for new higher definition 1920 pixels wide 'xxl' size
			  if ( isset( $srcs['xxl'] ) ) : ?>
		<source srcset="<?php echo $srcs['xxl']; ?>" media="(min-width: 1600px)">
		<?php endif; ?>	

		<?php if ( isset( $srcs['xl'] ) ) : ?>
		<source srcset="<?php echo $srcs['xl']; ?>" media="(min-width: 1200px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['lg'] ) ) : ?>
		<source srcset="<?php echo $srcs['lg']; ?>" media="(min-width: 992px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['md'] ) ) : ?>
		<source srcset="<?php echo $srcs['md']; ?>" media="(min-width: 768px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['sm'] ) ) : ?>
		<source srcset="<?php echo $srcs['sm']; ?>" media="(min-width: 576px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['xs'] ) ) : ?>
		<source srcset="<?php echo $srcs['xs']; ?>" media="(max-width: 575px)">
		<?php endif; ?>

		<img class="media-background object-fit-cover" src="<?php echo $srcs['fallback']; ?>" alt="">
	</picture>
<?php
	endif;

	return ob_get_clean();
}