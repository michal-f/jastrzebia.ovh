<?php
/**
 * The Special Recent Posts PRO Widget
 *
 * The SRP Widget Class extended from the default WP one.
 *
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 * @access public
 */
class WDG_SpecialRecentPostsPro extends WP_Widget {

	// Declaring global plugin values.
	private $plugin_args, $post_types, $inbuilt_post_types, $post_types_taxonomy_tree;

	/**
	 * __construct()
	 *
	 * The main SRP Widget Class constructor
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 * @return boolean true
	 */
	function __construct() {

		// Initializing parent constructor.
		parent::__construct(

			'wdg_specialrecentpostspro', // Base ID
			__( 'Special Recent Posts PRO', SRP_TRANSLATION_ID ),
			array(
				'description' => __( 'The Special Recent Posts PRO Edition widget. Drag to configure.', SRP_TRANSLATION_ID ),
				'classname'   => 'widget_specialrecentpostsPro'
			)
			
		);

		// Assigning global plugin option values to local variable.
		$this->plugin_args = get_option( 'srp_plugin_options' );

		// Returning true.
		return true;
	}

	/**
	 * form()
	 *
	 * This function builds the SRP widget instance
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 * @param  array $instance The current widget values set by the user.
	 * @return boolean true
	 */
	function form( $instance ) {

		// Outputs the options form on widget panel.
		$this->srp_build_widget_form( $instance );

		// Returning true.
		return true;
	}
	
	/**
	 * update()
	 *
	 * This function updates the current user values for this widget instance
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 * @global $srp_default_widget_values The global plugin values.
	 * @param  array $new_instance The new widget values to be saved.
	 * @param  array $old_instance The old widget values to be replaced.
	 * @return array $instance It returns the current widget instance
	 */
	function update( $new_instance, $old_instance ) {
	
		// Declaring global plugin values.
		global $srp_default_widget_values;

		// Processes widget options to be saved.
		$instance = SpecialRecentPostsPro::srp_version_map_check($old_instance);
		
		// Looping through the entire list of widget options values.
		foreach( $srp_default_widget_values as $k => $v ) {

			// Switching through each option.
			switch( $k ) {
				
				case "author_archive_link":
				case "author_autofilter":
				case "category_autofilter":
				case "category_autofilter_single":
				case "category_include_exclusive":
				case "category_title":
				case "date_timeago":
				case "enable_date_filter":
				case "enable_pagination":
				case "ext_shortcodes_compatibility":
				case "filter_sticky_posts_only":
				case "include_custom_taxonomy":
				case "nofollow_links":
				case "pagination_show_all":
				case "pagination_hide_prevnext":
				case "post_author":
				case "post_author_archive_link":
				case "post_author_url":
				case "post_category":
				case "post_category_link":
				case "post_comments":
				case "post_comments_show_num":
				case "post_current_hide":
				case "post_date":
				case "post_include_sub":
				case "post_link_excerpt":
				case "post_noimage_skip":
				case "post_random":
				case "post_tags":
				case "post_thumb_above_content":
				case "post_title_above_thumb":
				case "post_title_nolink":
				case "preserve_post_include_order":
				case "show_all_posts":
				case "show_sticky_posts":
				case "string_break_link":
				case "targetblank_links":
				case "thumbnail_link":
				case "vf_home":
				case "vf_allposts":
				case "vf_allpages":
				case "vf_everything":
				case "vf_allcategories":
				case "vf_allarchives":
				case "vf_disable":
				case "widget_title_hide":
				case "wp_filters_enabled":
				case "widget_title_show_default_wp":
					
					// Fixing all the NULL values coming from unchecked checkboxes.
					$instance[ $k ] = ( !isset($new_instance[$k]) ) ? 'no' : $new_instance[ $k ];

				break;
				
				case "thumbnail_height":
				case "thumbnail_width":
				
					// Checking if the new value is numeric. Then assign it.
					if ( is_numeric( $new_instance[ $k ] ) ) $instance[ $k ] = trim( $new_instance[ $k ] );

				break;
				
				case "layout_num_cols":
				case "post_content_length":
				case "pagination_mid_size":
				case "post_limit":
				case "post_title_length":
				
				
					// Checking if the new value is numeric and not zero. Then assign it.
					if ( ( is_numeric($new_instance[ $k ] ) ) && ( $new_instance[ $k ] != 0 ) ) $instance[ $k ] = trim( $new_instance[ $k ] );

				break;
				
				case "post_offset":
					
					// Checking if the new value is numeric and > zero. Then assign it.
					$instance[ $k ] = ( ( is_numeric( $new_instance[ $k ] ) ) && ( $new_instance[ $k ] > 0 ) ) ? trim( $new_instance[ $k ] ) : 0;

				break;

				case "date_filter_number":
				case "post_content_expand":
				case "style_font_size_post_title":
				case "style_font_size_widget_title":

					// Checking if the new value is numeric. Otherwise assign an empty string.
					$instance[ $k ] = ( is_numeric( $new_instance[ $k ] ) ) ? trim( $new_instance[ $k ] ) : '';

				break;
				
				case "shortcode_generator_area":
				case "phpcode_generator_area":
				
					// Deleting these values because they could get the whole plugin into trouble.
					unset( $new_instance[ $k ] );
					
				break;

				case "allowed_tags":

					// Default behaviour: for all other options, assign the new value.
					$instance[ $k ] = $new_instance[ $k ];

				break;

				default:
				
					// Escaping HTML characters with quotes.
					$instance[ $k ] = htmlspecialchars( $new_instance[ $k ], ENT_QUOTES );
					
				break;
			}
		}

		// Returning the new widget instance.
		return $instance;
	}
	
	/**
	 * widget()
	 *
	 * This is the main function that initializes the SRP rendering process.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 * @param  array $args The option values args.
	 * @param  array $instance The current widget instance.
	 * @return boolean true
	 */
	function widget( $args, $instance ) {
	
		// Checking Visualization Filter.
		if ( SpecialRecentPostsPro::visualization_check( $instance, 'widget' ) ) {
		
			// Extracting arguments.
			extract( $args, EXTR_SKIP );
			
			// Printing pre-widget stuff.
			echo $before_widget;

			// Checking for 'Use default Wordpress HTML layout for widget title' option value.
			if ( isset( $instance['widget_title_show_default_wp'] ) && 'yes' == $instance['widget_title_show_default_wp'] ) {

				// Checking that this option exists.
				if ( isset( $instance['widget_title_hide'] ) ) {

					// Fetching widget title.
					$widget_title = apply_filters( 'widget_title', $instance['widget_title'] );

					// Checking for "widget title hide" option.
					if ( 'yes' != $instance['widget_title_hide'] ) {

						// Printing default Widget Title HTML layout.
						echo $before_title . $widget_title . $after_title;
					}
				}
				
			}
			
			// Creating an instance of the Special Recent Posts Class.
			$srp = new SpecialRecentPostsPro( $instance, $this->id );
			
			// Checking that the $srp is a valid SRP class object.
			if ( is_object( $srp ) ) {

				// Displaying posts.
				$srp->display_posts( true, 'print' );
			}
			
			// Printing after widget stuff.
			echo $after_widget;
		}

		// Returning true.
		return true;
	}

	/**
	 * srp_generate_code()
	 *
	 * This is the main function that initializes the SRP rendering process.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 * @global $srp_default_widget_values The global widget presets.
	 * @param  array $instance The current widget instance.
	 * @param  string $code_mode The type of code to generate (shortcode or PHP code)
	 * @return string It could return the generated shortcode or PHP code.
	 */
	function srp_generate_code( $instance, $code_mode ) {

		// Switching between "shortcode" or "PHP code".
		switch( $code_mode ) {
		
			case "shortcode":
			
				// Defining global widget values.
				global $srp_default_widget_values;
				
				// Opening shortcode.
				$shortcode_code = "[srp";				
				
				// Looping through the list of available widget values.
				foreach( $instance as $key => $value ) {

					// Checking if the current set value is different than the default one.
					if ( isset( $srp_default_widget_values[ $key ] ) && ( $srp_default_widget_values[ $key ] != $value ) ) {

						// Checking if the value is an array. 
						if ( is_array( $value ) ) {

							// If it's so, then convert it into a string format.
							$value = implode( '%%%', $value );
						}

						// Escaping special symbols to avoid conflict with the original WP ones.
						$specialSymbols = array( '[', ']' );

						// Replacing special symbols.
						$value = str_replace( $specialSymbols, array( '&amp;#91;', '&amp;#93;' ), $value );

						// Apart from the 'Post Allowed Tags' option...
						if ( 'allowed_tags' != $key ) {

							// ...escape quite eveything.
							$value = str_replace( array( "'", "\"", "&quot;" ), "", htmlspecialchars( $value ) );
						}

						// Puttting the new key => value in the shortcode.
						$shortcode_code .= " " . $key . "=\"" . $value . "\"";
					}
				}
				
				// Closing shortcode.
				$shortcode_code .= "]";

				// Returning the shortcode.
				return $shortcode_code;
				
			break;
			
			case "php":
			
				// Defining global widget values.
				global $srp_default_widget_values;
				
				// Opening PHP code.
				$phpcode_code = "&lt;?php\n";
				
				// Building PHP $args.
				$phpcode_code .= "\$args = array(\n";		
				
				// Looping through list of available widget values.
				foreach( $instance as $key => $value ) {
				
					// Checking if the current set value is different than the default one.
					if ( isset( $srp_default_widget_values[ $key ] ) && ( $srp_default_widget_values[ $key ] != $value ) ) {

						// Checking if a value is an array.
						if ( is_array( $value ) ) {

							// If it's so, then convert it into a string format.
							$value = implode( '%%%', $value );
						}

						// Escaping special symbols to avoid conflict with the original WP ones.
						$specialSymbols = array( '[', ']' );

						// Replaceing special symbols.
						$value = str_replace( $specialSymbols, array( '&amp;#91;', '&amp;#93;' ), $value );

						// Apart from the 'Post Allowed Tags' option...
						if ( 'allowed_tags' != $key ) {

							// ...escape quite eveything.
							$value = str_replace( array( "'", "\"", "&quot;" ), "", htmlspecialchars( $value ) );
						}

						// Putting the new key => value in the PHP code.
						$phpcode_code .= "\"" . $key . "\" => \"" . $value . "\",";
					}
				}
				
				// Right trimming the last comma from the $args list.
				$phpcode_code = rtrim( $phpcode_code, ',' );
				
				// Closing PHP code.
				$phpcode_code .= ");\n";
				$phpcode_code .= "special_recent_posts( \$args );\n";
				$phpcode_code .= "?&gt;\n";
				
				// Returning PHP code.
				return $phpcode_code;

			break;
		}
	}
	
	/**
	 * srp_build_widget_form()
	 *
	 * This method build the widget layout.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 * @access public
	 * @global  $srp_default_widget_values The default widget presets.
	 * @param  array $instance The current widget instance.
	 * @return boolean true
	 */
	function srp_build_widget_form( $instance ) {
	
		// Loading default widget values.
		global $srp_default_widget_values;
		
		// Loading default plugin settings.
		$plugin_args = get_option( 'srp_plugin_options' );
		
		// Merging default values with instance array, in case this is empty.
		$instance = wp_parse_args( (array) SpecialRecentPostsPro::srp_version_map_check( $instance ), $srp_default_widget_values );

		// Declaring object container for custom post types and relative taxonomies.
		$this->post_types_taxonomy_tree = array();

		// Fetching post types.
		$this->post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'object', 'and' );

		// Checking that fetched post types array isn't empty.
		if ( !empty( $this->post_types ) ) {

			// Looping through post types to assign relative taxonomies.
			foreach ( $this->post_types as $post_type => $post_type_obj ) {
				
				// Preparing single post type tree.
				$post_type_tree = array(
					'slug'       => $post_type_obj->name,
					'name'       => $post_type_obj->label,
					'taxonomies' => array()
				);

				// Fetching single post type taxonomies list.
				$post_type_taxonomies = get_object_taxonomies( $post_type_obj->name, 'object' );

				// Looping through single post type taxonomies list.
				foreach ( $post_type_taxonomies as $taxonomy => $taxonomy_obj ) {

					// Fetching single taxonomy terms.
					$taxonomy_terms = get_terms( $taxonomy_obj->name );

					// Preparing single post type tree.
					$post_taxonomy_tree = array(
						'slug'       => $taxonomy_obj->name,
						'name'       => $taxonomy_obj->label,
						'terms'      => $taxonomy_terms
					);

					// Populating Taxonomies Tree.
					array_push( $post_type_tree['taxonomies'], $post_taxonomy_tree );
				}

				// Populating Taxonomies Tree.
				array_push( $this->post_types_taxonomy_tree, $post_type_tree );

			}
		}
?>
	
	<!-- BEGIN Widget Accordion -->
	<dl class="srp-wdg-accordion">

		<!-- BEGIN Widget Accordion Header -->
		<div class="srp-widget-header">

			<!-- BEGIN Widget Accordion Header Image -->
			<img src="<?php echo SRP_PLUGIN_URL . SRP_IMAGES_FOLDER; ?>widget-header-logo.png" alt="<?php esc_attr_e( 'The Special Recent Posts PRO logo', SRP_TRANSLATION_ID ); ?>"/>
			<!-- END Widget Accordion Header Image -->

			<!-- BEGIN Widget Accordion Header Title -->
			<?php _e( 'Widget Settings', SRP_TRANSLATION_ID ); ?>
			<!-- END Widget Accordion Header Title -->

		</div>
		<!-- END Widget Accordion Header -->

		<!-- BEGIN Basic Options Tab -->
		<dt class="srp-widget-optionlist-dt-basic">
			<a class="srp-wdg-accordion-item active" href="#1" title="<?php esc_attr_e('Basic Options', SRP_TRANSLATION_ID); ?>"><?php _e('Basic Options', SRP_TRANSLATION_ID); ?></a>
		</dt>
		<!-- END Basic Options Tab -->

		<!-- BEGIN Basic Options Content -->
		<dd class="srp-widget-optionlist-dd-basic">

			<!-- BEGIN Basic Options Content List -->
			<ul class="srp-widget-optionlist-basic srp-widget-optionlist">

				<!-- BEGIN Widget Title -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>" class="srp-widget-label">
						<?php _e( 'Widget Title', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Type in the widget title text.',SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php esc_html_e( $instance['widget_title'] ); ?>" size="30" class="fullwidth" />
					<!-- END Form Field -->

				</li>
				<!-- END Widget Title -->
				
				<!-- BEGIN Widget title link option. -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_title_link' ); ?>" class="srp-widget-label">
						<?php _e( 'Widget Title URL Link', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'If you want to link the widget title to a custom URL, please type it here. Leave blank for no linking.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'widget_title_link' ); ?>" name="<?php echo $this->get_field_name( 'widget_title_link' ); ?>" value="<?php esc_html_e( $instance['widget_title_link'] ); ?>" size="30" class="fullwidth" placeholder="<?php _e( 'Example: http://www.yoursite.com', SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form Field -->

				</li>
				<!-- END Widget title link option. -->

				<!-- BEGIN Post Type -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_type' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Type', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select what kind of post type to display.',SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" class="srp-widget-select">

						<option value="post" <?php selected( $instance['post_type'], 'post' ); ?>>
							<?php _e( 'Posts', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="page" <?php selected( $instance['post_type'], 'page' ); ?>>
							<?php _e( 'Pages', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="cpt" <?php selected( $instance['post_type'], 'cpt' ); ?>>
							<?php _e( 'Custom Post Type', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="revision" <?php selected( $instance['post_type'], 'revision' ); ?>>
							<?php _e( 'Revision', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="any" <?php selected( $instance['post_type'], 'any' ); ?>>
							<?php _e( 'Any Type', SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<!-- END Form Field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: If you choose 'Custom Post Type', you need to select which one to show in the 'Custom Post Types & Taxonomy' panel.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Post type display. -->
				
				<!-- BEGIN Post Limit -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_limit' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Limit', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter the maximum number of posts/pages to display.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_limit' ); ?>" name="<?php echo $this->get_field_name( 'post_limit' ); ?>" value="<?php echo stripslashes( $instance['post_limit'] ); ?>" size="2" />
					<!-- END Form Field -->

				</li>
				<!-- END Post Limit -->

				<!-- BEGIN Show All Posts -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_all_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_all_posts' ); ?>" value="yes" <?php checked( $instance['show_all_posts'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'show_all_posts' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Show All Posts/Pages', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Check this box if you want to show all of your blog's posts and pages. This option will override the 'Post Limit' option above.", SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: no pagination will be applied and if you have many entries, your website could be very slow.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Show All Posts -->

				<!-- BEGIN Show Sticky Posts -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'show_sticky_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_sticky_posts' ); ?>" value="yes" <?php checked( $instance['show_sticky_posts'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'show_sticky_posts' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Show Sticky Posts?', SRP_TRANSLATION_ID ); ?></label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to show sticky posts.',SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Show Sticky Posts -->
				
				<!-- BEGIN Visualization Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'visualization_filter' ); ?>" class="srp-widget-label">
						<?php _e( 'Visualization filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Choose where this widget should appear:',SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'vf_everything' ); ?>" name="<?php echo $this->get_field_name( 'vf_everything' ); ?>" value="yes" <?php checked( $instance['vf_everything'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'vf_everything' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Everywhere', SRP_TRANSLATION_ID ); ?></label><br />
					<!-- END Label -->
					
					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'vf_home' ); ?>" name="<?php echo $this->get_field_name( 'vf_home' ); ?>" value="yes" <?php checked( $instance['vf_home'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'vf_home' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Home Page', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'vf_allposts' ); ?>" name="<?php echo $this->get_field_name( 'vf_allposts' ); ?>" value="yes" <?php checked( $instance['vf_allposts'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'vf_allposts' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'All posts', SRP_TRANSLATION_ID ); ?></label><br />
					<!-- END Label -->
					
					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'vf_allpages' ); ?>" name="<?php echo $this->get_field_name( 'vf_allpages' ); ?>" value="yes" <?php checked( $instance['vf_allpages'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'vf_allpages' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'All pages', SRP_TRANSLATION_ID ); ?></label><br />
					<!-- END Label -->
					
					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'vf_allcategories' ); ?>" name="<?php echo $this->get_field_name( 'vf_allcategories' ); ?>" value="yes" <?php checked( $instance['vf_allcategories'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'vf_allcategories' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'All categories', SRP_TRANSLATION_ID ); ?></label><br />
					<!-- END Label -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'vf_allarchives' ); ?>" name="<?php echo $this->get_field_name( 'vf_allarchives' ); ?>" value="yes" <?php checked( $instance['vf_allarchives'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'vf_allarchives' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'All archives', SRP_TRANSLATION_ID ); ?></label><br />
					<!-- END Label -->

				</li>
				<!-- END Visualization Filter -->

				<!-- BEGIN Disable Visualization Filter -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'vf_disable' ); ?>" name="<?php echo $this->get_field_name( 'vf_disable' ); ?>" value="yes" <?php checked( $instance['vf_disable'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'vf_disable' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Disable Visualization Filter', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Check this box if you want to disable the Visualization Filter.", SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: If you're experiencing issues with other plugins which manage the appereance and position of your widgets, you might want to turn on this option.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Disable Visualization Filter -->

			</ul>
			<!-- END Basic Options Content List -->

		</dd>
		<!-- END Basic Options Content -->
		
		<!-- BEGIN Thumbnails Options Tab -->
		<dt class="srp-widget-optionlist-dt-thumbnails">
			<a class="srp-wdg-accordion-item" href="#2" title="<?php esc_attr_e( 'Thumbnails Options', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Thumbnails Options', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- BEGIN Thumbnails Options Tab -->

		<!-- BEGIN Thumbnails Option Content -->
		<dd class="srp-widget-optionlist-dd-thumbnails">

			<!-- BEGIN Thumbnails Option List -->
			<ul class="srp-widget-optionlist-thumbnails srp-widget-optionlist">
				
				<!-- BEGIN Display Thumbnail -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'display_thumbnail' ); ?>" class="srp-widget-label">
						<?php _e( 'Display Thumbnails?', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Choose whether thumbnails should be displayed or not.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- BEGIN Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'display_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'display_thumbnail' ); ?>" class="srp-widget-select">

						<option value="yes" <?php selected( $instance['display_thumbnail'], 'yes' ); ?>>
							<?php _e( 'Yes', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="no" <?php selected($instance['display_thumbnail'], 'no' ); ?>>
							<?php _e( 'No', SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Display Thumbnail -->

				<!-- BEGIN Thumbnail Type -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'thumbnail_type' ); ?>" class="srp-widget-label">
						<?php _e( 'Thumbnail Type', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select which kind of thumbnail should be displayed:',SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'thumbnail_type' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_type' ); ?>" class="srp-widget-select">

						<option value="thumb-post" <?php selected( $instance['thumbnail_type'], 'thumb-post' ); ?>>
							<?php _e( 'Custom Thumbnail Size (default)', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="thumb-wp" <?php selected( $instance['thumbnail_type'], 'thumb-wp' ); ?>>
							<?php _e( 'Wordpress Featured Image', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="thumb-author" <?php selected( $instance['thumbnail_type'], 'thumb-author' ); ?>>
							<?php _e( 'Author Avatar', SRP_TRANSLATION_ID); ?>
						</option>
						
					</select>
					<!-- END Form Field -->
				</li>
				<!-- END Thumbnail Type -->

				<!-- BEGIN Wordpress WP Thumbnail Size -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'wp_thumbnail_size' ); ?>" class="srp-widget-label">
						<?php _e( 'Select Wordpress WP Thumbnail Size', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "These are the registered thumbnail sizes for the current active Wordpress theme. Select which size you'd like to show:",SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'wp_thumbnail_size' ); ?>" name="<?php echo $this->get_field_name( 'wp_thumbnail_size' ); ?>" class="srp-widget-select">

						<option value="none" <?php selected( $instance['wp_thumbnail_size'], 'none' ); ?>>
							<?php _e( 'Choose available size:', SRP_TRANSLATION_ID ); ?>
						</option>
						<?php

						// Getting image sizes.
						$wp_thumbs_sizes = get_image_sizes();

						// Looping through each image size.
						foreach ( $wp_thumbs_sizes as $key => $value ) :
						?>
						<option value="<?php echo $key; ?>" <?php selected( $instance['wp_thumbnail_size'], $key ); ?>>
							<?php echo $key . ' ' . $value['width'] . ' x ' . $value['height']; ?>
						</option>

						<?php endforeach; ?>
					</select>
					<!-- END Form Field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: This option only works when the 'Thumbnail Type' option is set as 'Wordpress Featured Image'.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Wordpress WP Thumbnail Size -->

				<!-- BEGIN Link Author Avatar To The Author Post Archive -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'author_archive_link' ); ?>" name="<?php echo $this->get_field_name( 'author_archive_link' ); ?>" value="yes" <?php checked( $instance['author_archive_link'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'author_archive_link' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Link Author Avatar To The Author Post Archive?', SRP_TRANSLATION_ID ); ?></label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "If you chosen to display author avatars as thumbnails, check this box to link the author avatar image to the relative author post archive.", SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Link Author Avatar To The Author Post Archive -->
				
				<!-- BEGIN Custom Thumbnail Width. -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>" class="srp-widget-label">
						<?php _e( 'Custom Thumbnail Width', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter the custom thumbnail width in pixel:',SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'thumbnail_width' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_width' ); ?>" value="<?php echo $instance['thumbnail_width']; ?>" size="5" />px
					<!-- END Form Field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: This option only works when the 'Thumbnail Type' option is set as 'Custom Thumbnail Size' or 'Author Avatar'.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Custom Thumbnail Width. -->
				
				<!-- BEGIN Custom Thumbnail Height. -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'thumbnail_height' ); ?>" class="srp-widget-label">
						<?php _e( 'Custom Thumbnail Height', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter the custom thumbnail height in pixel:',SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'thumbnail_height' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_height' ); ?>" value="<?php echo $instance['thumbnail_height']; ?>" size="5" />px
					<!-- END Form Field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: This option only works when the 'Thumbnail Type' option is set as 'Custom Thumbnail Size' or 'Author Avatar'.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Custom Thumbnail Height. -->
				
				<!--BEGIN Link Thumbnail To Post -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'thumbnail_link' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_link' ); ?>" value="yes" <?php checked( $instance['thumbnail_link'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'thumbnail_link' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Link Thumbnail To Post', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to link the thumbnail to the related post/page.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!--END Link Thumbnail To Post -->
				
				<!--BEGIN Thumbnail Custom Field -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'thumbnail_custom_field' ); ?>" class="srp-widget-label">
						<?php _e( 'Thumbnail Custom Field', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "If you're using a custom field to specify the thumbnail image source, put its key name here.",SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'thumbnail_custom_field' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_custom_field' ); ?>" value="<?php esc_html_e( $instance['thumbnail_custom_field'] ); ?>" size="30" class="fullwidth" placeholder="<?php _e( 'Example: my-thumb-custom-field', SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form Field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: Provide an already resized image, because SRP won't process the image at all.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!--END Thumbnail Custom Field -->
				
				<!-- BEGIN Thumbnail Rotation -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'thumbnail_rotation' ); ?>" class="srp-widget-label">
						<?php _e( 'Thumbnail Rotation', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select the thumbnail rotation mode:',SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'thumbnail_rotation' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_rotation' ); ?>" class="srp-widget-select">

						<option value="no" <?php selected( $instance['thumbnail_rotation'], 'adaptive' ); ?>>
							<?php _e( 'No Rotation (default)', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="rotate-cw" <?php selected( $instance['thumbnail_rotation'], 'rotate-cw' ); ?>>
							<?php _ex( 'Rotate CW', "CW stands for 'Clockwise'.", SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="rotate-ccw" <?php selected( $instance['thumbnail_rotation'], 'rotate-ccw' ); ?>>
							<?php _ex( 'Rotate CCW', "CCW stands for 'Counterclockwise'.", SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Thumbnail Rotation -->

				<!-- BEGIN Thumbnail Index -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_thumbnail_index_basic' ); ?>" class="srp-widget-label">
						<?php _e( 'Select Thumbnail Index', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select the image index that should be picked up within the post content when no featured image is available.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_thumbnail_index_basic' ); ?>" name="<?php echo $this->get_field_name( 'post_thumbnail_index_basic' ); ?>" class="srp-widget-select">

						<option value="first" <?php selected( $instance['post_thumbnail_index_basic'], 'first'); ?>>
							<?php _e( 'First Image (default)', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="last" <?php selected( $instance['post_thumbnail_index_basic'], 'last'); ?>>
							<?php _e( 'Last Image', SRP_TRANSLATION_ID); ?>
						</option>

						<option value="random" <?php selected( $instance['post_thumbnail_index_basic'], 'random'); ?>>
							<?php _e( 'Random Image', SRP_TRANSLATION_ID ); ?>
						</option>

					</select><br />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Or enter a specific index number. (This will override the previous settings - Leave blank for no specific indexing).', SRP_TRANSLATION_ID); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_thumbnail_index_adv' ); ?>" name="<?php echo $this->get_field_name( 'post_thumbnail_index_adv' ); ?>" value="<?php esc_html_e( $instance['post_thumbnail_index_adv'] ); ?>" size="2" placeholder="<?php _e( 'Eg: 3', SRP_TRANSLATION_ID ); ?>" /><br />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Example: type 3 to fetch the third image in the post content.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: This option only works when the 'Thumbnail Type' option is set as 'Custom Thumbnail Size'.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- EOF Thumbnail index -->

			</ul>
			<!-- END Thumbnails Option List -->

		</dd>
		<!-- END Thumbnails Option Content -->
		
		<!-- BEGIN Post Options Tab -->
		<dt class="srp-widget-optionlist-dt-posts">
			<a class="srp-wdg-accordion-item" href="#3" title="<?php esc_attr_e( 'Post Options', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Post Options', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Post Options Tab -->

		<!-- BEGIN Post Options Content -->
		<dd class="srp-widget-optionlist-dd-posts">

			<!-- BEGIN Post Options List -->
			<ul class="srp-widget-optionlist-posts srp-widget-optionlist">
			
				<!-- BEGIN Post Title Length -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_title_length' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Title Length', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select how many characters or words every post title should be cut after:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- BEGIN Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_title_length' ); ?>" name="<?php echo $this->get_field_name( 'post_title_length' ); ?>" value="<?php esc_html_e( $instance["post_title_length"] ); ?>" size="4" style="float: left;" />
					<!-- END Form Field -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_title_length_mode' ); ?>" name="<?php echo $this->get_field_name( 'post_title_length_mode' ); ?>" class="srp-widget-select">

						<option value="words" <?php selected( $instance['post_title_length_mode'], 'words' ); ?>>
							<?php _e( 'Words', SRP_TRANSLATION_ID); ?>
						</option>

						<option value="chars" <?php selected( $instance['post_title_length_mode'], 'chars' ); ?>>
							<?php _e( 'Characters', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="fulltitle" <?php selected( $instance['post_title_length_mode'], 'fulltitle' ); ?>>
							<?php _e( 'Use Full Length (no cut)', SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Post Title Length -->
				
				<!-- BEGIN Post Content Type -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_content_type' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Content Type', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select if you wish to display the normal post content or the post excerpt:',SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_content_type' ); ?>" name="<?php echo $this->get_field_name( 'post_content_type' ); ?>" class="srp-widget-select">

						<option value="content" <?php selected( $instance['post_content_type'], 'content' ); ?>>
							<?php _e( 'Post Content', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="excerpt" <?php selected( $instance['post_content_type'], 'excerpt' ); ?>>
							<?php _e( 'Post Excerpt', SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Post Content Type -->
				
				<!-- BEGIN Post Content Length -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_content_length' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Content Length', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select how many characters or words every post content should be cut after:',SRP_TRANSLATION_ID ); ?>
					</small><br />			
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_content_length' ); ?>" name="<?php echo $this->get_field_name( 'post_content_length' ); ?>" value="<?php esc_html_e( $instance['post_content_length'] ); ?>" size="4" style="float: left;" />
					<!-- END Form Field -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_content_length_mode' ); ?>" name="<?php echo $this->get_field_name( 'post_content_length_mode' ); ?>" class="srp-widget-select">

						<option value="words" <?php selected( $instance['post_content_length_mode'], 'words' ); ?>>
							<?php _e( 'Words', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="chars" <?php selected( $instance['post_content_length_mode'], 'chars' ); ?>>
							<?php _e( 'Characters', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="fullcontent" <?php selected( $instance['post_content_length_mode'], 'fullcontent' ); ?>>
							<?php _e( 'Use full length (no cut)', SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Post Content Length -->
				
				<!-- BEGIN Posts/Pages Order -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_order' ); ?>" class="srp-widget-label">
						<?php _e( 'Posts/Pages Order', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Select the ascending or descending order of the 'Order By' parameter:",SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" class="srp-widget-select">

						<option value="DESC" <?php selected( $instance['post_order'], 'DESC' ); ?>>
							<?php _ex( 'DESC (default)', "DESC stands for 'Descending Order'", SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="ASC" <?php selected( $instance['post_order'], 'ASC' ); ?>>
							<?php _ex( 'ASC', "ASC stands for 'Ascending Order'", SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<br />
					<!-- END Form Field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e('ASC: ascending order from lowest to highest values (1, 2, 3; a, b, c).', SRP_TRANSLATION_ID); ?>
					<br />
						<?php _e('DESC: descending order from highest to lowest values (3, 2, 1; c, b, a).', SRP_TRANSLATION_ID); ?>
					<!-- END Notebox -->

				</li>
				<!-- END Posts/Pages Order -->

				<!-- BEGIN Posts/Pages Order By -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_order_by' ); ?>" class="srp-widget-label">
						<?php _e( 'Posts/Pages Order By:', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Sort retrieved posts by parameter. Defaults to 'date'.",SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- BEGIN Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_order_by' ); ?>" name="<?php echo $this->get_field_name( 'post_order_by' ); ?>" class="srp-widget-select">

						<option value="date" <?php selected( $instance['post_order_by'], 'date' ); ?>>
							<?php _e( 'Sort Posts by Date (default)', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="title" <?php selected( $instance['post_order_by'], 'title' ); ?>>
							<?php _e( 'Sort Posts by Title', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="modified" <?php selected( $instance['post_order_by'], 'modified' ); ?>>
							<?php _e( 'Sort Posts by Last Update', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="comment_count" <?php selected( $instance['post_order_by'], 'comment_count' ); ?>>
							<?php _e( 'Sort Posts by Comment Count', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="author" <?php selected( $instance['post_order_by'], 'author' ); ?>>
							<?php _e( 'Sort Posts by Author', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="parent" <?php selected( $instance['post_order_by'], 'parent' ); ?>>
							<?php _e( 'Sort Posts by Parent Post/Page ID', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="ID" <?php selected( $instance['post_order_by'], 'ID' ); ?>>
							<?php _e( 'Sort Posts by ID', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="none" <?php selected( $instance['post_order_by'], 'none' ); ?>>
							<?php _e( 'No Sorting', SRP_TRANSLATION_ID); ?>
						</option>

					</select>
					<!-- BEGIN Form Field -->

				</li>
				<!-- END Posts/Pages Order By -->
				
				<!-- BEGIN Disable Post Title Link -->
				<li>
					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_title_nolink' ); ?>" name="<?php echo $this->get_field_name( 'post_title_nolink' ); ?>" value="yes" <?php checked( $instance['post_title_nolink'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_title_nolink' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Disable Post Title Link', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to unlink the post titles from the related post/page.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Disable Post Title Link -->
				
				<!-- BEGIN Enable Random Mode -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_random' ); ?>" name="<?php echo $this->get_field_name( 'post_random' ); ?>" value="yes" <?php checked( $instance['post_random'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_random' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Enable Random Mode', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to randomize the posts order.',SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Enable Random Mode -->
				
				<!-- BEGIN Skip Posts Without Featured Image -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_noimage_skip' ); ?>" name="<?php echo $this->get_field_name( 'post_noimage_skip' ); ?>" value="yes" <?php checked( $instance['post_noimage_skip'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_noimage_skip' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Skip Posts Without Featured Image', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to skip the posts with no assigned featured image.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Skip Posts Without Featured Image -->
				
				<!-- BEGIN Post Content Link -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_link_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'post_link_excerpt' ); ?>" value="yes" <?php checked( $instance['post_link_excerpt'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_link_excerpt' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Post Content Link', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to link the entire post content to the related post/page.',SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- BEGIN Post Content Link -->

				<!-- BEGIN Enable External Shortcodes Compatibility -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'ext_shortcodes_compatibility' ); ?>" name="<?php echo $this->get_field_name( 'ext_shortcodes_compatibility' ); ?>" value="yes" <?php checked( $instance['ext_shortcodes_compatibility'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'ext_shortcodes_compatibility' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Enable External Shortcodes Compatibility', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want SRP to let other plugins shortcodes to work within the post content.',SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Enable External Shortcodes Compatibility -->

				<!-- BEGIN Enable Wordpress Filters -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'wp_filters_enabled' ); ?>" name="<?php echo $this->get_field_name( 'wp_filters_enabled' ); ?>" value="yes" <?php checked( $instance['wp_filters_enabled'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'wp_filters_enabled' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Enable Wordpress Filters', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want SRP to apply WP filters before outputting the post content.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Enable Wordpress Filters -->

			</ul>
			<!-- END Post Options List -->

		</dd>
		<!-- END Post Options Content -->
		
		<!-- BEGIN Advanced post options 1 Tab -->
		<dt class="srp-widget-optionlist-dt-advposts">
			<a class="srp-wdg-accordion-item" href="#4" title="<?php esc_attr_e( 'Advanced Post Options 1', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Advanced Post Options 1', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Advanced Post Options 1 Tab -->

		<!-- BEGIN Advanced Post Options 1 Content -->
		<dd class="srp-widget-optionlist-dd-advposts">

			<!-- BEGIN Advanced Post Options 1 List -->
			<ul class="srp-widget-optionlist-advposts srp-widget-optionlist">

				<!-- BEGIN No Posts Default Text -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'noposts_text' ); ?>" class="srp-widget-label">
						<?php _e( 'No Posts Default Text', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Type in the default text to display when there are no posts available:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'noposts_text' ); ?>" name="<?php echo $this->get_field_name( 'noposts_text' ); ?>" value="<?php echo ( !empty( $instance['noposts_text'] ) ) ? stripslashes( $instance['noposts_text'] ) : __( 'No posts available', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth" />
					<!-- END Form Field -->

				</li>
				<!-- END No Posts Default Text -->

				<!-- BEGIN No Posts Text Link -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'noposts_link' ); ?>" class="srp-widget-label">
						<?php _e( 'No Posts Text Link', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Type in the URL you'd like to link the 'No posts available' text to:", SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'noposts_link' ); ?>" name="<?php echo $this->get_field_name( 'noposts_link' ); ?>" value="<?php echo stripslashes( $instance['noposts_link'] ); ?>" size="30" class="fullwidth" placeholder="<?php _e( "Example: http://www.mynopostspage.com", SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form Field -->

				</li>
				<!-- END No Posts Text Link -->
				
				<!-- BEGIN Hide Current Viewed Post -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_current_hide' ); ?>" name="<?php echo $this->get_field_name( 'post_current_hide' ); ?>" value="yes" <?php checked( $instance['post_current_hide'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_current_hide' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Hide Current Viewed Post', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Check this box if you want to hide the current viewed post/page. Useful when SRP is on a sidebar and you're on a single post page.", SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Hide Current Viewed Post -->
				
				<!-- BEGIN Post Offset -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_offset' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Offset', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter the number of post/pages to skip from the beginning:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_offset' ); ?>" name="<?php echo $this->get_field_name( 'post_offset' ); ?>" value="<?php echo stripslashes( $instance['post_offset'] ); ?>" size="2" />
					<!-- END Form Field -->

				</li>
				<!-- END Post Offset -->
				
				<!-- BEGIN Title String Break -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'title_string_break' ); ?>" class="srp-widget-label">
						<?php _e( 'Title String Break', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter the text to be displayed as string break just after the end of the post/page title:', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'title_string_break' ); ?>" name="<?php echo $this->get_field_name( 'title_string_break' ); ?>" value="<?php echo stripslashes( $instance['title_string_break'] ); ?>" size="30" class="fullwidth" />
					<!-- END Form Field -->

				</li>
				<!-- END Title String Break -->
				
				<!-- BEGIN Post String Break -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'string_break' ); ?>" class="srp-widget-label">
						<?php _e( 'Post String Break', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter the text to be displayed as string break just after the end of the post/page content:', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'string_break' ); ?>" name="<?php echo $this->get_field_name( 'string_break' ); ?>" value="<?php echo stripslashes( $instance['string_break'] ); ?>" size="30" class="fullwidth" />
					<!-- END Form Field -->

				</li>
				<!-- END Post String Break -->
				
				<!-- BEGIN Image String Break -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'image_string_break' ); ?>" class="srp-widget-label">
						<?php _e( 'Image String Break', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter the absolute URL of a custom image to use as a string break:', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'image_string_break' ); ?>" name="<?php echo $this->get_field_name( 'image_string_break' ); ?>" value="<?php echo stripslashes( $instance['image_string_break'] ); ?>" size="30" class="fullwidth" placeholder="<?php _e( "Example: http://www.test.com/myabsoluteimage.jpg", SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form Field -->

				</li>
				<!-- END Image String Break -->

				<!-- BEGIN Link String/Image Break To Post -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'string_break_link' ); ?>" name="<?php echo $this->get_field_name( 'string_break_link' ); ?>" value="yes" <?php checked( $instance['string_break_link'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'string_break_link' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Link String/Image Break To Post?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to link the string/image break to the related post/page.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Link String/Image Break To Post -->
				
				<!-- BEGIN Post Allowed Tags -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'allowed_tags' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Allowed Tags', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a list of allowed HTML tags to be rendered in the post content. Leave blank for clean text without any markup.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'allowed_tags' ); ?>" name="<?php echo $this->get_field_name( 'allowed_tags' ); ?>" value="<?php echo stripslashes( $instance['allowed_tags'] ); ?>" size="30" class="fullwidth" />
					<!-- END Form Field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php echo esc_html( __( 'NOTE: When using this option, type in your tags in the following form: <a><span><p>', SRP_TRANSLATION_ID ) );?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Post Allowed Tags -->

			</ul>
			<!-- END Advanced post options 1 List -->

		</dd>
		<!-- END Advanced Post Options 1 Content -->

		<!-- BEGIN Advanced Post Options 2 Tab -->
		<dt class="srp-widget-optionlist-dt-advposts">
			<a class="srp-wdg-accordion-item" href="#5" title="<?php esc_attr_e( 'Advanced Post Options 2', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Advanced Post Options 2', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Advanced Post Options 2 Tab -->

		<!-- BEGIN Advanced Post Options 2 Content -->
		<dd class="srp-widget-optionlist-dd-advposts">

			<!-- BEGIN Advanced Post Options 2 List -->
			<ul class="srp-widget-optionlist-advposts srp-widget-optionlist">

				<!-- BEGIN Add 'rel=nofollow' Attribute On Links -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'nofollow_links' ); ?>" name="<?php echo $this->get_field_name( 'nofollow_links' ); ?>" value="yes" <?php checked( $instance['nofollow_links'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'nofollow_links' ); ?>" class="srp-widget-label-inline">
						<?php _e( "Add 'rel=nofollow' Attribute On Links?", SRP_TRANSLATION_ID ); ?></label><br />
					<!-- BEGIN Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Check this box if you want to use the 'rel=nofollow' attribute on every post/page link.", SRP_TRANSLATION_ID ); ?>
						<a href="http://en.wikipedia.org/wiki/Nofollow" title="nofollow" target="_blank">
							<?php _e( 'Learn more', SRP_TRANSLATION_ID); ?>
						</a>
					</small>
					<!-- BEGIN Description -->
					
				</li>
				<!-- END Add 'rel=nofollow' Attribute On Links -->

				<!-- BEGIN Open Widget Links In A New Window -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'targetblank_links' ); ?>" name="<?php echo $this->get_field_name( 'targetblank_links' ); ?>" value="yes" <?php checked( $instance['targetblank_links'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'targetblank_links' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Open Widget Links In A New Window?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want all links to be opened in a new browser window.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Open Widget Links In A New Window -->

				<!-- BEGIN Post Meta -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'meta_data' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Meta', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- BEGIN Label -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_author' ); ?>" name="<?php echo $this->get_field_name( 'post_author' ); ?>" value="yes" <?php checked( $instance['post_author'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Display post author', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_category' ); ?>" name="<?php echo $this->get_field_name( 'post_category' ); ?>" value="yes" <?php checked( $instance['post_category'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Display post category', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- ESC Description -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_date' ); ?>" name="<?php echo $this->get_field_name( 'post_date' ); ?>" value="yes" <?php checked( $instance['post_date'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Display post date', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_tags' ); ?>" name="<?php echo $this->get_field_name( 'post_tags' ); ?>" value="yes" <?php checked( $instance['post_tags'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Display post tags', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_comments' ); ?>" name="<?php echo $this->get_field_name( 'post_comments' ); ?>" value="yes" <?php checked( $instance['post_comments'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Display post comments', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_comments_show_num' ); ?>" name="<?php echo $this->get_field_name( 'post_comments_show_num' ); ?>" value="yes" <?php checked( $instance['post_comments_show_num'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Display post comments number', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Post Meta -->
				
				<!-- BEGIN Post Meta Advanced Options -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'meta_data_extra' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Meta Advanced Options', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->
					
					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_author_url' ); ?>" name="<?php echo $this->get_field_name( 'post_author_url' ); ?>" value="yes" <?php checked( $instance['post_author_url'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enable post author URL link', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_author_archive_link' ); ?>" name="<?php echo $this->get_field_name( 'post_author_archive_link' ); ?>" value="yes" <?php checked( $instance['post_author_archive_link'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e(' Enable post author archive link. (overrides the post author URL link)', SRP_TRANSLATION_ID); ?>
					</small><br />
					<!-- END Description -->
					
					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_category_link' ); ?>" name="<?php echo $this->get_field_name( 'post_category_link' ); ?>" value="yes" <?php checked($instance["post_category_link"], 'yes'); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enable post category link', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Post date prefix:', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_date_prefix' ); ?>" name="<?php echo $this->get_field_name( 'post_date_prefix' ); ?>" value="<?php echo stripslashes( $instance['post_date_prefix'] ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Post author prefix:', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_author_prefix' ); ?>" name="<?php echo $this->get_field_name( 'post_author_prefix' ); ?>" value="<?php echo ( !empty( $instance['post_author_prefix'] ) ) ? stripslashes( $instance['post_author_prefix'] ) : __( 'Published by:', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Post category prefix:', SRP_TRANSLATION_ID); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_category_prefix' ); ?>" name="<?php echo $this->get_field_name( 'post_category_prefix' ); ?>" value="<?php echo ( !empty( $instance['post_category_prefix'] ) ) ? stripslashes( $instance['post_category_prefix'] ) : __( 'Category:', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Post tags prefix:', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_tags_prefix' ); ?>" name="<?php echo $this->get_field_name( 'post_tags_prefix' ); ?>" value="<?php echo ( !empty( $instance['post_tags_prefix'] ) ) ? stripslashes( $instance['post_tags_prefix'] ): __( 'Tags:', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e(' Category names separator:', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_category_separator' ); ?>" name="<?php echo $this->get_field_name( 'post_category_separator' ); ?>" value="<?php echo stripslashes( $instance['post_category_separator'] ); ?>" size="10" />
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e(' Tags names separator:', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_tags_separator' ); ?>" name="<?php echo $this->get_field_name( 'post_tags_separator' ); ?>" value="<?php echo stripslashes( $instance['post_tags_separator'] ); ?>" size="10" />
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Default text for comments link:', SRP_TRANSLATION_ID); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_default_comments_string' ); ?>" name="<?php echo $this->get_field_name( 'post_default_comments_string' ); ?>" value="<?php echo ( !empty( $instance['post_default_comments_string'] ) ) ? stripslashes( $instance['post_default_comments_string'] ) : __( 'Comments', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e(' Text for no comments:', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_no_comments_string' ); ?>" name="<?php echo $this->get_field_name( 'post_no_comments_string' ); ?>" value="<?php echo ( !empty( $instance['post_no_comments_string'] ) ) ? stripslashes( $instance['post_no_comments_string'] ) : __( 'No Comments', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Text for 1 comment: (in numeric mode)', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_single_comments_string' ); ?>" name="<?php echo $this->get_field_name( 'post_single_comments_string' ); ?>" value="<?php echo ( !empty( $instance['post_single_comments_string'] ) ) ? stripslashes( $instance['post_single_comments_string'] ) : __( '1 Comment', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

					<!-- BEGIN Option Box -->
					<div class="srp-advpost2-optionbox">

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Text for multiple comments: (in numeric mode)', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<input type="text" id="<?php echo $this->get_field_id( 'post_multiple_comments_string' ); ?>" name="<?php echo $this->get_field_name( 'post_multiple_comments_string' ); ?>" value="<?php echo ( !empty( $instance['post_multiple_comments_string'] ) ) ? stripslashes( $instance['post_multiple_comments_string'] ) : __( 'Comments', SRP_TRANSLATION_ID ); ?>" size="30" class="fullwidth"/>
						<!-- END Form Field -->

					</div>
					<!-- END Option Box -->

				</li>
				<!-- END Post Meta Advanced Options -->
				
				<!-- BEGIN Post Date Format -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'date_format' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Date Format (*)', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- BEGIN Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Type in the coded format of post dates:', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'date_format' ); ?>" name="<?php echo $this->get_field_name( 'date_format' ); ?>" value="<?php echo stripslashes( $instance['date_format'] ); ?>" size="30" class="fullwidth" /><br />
					<!-- END Form Field -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( '*(F = Month name | j = Day of the month | S = ordinal suffix for the day of the month | Y = Year)', SRP_TRANSLATION_ID ); ?>
					</small>
					<br />
					<small>
						<a href="http://php.net/manual/en/function.date.php" title="Date formatting" target="_blank">
							<?php _e( 'Learn more about date formatting', SRP_TRANSLATION_ID ); ?>
						</a>
					</small>
					<!-- END Description -->
				</li>
				<!-- END Post Date Format -->

				<li>
					<input type="checkbox" id="<?php echo $this->get_field_id('date_timeago'); ?>" name="<?php echo $this->get_field_name('date_timeago'); ?>" value="yes" <?php checked($instance["date_timeago"], 'yes'); ?> />
					<label for="<?php echo $this->get_field_id('date_timeago'); ?>" class="srp-widget-label-inline"><?php _e("Use the 'Time Ago' mode", SRP_TRANSLATION_ID); ?></label><br />
					<small>
						<?php _e("Check this box if you want to display the post date like in the following form: 'This post was published 2 days ago'.", SRP_TRANSLATION_ID); ?>
					</small>
				</li>
				<!-- EOF Date content option. -->

			</ul>
			<!-- END Advanced Post Options 2 List -->

		</dd>
		<!-- END Advanced Post Options 2 Content -->

		<!-- BEGIN Filtering Options 1 Tab -->
		<dt class="srp-widget-optionlist-dt-filtering">
			<a class="srp-wdg-accordion-item" href="#6" title="<?php esc_attr_e( 'Filtering Options 1', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Filtering Options 1', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Filtering Options 1 Tab -->

		<!-- BEGIN Filtering Options 1 Content -->
		<dd class="srp-widget-optionlist-dd-filtering">

			<!-- BEGIN Filtering Options 1 List -->
			<ul class="srp-widget-optionlist-filtering srp-widget-optionlist">
				
				<!-- BEGIN Enable Auto Category Filtering -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'category_autofilter' ); ?>" name="<?php echo $this->get_field_name( 'category_autofilter' ); ?>" value="yes" <?php checked( $instance['category_autofilter'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'category_autofilter' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Enable Auto Category Filtering', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to automatically change the displayed posts according to the current viewed category archive.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( 'NOTE: This option will override any previously applied category filter.', SRP_TRANSLATION_ID );?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Enable Auto Category Filtering -->

				<!-- BEGIN Enable Auto Category Filtering On Single Posts/Pages -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'category_autofilter_single' ); ?>" name="<?php echo $this->get_field_name( 'category_autofilter_single' ); ?>" value="yes" <?php checked( $instance['category_autofilter_single'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'category_autofilter_single' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Enable Auto Category Filtering On Single Posts/Pages', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to automatically change the displayed posts according to the category of the current viewed post.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( 'NOTE: This option will override any previously applied category filter.', SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- BEGIN Enable Auto Category Filtering On Single Posts/Pages -->

				<!-- BEGIN Auto Author Filtering -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'author_autofilter' ); ?>" name="<?php echo $this->get_field_name( 'author_autofilter' ); ?>" value="yes" <?php checked( $instance['author_autofilter'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'author_autofilter' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Enable Auto Author Filtering?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to automatically change the displayed posts according to the author of the current viewed post.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( 'NOTE: This option will override any previously applied category filter.', SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Auto Author Filtering -->

				<!-- BEGIN Post Status Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_status' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Status Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select how to filter displayed posts/pages based on their status:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<select id="<?php echo $this->get_field_id( 'post_status' ); ?>" name="<?php echo $this->get_field_name( 'post_status' ); ?>" class="srp-widget-select">

						<option value="publish" <?php selected( $instance['post_status'], 'publish' ); ?>>
							<?php _e( 'Published (default)', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="private" <?php selected( $instance['post_status'], 'private' ); ?>>
							<?php _e( 'Private', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="inherit" <?php selected( $instance['post_status'], 'inherit' ); ?>>
							<?php _e( 'Inherit', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="pending" <?php selected( $instance['post_status'], 'pending' ); ?>>
							<?php _e( 'Pending', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="future" <?php selected( $instance['post_status'], 'future' ); ?>>
							<?php _e( 'Future', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="draft" <?php selected( $instance['post_status'], 'draft' ); ?>>
							<?php _e( 'Draft', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="auto-draft" <?php selected( $instance['post_status'], 'auto-draft' ); ?>>
							<?php _e( 'Auto Draft', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="trash" <?php selected( $instance['post_status'], 'trash' ); ?>>
							<?php _e( 'Trash', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="any" <?php selected( $instance['post_status'], 'any' ); ?>>
							<?php _e( 'Any Status', SRP_TRANSLATION_ID ); ?>
						</option>
						
					</select>
					<!-- END Form field -->

				</li>
				<!-- END Post Status Filter -->
				
				<!-- BEGIN Category Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'category_include' ); ?>" class="srp-widget-label">
						<?php _e( 'Category Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a comma separated list of numeric categories IDs to filter posts by. Leave blank for no specific filtering.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'category_include' ); ?>" name="<?php echo $this->get_field_name( 'category_include' ); ?>" value="<?php esc_html_e( $instance['category_include'] ); ?>" class="fullwidth" placeholder="<?php _e( "Example: 2, 7, 23", SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form field -->

				</li>
				<!-- END Category Filter -->

				<!-- BEGIN Exclusive Category Filter -->
				<li>

					<!-- BEGIN Form field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'category_include_exclusive' ); ?>" name="<?php echo $this->get_field_name( 'category_include_exclusive' ); ?>" value="yes" <?php checked( $instance['category_include_exclusive'], 'yes' ); ?> />
					<!-- END Form field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'category_include_exclusive' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Exclusive Category Filter', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Check this box if you want to display posts that belong exclusively to all the categories they're filtered by (two or more).", SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Exclusive Category Filter -->
				
				<!-- BEGIN Exclude Categories by IDs -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'category_exclude' ); ?>" class="srp-widget-label">
						<?php _e( 'Exclude Categories by IDs', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- BEGIN Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a comma separated list of numeric categories IDs to exclude. Leave blank for no specific exclusion.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'category_exclude' ); ?>" name="<?php echo $this->get_field_name( 'category_exclude' ); ?>" value="<?php esc_html_e( $instance['category_exclude'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: 3, 5, 32', SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form field -->
				</li>
				<!-- END Exclude Categories by IDs -->
				
				<!-- BEGIN Use Category Name As Widget Title -->
				<li>

					<!-- BEGIN Form field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'category_title' ); ?>" name="<?php echo $this->get_field_name( 'category_title' ); ?>" value="yes" <?php checked( $instance['category_title'], 'yes' ); ?> />
					<!-- END Form field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'category_title' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Use Category Name As Widget Title?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to use the category name as the widget title when a category filter is on.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "In case of multiple categories, SRP will pull out the first category ID title in the list above.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Use Category Name As Widget Title -->

				<!-- BEGIN Search Text Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_search_filter' ); ?>" class="srp-widget-label">
						<?php _e( 'Search Text Filter', SRP_TRANSLATION_ID); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a text string to filter posts by (without quotes).', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_search_filter' ); ?>" name="<?php echo $this->get_field_name( 'post_search_filter' ); ?>" value="<?php esc_html_e( $instance['post_search_filter'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: My Search Text', SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form field -->

				</li>
				<!-- END Search Text Filter -->

				<!-- BEGIN Show Sticky Posts Only -->
				<li>

					<!-- BEGIN Form field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'filter_sticky_posts_only' ); ?>" name="<?php echo $this->get_field_name( 'filter_sticky_posts_only' ); ?>" value="yes" <?php checked( $instance['filter_sticky_posts_only'], 'yes' ); ?> />
					<!-- END Form field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'filter_sticky_posts_only' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Show Sticky Posts Only?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to display sticky posts only.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Show Sticky Posts Only -->

			</ul>
			<!-- END Filtering Options 1 List -->

		</dd>
		<!-- END Filtering Options 1 Content -->

		<!-- BEGIN Filtering Options 2 Tab -->
		<dt class="srp-widget-optionlist-dt-filtering">
			<a class="srp-wdg-accordion-item" href="#7" title="<?php esc_attr_e( 'Filtering Options 2', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Filtering Options 2', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Filtering Options 2 Tab -->

		<!-- BEGIN Filtering Options 2 Content -->
		<dd class="srp-widget-optionlist-dd-filtering">

			<!-- BEGIN Filtering Options 2 List -->
			<ul class="srp-widget-optionlist-filtering srp-widget-optionlist">

				<!-- BEGIN Posts/Page ID Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_include' ); ?>" class="srp-widget-label">
						<?php _e( 'Posts/Page ID Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a comma separated list of numeric posts/pages IDs to filter by. Leave blank for no specific filtering.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_include' ); ?>" name="<?php echo $this->get_field_name( 'post_include' ); ?>" value="<?php esc_html_e( $instance['post_include'] ); ?>" class="fullwidth" placeholder="<?php _e( "Example: 5, 7, 23", SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form field -->

				</li>
				<!-- END Posts/Page ID Filter -->

				<!-- BEGIN Preserve Posts/Page ID Filter Order -->
				<li>

					<!-- BEGIN Form field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'preserve_post_include_order' ); ?>" name="<?php echo $this->get_field_name( 'preserve_post_include_order' ); ?>" value="yes" <?php checked( $instance['preserve_post_include_order'], 'yes' ); ?> />
					<!-- END Form field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'preserve_post_include_order' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Preserve Posts/Page ID Filter Order?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- BEGIN Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Check this box if you want to display posts in the order they're filtered by in the Posts/Page ID Filter.", SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Preserve Posts/Page ID Filter Order -->

				<!-- BEGIN Include Subpages -->
				<li>

					<!-- BEGIN Form field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_include_sub' ); ?>" name="<?php echo $this->get_field_name( 'post_include_sub' ); ?>" value="yes" <?php checked( $instance['post_include_sub'], 'yes' ); ?> />
					<!-- END Form field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_include_sub' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Include Subpages', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to include available subpages/subposts when filtering by the Posts/Page ID Filter.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Include Subpages -->
				
				<!-- BEGIN Exclude Posts/Pages By IDs -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_exclude' ); ?>" class="srp-widget-label">
						<?php _e( 'Exclude Posts/Pages By IDs', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a comma separated list of numeric posts/pages IDs to exclude. Leave blank for no exclusion.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_exclude' ); ?>" name="<?php echo $this->get_field_name( 'post_exclude' ); ?>" value="<?php esc_html_e( $instance['post_exclude'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: 6, 14, 45', SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form field -->

				</li>
				<!-- END Exclude Posts/Pages By IDs -->

				<!-- BEGIN Author Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'author_include' ); ?>" class="srp-widget-label">
						<?php _e( 'Author Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a comma separated list of author IDs to filter posts by. To exclude posts by author, just prefix a -(minus) sign before the author ID. Example: 2,3,-5 | Leave blank for no filtering.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'author_include' ); ?>" name="<?php echo $this->get_field_name( 'author_include' ); ?>" value="<?php esc_html_e( $instance['author_include'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: 8, -15, 35', SRP_TRANSLATION_ID ); ?>"/>
					<!-- END Form field -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: This will override the 'Enable Auto Author Filtering' option.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Author Filter -->
				
				<!-- BEGIN Tag Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'tags_include' ); ?>" class="srp-widget-label">
						<?php _e( 'Tag Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a comma separated list of tag slugs to filter posts by. Leave blank for no filtering.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'tags_include' ); ?>" name="<?php echo $this->get_field_name( 'tags_include' ); ?>" value="<?php esc_html_e( $instance['tags_include'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: tag1, tag2, tag3', SRP_TRANSLATION_ID ); ?>"/>
					<!-- END Form field -->

				</li>
				<!-- END Tag Filter -->
				
				<!-- BEGIN Custom field post filtering. -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_meta_key' ); ?>" class="srp-widget-label">
						<?php _e( 'Custom Field Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Type in the meta key or meta value you wish to filter posts by.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<strong><?php _e( 'Meta Key', SRP_TRANSLATION_ID ); ?></strong><br />
					<!-- END Description -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_meta_key' ); ?>" name="<?php echo $this->get_field_name( 'post_meta_key' ); ?>" value="<?php echo stripslashes( $instance['post_meta_key'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: My Meta Key', SRP_TRANSLATION_ID ); ?>" /><br />
					<!-- END Form field -->

					<!-- BEGIN Label -->
					<strong><?php _e( 'Meta Value', SRP_TRANSLATION_ID ); ?></strong><br />
					<!-- END Label -->

					<!-- BEGIN Form field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_meta_value' ); ?>" name="<?php echo $this->get_field_name( 'post_meta_value' ); ?>" value="<?php echo stripslashes( $instance['post_meta_value'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: My Meta Value', SRP_TRANSLATION_ID ); ?>"/>
					<!-- END Form field -->

				</li>
				<!-- END Custom field post filtering. -->

				<!-- BEGIN Date Filter -->
				<li>

					<!-- BEGIN Form field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'enable_date_filter' ); ?>" name="<?php echo $this->get_field_name( 'enable_date_filter' ); ?>" value="yes" <?php checked( $instance['enable_date_filter'], 'yes' ); ?> />
					<!-- END Form field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'enable_date_filter' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Date Filter', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
					<?php _e( 'Check this box if you want to display only posts from a specific date on. Select the date in the field here below:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Description -->
					<small>
						<strong><?php _e( 'Show Posts In The Last:', SRP_TRANSLATION_ID ); ?></strong>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'date_filter_number' ); ?>" name="<?php echo $this->get_field_name( 'date_filter_number' ); ?>" value="<?php esc_html_e( $instance['date_filter_number'] ); ?>" size="2" placeholder="<?php _e( 'Eg: 2', SRP_TRANSLATION_ID ); ?>" style="float: left;" />
					<!-- END Form Field -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'date_filter_time' ); ?>" name="<?php echo $this->get_field_name( 'date_filter_time' ); ?>" class="srp-widget-select">

						<option value="hour" <?php selected( $instance['date_filter_time'], 'hour' ); ?>>
							<?php _e( 'Hour/s', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="day" <?php selected( $instance['date_filter_time'], 'day' ); ?>>
							<?php _e( 'Day/s', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="week" <?php selected( $instance['date_filter_time'], 'week' ); ?>>
							<?php _e( 'Week/s', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="month" <?php selected( $instance['date_filter_time'], 'month' ); ?>>
							<?php _e( 'Month/s', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="year" <?php selected( $instance['date_filter_time'], 'year' ); ?>>
							<?php _e( 'Year/s', SRP_TRANSLATION_ID ); ?>
						</option>

					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Date Filter -->

			</ul>
			<!-- END Filtering Options 2 List -->

		</dd>
		<!-- END Filtering Options 2 Content -->

		<!-- BEGIN Custom Post Types & Taxonomies Tab -->
		<dt class="srp-widget-optionlist-dt-layout">
			<a class="srp-wdg-accordion-item" href="#8" title="<?php esc_attr_e( 'Custom Post Types & Taxonomies', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Custom Post Types & Taxonomies', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Custom Post Types & Taxonomies Tab -->

		<!-- BEGIN Custom Post Types & Taxonomies Content -->
		<dd class="srp-widget-optionlist-dd-layout">

			<!-- BEGIN Custom Post Types & Taxonomies List -->
			<ul class="srp-widget-optionlist-layout srp-widget-optionlist">

				<!-- BEGIN Custom Post Type Filter -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'custom_post_type' ); ?>" class="srp-widget-label">
						<?php _e( 'Custom Post Type Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- BEGIN Label -->
					
					<?php

					// Checking for custom post types & taxonomies tree.
					if ( empty( $this->post_types_taxonomy_tree ) ) :
					?>

						<!-- BEGIN Text -->
						<p>
							<?php _e( 'Sorry, there are no Custom Post Types available.', SRP_TRANSLATION_ID ); ?>
						</p>
						<!-- END Text -->

						<!-- BEGIN Hidden Fields -->
						<input type="hidden" name="<?php echo $this->get_field_name( 'custom_post_type' ); ?>" value="no-cpt" />
						<input type="hidden" name="<?php echo $this->get_field_name( 'taxonomy_bool' ); ?>" value="AND" />
						<!-- BEGIN Hidden Fields -->

					</li>
					<!-- END Custom Post Type Filter -->

					<?php else: ?>

						<!-- BEGIN Description -->
						<small>
							<?php _e( 'Select a custom post type you wish to filter posts by:', SRP_TRANSLATION_ID ); ?>
						</small><br />
						<!-- END Description -->

						<!-- BEGIN Form Field -->
						<select class="srp-cpt-switch" id="<?php echo $this->get_field_id( 'custom_post_type' ); ?>" name="<?php echo $this->get_field_name( 'custom_post_type' ); ?>" class="srp-widget-select">
							
							<option value="no-cpt">
								<?php _e( 'Select a Custom Post Type', SRP_TRANSLATION_ID ); ?>
							</option>

							<?php

							// Looping through taxonomies.
							foreach ( $this->post_types_taxonomy_tree as $post_type ) :
							?>

								<option value="<?php echo $post_type['slug']; ?>" <?php selected( $instance['custom_post_type'], $post_type['slug'] ); ?>>
									<?php echo $post_type['name']; ?>
								</option>

							<?php endforeach; ?>

						</select>
						<!-- END Form Field -->

				</li>
				<!-- END Custom Post Type Filter -->

				<!-- BEGIN Custom Taxonomies Logical Switcher -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'taxonomy_bool' ); ?>" class="srp-widget-label">
						<?php _e( 'Custom Taxonomies Logical Switcher', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select which logical operator should taxonomies be grouped by.', SRP_TRANSLATION_ID); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'taxonomy_bool' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy_bool' ); ?>" class="srp-widget-select">

						<option value="AND" <?php selected( $instance['taxonomy_bool'], 'AND' ); ?>>
							<?php _e( 'AND (default)', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="OR" <?php selected( $instance['taxonomy_bool'], 'OR' ); ?>>
							<?php _e('OR', SRP_TRANSLATION_ID); ?>
						</option>

					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Custom Taxonomies Logical Switcher -->

				<!-- BOF Filter By Custom Taxonomies. -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'include_custom_taxonomy' ); ?>" class="srp-widget-label">
						<?php _e( 'Custom Taxonomy Filter', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN No CPT Selected Text -->
					<small class="srp-cpt-taxonomy-desc-init" <?php if ('no-cpt' == $instance['custom_post_type'] ) : ?> style="display: inline;" <?php endif; ?>>
						
						<!-- BEGIN Text -->
						<?php _e( 'Please Select a Custom Post Type first.', SRP_TRANSLATION_ID ); ?>
						<!-- END Text -->

					</small>
					<!-- END No CPT Selected Text -->

					<!-- BEGIN Description -->
					<small class="srp-cpt-taxonomy-desc-choose">

						<!-- BEGIN Text -->
						<?php _e( 'Select one or more custom taxonomies you wish to filter custom post types posts by:', SRP_TRANSLATION_ID ); ?>
						<!-- END Text -->

					</small>
					<!-- END Description -->

					<!-- BEGIN Multi Select Checkboxes -->
					<div class="srp-widget-multiselect">

					<?php foreach ( $this->post_types_taxonomy_tree as $post_type ) : ?>

						<div class="srp-taxonomy-list srp-cpt-<?php echo $post_type['slug']; ?> <?php if ( $post_type['slug'] == $instance['custom_post_type'] ) { echo 'srp-cpt-taxonomy-show'; } ?>">

							<?php if ( empty( $post_type['taxonomies'] ) ) : ?>
								
								<!-- BEGIN Text -->
								<small>
									<?php _e( 'No custom taxonomies available.', SRP_TRANSLATION_ID ); ?>
								</small>
								<!-- END Text -->

							<?php else : ?>

								<?php foreach ( $post_type['taxonomies'] as $taxonomy ) : ?>
								
									<h4><?php echo $post_type['name']; ?> > <?php echo $taxonomy['name']; ?></h4>

									<ul>

										<?php foreach ( $taxonomy['terms'] as $term ) : ?>
											<li>
												<label>
													<?php
													// Building option value.
													$optionValue = $term->taxonomy . '|' . $term->slug;
													?>
													<input type="checkbox" id="<?php echo $this->get_field_id( 'include_custom_taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'include_custom_taxonomy' ); ?>[]" value="<?php echo $optionValue; ?>" <?php if ( ('no' != $instance[ 'include_custom_taxonomy'] ) && ( is_array( $instance['include_custom_taxonomy'] ) ) && ( in_array( $optionValue, $instance['include_custom_taxonomy'] ) ) ) echo 'checked="checked"'; ?> />
													<?php echo $term->name; ?>
												</label>
											</li>

										<?php endforeach; ?>
									</ul>

								<?php endforeach; ?>

							<?php endif; ?>
							
						</div>

					<?php endforeach; ?>

					</div>
					<!-- END Multi Select Checkboxes -->

					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "NOTE: Remember to choose 'Custom Post Type' in the 'Post Type' options before using this section.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- EOF Filter By Custom Taxonomies. -->
				<?php endif; ?>

			</ul>
			<!-- END Custom Post Types & Taxonomies List -->

		</dd>
		<!-- END Custom Post Types & Taxonomies Content -->
		
		<!-- BEGIN Layout Options Tab -->
		<dt class="srp-widget-optionlist-dt-layout">
			<a class="srp-wdg-accordion-item" href="#9" title="<?php esc_attr_e( 'Layout Options 1', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Layout Options 1', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Layout Options Tab -->

		<!-- BEGIN Layout Options Content -->
		<dd class="srp-widget-optionlist-dd-layout">

			<!-- BEGIN Layout Options List -->
			<ul class="srp-widget-optionlist-layout srp-widget-optionlist">
				
				<!-- BEGIN Widget Container CSS ID -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_css_id' ); ?>" class="srp-widget-label">
						<?php _e( 'Widget Container CSS ID', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Type in a unique CSS ID selector for this widget instance.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'widget_css_id' ); ?>" name="<?php echo $this->get_field_name( 'widget_css_id' ); ?>" value="<?php echo stripslashes( $instance['widget_css_id'] ); ?>" size="30" class="fullwidth" placeholder="<?php _e( 'Example: my-srp-instance', SRP_TRANSLATION_ID ); ?>"/>
					<!-- END Form Field -->

				</li>
				<!-- END Widget Container CSS ID -->
				
				<!-- BEGIN Additional Widget Container CSS Classes -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_additional_classes' ); ?>" class="srp-widget-label">
						<?php _e( 'Additional Widget Container CSS Classes', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a space separated list of additional CSS classes for this widget instance.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'widget_additional_classes' ); ?>" name="<?php echo $this->get_field_name( 'widget_additional_classes' ); ?>" value="<?php echo stripslashes( $instance['widget_additional_classes'] ); ?>" size="30" class="fullwidth" placeholder="<?php _e( 'Example: myclass1 myclass2', SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form Field -->

				</li>
				<!-- END Additional Widget Container CSS Classes -->

				<!-- BEGIN Default WP Widget Title HTML -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'widget_title_show_default_wp' ); ?>" name="<?php echo $this->get_field_name( 'widget_title_show_default_wp' ); ?>" value="yes" <?php checked( $instance['widget_title_show_default_wp'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_title_show_default_wp' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Use Default Wordpress HTML Layout for Widget Title', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
					<?php _e( 'Check this box if you want to show the widget title HTML layout as Wordpress would normally render it.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->


					<!-- BEGIN Notebox -->
					<div class="srp-accordion-notebox">
						<?php _e( "If you're experiencing issues with widget titles compatibility, you might want to turn this option on.", SRP_TRANSLATION_ID ); ?>
					</div>
					<!-- END Notebox -->

				</li>
				<!-- END Default WP Widget Title HTML -->

				<!-- BEGIN Widget Title HTML Header -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_title_header' ); ?>" class="srp-widget-label">
						<?php _e( 'Widget Title HTML Header', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select the type of HTML header to be used to enclose the widget title:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'widget_title_header' ); ?>" name="<?php echo $this->get_field_name( 'widget_title_header' ); ?>" class="srp-widget-select">
						<option value="h1" <?php selected( $instance['widget_title_header'], 'h1' ); ?>>H1</option>
						<option value="h2" <?php selected( $instance['widget_title_header'], 'h2' ); ?>>H2</option>
						<option value="h3" <?php selected( $instance['widget_title_header'], 'h3' ); ?>>H3 <?php _e('(default)', SRP_TRANSLATION_ID ); ?></option>
						<option value="h4" <?php selected( $instance['widget_title_header'], 'h4' ); ?>>H4</option>
						<option value="h5" <?php selected( $instance['widget_title_header'], 'h5' ); ?>>H5</option>
						<option value="h6" <?php selected( $instance['widget_title_header'], 'h6' ); ?>>H6</option>
					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Widget Title HTML Header -->

				<!-- BEGIN Post Title HTML Header -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_title_header' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Title HTML Header', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select the type of HTML header to be used to enclose the post title:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_title_header' ); ?>" name="<?php echo $this->get_field_name( 'post_title_header' ); ?>" class="srp-widget-select">
						<option value="h1" <?php selected( $instance['post_title_header'], 'h1' ); ?>>H1</option>
						<option value="h2" <?php selected( $instance['post_title_header'], 'h2' ); ?>>H2</option>
						<option value="h3" <?php selected( $instance['post_title_header'], 'h3' ); ?>>H3</option>
						<option value="h4" <?php selected( $instance['post_title_header'], 'h4' ); ?>>H4 <?php _e('(default)', SRP_TRANSLATION_ID ); ?></option>
						<option value="h5" <?php selected( $instance['post_title_header'], 'h5' ); ?>>H5</option>
						<option value="h6" <?php selected( $instance['post_title_header'], 'h6' ); ?>>H6</option>
					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Post Title HTML Header -->

				<!-- BEGIN Additional Widget Title CSS Classes -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_title_header_classes' ); ?>" class="srp-widget-label">
						<?php _e( 'Additional Widget Title CSS Classes', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Enter a space separated list of additional CSS classes for the custom widget title:', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'widget_title_header_classes' ); ?>" name="<?php echo $this->get_field_name( 'widget_title_header_classes' ); ?>" value="<?php echo stripslashes( $instance['widget_title_header_classes'] ); ?>" class="fullwidth" placeholder="<?php _e( 'Example: myclass1 myclass2', SRP_TRANSLATION_ID ); ?>" />
					<!-- END Form Field -->

				</li>
				<!-- END Additional Widget Title CSS Classes -->

			</ul>
			<!-- END Layout Options List -->

		</dd>
		<!-- END Layout Options List -->

		<!-- BEGIN Layout Options Tab -->
		<dt class="srp-widget-optionlist-dt-layout">
			<a class="srp-wdg-accordion-item" href="#10" title="<?php esc_attr_e( 'Layout Options 2', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Layout Options 2', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Layout Options Tab -->

		<!-- BEGIN Layout Options Content -->
		<dd class="srp-widget-optionlist-dd-layout">

			<!-- BEGIN Layout Options List -->
			<ul class="srp-widget-optionlist-layout srp-widget-optionlist">

				<!-- BEGIN Hide Widget Title -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'widget_title_hide' ); ?>" name="<?php echo $this->get_field_name( 'widget_title_hide' ); ?>" value="yes" <?php checked( $instance['widget_title_hide'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'widget_title_hide' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Hide Widget Title', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to hide the widget title.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Hide Widget Title -->

				<!-- BEGIN Post Title Above Thumbnail -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_title_above_thumb' ); ?>" name="<?php echo $this->get_field_name( 'post_title_above_thumb' ); ?>" value="yes" <?php checked( $instance['post_title_above_thumb'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_title_above_thumb' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Post Title Above Thumbnail', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to display the post title on top of the thumbnail.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Post Title Above Thumbnail -->

				<!-- BEGIN Post Thumbnail Above Content -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'post_thumb_above_content' ); ?>" name="<?php echo $this->get_field_name( 'post_thumb_above_content' ); ?>" value="yes" <?php checked( $instance['post_thumb_above_content'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_thumb_above_content' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Post Thumbnail Above Content?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to display the post thumbnail above all.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Post Thumbnail Above Content -->

				<!-- BEGIN Post Content Display Mode -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_content_mode' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Content Display Mode', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select the content type that should appear on each post:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'post_content_mode' ); ?>" name="<?php echo $this->get_field_name( 'post_content_mode' ); ?>" class="srp-widget-select">

						<option value="thumbonly" <?php selected( $instance['post_content_mode'], 'thumbonly' ); ?>>
							<?php _e( 'Thumbnail Only', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="titleonly" <?php selected( $instance['post_content_mode'], 'titleonly' ); ?>>
							<?php _e( 'Title + Thumbnail', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="titleexcerpt" <?php selected( $instance['post_content_mode'], 'titleexcerpt' ); ?>>
							<?php _e( 'Title + Thumbnail + Post Content', SRP_TRANSLATION_ID ); ?>
						</option>
						
					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Post Content Display Mode -->

				<!-- BEGIN Selective Post Content Expansion -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'post_content_expand' ); ?>" class="srp-widget-label">
						<?php _e( 'Selective Post Content Expansion', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "If you wish to display one or more posts with their content fully expanded, please type in here how many posts from the beginning should be affected by this option. Leave blank for no expansion.", SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'post_content_expand' ); ?>" name="<?php echo $this->get_field_name( 'post_content_expand' ); ?>" value="<?php echo stripslashes( $instance['post_content_expand'] ); ?>" size="2" placeholder="<?php _e( 'Eg: 1', SRP_TRANSLATION_ID ); ?>" />
					<small>
						<?php _e( 'post/s', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Form Field -->

				</li>
				<!-- END Selective Post Content Expansion -->

				<!-- BEGIN Layout Mode -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_name( 'layout_mode' ); ?>" class="srp-widget-label">
						<?php _e( 'Layout Mode', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Choose between three different layout styles. Additional CSS rules might be needed to suite your needs.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Fields -->
					<input type="radio" name="<?php echo $this->get_field_name( 'layout_mode' ); ?>" value="single_column" <?php checked( $instance['layout_mode'], 'single_column'); ?>> <?php _e( 'Single Column (default)', SRP_TRANSLATION_ID ); ?><br />
					<input type="radio" name="<?php echo $this->get_field_name( 'layout_mode' ); ?>" value="single_row" <?php checked( $instance['layout_mode'], 'single_row' ); ?>> <?php _e( 'Single Row', SRP_TRANSLATION_ID ); ?><br />
					<input type="radio" name="<?php echo $this->get_field_name( 'layout_mode' ); ?>" value="multi_column" <?php checked( $instance['layout_mode'], 'multi_column' ); ?>> <?php _e( 'Multiple Columns', SRP_TRANSLATION_ID ); ?>
					<!-- END Form Field -->

				</li>
				<!-- END Layout Mode -->
				
				<!-- BEGIN Multi Columns Options -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_name( 'layout_num_cols' ); ?>" class="srp-widget-label">
						<?php _e( 'Multi Columns Options', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'In order for the multi columns layout mode to work, you must provide a total number of columns to display.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'layout_num_cols' ); ?>" name="<?php echo $this->get_field_name( 'layout_num_cols' ); ?>" value="<?php esc_html_e( $instance['layout_num_cols'] ); ?>" size="2" /> <?php _e( 'Cols', SRP_TRANSLATION_ID ); ?>
					<!-- BEGIN Form Field -->

				</li>
				<!-- END Multi Columns Options -->

				</ul>
			<!-- END Layout Options List -->

		</dd>
		<!-- END Layout Options List -->

		<!-- BEGIN Styles & Colours Options Tab -->
		<dt class="srp-widget-optionlist-dt-layout">
			<a class="srp-wdg-accordion-item" href="#11" title="<?php esc_attr_e( 'Styles & Colours', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Styles & Colours', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Styles & Colours Options Tab -->

		<!-- BEGIN Styles & Colours Options Content -->
		<dd class="srp-widget-optionlist-dd-layout">

			<!-- BEGIN Styles & Colours Options List -->
			<ul class="srp-widget-optionlist-layout srp-widget-optionlist">
				
				<!-- BEGIN Widget Title Font Size & Colour -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'style_font_size_widget_title' ); ?>" class="srp-widget-label">
						<?php _e( 'Widget Title Font Size & Colour', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Type in the widget title font size and/or choose a colour for it.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'style_font_size_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'style_font_size_widget_title' ); ?>" value="<?php echo stripslashes( $instance['style_font_size_widget_title'] ); ?>" size="2" style="float: left; margin-top: 0;" />
					<!-- END Form Field -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'style_font_size_type_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'style_font_size_type_widget_title' ); ?>" class="srp-widget-select" style="float: left; margin-top: 0;">
						<option value="px" <?php selected( $instance['style_font_size_type_widget_title'], 'px' ); ?>>Px</option>
						<option value="em" <?php selected( $instance['style_font_size_type_widget_title'], 'em' ); ?>>Em</option>
						<option value="rem" <?php selected( $instance['style_font_size_type_widget_title'], 'rem' ); ?>>Rem</option>
					</select>
					<!-- END Form Field -->
					
					<!-- BEGIN WP Color Picker -->
					<input type="text" class="srp-color-picker" id="<?php echo $this->get_field_id( 'style_color_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'style_color_widget_title' ); ?>" value="<?php echo stripslashes( $instance['style_color_widget_title'] ); ?>" />
					<!-- END WP Color Picker -->

				</li>
				<!-- END Widget Title Font Size & Colour -->

				<!-- BEGIN Post Title Font Size & Colour -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'style_font_size_post_title' ); ?>" class="srp-widget-label">
						<?php _e( 'Post Title Font Size & Colour', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Type in the post title font size and/or choose a colour for it.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'style_font_size_post_title' ); ?>" name="<?php echo $this->get_field_name( 'style_font_size_post_title' ); ?>" value="<?php echo stripslashes( $instance['style_font_size_post_title'] ); ?>" size="2" style="float: left; margin-top: 0;" />
					<!-- BEGIN Form Field -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'style_font_size_type_post_title' ); ?>" name="<?php echo $this->get_field_name( 'style_font_size_type_post_title' ); ?>" class="srp-widget-select" style="float: left; margin-top: 0;">
						<option value="px" <?php selected( $instance['style_font_size_type_post_title'], 'px' ); ?>>Px</option>
						<option value="em" <?php selected( $instance['style_font_size_type_post_title'], 'em' ); ?>>Em</option>
						<option value="rem" <?php selected( $instance['style_font_size_type_post_title'], 'rem' ); ?>>Rem</option>
					</select>
					<!-- BEGIN Form Field -->

					<!-- BEGIN Wp Color Picker -->
					<input type="text" class="srp-color-picker" id="<?php echo $this->get_field_id( 'style_color_post_title' ); ?>" name="<?php echo $this->get_field_name( 'style_color_post_title' ); ?>" value="<?php echo stripslashes( $instance['style_color_post_title'] ); ?>" />
					<!-- BEGIN Wp Color Picker -->

				</li>
				<!-- END Post Title Font Size & Colour -->

			</ul>
			<!-- END Styles & Colours Options List -->

		</dd>
		<!-- END Styles & Colours Options List -->

		<!-- BEGIN Pagination Options Tab -->
		<dt class="srp-widget-optionlist-dt-layout">
			<a class="srp-wdg-accordion-item" href="#12" title="<?php esc_attr_e( 'Pagination Options', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Pagination Options', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Pagination Options Tab -->

		<!-- BEGIN Pagination Options Content -->
		<dd class="srp-widget-optionlist-dd-layout">

			<!-- BEGIN Pagination Options List -->
			<ul class="srp-widget-optionlist-layout srp-widget-optionlist">

				<!-- BEGIN Enable Pagination option. -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'enable_pagination' ); ?>" name="<?php echo $this->get_field_name( 'enable_pagination' ); ?>" value="yes" <?php checked( $instance['enable_pagination'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'enable_pagination' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Enable Pagination', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to enable pagination links.', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- END Enable Pagination option. -->

				<!-- BEGIN Pagination Type -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'pagination_type' ); ?>" class="srp-widget-label">
						<?php _e( 'Pagination Type', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Select the type of pagination layout to use:', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<select id="<?php echo $this->get_field_id( 'pagination_type' ); ?>" name="<?php echo $this->get_field_name( 'pagination_type' ); ?>" class="srp-widget-select">

						<option value="prevnext_links" <?php selected( $instance['pagination_type'], 'prevnext_links' ); ?>>
							<?php _e( 'Default WP Prev/Next Links', SRP_TRANSLATION_ID ); ?>
						</option>

						<option value="page_numbers" <?php selected( $instance['pagination_type'], 'page_numbers' ); ?>>
							<?php _e( 'Numeric Pages', SRP_TRANSLATION_ID ); ?>
						</option>
					</select>
					<!-- END Form Field -->

				</li>
				<!-- END Pagination Type -->

				<!-- BEGIN Previous Link Text -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_name( 'pagination_prevlink_text' ); ?>" class="srp-widget-label">
						<?php _e( 'Previous Link Text', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Type in the previous link's text", SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'pagination_prevlink_text' ); ?>" name="<?php echo $this->get_field_name( 'pagination_prevlink_text' ); ?>" value="<?php echo ( !empty( $instance['pagination_prevlink_text'] ) ) ? esc_html( $instance['pagination_prevlink_text'] ) : __( 'Previous Posts', SRP_TRANSLATION_ID ); ?>" class="fullwidth" />
					<!-- END Form Field -->

				</li>
				<!-- END Next Link Text -->

				<!-- BEGIN Previous Link Text -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_name( 'pagination_nextlink_text' ); ?>" class="srp-widget-label">
						<?php _e( 'Next Link Text', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Type in the next link's text", SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'pagination_nextlink_text' ); ?>" name="<?php echo $this->get_field_name( 'pagination_nextlink_text' ); ?>" value="<?php echo ( !empty( $instance['pagination_nextlink_text'] ) ) ? esc_html( $instance['pagination_nextlink_text'] ) : __( 'Next Posts', SRP_TRANSLATION_ID ); ?>" class="fullwidth" />
					<!-- END Form Field -->

				</li>
				<!-- END Next Link Text -->

				<!-- BEGIN Hide Prev/Next Links. -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'pagination_hide_prevnext' ); ?>" name="<?php echo $this->get_field_name( 'pagination_hide_prevnext' ); ?>" value="yes" <?php checked( $instance['pagination_hide_prevnext'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'pagination_hide_prevnext' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Hide Prev/Next Links?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Check this box if you want to hide the prev/next links from the 'Numeric Pages' pagination type.", SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- BEGIN Hide Prev/Next Links. -->

				<!-- BEGIN Show All Pagination Numbers. -->
				<li>

					<!-- BEGIN Form Field -->
					<input type="checkbox" id="<?php echo $this->get_field_id( 'pagination_show_all' ); ?>" name="<?php echo $this->get_field_name( 'pagination_show_all' ); ?>" value="yes" <?php checked( $instance['pagination_show_all'], 'yes' ); ?> />
					<!-- END Form Field -->

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_id( 'pagination_show_all' ); ?>" class="srp-widget-label-inline">
						<?php _e( 'Show All Page Numbers?', SRP_TRANSLATION_ID ); ?>
					</label><br />
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'Check this box if you want to show all page numbers. (Numeric pagination type)', SRP_TRANSLATION_ID ); ?>
					</small>
					<!-- END Description -->

				</li>
				<!-- BEGIN Show All Pagination Numbers. -->

				<!-- BEGIN 'Set Mid Size Value'. -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_name( 'pagination_mid_size' ); ?>" class="srp-widget-label">
						<?php _e( 'Pagination Mid Size', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- END Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( "Type in how many numbers to either side of current page, but not including current page. (default 2, numeric pagination type)", SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- END Description -->

					<!-- BEGIN Form Field -->
					<input type="text" id="<?php echo $this->get_field_id( 'pagination_mid_size' ); ?>" name="<?php echo $this->get_field_name( 'pagination_mid_size' ); ?>" value="<?php echo stripslashes( $instance['pagination_mid_size'] ); ?>" size="2" />
					<!-- END Form Field -->

				</li>
				<!-- BEGIN 'Set Mid Size Value'. -->

			</ul>
			<!-- END Pagination Options List -->

		</dd>
		<!-- END Pagination Options Content -->

		<!-- BEGIN Code Generator Tab -->
		<dt class="srp-widget-optionlist-dt-codegenerator">
			<a class="srp-wdg-accordion-item" href="#13" title="<?php esc_attr_e( 'Code Generator', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Code Generator', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Code Generator Tab -->

		<!-- BEGIN Code Generator Content -->
		<dd class="srp-widget-optionlist-dd-codegenerator">

			<!-- BEGIN Code Generator List -->
			<ul class="srp-widget-optionlist-codegenerator srp-widget-optionlist">
				
				<!-- BEGIN Generated Shortcode -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_name( 'shortcode_generator_btn' ); ?>" class="srp-widget-label">
						<?php _e( 'Generated Shortcode', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- BEGIN Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'This is the shortcode generated from all the saved options of this widget instance. Copy it and paste it inside a post/page.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- BEGIN Description -->

					<!-- BEGIN Form Field -->
					<textarea id="<?php echo $this->get_field_id( 'shortcode_generator_area' ); ?>" name="<?php echo $this->get_field_name( 'shortcode_generator_area' ); ?>" class="fullwidth srp-code-textarea"><?php echo $this->srp_generate_code( $instance, 'shortcode' ); ?></textarea>
					<!-- BEGIN Form Field -->
				</li>
				<!-- END Generated Shortcode -->
				
				<!-- BEGIN Generated PHP Code -->
				<li>

					<!-- BEGIN Label -->
					<label for="<?php echo $this->get_field_name( 'phpcode_generator_btn' ); ?>" class="srp-widget-label">
						<?php _e( 'Generated PHP Code', SRP_TRANSLATION_ID ); ?>
					</label>
					<!-- BEGIN Label -->

					<!-- BEGIN Description -->
					<small>
						<?php _e( 'This is the PHP code generated from all the saved options of this widget instance. Copy it and paste it inside a post/page.', SRP_TRANSLATION_ID ); ?>
					</small><br />
					<!-- BEGIN Description -->

					<!-- BEGIN Form Field -->
					<textarea id="<?php echo $this->get_field_id( 'phpcode_generator_area' ); ?>" name="<?php echo $this->get_field_name( 'phpcode_generator_area'); ?>" class="fullwidth srp-code-textarea"><?php echo $this->srp_generate_code( $instance, 'php' ); ?></textarea>
					<!-- BEGIN Form Field -->

				</li>
				<!-- BEGIN Generated PHP Code -->

			</ul>
			<!-- END Code Generator List -->

		</dd>
		<!-- END Code Generator Content -->
		
		<!-- BEGIN Credits Options Tab -->
		<dt class="srp-widget-optionlist-dt-credits">
			<a class="srp-wdg-accordion-item" href="#14" title="<?php esc_attr_e( 'Credits', SRP_TRANSLATION_ID ); ?>">
				<?php _e( 'Credits', SRP_TRANSLATION_ID ); ?>
			</a>
		</dt>
		<!-- END Credits Options Tab -->

		<!-- BEGIN Credits Options Content -->
		<dd class="srp-widget-optionlist-dd-credits">

			<!-- BEGIN Credits Options List -->
			<ul class="srp-widget-optionlist-credits srp-widget-optionlist">
				
				<!-- BEGIN Credits Text -->
				<li>

					<p>
						<?php printf( __( 'The Special Recent Posts plugin is created, developed and supported by %1$sLuca Grandicelli%2$s', SRP_TRANSLATION_ID ), '<a href="http://www.lucagrandicelli.co.uk/?ref=author_w" title="Luca Grandicelli | Official Website" target="_blank">', '</a>' ); ?>
					</p>

					<ul class="srp-widget-credits-list">
						
						<li>
							<strong><?php _e( 'Plugin Version:', SRP_TRANSLATION_ID ); ?></strong>
							<br />
							<?php _e( SRP_PLUGIN_VERSION ); ?>
						</li>

						<li>
							<strong><?php _e( 'Latest update:', SRP_TRANSLATION_ID ); ?></strong>
							<br />
							<?php _e( 'September 10, 2014', SRP_TRANSLATION_ID ); ?>
						</li>
						
						<li>
							<strong><?php _e( 'Website:', SRP_TRANSLATION_ID ); ?></strong>
							<br />
							<?php printf( '<a href="%1$s" title="%2$s" target="_blank">http://www.specialrecentposts.com/</a>', esc_url( 'http://www.specialrecentposts.com/?ref=uri_w'), __( 'The Special Recent Posts Official Website.', SRP_TRANSLATION_ID ) );?>
						</li>

						<li>
							<strong><?php _e( 'Customer Support &amp; F.A.Q:', SRP_TRANSLATION_ID ); ?></strong>
							<br />
							<?php printf( '<a href="%1$s" title="%2$s" target="_blank">http://specialrecentposts.ticksy.com/</a>', esc_url( 'http://specialrecentposts.ticksy.com/' ), __( 'Visit the online Help Desk to get instant support.', SRP_TRANSLATION_ID ) );?>
						</li>

						<li>
							<strong><?php _e( 'Online Documentation:', SRP_TRANSLATION_ID ); ?></strong>
							<br />
							<?php printf( '<a href="%1$s" title="%2$s" target="_blank">http://www.specialrecentposts.com/docs/</a>', esc_url( 'http://www.specialrecentposts.com/docs/?ref=docs_w' ), __( 'Learn how to use SRP. View the online documentation.', SRP_TRANSLATION_ID ) );?>
						</li>

						<li>
							<strong><?php _e( 'Follow Special Recent Posts on:', SRP_TRANSLATION_ID ); ?></strong>
							<br />
							
							<ul class="srp-social-list">

								<li>
									<a class="srp-social-icon-facebook" href="https://www.facebook.com/SpecialRecentPosts/" title="<?php echo esc_attr( __( 'Follow SRP on Facebook', SRP_TRANSLATION_ID ) ); ?>" target="_blank"></a>
								</li>

								<li>
									<a class="srp-social-icon-twitter" href="https://twitter.com/lucagrandicelli" title="<?php echo esc_attr( __( 'Follow Luca Grandicelli on Twitter', SRP_TRANSLATION_ID ) ); ?>" target="_blank"></a>
								</li>

								<li>
									<a class="srp-social-icon-googlep" href="https://google.com/+Specialrecentposts" title="<?php echo esc_attr( __( 'Follow SRP on Google+', SRP_TRANSLATION_ID ) ); ?>" target="_blank"></a>
								</li>

								<li>
									<a class="srp-social-icon-envato" href="http://codecanyon.net/user/lucagrandicelli/?ref=lucagrandicelli" title="<?php echo esc_attr( __( 'Follow Luca Grandicelli on Envato', SRP_TRANSLATION_ID ) ); ?>" target="_blank"></a>
								</li>
								
							</ul>

						</li>

					</ul>
				</li>
				<!-- END Credits Text -->

			</ul>
			<!-- END Credits Options List -->

		</dd>
		<!-- END Credits Options Content -->

	</dl>
	<!-- EOF Widget accordion. -->
<?php
	}
}