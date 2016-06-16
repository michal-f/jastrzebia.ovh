<?php
/**
 * SpecialRecentPostsPro
 *
 * This is the main plugin class which handles the core of the Special Recent Post plugin.
 *
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 * @access public
 */
class SpecialRecentPostsPro {

	/**
     * The default plugin presets
     * @var $plugin_args
     */
	private $plugin_args;

	/**
     * The widget instance values.
     * @var $widget_args
     */
	private $widget_args;

	/**
     * The current post ID when in single post mode.
     * @var $singleID
     */
	private $singleID;

	/**
     * The post author ID (when in single post/page mode).
     * @var $authorID
     */
	private $authorID;

	/**
     * The Cache folder basepath.
     * @var $cache_basepath
     */
	private $cache_basepath;

	/**
     * The upload dir for WP multi-site compatibility.
     * @var $uploads_dir
     */
	private $uploads_dir;

	/**
     * The current sidget instance ID.
     * @var $widget_id
     */
	private $widget_id;

	/**
     * The global paging WP variable which handles the current viewed page index.
     * @var $paged
     */
	private $paged;

	/**
	 * __construct()
	 *
	 * The main SRP Class constructor
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 * @global $srp_default_widget_values The global default widget presets.
	 * @global $post The global $post WP object.
	 * @param array $args The widget instance configuration values.
	 * @param string $widget_id The current Widget ID.
	 * @return boolean true
	 */
	public function __construct( $args = array(), $widget_id = NULL ) {

		// Including global default widget values.
		global $srp_default_widget_values;

		// Setting up upload dir for multi-site hack.
		$this->uploads_dir = wp_upload_dir();
		
		// Setting up plugin options to be available throughout the plugin.
		$this->plugin_args = get_option( 'srp_plugin_options' );
		
		// Checking that provided configuration values is an array.
		$args = ( !is_array($args) ) ? array() : SpecialRecentPostsPro::srp_version_map_check( $args );
		
		// Setting up widget options to be available throughout the plugin.
		$this->widget_args = array_merge( $srp_default_widget_values, $args );

		// Setting up pagination counter.
		$this->paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

		// Setting up post/page ID when on a single post/page.
		if ( is_single() || is_page() ) {
		
			// Including global $post object.
			global $post;
			
			// Assigning post ID.
			$this->singleID = $post->ID;

			// Assigning post author id.
			$this->authorID = $post->post_author;

		}

		// Checking if we're on a static home page.
		if ( is_front_page() && !is_home() ) {
			
			// Setting up pagination counter for static home page.
			$this->paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
		}
		
		// Setting up cache folder basepath..
		$this->cache_basepath = SRP_CACHE_DIR;
		
		// Setting up current widget instance id.
		$this->widget_id = ( $widget_id ) ? $widget_id : false;

		// Returning true.
		return true;
	}
	
	/**
	 * __deconstruct()
	 *
	 * The main SRP Class deconstructor
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 */
	public function __deconstruct() {}

	/**
	 * install_plugin()
	 *
	 * This method handles all the actions for the plugin initialization.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 */
	static function install_plugin() {
		
		// Performing a global database options check.
		SpecialRecentPostsPro::srp_dboptions_check();
	}

	/**
	 * uninstall_plugin()
	 *
	 * This method handles all the actions for the plugin uninstall process.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 */
	static function uninstall_plugin() {
		
		// Deleting SRP saved option values.
		delete_option( 'srp_plugin_options' );
	}

	/**
	 * visualization_check()
	 *
	 * This method handles the visualization filter.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @global $srp_default_widget_values the global default plugin presets.
	 * @access public
	 * @return boolean It returns true if the widget is allowed to be displayed on the current page/post. Otherwise false.
	 */
	static function visualization_check( $instance, $call ) {
		
		// Declaring global plugin values.
		global $srp_default_widget_values;
		
		// Checking source call.
		switch ( $call ) {
			
			case 'phpcall':
			case 'shortcode':

				// Merging current widget user values with the default presets.
				$new_instance = array_merge( $srp_default_widget_values, $instance );

			break;
			
			case 'widget':

				// Just coping the current widget options.
				$new_instance = $instance;

			break;
		}

		// Checking if the Visualization Filter is disabled.
		if ( ( isset( $new_instance['vf_disable'] ) ) && ( 'yes' == $new_instance['vf_disable'] ) ) return true;
		
		// Checking if the widget should appear on all the site through.
		if ( ( isset( $new_instance['vf_everything'] ) ) && ( 'yes' == $new_instance['vf_everything'] ) ) {
			return true;
		
		// Checking if the widget should appear on home page.
		} else if ( ( isset( $new_instance['vf_home'] ) ) && ( 'yes' == $new_instance['vf_home'] ) && ( is_home() || is_front_page() ) ) {
			return true;
			
		// Checking if the widget should appear on all posts
		} else if ( ( isset( $new_instance['vf_allposts'] ) ) && ( 'yes' == $new_instance['vf_allposts'] ) && ( is_single() ) ) {
			return true;
			
		// Checking if the widget should appear on all pages
		} else if ( ( isset( $new_instance['vf_allpages'] ) ) && ( 'yes' == $new_instance['vf_allpages'] ) && ( is_page() ) ) {
			return true;
			
		// Checking if the widget should appear on all category pages
		} else if ( ( isset( $new_instance['vf_allcategories'] ) ) && ( 'yes' == $new_instance['vf_allcategories'] ) && ( is_category() ) ) {
			return true;
			
		// Checking if the widget should appear on all archive pages
		} else if ( ( isset( $new_instance['vf_allarchives'] ) ) && ( 'yes' == $new_instance['vf_allarchives'] ) && ( is_archive() ) ) {
			return true;
			
		// Widget is not allowed to be displayed here. Return false.
		} else {
			return false;
		}
	}

	/**
	 * srp_dboptions_check()
	 *
	 * This method does a version check of old database options, updating and passign existing values to new ones.
	 * This function is needed for compatibility with previous versions, without overwriting the old user values.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @global $srp_default_widget_values the global default plugin presets.
	 * @access public
	 * @return boolean true.
	 */
	static function srp_dboptions_check() {
		
		// Importing global default options array.
		global $srp_default_plugin_values;
		
		// Retrieving current db options.
		$srp_old_plugin_options = get_option( 'srp_plugin_options' );
		
		// Checking if plugin db options exist.
		if ( isset( $srp_old_plugin_options ) ) {

			// Performing version comparison.
			if ( version_compare( $srp_old_plugin_options['srp_version'], SRP_PLUGIN_VERSION, '<' ) ) {
			
				// Looping through each available plugin value.
				foreach( $srp_default_plugin_values as $k => $v ) {
				
					// Checking for plugin options that haven't changed name since last version.
					if ( ( isset($srp_old_plugin_options[ $k ] ) ) && ( 'srp_version' != $k ) ) {

						// In this case, assign the old value to the current new key.
						$srp_default_plugin_values[ $k ] = $srp_old_plugin_options[ $k ];
					}
				}
				
				// Deleting the old entry in the DB.
				delete_option( 'srp_plugin_options' );
				
				// Re-creating a new entry in the database with the new values.
				add_option( 'srp_plugin_options', $srp_default_plugin_values );
			}
			
		} else {
		
			// First install. Creating WP Option with default values.
			add_option( 'srp_plugin_options', $srp_default_plugin_values );
		}

		// Returning true.
		return true;
	}

	/**
	 * srp_version_map_check()
	 *
	 * This method does a version map check for old option arrays, assigning old values to new ones.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @global $srp_version_map The global version map super array.
	 * @access public
	 * @return array $oldargs The updated plugin values.
	 */
	static function srp_version_map_check( $oldargs ) {
		
		// Including global version map super array.
		global $srp_version_map;
		
		// Checking that old plugin values array exists and is not empty.
		if ( ( is_array( $oldargs ) ) && ( !empty( $oldargs ) ) ) {
		
			// Mapping possible old parameters versions.
			foreach( $oldargs as $oldargs_key => $oldargs_value ) {
				
				// Checking if old parameter exists in the version map array, and if its name is different than the relative new one.
				if ( ( array_key_exists( $oldargs_key, $srp_version_map ) ) && ( $oldargs_key != $srp_version_map[ $oldargs_key ] ) ) {
					
					// Creating a new parameter key with the old parameter value, to mantain options names.
					$oldargs[ $srp_version_map[ $oldargs_key ] ] = $oldargs_value;
					
					// Deleting old parameter key.
					unset( $oldargs[ $oldargs_key ] );
				}
			}
			
		} else {
			
			// If $oldargs is not an array or it's empty, redefine it as a new empty array.
			$oldargs = array();
		}
		
		// Returning updated $args.
		return $oldargs;
	}

	/**
	 * generate_gd_image()
	 *
	 * This is the main method for the image manipulation.
	 * Every fetched image is stored in the cache folder then displayed on screen.
	 * Here lies the core of PHP Thumbnailer Class which takes care of all image resizements and manipulations.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $post The global WP post object
	 * @param $image_origin The original image source.
	 * @param $image_to_render The final image to be rendered and saved.
	 * @param $cached_image The cached image name.
	 * @param $image_width The thumbnail image width.
	 * @param $image_height The thumbnail image height.
	 * @param $image_rotation The image rotation mode.
	 * @access private
	 * @return mixed It could return a file handler to the saved thumbnail, or false in case some error has shown up, or an exception.
	 */
	private function generate_gd_image( $post, $image_origin, $image_to_render, $cached_image, $image_width, $image_height, $image_rotation ) {

		// Adjust image path by clipping eventual (back)slashes.
		//if (($image_path[0] == "/") || ($image_path[0] == "\\")) $image_path = substr($image_path, 1);

		// Removing querystring from image to save. This fixed the Jetpack Photon Issue.
		$cached_image = preg_replace( '/\?.*/', '', $cached_image );

		// Sometimes empty values can be posted to this funcion due to bad database arrays. In any case, exit returning false.
		if ( !$image_to_render ) return false;
		
		// Checking if we're processing a featured image or a first-post image.
		if ( 'firstimage' == $image_origin ) {

			// Building image path.
			$image_path = ( is_multisite() && isset( $blog_id ) && $blog_id > 0 ) ? getcwd() . $image_to_render : $_SERVER["DOCUMENT_ROOT"] . $image_to_render;
			
		} else {
		
			// Featured image path doesn't need to be processed because it's already a physical path.
			$image_path = $image_to_render;
		}
		
		// Checking if original image exists and can be properly read. If it's not, throw an error.
		if ( ( !is_file( $image_path ) ) || ( !file_exists( $image_path ) ) ) {
		
			// Checking if "Log Errors on Screen" option is on.
			if ( 'yes' == $this->plugin_args['srp_log_errors_screen'] ) {
			
				// Displaying informations about the original file where the error has been found.
				printf( __( 'Problem detected on post ID: %d on file %s', SRP_TRANSLATION_ID ) , $post->ID, $image_path );
				echo '<br />';
			}
			
			// Return false.
			return false;
		}

		// Putting the whole image process in a Try & Catch block.
		try {

			// Setting up Thumbnail Image Quality Ratio.
			$phpThumbArgs = array( 'jpegQuality' => $this->plugin_args['srp_thumbnail_jpeg_quality'] );

			// Initializing PHP Thumb Class.
			$thumb = PhpThumbFactory::create( $image_path, $phpThumbArgs );
		
			// Resizing thumbnail with adaptive mode.
			$thumb->adaptiveResize( $image_width, $image_height );

			// Checking for rotation value.
			if ( isset( $image_rotation ) ) {

				// Checking for display mode.
				switch( $image_rotation ) {
					
					// No rotation. Do nothing.
					case 'no':
					break;
					
					// Rotating CW.
					case 'rotate-cw':
						
						// Rotating image CW.
						$thumb->rotateImage( 'CW' );

					break;
					
					// Rotating CCW.
					case 'rotate-ccw':
					
						// rotating image CCW.
						$thumb->rotateImage( 'CCW' );

					break;
				}
			}

			// Saving generated image in the cache folder.
			$thumb->save( $cached_image );
			
			// Checking if thumbnail has been properly saved.
			return ( file_exists( $cached_image ) ) ? true : false;
			
		} catch ( Exception $e ) {

			// Handling catched errors.
			echo $e->getMessage() . '<br />' . __( 'Problem detected on file:', SRP_TRANSLATION_ID ) . $image_path . '<br />';
			
			// Returning false.
			return false;
		}
	}

	/**
	 * display_default_thumb()
	 *
	 * This is the main method to display the default "no image" placeholder.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $thumb_width The thumbnail width.
	 * @param $thumb_height The thumbnail height.
	 * @access private
	 * @return string the 'no image' HTML tag.
	 */
	private function display_default_thumb( $post, $thumb_width, $thumb_height ) {
		
		// Checking if a custom thumbnail url has been provided.
		$noimage_url = ( !empty( $this->plugin_args['srp_noimage_url'] ) ) ? $this->plugin_args['srp_noimage_url'] : SRP_DEFAULT_THUMB;

		// Checking if thumbnail should be linked to post.
		if ( 'yes' == $this->widget_args['thumbnail_link'] ) {
			
			// Building HTML link atts.
			$linkatts = array(
				'class' => 'srp-post-thumbnail-link',
				'href'  => get_permalink( $post->ID ),
				'title' => esc_attr( $post->post_title )
			);

			// Building the no image placeholder HTML.
			$noimageHtmlContent = '<img src="' . $noimage_url . '" class="srp-post-thumbnail" width="' . $thumb_width . '" height="' . $thumb_height . '" alt="' . esc_attr( __( 'No thumbnail available', SRP_TRANSLATION_ID ) ) . '" />';

			// Returning no image linked placeholder.
			return $this->srp_create_tag( 'a', $noimageHtmlContent, $linkatts );
			
		} else {
		
			// Returning default thumbnail image.
			return '<img src="' . $noimage_url . '" class="srp-post-thumbnail" width="' . $thumb_width . '" height="' . $thumb_height . '" alt="' .  esc_attr( __( 'No thumbnail available', SRP_TRANSLATION_ID ) ) . '" />';
		}

	}

	/**
	 * get_first_image_url()
	 *
	 * This method retrieves the first image url in the post content.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $post The global WP post object.
	 * @param $thumb_width The thumbnail width.
	 * @param $thumb_height The thumbnail height.
	 * @param $post_title The current post title.
	 * @access private
	 * @return mixed It could return the HTML code for the first image found, generate a new thumbnail and returning it, generate the 'no image' placeholder or returning false.
	 */
	private function get_first_image_url( $post, $thumb_width, $thumb_height, $post_title ) {

		// Using REGEX to find the first occurrence of an image tag in the post content.
		$output = preg_match_all( '/<img [^>]*src=["|\']([^"|\']+)/i', $post->post_content, $matches );

		// Checking if REGEX has found something.
		if ( !empty( $output ) ) {

			// An image has been found. Determine which one should be extracted based upon the post thumbnail index option.
			if ( !empty( $this->widget_args['post_thumbnail_index_adv'] ) ) {

				$post_thumbnail_index = ( isset( $matches[1][ $this->widget_args['post_thumbnail_index_adv'] - 1 ] ) ) ? ( $this->widget_args['post_thumbnail_index_adv'] - 1 ) : 0;

			} else {
				
				switch( $this->widget_args['post_thumbnail_index_basic'] ) {

					case 'first':
						$post_thumbnail_index = 0;
					break;

					case 'last':
						$post_thumbnail_index = ( count( $matches[1] ) - 1);
					break;

					case 'random':
						$post_thumbnail_index = rand( 0, count( $matches[1] ) -1 );
					break;
				}
			}

			// Extract image path.
			$first_img = $matches[1][ $post_thumbnail_index ];
			
		} else {

			// No images found in the post content. Display default 'no-image' thumbnail image.
			return ( $this->display_default_thumb( $post, $this->widget_args['thumbnail_width'], $this->widget_args['thumbnail_height'] ) );
			
		}
		
		// Parsing image URL.
		$parts = parse_url( $first_img );
		
		// Getting the image basename pathinfo.
		$first_img_obj = pathinfo( basename( $first_img ) );

		// Removing querystring from image to save. This fixed the Jetpack Photon Issue.
		$first_img_obj['extension'] = preg_replace( '/\?.*/', '', $first_img_obj['extension'] );
		
		// Building the associated cached image URL.
		$imageNameToSave = $this->cache_basepath . 'srpthumb-p' . $post->ID .  '-ID' . $post_thumbnail_index . '-' . $this->widget_args['thumbnail_width'] . 'x' . $this->widget_args['thumbnail_height'] . '-' . $this->widget_args['thumbnail_rotation'] . '.' . $first_img_obj['extension'];
		
		// Building image path depending wheter this is a multi site WP or not.
		$image_to_render = $parts['path'];

		// Checking if this is a multisite blog, then adjust image paths.
		if ( is_multisite() ) {

			// Retrieving global multi site info.
			global $current_blog, $blog_id;

			// Is this is a network's blog.
			if ( isset( $blog_id ) && $blog_id > 0 ) {

				// Fetching image path parts.
				$imageParts = explode( '/files/', $image_to_render );

				// Checking if image exists.
				if ( isset( $imageParts[1] ) ) {

					// Fetching multisite image path.
					$image_to_render = '/wp-content/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
				}
			}
		}
		
		// Checking if the thumbnail already exists. In this case, simply render it. Otherwise generate it.
		if ( ( file_exists( SRP_PLUGIN_DIR . $imageNameToSave ) ) || ( $this->generate_gd_image( $post, 'firstimage', $image_to_render, SRP_PLUGIN_DIR . $imageNameToSave, $thumb_width, $thumb_height, $this->widget_args['thumbnail_rotation'] ) ) ) {
			
			// Building thumbnail image tag.
			return '<img src="' . SRP_PLUGIN_URL . $imageNameToSave . '" class="srp-post-thumbnail" width="' . $this->widget_args['thumbnail_width'] . '" height="' . $this->widget_args['thumbnail_height'] . '" alt="' . esc_attr( $post_title ) . '" />';

		} elseif ( 'yes' == $this->widget_args['post_noimage_skip'] ) {

			// If some errors are generated from the thumbnail generation process and  the "post_noimage_skip" option is on, skip this image returning false.
			return false;
			
		} else {
		
			// If some errors are generated from the thumbnail generation process and  the "post_noimage_skip" option is off, display the default no-image placeholder.
			return $this->display_default_thumb( $post, $this->widget_args['thumbnail_width'], $this->widget_args['thumbnail_height'] );
		}
	}

	/**
	 * display_thumb()
	 *
	 * This method displays the post thumbnail based on the user choices.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $post The global WP post object.
	 * @access private
	 * @return string It returns the thumbnail HTML.
	 */
	private function display_thumb( $post ) {

		// Checking if thumbnail custom field option is on.
		if ( !empty($this->widget_args['thumbnail_custom_field'] ) ) {
		
			// Fetching thumbnail post meta.
			$thumb_postmeta = get_post_meta( $post->ID, $this->widget_args['thumbnail_custom_field'] );
			
			// Checking if thumbnail custom field option is on and it exists in the current post meta.
			if ( !empty( $thumb_postmeta ) ) {
			
				// Checking if thumbnail should be linked to post.
				if ( 'yes' == $this->widget_args['thumbnail_link'] ) {
				
					// Building thumbnail link and image tag.	
					$thumbimg  = '<img src="' . $thumb_postmeta[0] . '" class="srp-post-thumbnail" alt="' . esc_attr( $post->post_title ) . '" />';
					
					// Building HTML link atts.
					$linkatts = array(
						'class' => 'srp-post-thumbnail-link',
						'href'  => get_permalink( $post->ID ),
						'title' => esc_attr( $post->post_title )
					);

					// Building HTML link.
					$thumb = $this->srp_create_tag( 'a', $thumbimg, $linkatts );

					// Return generated thumnail tag.
					return $thumb;
					
				} else {
				
					// Thumbnail is not linked to post. Building the image tag.
					$thumb = '<img src="' . $thumb_postmeta[0] . '" class="srp-post-thumbnail" alt="' . esc_attr( $post->post_title ) . '" />';
					
					// Return generated thumbnail image tag.
					return $thumb;
				}
			}
		}

		// Switching through different thumbnail types.
		switch( $this->widget_args['thumbnail_type'] ) {
		
			case 'thumb-post':
			
				// Generating the custom post thumbnail.
				return $this->generate_custom_thumb( $post );

			break;

			case 'thumb-wp':
				
				// Generating the post thumbnail from the featured image.
				return $this->generate_wpfeatured_thumb( $post );

			break;
			
			case 'thumb-author':

				// Checking if the author post archive link is enabled.
				$thumb_author_href = ( 'yes' == $this->widget_args['author_archive_link'] ) ? get_author_posts_url( get_the_author_meta( 'ID' ) ) : get_permalink( $post->ID );
				
				// Building HTML link atts.
				$linkatts = array(
					'class' => 'srp-post-thumbnail-link',
					'href'  => $thumb_author_href,
					'title' => esc_attr( $post->post_title )
				);

				// Generating the thumbnail box.
				return $this->srp_create_tag( 'a', get_avatar( $post->post_author, $this->widget_args['thumbnail_width'] ), $linkatts );

			break;
		}
	}

	/**
	 * generate_custom_thumb()
	 *
	 * This method generates the post custom thumbnail.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $post The global WP post object.
	 * @access private
	 * @return mixed It returns the custom post thumbnail HTML.
	 */
	private function generate_custom_thumb( $post ) {
		
		// Checking if featured thumbnails setting is active, if the current post has one and if it exists as file.
		if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post->ID ) ) {

			// Fetching Thumbnail ID.
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			
			// Checking if current featured thumbnail comes from the NextGen Plugin.
			if( stripos( $thumbnail_id, 'ngg-' ) !== false && class_exists( 'nggdb' ) ){
			
				try {
				
					// Creating New NextGen Class instance.
					$nggdb = new nggdb();
					
					// Fetching NGG thumbnail object.
					$nggImage = $nggdb->find_image( str_replace( 'ngg-', '', $thumbnail_id ) );
					
					// Retrieving physical path of NGG thumbnail image.
					$featured_physical_path = $nggImage->imagePath;
					
					// Fetching NGG thumbnail image URL.
					$featured_thumb_url = $nggImage->imageURL;
				
				} catch ( Exception $e ) {}

			} else {

				// Retrieving featured image attachment src.
				$featured_thumb_attachment = wp_get_attachment_image_src( $thumbnail_id, 'large' );
				
				// Retrieving physical path of featured image.
				$featured_physical_path = get_attached_file( $thumbnail_id );

				// Retrieving featured image url.
				$featured_thumb_url = $featured_thumb_attachment[0];
			}

			// Parsing featured image url.
			$featured_thumb_url_obj = parse_url( $featured_thumb_url );
			
			// Retrieving featured image basename.
			$featured_thumb_basename = pathinfo( basename( $featured_thumb_url ) );			
			
			// Removing querystring from image to save. This fixed the Jetpack Photon Issue.
			$featured_thumb_basename['extension'] = preg_replace( '/\?.*/', '', $featured_thumb_basename['extension'] );

			// Building featured image cached path.
			$featured_thumb_cache = $this->cache_basepath . 'srpthumb-p' . $post->ID .  '-' . $this->widget_args['thumbnail_width'] . 'x' . $this->widget_args['thumbnail_height'] . '-' . $this->widget_args['thumbnail_rotation'] . '.' . $featured_thumb_basename['extension'];

			// Checking if the thumbnail already exists. In this case, simply render it. Otherwise generate it.
			if ( ( file_exists( SRP_PLUGIN_DIR . $featured_thumb_cache )) || ( $this->generate_gd_image( $post, 'featured', $featured_physical_path, SRP_PLUGIN_DIR . $featured_thumb_cache, $this->widget_args['thumbnail_width'], $this->widget_args['thumbnail_height'], $this->widget_args['thumbnail_rotation'] ) ) ) {
			
				// Return cached image as source (URL path).
				$featured_thumb_src = SRP_PLUGIN_URL . $featured_thumb_cache;
				
				// Generating Image HTML Tag.
				$featured_htmltag = '<img src="' . $featured_thumb_src . '" class="srp-post-thumbnail" width="' . $this->widget_args['thumbnail_width'] . '" height="' . $this->widget_args['thumbnail_height'] . '" alt="' . esc_attr( $post->post_title ) . '" />';
			
			} else {

				// No featured image has been found. Trying to fetch the first image tag from the post content.
				$featured_htmltag = $this->get_first_image_url( $post, $this->widget_args['thumbnail_width'], $this->widget_args['thumbnail_height'], $post->post_title );
			}

			// Checking if thumbnail should be linked to post.
			if ( 'yes' == $this->widget_args['thumbnail_link'] ) {

				// Building HTML link atts.
				$linkatts = array(
					'class' => 'srp-post-thumbnail-link',
					'href'  => get_permalink( $post->ID ),
					'title' => esc_attr( $post->post_title )
				);

				// Building featured image link tag.
				$featured_temp_content  = $this->srp_create_tag( 'a', $featured_htmltag, $linkatts );
			
			} else {
			
				// Displaying post thumbnail without link.
				$featured_temp_content = $featured_htmltag;
			}
			
		} else {

			// No featured image has been found. Trying to fetch the first image tag from the post content.
			$featured_htmltag = $this->get_first_image_url( $post, $this->widget_args['thumbnail_width'], $this->widget_args['thumbnail_height'], $post->post_title );

			// Checking if returned image is real or it is a false value due to skip_noimage_posts option enabled.
			if ($featured_htmltag) {

				// Checking if thumbnail should be linked to post.
				if ( 'yes' == $this->widget_args['thumbnail_link'] ) {
					
					// Building HTML link atts.
					$linkatts = array(
						'class' => 'srp-post-thumbnail-link',
						'href'  => get_permalink( $post->ID ),
						'title' => esc_attr( $post->post_title )
					);

					// Building image tag.
					$featured_temp_content = $this->srp_create_tag( 'a', $featured_htmltag, $linkatts );
					
				} else {
				
					// Displaying post thumbnail without link.
					$featured_temp_content = $featured_htmltag;
				}

			} else {

				// Return false.
				return false;
			}
		}
		
		// Return all the image process.
		return $featured_temp_content;
	}

	/**
	 * generate_wpfeatured_thumb()
	 *
	 * This method generates the post thumbnail from the featured image.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $post The global WP post object.
	 * @access private
	 * @return string The thumbnail HTML.
	 */
	private function generate_wpfeatured_thumb( $post ) {

		// Checking if featured thumbnails setting is active, if the current post has one and if it exists as file.
		if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post->ID ) ) {

			// Checking if thumbnail should be linked to post.
			if ( 'yes' == $this->widget_args['thumbnail_link'] ) {
				
				// Building HTML link atts.
				$linkatts = array(
					'class' => 'srp-post-thumbnail-link',
					'href'  => get_permalink( $post->ID ),
					'title' => esc_attr( $post->post_title )
				);

				// Returning the linked thumbnail HTML.
				return $this->srp_create_tag( 'a', get_the_post_thumbnail( $post->ID, $this->widget_args['wp_thumbnail_size'] ), $linkatts );

			} else {

				// Returning the featured thumbnail image.
				return get_the_post_thumbnail( $post->ID, $this->widget_args['wp_thumbnail_size'] );

			}

		} else {

			// No featured image is set for this post. Returning default no image placeholder.
			return $this->display_default_thumb( $post, $this->widget_args['thumbnail_width'], $this->widget_args['thumbnail_height'] );
		}
	}

	/**
	 * extract_content()
	 *
	 * This method extracts the post content.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $post The global WP post object.
	 * @param $content_type The type of post content to display.
	 * @param $post_global_counter The global post counter.
	 * @access private
	 * @return string The post content text.
	 */
	private function extract_content( $post, $content_type, $post_global_counter ) {

		// Switching the content length based on the 'Post Content Expansion' option.
		$content_length      = ($post_global_counter <= $this->widget_args['post_content_expand']) ? 'content' : $this->widget_args['post_content_length'];
		$content_length_mode = ($post_global_counter <= $this->widget_args['post_content_expand']) ? 'fullcontent' : $this->widget_args['post_content_length_mode'];
		
		// Checking for post content text 'cut' mode.
		switch( $content_length_mode ) {
		
			case 'words':
				
				// Switching through content type.
				switch( $content_type ) {
				
					case 'content':

						// Sanitizing post content.
						$sanitized_string = $this->srp_sanitize( $post->post_content );

					break;
					
					case 'excerpt':

						// Sanitizing excerpt.
						$sanitized_string = $this->srp_sanitize( $post->post_excerpt );

					break;
				}
				
				// Making a tag clean copy of the excerpt to calculate the total num of characters from words.
				$stripped_string = strip_tags( $sanitized_string );
				
				// In order to cut by words without truncating html tags, we need to first calculate the approximate num of characters equal to the number of specified words limit. This is done by the method substr_words() with the $mode parameter set to "count". Instead of returning the cutted string, it will return the num of characters that will be passed to the truncate_text() method as character limit. 
				return $this->srp_truncate_text( $sanitized_string, $this->substr_words( $stripped_string, $content_length, 'count'), '', true );
				
			break;
			
			case 'chars':
				
				// Switching through content type.
				switch( $content_type ) {
					
					case 'content':

						// Retrieving text from post content using 'characters cut'.
						return $this->srp_truncate_text( $this->srp_sanitize( $post->post_content ), $content_length );

					break;
					
					case 'excerpt':

						// Return normal excerpt using 'characters cut'.
						return $this->srp_truncate_text( $this->srp_sanitize( $post->post_excerpt ), $content_length );

					break;
				}
				
			break;
			
			case 'fullcontent':
			
				// Switching through content type.
				switch( $content_type ) {
					
					case 'content':

						// Retrieving text from post content using 'characters cut'.
						return $this->srp_sanitize( $post->post_content );

					break;
					
					case 'excerpt':

						// Return normal excerpt using 'characters cut'.
						return $this->srp_sanitize( $post->post_excerpt );

					break;
				}
				
			break;
		}
	}

	/**
	 * extract_title()
	 *
	 * This method extracts the post title.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $post The global WP post object.
	 * @access private
	 * @return string The post title text.
	 */
	private function extract_title( $post ) {
		
		// Loading default plugin values.
		$title_length      = $this->widget_args['post_title_length'];
		$title_length_mode = $this->widget_args['post_title_length_mode'];
		$output_title      = "";
		
		// Checking for 'cut' mode.
		switch( $title_length_mode ) {
		
			case 'words':
			
				// Returning normal title using 'words cut'.
				$output_title = $this->substr_words( $this->srp_sanitize( $post->post_title ), $title_length );

			break;
			
			case 'chars':
			
				// Return normal title using 'characters cut'.
				$output_title = mb_substr( $this->srp_sanitize( $post->post_title ), 0, $title_length, 'UTF-8' );

			break;
			
			case 'fulltitle':
			
				// Returning normal title using 'characters cut'.
				$output_title = $this->srp_sanitize( $post->post_title );

				// Returning title.
				return $output_title;
				
			break;
		}
		
		// Checking title's string break.
		if ( !empty( $this->widget_args['title_string_break'] ) ) {

			// Adding title string break to output.
			$output_title .= esc_html( $this->widget_args['title_string_break'] );
		}
		
		// Returning title.
		return $output_title;
	}

	/**
	 * set_style_attributes()
	 *
	 * This method sets the inline CSS styles for the requested DOM element.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @param $srp_element The DOM element CSS styles must be applied to.
	 * @access private
	 * @return mixed It could return the relative CSS inline style, a boolean false or true.
	 */
	private function set_style_attributes( $srp_element ) {

		// Switching through the different types of widget elements.
		switch ( $srp_element ) {

			case 'widget_title':

				// Setting up widget title HTML attributes
				$wtitle_heading_atts_styles = array( 'style' => '' );

				// Checking for a custom font size.
				if ( !empty( $this->widget_args['style_font_size_widget_title'] ) ) {

					// Setting up font size.
					$wtitle_heading_atts_styles['style'] .= 'font-size:' . $this->widget_args['style_font_size_widget_title'] . $this->widget_args['style_font_size_type_widget_title'] . ';';
				}

				// Checking for a custom colour.
				if ( !empty( $this->widget_args['style_color_widget_title'] ) ) {

					// Setting up colour.
					$wtitle_heading_atts_styles['style'] .= 'color: ' . $this->widget_args['style_color_widget_title'] . ';';
				}

				return ( !empty($wtitle_heading_atts_styles['style']) ) ? $wtitle_heading_atts_styles['style'] : false;

			break;

			case 'post_title':

				// Setting up post title HTML attributes
				$ptitle_heading_atts_styles = array( 'style' => '' );

				// Checking for a custom font size.
				if ( !empty( $this->widget_args['style_font_size_post_title'] ) ) {

					// Setting up font size.
					$ptitle_heading_atts_styles['style'] .= 'font-size:' . $this->widget_args['style_font_size_post_title'] . $this->widget_args['style_font_size_type_post_title'] . ';';
				}

				// Checking for a custom colour.
				if ( !empty( $this->widget_args['style_color_post_title'] ) ) {

					// Setting up colour.
					$ptitle_heading_atts_styles['style'] .= 'color: ' . $this->widget_args['style_color_post_title'] . ';';
				}

				// If there is at least one custom style applied, return it.
				return ( !empty( $ptitle_heading_atts_styles['style'] ) ) ? $ptitle_heading_atts_styles['style'] : false;

			break;

			default:

				// Returning true.
				return true;
			break;
		}

		return '';
	}

	/**
	 * generate_widget_title()
	 *
	 * This method generates the widget title.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @return string It returns the widget title.
	 */
	private function generate_widget_title() {

		// Checking for 'Use default Wordpress HTML layout for widget title' option value.
		if ( 'yes' == $this->widget_args['widget_title_show_default_wp']) return;

		// Checking for 'Hide Widget Title' option.
		if ( 'yes' != $this->widget_args['widget_title_hide'] ) {
		
			// Checking if SRP is displaying a category filter result and if it should use the linked category title.
			if ( 'yes' == $this->widget_args['category_title'] && !empty( $this->widget_args['category_include'] ) ) {

				// Assigning category ID.
				$thisCategoryId = $this->widget_args['category_include'];

				if ( 'yes' == $this->widget_args['category_autofilter'] ) {

					// Fetching category link.
					$thisCategory   = get_the_category();
					$thisCategoryId = $thisCategory[0]->cat_ID;
				}

				// Fetching Category Link.
				$srp_category_link = get_category_link( $thisCategoryId );

				// Building HTML link atts.
				$linkatts = array(
					'class' => 'srp-widget-title-link',
					'href'  => $srp_category_link,
					'title' => esc_attr( get_cat_name( $thisCategoryId ) )
				);

				// Checking if some style has been defined.
				if ( $this->set_style_attributes( 'widget_title' ) ) {

					// Setting up style attribute.
					$linkatts['style'] = $this->set_style_attributes( 'widget_title' );
				}

				// Building category title HTML.
				$category_title_link = $this->srp_create_tag( 'a', get_cat_name( $thisCategoryId ), $linkatts );
				
				// Preparing widget title classes.
				$categoryAdditionalClasses = array( 'class' => 'widget-title srp-widget-title' );
				
				// Checking for additional widget header CSS classes.
				if ( !empty( $this->widget_args['widget_title_header_classes'] ) ) {

					// Adding additional CSS classes to the widget header.
					$categoryAdditionalClasses['class'] .= ' ' . $this->widget_args['widget_title_header_classes'];
				}

				// Returning the widget title.
				return $this->srp_create_tag( $this->widget_args['widget_title_header'], $category_title_link, $categoryAdditionalClasses );
				
			} else {
			
				// Checking if widget title should be linked to a custom URL.
				if ( !empty( $this->widget_args['widget_title_link'] ) ) {
					
					// Checking if future posts filter is on.
					switch ( $this->widget_args['post_status'] ) {
						
						case 'publish':
						case 'private':
						case 'inherit':
						case 'pending':
						case 'trash':

							// Fixing URL with http prefix.
							$widgetTitleLink = ( false === strpos( $this->widget_args['widget_title_link'], 'http://' ) ) ? 'http://' . $this->widget_args['widget_title_link'] : $this->widget_args['widget_title_link'];
							
							// Building HTML link atts.
							$linkatts = array(
								'class' => 'srp-widget-title-link',
								'href'  => esc_url( $widgetTitleLink )
							);

							// Checking if some style has been defined.
							if ( $this->set_style_attributes( 'widget_title' ) ) {

								// Setting up style attribute.
								$linkatts['style'] = $this->set_style_attributes( 'widget_title' );
							}

							// Building widget title HTML.
							$widget_title_link = $this->srp_create_tag( 'a', esc_html( $this->widget_args['widget_title'] ), $linkatts );
							
							// Preparing widget title classes.
							$widgetTitleAdditionalClasses = array( 'class' => 'widget-title srp-widget-title' );
							
							// Handling additional widget title classes.
							if ( !empty( $this->widget_args['widget_title_header_classes'] ) ) {

								// Adding additional CSS classes to the widget title.
								$widgetTitleAdditionalClasses['class'] .= ' ' . $this->widget_args['widget_title_header_classes'];
							}

							// Returning the widget title
							return $this->srp_create_tag( $this->widget_args['widget_title_header'], $widget_title_link, $widgetTitleAdditionalClasses );
							
						break;
						
						case 'draft':
						case 'future':
							
							// Preparing widget title classes.
							$widgetTitleAdditionalClasses = array( 'class' => 'widget-title srp-widget-title' );

							// Checking if some style has been defined.
							if ( $this->set_style_attributes( 'widget_title' ) ) {

								// Setting up style attribute.
								$linkatts['style'] = $this->set_style_attributes( 'widget_title' );
							}
							
							// Handling additional widget title classes.
							if ( !empty( $this->widget_args['widget_title_header_classes'] ) ) {

								// Adding additional CSS classes to the widget title.
								$widgetTitleAdditionalClasses['class'] .= ' ' . $this->widget_args['widget_title_header_classes'];
							}

							// Returning the widget title
							return $this->srp_create_tag( $this->widget_args['widget_title_header'], esc_html( $this->widget_args['widget_title'] ), $widgetTitleAdditionalClasses );
							
						break;
						
						default:
						break;
					}
					
				} else {
					
					// Preparing widget title classes.
					$widgetTitleAdditionalClasses = array( 'class' => 'widget-title srp-widget-title' );

					// Setting up inline CSS styles
					$widgetTitleAtts['style'] = $this->set_style_attributes( 'widget_title' );

					// Handling additional widget title classes.
					if ( !empty( $this->widget_args['widget_title_header_classes'] ) ) {

						// Adding additional CSS classes to the widget title.
						$widgetTitleAdditionalClasses['class'] .= ' ' . $this->widget_args['widget_title_header_classes'];
					}

					// Returning the widget title
					return $this->srp_create_tag( $this->widget_args['widget_title_header'], esc_html( $this->widget_args['widget_title'] ), array_merge( $widgetTitleAdditionalClasses, $widgetTitleAtts ) );
				}
			}
		}

	}

	/**
	 * generate_no_posts_text()
	 *
	 * This method generates the 'No Posts' text.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @return string It returns 'No Posts' text.
	 */
	private function generate_no_posts_text() {

		// Checking if No Posts Link option has a value.
		if ( !empty( $this->widget_args['noposts_link'] ) ) {

			// Setting No Posts link attributes.
			$noposts_link_atts = array(
				'class' => 'srp-noposts-link',
				'href'  => $this->widget_args['noposts_link'],
				'title' => esc_attr( $this->widget_args['noposts_text'] )
			);

			// No posts available. Returning the "no posts" linked message.
			return $this->srp_create_tag( 'a', trim( esc_html( $this->widget_args['noposts_text'] ) ), $noposts_link_atts );

		} else {

			// Setting No Posts Text attributes.
			$noposts_atts = array( 'class' => 'srp-noposts-text' );

			// No posts available. Returning the "no posts" message.
			return $this->srp_create_tag( 'p', trim( esc_html( $this->widget_args['noposts_text'] ) ), $noposts_atts );
		}
	}

	/**
	 * generate_post_title()
	 *
	 * This method generates the post title
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $post The current WP post object.
	 * @return string It returns post title
	 */
	private function generate_post_title( $post ) {

		// Setting up post title HTML attributes
		$ptitle_heading_atts = array( 'class' => 'srp-post-title' );
		
		// Checking if "post titles nolink" option is on.
		if ('yes' == $this->widget_args['post_title_nolink'] ) {
			
			// Setting up post title CSS style.
			$ptitle_heading_atts['style'] = $this->set_style_attributes( 'post_title' );

			// Returning the post title HTML.
			return $this->srp_create_tag( $this->widget_args['post_title_header'], $this->extract_title( $post ), $ptitle_heading_atts );
			
		} else {
			
			// Building HTML link atts.
			$linkatts = array('class' => 'srp-post-title-link', 'href' => get_permalink($post->ID), 'title' => esc_attr( $post->post_title ) );

			// Checking if some style has been defined.
			if ( $this->set_style_attributes( 'post_title' ) ) {

				// Setting up style attribute.
				$linkatts['style'] = $this->set_style_attributes( 'post_title' );
			}

			// Building linked post title HTML.
			$ptitlelink = $this->srp_create_tag( 'a', $this->extract_title( $post ), $linkatts );

			// Returning the post title HTML.
			return $this->srp_create_tag( $this->widget_args['post_title_header'], $ptitlelink, $ptitle_heading_atts );
		}
	}

	/**
	 * generate_post_date()
	 *
	 * This method generates the post date
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $post The current WP post object.
	 * @return string It returns post date.
	 */
	private function generate_post_date( $post ) {

		// Setting up post date string.
		$post_date_string = "";
		
		if ( !empty( $this->widget_args['post_date_prefix'] ) ) {

			// Adding post date PREFIX.
			$post_date_string .= esc_html( $this->widget_args['post_date_prefix'] );
		}

		// Switching between date formats.
		$date_format_mode = ( 'yes' == $this->widget_args['date_timeago'] ) ? $this->themeblvd_time_ago( $post ) : get_the_time( $this->widget_args['date_format'], $post );

		// Joining post date format with prefix.
		$post_date_string .= $date_format_mode;
		
		// Building post date container.
		return $this->srp_create_tag( 'div', $post_date_string, array( 'class' => 'srp-post-date') );
	}

	/**
	 * generate_post_author()
	 *
	 * This method generates the post author text/link
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $post The current WP post object.
	 * @return string It returns post author text/link.
	 */
	private function generate_post_author( $post ) {

		// Setting up the author text.
		$post_author_string = "";

		// Fetching Author Name:
		$author_name         = get_the_author_meta( 'display_name', $post->post_author );
		$author_archive_link = esc_url( get_author_posts_url( $post->post_author ) );
		$author_url          = esc_url( get_the_author_meta( 'url', $post->post_author) );

		// Checking for post author PREFIX.
		if ( !empty($this->widget_args['post_author_prefix'] ) ) {
			
			// Building post author PREFIX HTML.
			$post_author_string .= esc_html( $this->widget_args['post_author_prefix'] );
		}
		
		// Checking if post author archive link option is enabled.
		if ( 'yes' == $this->widget_args['post_author_archive_link'] ) {

			// Linking post author to the relative author's URL link.
			$post_author_string .= $this->srp_create_tag( 'a', $author_name, array( 'class' => 'srp-post-author-url', 'href' => $author_archive_link, 'title' => esc_attr( sprintf( __( 'View all %s&#8217;s posts', SRP_TRANSLATION_ID ), $author_name ) ), 'rel' => 'author' ) );

		} else if ( 'yes' == $this->widget_args['post_author_url'] && !empty( $author_url ) ) {

			// Linking post author to the relative author's URL link.
			$post_author_string .= $this->srp_create_tag( 'a', $author_name, array('class' => 'srp-post-author-url', 'href' => $author_url, 'title' => esc_attr( sprintf( __( 'Visit %s&#8217;s website', SRP_TRANSLATION_ID ), $author_name ) ), 'rel' => 'author external' ) );
		
		} else {

			// Fetching the plain text author name.
			$post_author_string .= $author_name;
		}

		// Returning the post author HTML.
		return $this->srp_create_tag( 'div', $post_author_string, array( 'class' => 'srp-post-author' ) );
	}

	/**
	 * generate_post_categories()
	 *
	 * This method generates the post categories text/links
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $post The current WP post object.
	 * @return string It returns post categories text/links
	 */
	private function generate_post_categories( $post ) {

		// Setting up category list string.
		$post_cat_string = '';
		
		// Setting up category list.
		$post_cat_list = '';
		
		// Checking for post category PREFIX.
		if ( !empty( $this->widget_args['post_category_prefix'] ) ) {
			
			// Building post category PREFIX HTML.
			$post_cat_string .= esc_html( $this->widget_args['post_category_prefix'] );
		}
		
		// Retrieving categories array.
		$srp_categories = get_the_category( $post->ID );
		
		// Checking if "post category link" option is on.
		if ( 'yes' == $this->widget_args['post_category_link'] ) {
			
			// Looping through categories array.
			foreach( $srp_categories as $srp_cat ) {
			
				// Fetching the current category link.
				$srp_category_link = get_category_link( $srp_cat->cat_ID );
				
				// Building HTML link atts.
				$linkatts = array(
					'href'  => $srp_category_link,
					'title' => $srp_cat->cat_name
				);

				// Building category link HTML.						
				$post_cat_list .= $this->srp_create_tag( 'a', $srp_cat->cat_name, $linkatts ) . esc_html( $this->widget_args['post_category_separator'] );
			}
			
		} else {
			
			// Looping through categories array.
			foreach( $srp_categories as $srp_cat ) {
			
				// Filling categories list.
				$post_cat_list .= $srp_cat->cat_name . esc_html( $this->widget_args['post_category_separator'] );
			}
		}
		
		// Right trimming the last category separator on the category list.
		$post_cat_string .= rtrim( $post_cat_list, esc_html( $this->widget_args['post_category_separator'] ) );
		
		// Returning the post category HTML.
		return $this->srp_create_tag( 'div', $post_cat_string, array( 'class' => 'srp-post-category' ) );
	}
	
	/**
	 * generate_post_comments()
	 *
	 * This method generates the post comments text/links
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $post The current WP post object.
	 * @return string It returns post comments text/links
	 */
	private function generate_post_comments( $post ) {

		// Fetching the comments number for this post.
		$post_num_comments  = get_comments_number( $post->ID );

		// Fetching the comments links for this post.
		$post_comments_link = get_comments_link( $post->ID );

		// Building the comment link HTML attributes
		$post_comments_link_atts = array(
			'class' => 'srp-post-comments-link',
			'href'  => esc_url( $post_comments_link ),
			'title' => esc_attr( __( 'Show Post Comments', SRP_TRANSLATION_ID ) )
		);

		// Checking for 'Display post comments number' option.
		if ( 'yes' == $this->widget_args['post_comments_show_num'] ) {

			// Switching through the different possible numbers.
			switch ( $post_num_comments ) {

				case 0:

					// Assigning relative text.
					$post_num_comments_string = esc_html( $this->widget_args['post_no_comments_string'] );

				break;

				case 1:

					// Assigning relative text.
					$post_num_comments_string =  esc_html( $this->widget_args['post_single_comments_string'] );

				break;

				default:

					// Assigning relative text.
					$post_num_comments_string = $post_num_comments . ' ' . esc_html( $this->widget_args['post_multiple_comments_string'] );

				break;
			}

		} else {

			// Assigning default text.
			$post_num_comments_string = esc_html( $this->widget_args['post_default_comments_string'] );
		}
		
		// Checking if total comments are > 0
		if ( $post_num_comments > 0 ) {

			// Building the comment link.
			$post_comments_string = $this->srp_create_tag( 'a', $post_num_comments_string, $post_comments_link_atts );

		} else {

			// Building 'No Comments Available' text.
			$post_comments_string = esc_html( $this->widget_args['post_no_comments_string'] );
		}

		// Returning post comments HTML.
		return $this->srp_create_tag( 'div', $post_comments_string, array( 'class' => 'srp-post-comments' ) );
	}

	/**
	 * generate_post_excerpt()
	 *
	 * This method generates the post comments text/links
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $post The current WP post object.
	 * @param  int $post_global_counter The global post counter.
	 * @return string It returns the post content text.
	 */
	private function generate_post_excerpt( $post, $post_global_counter ) {

		// Setting up the post content text.
		$postExcerptHTML = '';

		// Building post excerpt container.
		$postExcerptHTML .= '<div class="srp-post-content">';
		
		// Checking if "post link excerpt" option is on.
		if ( 'yes' == $this->widget_args['post_link_excerpt'] ) {
			
			// Building HTML link atts.
			$linkatts = array(
				'class' => 'srp-linked-content',
				'href'  => get_permalink( $post->ID ),
				'title' => esc_attr( $post->post_title )
			);

			// Building link tag to enclose the entire excerpt in.
			$postExcerptHTML .= $this->srp_create_tag( 'a', $this->extract_content( $post, $this->widget_args['post_content_type'], $post_global_counter ), $linkatts );
			
		} else {

			// Fetching post excerpt.
			$postExcerptHTML .= $this->extract_content( $post, $this->widget_args['post_content_type'], $post_global_counter );
		}
		
		// Checking for 'Image String Break' option.
		if ( !empty( $this->widget_args['image_string_break'] ) ) {
			
			// Building HTML image tag for the image string break.
			$image_string_break = '<img src="' . esc_url( $this->widget_args['image_string_break'] ) . '" class="srp-post-stringbreak-image" alt="' . esc_attr( $post->post_title ) . '" />';

			// Checking if "string break link" option is on.
			if ( 'yes' == $this->widget_args['string_break_link'] ) {
			
				// Building HTML link atts.
				$linkatts = array( 'class' => 'srp-post-stringbreak-link-image', 'href' => get_permalink( $post->ID ), 'title' => esc_attr( $post->post_title ) );

				// Building image string break link HTML tag.
				$postExcerptHTML .= $this->srp_create_tag( 'a', $image_string_break, $linkatts );
			
			} else {
			
				// Fetching the image string break URL.
				$postExcerptHTML .= $image_string_break;
			}
		
		} elseif ( !empty( $this->widget_args['string_break'] ) ) {
		
			// Using a text stringbreak. Checking if string break should be linked to post.
			if ( 'yes' == $this->widget_args['string_break_link'] ) {
				
				// Building HTML link atts.
				$linkatts = array(
					'class' => 'srp-post-stringbreak-link',
					'href'  => get_permalink($post->ID),
					'title' => esc_attr( $post->post_title )
				);

				// Building string break link HTML tag.					
				$postExcerptHTML .= $this->srp_create_tag( 'a', trim( esc_html( $this->widget_args['string_break'] ) ), $linkatts );
				
			} else {
				
				// Building string break HTML without link.
				$postExcerptHTML .= $this->srp_create_tag( 'span', trim( esc_html( $this->widget_args['string_break'] ) ), array( 'class' => 'srp-post-stringbreak' ) );
			}
		}
		
		// Closing post excerpt container.
		$postExcerptHTML .= '</div>';

		// Returning the post content text.
		return $postExcerptHTML;
	}

	/**
	 * generate_post_tags()
	 *
	 * This method generates the post tags text/links
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $post The current WP post object.
	 * @return string It returns the post tags text/links
	 */
	private function generate_post_tags( $post ) {

		// Retrieving list of associated post tags.
		$post_tags = get_the_tags( $post->ID );
		
		// Checking for valid results.
		if ( !empty( $post_tags ) ) {
		
			// Setting up Tag list.
			$tag_list = '';
			
			// Setting up tags list string.
			$post_tags_string = '';

			// Checking for post tags PREFIX.
			if ( !empty( $this->widget_args['post_tags_prefix'] ) ) {
				
				// Inserting Post Category PREFIX.
				$post_tags_string .= esc_html( $this->widget_args['post_tags_prefix'] );
			}
			
			// Looping through tags.
			foreach( $post_tags as $tag ) {
			
				// Getting tag link.
				$tag_link  = get_tag_link( $tag->term_id );

				// Building HTML link atts.
				$linkatts = array( 'href' => $tag_link );
				
				// Building tag link HTML.
				$tag_list .= $this->srp_create_tag( 'a', $tag->name, $linkatts ) . esc_html( $this->widget_args['post_tags_separator'] );
			}
			
			// Right trimming the last tag separator from the tag list.
			$post_tags_string .= rtrim( $tag_list, esc_html( $this->widget_args['post_tags_separator'] ) );
			
			// Returning post tags HTML.
			return $this->srp_create_tag( 'div', $post_tags_string, array( 'class' => 'srp-post-tags' ) );
		}
	}

	/**
	 * generate_post_tags()
	 *
	 * This method generates the pagination links
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  int $post The current page index.
	 * @param  object $recent_posts The list of fetched posts by the WP_query
	 * @return string It returns the pagination links
	 */
	private function generate_pagination( $current_page, $recent_posts ) {

		/* Hack */
		global $paged;
		$paged = $current_page;
		
		// Setting up pagination links HTML text.
		$paginationHTML = '';

		// Creating Pagination Container.
		$paginationHTML .= '<div class="srp-pagination">';

		// Switching through Pagination type.
		switch( $this->widget_args['pagination_type'] ) {

			case 'prevnext_links':

				// Checking for the max number of pages value.
				if ( isset( $recent_posts->max_num_pages ) ) {

					// Fetching 'previous' and 'next' WP links.
					$paginationHTML .= get_previous_posts_link( esc_html( $this->widget_args['pagination_prevlink_text'] ), $recent_posts->max_num_pages );
					$paginationHTML .= get_next_posts_link( esc_html( $this->widget_args['pagination_nextlink_text'] ), $recent_posts->max_num_pages );
				}
				
			break;

			case 'page_numbers':
				
				// Checking for the max number of pages value.
				if ( isset( $recent_posts->max_num_pages ) ) {

					// We need an unlikely integer.
					$big = 999999999; 

					// Setting up pagination properties.
					$paginationArgs = array(
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => max( 1, $paged ),
						'total'     => $recent_posts->max_num_pages,
						'prev_text' => esc_html( $this->widget_args['pagination_prevlink_text'] ),
						'next_text' => esc_html( $this->widget_args['pagination_nextlink_text'] ),
						'mid_size'  => $this->widget_args['pagination_mid_size']
					);

					// Checking for 'Hide Prev/Next Links' option value.
					if ( 'yes' == $this->widget_args['pagination_hide_prevnext'] ) {

						// Setting up the 'Hide Prev/Next Links'.
						$paginationArgs['prev_next'] = false;
					}

					// Checking for 'Show All Page Numbers' option value.
					if ( 'yes' == $this->widget_args['pagination_show_all'] ) {

						// Setting up the 'Show All Page Numbers'.
						$paginationArgs['show_all'] = true;
					}

					// Building pagination numeric links.
					$paginationHTML .= paginate_links( $paginationArgs );
				}
			break;
		}

		// Closing Pagination Container.
		$paginationHTML .= '</div>';

		// Returning pagination links.
		return $paginationHTML;
	}

	/**
	 * generate_posts()
	 *
	 * This method fetches all the WP posts based on the widget settings, using the WP_query method.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @return object It returns the WP_query object.
	 */
	private function generate_posts() {
		
		// Defining widget args array.
		$args = array();

		/**
		 * ********************************************************
		 * DEFAULT OPTIONS
		 * ********************************************************
		 */
		
		// Checking for 'Compatibility Mode' option.
		if ( 'yes' == $this->plugin_args['srp_compatibility_mode'] ) {
			
			// Compatibility mode filter. This might cause unknown problems. Deactivate it just in case.
			$args['suppress_filters'] = false;
		}
		
		// Ignore sticky posts
		$args['ignore_sticky_posts'] = 1;
		
		/**
		 * ********************************************************
		 * BASIC OPTIONS
		 * ********************************************************
		 */
		
		// Post Type
		$args['post_type'] = $this->widget_args["post_type"];

		// Checking for 'Post Type' and 'Include Subpages' option.
		if ( ( 'page' == $args['post_type'] ) && ( 'no' == $this->widget_args['post_include_sub']) ) {

			// Setting up Post Parent.
			$args['post_parent'] = 0;
		}

		// Post per Page
		$args['posts_per_page'] = ( 'yes' == $this->widget_args['show_all_posts'] ) ? -1 : $this->widget_args['post_limit'];

		// Checking for 'Show Sticky Posts?' option.
		if ('yes' == $this->widget_args["show_sticky_posts"]) {
			
			// Setting up 'Ignore Sticky Posts' value.
			$args['ignore_sticky_posts'] = 0;
		}
		
		/**
		 * ********************************************************
		 * POST OPTIONS
		 * ********************************************************
		 */
		
		// The Post Order.
		$args['order'] = $this->widget_args['post_order'];

		// the Post Order By.
		$args['orderby'] = $this->widget_args['post_order_by'];

		// Checking for 'Random Mode' option.
		if ('yes' == $this->widget_args['post_random'] ) {
			
			// Applying random order by.
			$args['orderby'] = 'rand';
		}

		// Checking for 'Skip Posts Without Featured Image' option.
		if ( 'yes' == $this->widget_args['post_noimage_skip'] ) {

			// Setting up meta query args to exlclude posts that don't have a featured image set.
			$args['meta_query'] = array(
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS'
					)
				);
		}
		
		/**
		 * ********************************************************
		 * ADVANCED POST OPTIONS 1
		 * ********************************************************
		 */
		
		// Checking if "Hide Current Viewed Post" option is enabled.
		if ( ( 'yes' == $this->widget_args["post_current_hide"] ) && ( is_single() || is_page() ) ) {

			// Filtering current post from visualization.
			$args['post__not_in'] = array( $this->singleID );
		}

		// Checking for 'Post Offset' option.
		if ( 0 !== $this->widget_args['post_offset'] ) {

			// Applying post offset.
			$args['offset'] = $this->widget_args['post_offset'];
		}

		/**
		 * ********************************************************
		 * FILTERING OPTIONS 1
		 * ********************************************************
		 */
		
		// Checking for 'Enable Auto Category Filtering' option.
		if ( ( 'yes' == $this->widget_args["category_autofilter"] ) && ( is_category() ) ) {
			
			// Fetching current category object.
			$thisCat = get_category( get_query_var( 'cat' ), false );

			// Filtering according to the current viewed category page.
			$args['cat'] = $thisCat->cat_ID;

		// Checking for 'Enable Auto Category Filtering On Single Posts/Pages' option.
		} else if ( ( 'yes' == $this->widget_args['category_autofilter_single'] ) && is_single() ) {

			// Fetching current category object.
			$thisCats = get_the_category();

			// Setting up categories list.
			$currentPostCategory = array();

			// Looping through categories.
			foreach ( $thisCats as $key => $value ) {

				// Filling the array of categories to filter posts by.
				array_push( $currentPostCategory, $thisCats[ $key ]->cat_ID );
			}

			// Filtering according to the current viewed category page.
			$args['cat'] = implode( ',', $currentPostCategory );
		
		// Checking for 'Category Filter' option.
		} else {
			
			// Checking if category filter is applied.
			if ( !empty( $this->widget_args['category_include'] ) ) {

				// Checking for Exclusive Filtering.
				if ( 'yes' == $this->widget_args['category_include_exclusive'] ) {
				
					// Filtering posts that belong exclusively to all listed categories.
					$args['category__and'] = explode( ',', $this->widget_args['category_include'] );
					
				} else {
				
					// Filtering result posts by category ID.
					$args['cat'] = $this->widget_args['category_include'];
				}
				
			} else if ( !empty( $this->widget_args['category_exclude'] ) ) {
				
				// Creating a temporary array to change sign on exclusion filtering values.
				$tempExcludeArray = explode( ',', $this->widget_args['category_exclude'] );
				
				// Applying the "-" sign to match wordpress exclusion rules.
				foreach( $tempExcludeArray as $k => &$v ) $v = '-' . $v;
				
				// Excluding categories by ID.
				$args['cat'] = implode( ',', $tempExcludeArray );
				
			}
		}

		// Checking for 'Enable Auto Author Filtering?' option.
		if ('yes' == $this->widget_args['author_autofilter'] ) {
			
			// Filtering result posts by tag.
			$args['author'] = $this->authorID;
		}

		// Post Status
		$args['post_status'] = $this->widget_args["post_status"];

		// Checking for 'Search Filter' option.
		if ( !empty( $this->widget_args['post_search_filter'] ) ) {

			// Applying 'Search Filter'.
			$args['s'] = $this->widget_args['post_search_filter'];
		}

		// Checking for 'Show Sticky Posts Only?' option
		if ( 'yes' == $this->widget_args['filter_sticky_posts_only'] ) {

			// Setting up 'Ignore Sticky Posts' value.
			$args['ignore_sticky_posts'] = 1;

			// Filtering posts by sticky posts.
			$args['post__in'] = get_option( 'sticky_posts' );

			// Checking if we have at least some sticky post to show.
			// If none are available, stop configuring the WP_Query call and return an empty array.
			if ( empty ( $args['post__in'] ) ) return array();
		}
		
		/**
		 * ********************************************************
		 * FILTERING OPTIONS 2
		 * ********************************************************
		 */
		
		// Checking for 'Posts/Page ID Filter' option.
		if ( !empty( $this->widget_args['post_include'] ) ) {
			
			// Including result posts by post IDs.
			$args['post__in'] = explode( ',', $this->widget_args['post_include'] );
		}

		// Checking for 'Preserve Posts/Page ID Filter Order?' option.
		if ('yes' == $this->widget_args['preserve_post_include_order'] && ( !empty( $this->widget_args['post_include'] ) ) ) {
			
			// Including result posts by post IDs.
			$args['orderby'] = 'post__in';
		}

		// Checking if 'Include Subpages' option is enabled. In this case, try to fetch all the child pages of the current post.
		if ( ( !empty( $this->widget_args["post_include"] ) ) && ( "yes" == $this->widget_args["post_include_sub"] ) ) {

			// Remove any Post ID filter.
			unset($args["post__in"]);

			// Setting up the Post Parent filter.
			$args['post_parent'] = explode( ',', $this->widget_args['post_include'] );
		}

		// Checking for 'Exclude Posts/Pages By IDs' option.
		if ( !empty( $this->widget_args['post_exclude'] ) ) {
			
			// Excluding result posts by post IDs.
			$args['post__not_in'] = explode( ',', $this->widget_args['post_exclude'] );
		}

		// Checking for author filter.
		if ( '' != $this->widget_args['author_include'] ) {

			// Filtering result posts by author.
			$args['author'] = $this->widget_args['author_include'];
		}

		// Checking for 'Tag Filter' option.
		if ( !empty( $this->widget_args['tags_include'] ) ) {
			
			// Filtering result posts by tag.
			$args['tag'] = $this->widget_args['tags_include'];
		}

		// Checking for 'Custom Field Filter' Meta Key option.
		if ( !empty( $this->widget_args['post_meta_key'] ) ) {
			
			// Filtering result posts by meta key.
			$args['meta_key'] = $this->widget_args['post_meta_key'];
		}
		
		// Checking for 'Custom Field Filter' Meta Value option.
		if ( !empty( $this->widget_args['post_meta_value'] ) ) {
			
			// Filtering result posts by meta value.
			$args['meta_value'] = $this->widget_args['post_meta_value'];
		}

		// Checking for 'Date Filter' option.
		if ( 'yes' == $this->widget_args['enable_date_filter'] ) {

			// Globalizing date filter numeric value and time.
			global $date_filter_num, $date_filter_time;

			// Fetching  date filter numeric value and time.
			$date_filter_num  = $this->widget_args['date_filter_number'];
			$date_filter_time = $this->widget_args['date_filter_time'];

			// Checking if the 'srp_filter_where' function already exists.
			if ( !function_exists('srp_filter_where') ) {

				// Declaring filter function to show posts only after a certain date.
				function srp_filter_where( $where = '' )  {

					// Globalizing date filter numeric value and time.
					global $date_filter_num, $date_filter_time;

				    // Showing Posts only after a certain date.
				    $where .= " AND post_date > '" . date( 'Y-m-d', strtotime('-' . $date_filter_num . ' ' . $date_filter_time) ) . "'";

				    // Returning WHERE clause.
				    return $where;
				}
			}

			// Adding WP filter to show posts only after a certain date.
			add_filter( 'posts_where', 'srp_filter_where' );
		}

		/**
		 * ********************************************************
		 * CUSTOM POST TYPES & TAXONOMIES
		 * ********************************************************
		 */
		
		// Fixing Custom Taxonomy filtering from shortcode.
		if ( 'no' != $this->widget_args['include_custom_taxonomy'] && is_string( $this->widget_args['include_custom_taxonomy'] ) ) {

			// Checking for shortcode string placeholder.
			if ( strpos( $this->widget_args['include_custom_taxonomy'], '%%%' ) ) {

				// Reconstructing the taxonomy list.
				$this->widget_args['include_custom_taxonomy'] = explode( '%%%', $this->widget_args['include_custom_taxonomy'] );

			} else {

				// Typecasting the taxonomy list.
				$this->widget_args['include_custom_taxonomy'] = (array) $this->widget_args['include_custom_taxonomy'];
			}

		}

		// Checking if a custom post type exists at least.
		if ( ('cpt' == $this->widget_args['post_type']) && ('no-cpt' != $this->widget_args['custom_post_type'] ) ) {

			// There is at least one custom post type.
			$args['post_type'] = $this->widget_args['custom_post_type'];

			// Checking for custom taxonomies filter.
			if ( (is_array( $this->widget_args['include_custom_taxonomy'] ) ) && ( !empty( $this->widget_args['include_custom_taxonomy'] ) ) ) {

				// Building Tax Query for multiple taxonomies.
				$args['tax_query'] = array();

				// Fetching the Tax Query conditional operator.
				$args['tax_query']['relation'] = $this->widget_args['taxonomy_bool'];

				// Looping through selected taxonomies.
				foreach ( $this->widget_args['include_custom_taxonomy'] as $term ) {

					// Splitting Term value.
					$taxonomy_trunks =  explode( '|', $term );
					$taxonomy_trunk  = $taxonomy_trunks[0];
					$term_trunk      = $taxonomy_trunks[1];

					// Loading Custom Taxonomies in the Tax Query.
					array_push( $args['tax_query'], array(
						'taxonomy' => $taxonomy_trunk,
						'field'    => 'slug',
						'terms'    => $term_trunk
					));
				}
			}
		}
		
		/**
		 * ********************************************************
		 * PAGINATION OPTIONS
		 * ********************************************************
		 */
		
		// Checking for 'Pagination' option.
		if ( 'yes' == $this->widget_args['enable_pagination'] ) {

			// Setting up pagination parameter.
			$args['paged'] = $this->paged;

			// Checing if we're using pagination with posts offset on.
			if ( $this->widget_args['post_offset'] > 0 ) {

				// We must fix the offset before calling the WP_query.
				add_action( 'pre_get_posts', array( &$this, 'srp_prefix_query_offset' ), 1 );

				// Fixing pagination links.
				add_filter( 'found_posts', array( &$this, 'srp_prefix_adjust_offset_pagination' ), 1, 2 );

			}

		}

		// WP_querying the database.
		$result_posts = new wp_query( $args );

		// Fixing pagination for static home pages.
		$result_posts->is_archive = true;
		$result_posts->is_home = false;

		// Checking if we set up some hooks for custom pagination.
		if ( 'yes' == $this->widget_args['enable_pagination'] && $this->widget_args['post_offset'] > 0 )  {

			// Removing hooks for custom pagination.
			remove_action( 'pre_get_posts', 'srp_prefix_query_offset', 1 );
			remove_filter( 'found_posts', 'srp_prefix_adjust_offset_pagination', 1 );

		}

		// Checking for 'Date Filter' option
		if ( 'yes' == $this->widget_args['enable_date_filter'] ) {

			// Removing filter to show posts only after a certain date.
			remove_filter( 'posts_where', 'filter_where' );
		}

		// Checking if the result posts array is empty.
		if ( !$result_posts->have_posts() ) {
		
			// No posts available. Return false.
			return false;
		}

		// Return result array.
		return $result_posts;
	}

	/**
	 * srp_prefix_query_offset()
	 *
	 * The method fixes the offset value for a paginated results with offset within a custom query.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $query The query object passed by reference.
	 */
	public function srp_prefix_query_offset( &$query ) {

	    // First, define your desired offset...
	    $offset = $this->widget_args['post_offset'];
	    
	    // Next, determine how many posts per page you want (we'll use WordPress's settings)
	    $ppp = $this->widget_args['post_limit'];

	    // Next, detect and handle pagination...
	    if ( $query->is_paged ) {

	        // Manually determine page query offset (offset + current page (minus one) x posts per page)
	        $page_offset = $offset + ( ( $query->query_vars['paged']-1 ) * $ppp );

	        // Apply adjust page offset
	        $query->set('offset', $page_offset );

	    }
	}

	/**
	 * srp_prefix_query_offset()
	 *
	 * This method fixes the pagination links when using offset with pagination on a custom query.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  object $found_posts The post returning from the custom query.
	 * @param  object The query object
	 * @return string It return the correct number of posts after offset and pagination fix.
	 */
	public function srp_prefix_adjust_offset_pagination( $found_posts, $query ) {

	    //Define our offset again...
	    $offset = $this->widget_args['post_offset'];
	 
        //Reduce WordPress's found_posts count by the offset... 
        return $found_posts - $offset;
	}

	/**
	 * display_posts()
	 *
	 * This method generates the SRP layout HTML.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param  string $widget_call This variable determines how the SRP engine is invoked.
	 * @param  string $return_mode This variable determines how the SRP output should be rendered.
	 * @return string It returns the SRP layout HTML.
	 */
	public function display_posts( $widget_call = NULL, $return_mode ) {

		// Initializing SRP content.
		$srp_content = '';

		// Generating the widget title.
		$srp_content .= $this->generate_widget_title();
		
		// Building special HTML comment with current SRP version.
		$srp_content  .= '<!-- BEGIN Special Recent Posts PRO Edition v' . SRP_PLUGIN_VERSION . ' -->';

		// Opening the widget container.
		$srp_content .= '<div ';
		
		// Checking for optional unique CSS ID or additional classes.
		if ( !empty( $this->widget_args['widget_css_id'] ) ) {

			// Adding the unique widget ID.
			$srp_content .= 'id="' . $this->widget_args['widget_css_id'] . '" ';
		}
		
		// Adding CSS classes.
		$srp_content .= 'class="srp-widget-container';
		$srp_content .= ' srp-container-' . str_replace( '_', '-', $this->widget_args['layout_mode'] );
		
		// Checking for additional CSS classes.
		if ( !empty( $this->widget_args["widget_additional_classes"] ) ) {

			// Adding additional CSS classes.
			$srp_content .= ' ' . $this->widget_args['widget_additional_classes'];
		}
		
		// Closing the widget container.
		$srp_content .=  '">';
		
		// Generating posts via WP_query.
		$recent_posts = $this->generate_posts();

		// Checking if posts are available.
		if ( !$recent_posts ) {

			// There are no posts available. Displaying the 'No Posts' text.
			$srp_content .= $this->generate_no_posts_text();
			
		} else {

			// Defining global column counter.
			$post_colrow_counter = 0;
			
			// Defining global post counter.
			$post_global_counter = 0;
			
			// Recent posts are available. Cyclying through result posts.
			while( $recent_posts->have_posts() ):

				// Switch to next post.
				$recent_posts->next_post();

				// Adding +1 to global post counter.
				$post_global_counter = $recent_posts->current_post + 1;
				
				// Adding +1 to post column counter.
				$post_colrow_counter++;
				
				// Setting up additional built-in classes.
				switch( $this->widget_args['layout_mode'] ) {
				
					case 'single_column':
						$single_post_additional_classes = 'srp-post-single-column';
					break;
					
					case 'single_row':
						$single_post_additional_classes = 'srp-post-single-row';
					break;
					
					case 'multi_column':
						$single_post_additional_classes = 'srp-post-multi-column';
					break;
				}

				// Opening column container.
				if ( ( $post_colrow_counter == 1 ) && ( 'multi_column' == $this->widget_args['layout_mode'] ) ) {
				
					$srp_content .= '<div class="srp-widget-row">';
				}
				
				// Compiling single post id.
				$srp_post_id = ( !$this->widget_id ) ? 'srp-singlepost-' . $post_global_counter : $this->widget_id . '-srp-singlepost-' . $post_global_counter;

				// Opening single post container.
				$srp_content .= '<div id="' . $srp_post_id . '" class="srp-widget-singlepost ' . $single_post_additional_classes . '">';

				// Checking if "post title above thumb" option is on.
				if ( 'yes' == $this->widget_args['post_title_above_thumb'] ) {
					
					// Generating the post title.
					$srp_content .= $this->generate_post_title( $recent_posts->post );
				}
				
				// Opening the post content container.
				$srp_content .= '<div class="srp-post-content-container ';
				$srp_content .= ( 'yes' == $this->widget_args['post_thumb_above_content'] ) ? 'srp-thumbnail-position-above' : 'srp-thumbnail-position-default';
				$srp_content .= '">';

				// Checking for the 'Display Thumbnail' option.
				if ( 'yes' == $this->widget_args['display_thumbnail'] ) {
					
					$srp_content .= $this->srp_create_tag( 'div', $this->display_thumb( $recent_posts->post ), array( 'class' => 'srp-thumbnail-box' ) );
				}
				
				// Checking for "no content at all" option. In this case, leave the content-box empty.
				if ( 'thumbonly' != $this->widget_args['post_content_mode'] ) {
				
					// Opening container for Content Box.
					$srp_content .= '<div class="srp-content-box">';

					// Checking if "post title above thumb" option is on.
					if ( 'no' == $this->widget_args['post_title_above_thumb'] ) {

						// Generating the post title.
						$srp_content .= $this->generate_post_title( $recent_posts->post );
					}

					// Checking that at least one meta data is present.
					if ( 'yes' == $this->widget_args['post_date'] || 'yes' == $this->widget_args['post_author'] || 'yes' == $this->widget_args['post_category'] || 'yes' == $this->widget_args['post_comments'] ) {

						// Opening post meta container.
						$srp_content .= '<div class="srp-post-meta-container">';

						// Checking for the 'Display post date' option.
						if ( 'yes' == $this->widget_args['post_date'] ) {

							// Generating the post date.
							$srp_content .= $this->generate_post_date( $recent_posts->post );
						}

						// Checking for the 'Display post author' option.
						if ( 'yes' == $this->widget_args['post_author'] ) {
							
							// Generating the post author text/link.
							$srp_content .= $this->generate_post_author( $recent_posts->post );
						}
						
						// Checking for the 'Display post category' option.
						if ( 'yes' == $this->widget_args['post_category'] ) {
							
							// Generating the post categories text/link
							$srp_content .= $this->generate_post_categories( $recent_posts->post );
						}

						// Checking for the 'Display post comments' option.
						if ( 'yes' == $this->widget_args['post_comments'] ) {

							$srp_content .= $this->generate_post_comments( $recent_posts->post );
						}

						// Closing post meta container.
						$srp_content .= '</div>';
					}
					
					// Checking for the 'Post Content Display Mode' option.
					if ( 'titleexcerpt' == $this->widget_args['post_content_mode'] ) {
						
						// Generating the post content text.
						$srp_content .= $this->generate_post_excerpt( $recent_posts->post, $post_global_counter );
						
					}
					
					// Checking for the 'Display post tags' option.
					if ( 'yes' == $this->widget_args['post_tags'] ) {
						
						// Generating the post tags.
						$srp_content .= $this->generate_post_tags( $recent_posts->post );
					}
					
					// Closing the post content container.
					$srp_content .= '</div>';
					
				}

				// END Content Box.
				$srp_content .= '</div>';
				
				// Closing Single Post Container.
				$srp_content .= '</div>';
				
				// Checking for "multi column" layout mode.
				if ( 'multi_column' == $this->widget_args['layout_mode'] ) {

					// Let's do some math to calculate if this should be the last post of the column or not.
					if ( ( $post_colrow_counter == $this->widget_args['layout_num_cols'] ) || ( ( $post_colrow_counter < $this->widget_args['layout_num_cols'] ) && ( $post_global_counter == $recent_posts->post_count ) ) ) {
						
						// Closing column.
						$srp_content .= '</div>';
						
						// Resetting column counter.
						$post_colrow_counter = 0;
					}
				}
				
			endwhile; // EOF While Cycle.

			// Resetting $post data objects.
			wp_reset_postdata();
			wp_reset_query();
			
		}
		
		// Closing Widget Container.
		$srp_content .= '</div>';

		// Checking for the 'Pagination' option.
		if ( 'yes' == $this->widget_args['enable_pagination'] ) {
			
			// Generating pagination links.
			$srp_content .= $this->generate_pagination( $this->paged, $recent_posts );

		}
		
		// Closing Special Recent Post PRO Version comment.
		$srp_content .= '<!-- END Special Recent Posts PRO Edition v' . SRP_PLUGIN_VERSION . ' -->';

		// Checking if the 'External Shortcodes Compatibility' option is enabled.
		if ( 'yes' == $this->widget_args['ext_shortcodes_compatibility'] ) {

			// Executing external shortcodes before outputting the content.
			$srp_content = do_shortcode($srp_content);
		}

		// Checking if the 'WP Filters Enabled' option is enabled.
		if ( 'yes' == $this->widget_args['wp_filters_enabled'] ) {

			// Executing external shortcodes before outputting the content.
			$srp_content = apply_filters('the_content', $srp_content);
		}
		
		// Switching through display return mode.
		switch($return_mode) {
		
			// Displaying HTML on screen.
			case 'print':

				echo $srp_content;

			break;
			
			// Returning HTML.
			case 'return':

				return $srp_content;

			break;
		}
	}

	/**
	 * srp_create_tag()
	 *
	 * This method creates an HTML tag.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param string $tagname The HTML tag name.
	 * @param string $tag_content The HTML tag content
	 * @param array $tag_attrs The HTML tag content
	 * @return string It returns or prints the tag HTML.
	 */
	private function srp_create_tag( $tagname, $tag_content = NULL, $tag_attrs = NULL ) {
	
		// Defining DOM root.
		$tagdom = new DOMDocument( '1.0' );
		
		// Creating tag element.
		$tag = ( $tag_content ) ? $tagdom->createElement( $tagname, htmlentities( $tag_content, ENT_QUOTES, 'UTF-8' ) ) : $tagdom->createElement( $tagname );
	
		// Checking if attributes array is empty.
		if ( !empty( $tag_attrs ) && ( isset( $tag_attrs ) ) ) {
		
			// Looping through attributes.
			foreach ( $tag_attrs as $att_name => $att_value ) {
			
				// Setting attribute.
				$tag->setAttribute( $att_name, $att_value );
			}
			
			// If the tag is a link (<a>), do the "nofollow_links" optio check. If it's enables, add the nofollow attribute.
			if ( ( 'a' == $tagname ) && ( 'yes' == $this->widget_args['nofollow_links'] ) ) $tag->setAttribute( 'rel', 'nofollow' );

			// Checking if the "target='_blank'" option is enabled.
			if ( ( 'a' == $tagname ) && ( 'yes' == $this->widget_args['targetblank_links'] ) ) $tag->setAttribute( 'target', '_blank' );
		}
		
		// Appending created tag to DOM root.
		$tagdom->appendChild( $tag );
		
		// Saving HTML.
		$taghtml = trim( $tagdom->saveHTML() );

		// Cleaning DOM Root.
		unset( $tagdom );
		
		// Return the HTML tag.
		return htmlspecialchars_decode( $taghtml );
	}

	/**
	 * srp_sanitize()
	 *
	 * This method sanitizes strings output.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param string $string The string to sanitize.
	 * @return string It returns a sanitized string.
	 */
	private function srp_sanitize( $string ) {
		
		// Checking for External Shortcodes Compatibility option.
		// If it's enabled, let's not remove any shortcode found within the post content.
		if ( 'no' == $this->widget_args['ext_shortcodes_compatibility'] ) $string = strip_shortcodes( $string );

		// We need to remove all the exceeding stuff. Removing shortcodes and slashes.
		$temp_output = trim( html_entity_decode( stripslashes( $string ) ) );
		
		// Checking for the qTranslate filter.
		if ( function_exists( 'qtrans_useCurrentLanguageIfNotFoundShowAvailable' ) ) {

			// Applying qTranslate Filter if this exists.
			$temp_output = qtrans_useCurrentLanguageIfNotFoundShowAvailable( $temp_output );
		}
		
		// If the 'Post Allowed Tags' option is on, keep them separated from strip_tags.
		if ( !empty( $this->widget_args['allowed_tags'] ) ) {
		
			// Handling the <br /> tag.
			$this->widget_args['allowed_tags'] = str_replace( '<br />', '<br>', $this->widget_args['allowed_tags'] );
			
			// Stripping tags except the ones specified.
			return strip_tags( $temp_output, htmlspecialchars_decode( $this->widget_args['allowed_tags'] ) );
			
		} else {
		
			// Otherwise completely strip tags from text.
			return strip_tags( $temp_output );
		}
	}

	/**
	 * substr_words()
	 *
	 * This method uses the same logic of PHP function 'substr' but works with words instead of characters.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param string $string The string to search in
	 * @param int $n A counter.
	 * @param string $mode The search mode.
	 * @return mixed It could return the words count or the string found.
	 */
	private function substr_words( $str, $n, $mode = 'return' ) {

		// Counting words.
		$words_count = count( preg_split( '~[^\p{L}\p{N}\']+~u', $str ) );

		// Checking if max length is equal to original string length. In that case, return the string without making any 'cut'.
		if ( $words_count > $n ) {

			// Uses PHP 'count and preg_split' function to extract total words and put them into an array.
			$w = explode( ' ', $str );
			
			// Let's cut the array using our max length variable ($n).
			array_splice( $w, $n );
			
			// Switch mode.
			switch( $mode ) {
			
				case 'return':

					// Re-converting array to string and return.
					return implode( ' ', $w );

				break;
				
				case 'count':

					// Return count.
					return strlen( utf8_decode( implode( ' ', $w ) ) );

				break;
			}
			
		} else {
			
			// Switch mode.
			switch( $mode ) {

				case 'return':

					// Return string as it is, without making any 'cut'.
					return $str;

				break;
				
				case 'count':

					// Return count.
					// 
					return strlen( utf8_decode( $str ) );
				break;
			}
		}
	}

	/**
	 * srp_truncate_text()
	 *
	 * This method truncates a string preserving html tags integrity.
	 * Only works on characters. (Credits: http://jsfromhell.com)
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param string $text The text to be truncated.
	 * @param int $length The desired text length.
	 * @param string $suffix The possible string suffix.
	 * @param boolean $isHTML Checks whether the string is HTML or not.
	 * @return mixed It could return the words count or the string found.
	 * @see http://jsfromhell.com
	 */
	private function srp_truncate_text( $text, $length, $suffix = '', $isHTML = true ) {

		// Defining Internal counter.
		$i = 0;

		// Dafining array for tags collection.
		$tags = array();

		// Checking if "Allowed Tags" option is enabled.
		if ( !empty( $this->widget_args['allowed_tags'] ) ) {

			// Checking if source string is HTML.
			if( $isHTML ) { 

				// Regex to find tags.
				preg_match_all( '/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER );

				// Looping inside the string.
				foreach( $m as $o ){

					// Check if chars limit is equal or superior the string length.
					if( $o[0][1] - $i >= $length ) break;

					// Trimming the string.
					$t = mb_substr(strtok( $o[0][0], " \t\n\r\0\x0B>" ), 1 );

					// Repairing HTML tags.
					if ( $t[0] != '/' ) $tags[] = $t;
					elseif ( end( $tags ) == mb_substr( $t, 1 ) ) array_pop( $tags ); $i += $o[1][1] - $o[0][1];
				}
			}
		}

		// Composing Result String.
		$output = mb_substr( $text, 0, $length = min( strlen( $text ), $length + $i ) ) . ( count( $tags = array_reverse( $tags ) ) ? '' : '' );

		if ( strlen( $text ) > $length ) {
			$output = mb_substr( $output, -4, 4 ) == '' ? $output = mb_substr( $output, 0, ( strlen( $output) - 4 ) ) . $suffix . '' : $output .= $suffix;
		}

		// Returning Result String.
		return $output; 

	}

	// 
	/**
	 * themeblvd_time_ago()
	 *
	 * This method produces the magic "time ago" format on dates.
	 * Function by: Jason Bobich http://www.jasonbobich.com/
	 *
	 * @author Jason Bobich
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access private
	 * @param object $post The global WP post object.
	 * @return mixed It could return the words count or the string found.
	 * @see http://www.jasonbobich.com/
	 */
	private function themeblvd_time_ago( $post ) {
	 
		$date = get_post_time( 'G', true, $post );

		// Defining array of time chunks.
		$totalSeconds = array(
			array( 60 * 60 * 24 * 365),
			array( 60 * 60 * 24 * 30 ),
			array( 60 * 60 * 24 * 7 ),
			array( 60 * 60 * 24),
			array( 60 * 60 ),
			array( 60),
			array( 1 )
		);
	 
		if ( !is_numeric( $date ) ) {
			$time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
			$date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
			$date = gmmktime( (int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0] );
		}
	 
		$current_time = current_time( 'mysql', $gmt = 0 );
		$newer_date = strtotime( $current_time );
	 
		// Difference in seconds
		$since = $newer_date - $date;
	 
		// Something went wrong with date calculation and we ended up with a negative date.
		if ( 0 > $since )
			return __( 'date undefined', SRP_TRANSLATION_ID );
	 
		/**
		 * We only want to output one chunks of time here, eg:
		 * x years
		 * xx months
		 * so there's only one bit of calculation below:
		 */
	 
		//Step one: the first chunk
		for ( $i = 0, $j = count( $totalSeconds ); $i < $j; $i++ ) {
			$seconds = $totalSeconds[$i][0];
	 
			// Finding the biggest chunk (if the chunk fits, break)
			if ( ( $count = floor( $since / $seconds ) ) != 0 ) break;
		}

		$chunks = array(
			array( 60 * 60 * 24 * 365, _nx( '%s year', '%s years', $count, 'The form used for the Time Ago option. Example: post published 2 years ago.', SRP_TRANSLATION_ID ) ),
			array( 60 * 60 * 24 * 30,  _nx( '%s month', '%s months', $count, 'The form used for the Time Ago option. Example: post published 2 months ago.',SRP_TRANSLATION_ID ) ),
			array( 60 * 60 * 24 * 7,  _nx( '%s week', '%s weeks', $count, 'The form used for the Time Ago option. Example: post published 2 weeks ago.',SRP_TRANSLATION_ID ) ),
			array( 60 * 60 * 24, _nx( '%s day', '%s days', $count, 'The form used for the Time Ago option. Example: post published 2 days ago.',SRP_TRANSLATION_ID ) ),
			array( 60 * 60, _nx( '%s hour', '%s hours', $count, 'The form used for the Time Ago option. Example: post published 2 hours ago.',SRP_TRANSLATION_ID ) ),
			array( 60, _nx( '%s minute', '%s minutes', $count, 'The form used for the Time Ago option. Example: post published 2 minutes ago.',SRP_TRANSLATION_ID ) ),
			array( 1, _nx( '%s second', '%s seconds', $count, 'The form used for the Time Ago option. Example: post published 2 seconds ago.',SRP_TRANSLATION_ID ) )
		);
	 
		// Set output var
	 	$output = sprintf( $chunks[$i][1], $count );
	 	
		if ( !(int)trim( $output ) ){
			$output = '0 ' . _x( 'seconds', 'The plural form for zero seconds.', SRP_TRANSLATION_ID );
		}
	 
		$output .= _x(' ago', "The suffix for the form: 'Sometime ago'", SRP_TRANSLATION_ID);
	 	
	 	// Returning Output.
		return $output;
	}

} // END SpecialRecentPostsPro Class