<?php
if (!class_exists('TS_Postgrids')){
	class TS_Postgrids {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                                  array($this, 'TS_VCSC_Add_Posts_Grid_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                    array($this, 'TS_VCSC_Add_Posts_Grid_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',                                array($this, 'TS_VCSC_Add_Posts_Grid_Elements'), 9999999);
            }
            add_shortcode('TS_VCSC_Posts_Grid_Standalone',              array($this, 'TS_VCSC_Posts_Grid_Standalone'));
		}
		
		// Load Isotope Customization at Page End
		function TS_VCSC_Posts_Grid_Function_Isotope () {
			echo '<script data-cfasync="false" type="text/javascript" src="' . TS_VCSC_GetResourceURL('js/jquery.vcsc.isotope.custom.min.js') . '"></script>';
		}
        
        // Standalone Posts Grid
        function TS_VCSC_Posts_Grid_Standalone ($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();

			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == 'false') {
				if ((function_exists('vc_is_inline')) && (vc_is_inline())) {
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
						wp_enqueue_style('ts-visual-composer-extend-front');
					}
				} else {			
					wp_enqueue_style('ts-extend-dropdown');
					wp_enqueue_script('ts-extend-dropdown');					
					wp_enqueue_style('ts-font-teammates');
					wp_enqueue_style('dashicons');					
					if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
						wp_enqueue_style('ts-extend-buttons');
						wp_enqueue_style('ts-visual-composer-extend-front');
						wp_enqueue_script('ts-extend-isotope');
						//wp_enqueue_script('isotope');
						wp_enqueue_script('ts-visual-composer-extend-front');
					}
					add_action('wp_footer', 											array($this, 'TS_VCSC_Posts_Grid_Function_Isotope'), 9999);
				}
			}
            
            extract( shortcode_atts( array(
				'post_type'				=> 'post',
				'date_format'			=> 'F j, Y',
				'time_format'			=> 'l, g:i A',
				
				'limit_posts'			=> 'true',
				'limit_by'				=> 'category',							// post_tag, cust_tax
				'limit_term'			=> '',
				
				'filter_menu'			=> 'true',
				'layout_menu'			=> 'true',
				'sort_menu'				=> 'false',
				'directions_menu'		=> 'false',
				
				'filter_by'				=> 'category', 							// post_tag, cust_tax
				
				'layout'				=> 'masonry',							// spineTimeline, masonry, fitRows, straightDown
				'column_width'			=> 275,
				'layout_break'			=> 600,
				'show_periods'			=> 'false',
				'sort_by'				=> 'postDate',							// name, postDate
				'sort_order'			=> 'desc',
				
				'show_content'			=> 'excerpt',							// excerpt, cutcharacters, complete
				'cutoff_characters'		=> 400,
				'show_button'			=> 'true',
				'content_read'			=> 'Read Post',
				'content_target'		=> '_parent',
				
				'show_featured'			=> 'true',
				'show_share'			=> 'true',
				'show_categories'		=> 'true',
				'show_tags'				=> 'true',
				'show_metadata'			=> 'true',
				'show_avatar'			=> 'true',
				'show_editlinks'		=> 'true',
				
				'posts_limit'			=> 25,
				'posts_lazy'			=> 'false',
				'posts_ajax'			=> 10,
				'posts_load'			=> 'Show More',
				'posts_trigger'			=> 'click',
				
                'margin_top'			=> 0,
                'margin_bottom'			=> 0,
                'el_id' 				=> '',
                'el_class'              => '',
				'css'					=> '',
            ), $atts ));
			
			$postsgrid_random			= mt_rand(999999, 9999999);
			$output						= '';
			
            if (!empty($el_id)) {
                $posts_container_id		= $el_id;
            } else {
                $posts_container_id		= 'ts-isotope-posts-grid-parent-' . $postsgrid_random;
            }
			
			$limit_term 				= str_replace(' ', '', $limit_term);

			if ($limit_by == 'category') {
				$limit_tax 				= 'category';
			} else if ($limit_by == 'post_tag') {
				$limit_tax 				= 'post_tag';
			} else if ($limit_by == 'cust_tax') {
				$limit_tax 				= '';
			}

			$filter_tax 				= '';
			
			// - set the taxonomy for the filter menu -
			if ($filter_by == 'category') {
				$menu_tax 				= 'category';
			} else if ($filter_by == 'post_tag') {
				$menu_tax 				= 'post_tag';
			} else if ($filter_by == 'cust_tax') {
				$menu_tax 				= $filter_tax; 
			}

			// Set the WP Query Arguments
			$args = array(
				'post_type' 			=> $post_type,
				'posts_per_page' 		=> '-1'
			);
			if ($limit_posts == 'true' && taxonomy_exists($limit_tax)) {
				$limited_terms 			= explode(',', $limit_term);
				$args['tax_query'] = array(
					array (
						'taxonomy' 		=> $limit_tax,
						'field' 		=> 'slug',
						'terms' 		=> $limited_terms,
						'operator' 		=> 'NOT IN'
					)
				);
			}
			$isoposts = new WP_Query($args);

			/*
			// Listing of all Registered Post Types (Standard + Custom)
			$post_types = get_post_types( '', 'names' ); 
			foreach ( $post_types as $post_type ) {
				$post_obj = get_post_type_object($post_type);
				echo '<p>' . $post_obj->labels->singular_name . ' / ' . $post_type . '</p>';
			}
			// Categories for Custom Post Type
			$customPostTaxonomies = get_object_taxonomies('ts_team');
			if (count($customPostTaxonomies) > 0) {
				foreach ($customPostTaxonomies as $tax) {
					$args = array(
						'orderby' 			=> 'name',
						'show_count' 		=> 0,
						'pad_counts' 		=> 0,
						'hierarchical' 		=> 1,
						'taxonomy' 			=> $tax,
						'title_li' 			=> ''
					);
					$categories = get_categories($args);
					foreach($categories as $category) { 
						echo '<span>Category: <a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name . '</a></span> ';
						echo '<span> Slug: '. $category->slug . ' / Post Count: '. $category->count . ' / Cat ID: ' . $category->cat_ID . ' / Term ID: ' . $category->term_id . ' / Tax ID: ' . $category->term_taxonomy_id . '</span><br/>';
					} 
				}
			}
			*/
			
			// Language Settings: Isotope Posts
			$TS_VCSC_Isotope_Posts_Language = get_option('ts_vcsc_extend_settings_translationsIsotopePosts', '');
			if (($TS_VCSC_Isotope_Posts_Language == false) || (empty($TS_VCSC_Isotope_Posts_Language))) {
				$TS_VCSC_Isotope_Posts_Language	= $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Isotope_Posts_Language_Defaults;
			}
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Posts_Grid_Standalone', $atts);
			} else {
				$css_class	= '';
			}
			
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == 'false') {
				$isotope_posts_list_class	= 'ts-posts-timeline-view';
			} else {
				$isotope_posts_list_class	= 'ts-posts-timeline-edit';
			}
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == 'true') {
				echo '<div id="ts-isotope-posts-grid-frontend-' . $postsgrid_random . '" class="ts-isotope-posts-grid-frontend" style="border: 1px solid #ededed; padding: 10px;">';
					echo '<div style="font-weight: bold;">"TS Isotope Posts"</div>';
					echo '<div style="margin-bottom: 20px;">The element has been disabled in order to ensure compatiblity with the Visual Composer Front-End Editor.</div>';
					echo '<div>' . __( "Exclude Categories", "ts_visual_composer_extend" ) . ': ' . $limit_posts . '</div>';
					if ($limit_posts == 'true') {
						echo '<div>' . __( "Excluded", "ts_visual_composer_extend" ) . ': ' . (empty($limit_term) ? __( 'None', "ts_visual_composer_extend" ) : $limit_term) . '</div>';
					}
					echo '<div>' . __( "Number of Posts", "ts_visual_composer_extend" ) . ': ' . $posts_limit . '</div>';
					$front_edit_reverse = array(
						"excerpt" 			=> __( 'Excerpt', "ts_visual_composer_extend" ),
						"cutcharacters" 	=> __( 'Character Limited Content', "ts_visual_composer_extend" ),
						"complete" 			=> __( 'Full Content', "ts_visual_composer_extend" ),
					);
					foreach($front_edit_reverse as $key => $value) {
						if ($key == $show_content) {
							echo '<div>' . __( "Content Length", "ts_visual_composer_extend" ) . ': ' . $value . '</div>';
						}
					};
					echo '<div>' . __( "Show 'Read Post' Button", "ts_visual_composer_extend" ) . ': ' . $show_button . '</div>';
					$front_edit_reverse = array(
						"spineTimeline" 	=> __( 'Timeline', "ts_visual_composer_extend" ),
						"masonry"			=> __( 'Centered Masonry', "ts_visual_composer_extend" ),
						"fitRows"			=> __( 'Fit Rows', "ts_visual_composer_extend" ),
						"straightDown"		=> __( 'Straight Down', "ts_visual_composer_extend" ),
					);
					foreach($front_edit_reverse as $key => $value) {
						if ($key == $layout) {
							echo '<div>' . __( "Layout", "ts_visual_composer_extend" ) . ': ' . $value . '</div>';
						}
					};
					$front_edit_reverse = array(
						"postDate"			=> __( 'Post Date', "ts_visual_composer_extend" ),
						"postModified"		=> __( 'Post Modified', "ts_visual_composer_extend" ),
						"postName"			=> __( 'Post Name', "ts_visual_composer_extend" ),
						"postAuthor"		=> __( 'Post Author', "ts_visual_composer_extend" ),
						"postID"			=> __( 'Post ID', "ts_visual_composer_extend" ),
						"postComments"		=> __( 'Number of Comments', "ts_visual_composer_extend" ),
					);
					foreach($front_edit_reverse as $key => $value) {
						if ($key == $sort_by) {
							echo '<div>' . __( "Sort Criterion", "ts_visual_composer_extend" ) . ': ' . $value . '</div>';
						}
					};
					$front_edit_reverse = array(
						"asc"				=> __( 'Bottom to Top', "ts_visual_composer_extend" ),
						"desc"				=> __( 'Top to Bottom', "ts_visual_composer_extend" ),
					);
					foreach($front_edit_reverse as $key => $value) {
						if ($key == $sort_order) {
							echo '<div>' . __( "Initial Order", "ts_visual_composer_extend" ) . ': ' . $value . '</div>';
						}
					};
					echo '<div>' . __( "Show Filter Button", "ts_visual_composer_extend" ) . ': ' . $filter_menu . '</div>';
					echo '<div>' . __( "Show Layout Button", "ts_visual_composer_extend" ) . ': ' . $layout_menu . '</div>';
					echo '<div>' . __( "Show Sort Criterion Button", "ts_visual_composer_extend" ) . ': ' . $sort_menu . '</div>';
					echo '<div>' . __( "Show Directions Buttons", "ts_visual_composer_extend" ) . ': ' . $directions_menu . '</div>';
				echo '</div>';
			} else {
				echo '<div id="' . $posts_container_id . '" class="ts-isotope-posts-grid-parent ' . ($layout == 'spineTimeline' ? 'ts-timeline ' : 'ts-postsgrid ') . 'ts-timeline-' . $sort_order . ' ts-posts-timeline ' . $isotope_posts_list_class . ' ' . $css_class . '" style="margin-top: ' . $margin_top. 'px; margin-bottom: ' . $margin_bottom . ';" data-lazy="' . $posts_lazy . '" data-count="' . $posts_limit . '" data-ajax="' . $posts_ajax . '" data-trigger="' . $posts_trigger . '" data-column="' . $column_width . '" data-layout="' . $layout . '" data-sort="' . $sort_by . '" data-order="' . $sort_order . '" data-break="' . $layout_break . '" data-type="' . $post_type . '">';
					// Create Post Controls (Filter, Sort)
					echo '<div id="ts-isotope-posts-grid-controls-' . $postsgrid_random . '" class="ts-isotope-posts-grid-controls">';
						if (($directions_menu == 'true') && ($posts_lazy == 'false')) {
							echo '<div class="ts-button ts-button-flat ts-timeline-controls-desc ts-isotope-posts-controls-desc ' . ($sort_order == "desc" ? "active" : "") . '"><span class="ts-isotope-posts-controls-desc-image"></span></div>';
							echo '<div class="ts-button ts-button-flat ts-timeline-controls-asc ts-isotope-posts-controls-asc ' . ($sort_order == "asc" ? "active" : "") . '"><span class="ts-isotope-posts-controls-asc-image"></span></div>';
						}
						echo '<div class="ts-isotope-posts-grid-controls-menus">';
							if ($filter_menu == 'true' && taxonomy_exists($menu_tax)) {
								if (($menu_tax == $limit_tax) && ($limit_posts == 'true')) {
									global $wpdb;
									$limited_terms 			= explode(',', $limit_term);
									$excluded_ids 			= array();
									foreach ($limited_terms as $limitedterm) {
										$term_id 			= $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug='$limitedterm'");
										$excluded_ids[] 	= $term_id;
									}
									$id_string 				= implode(' ', $excluded_ids);
								} else {
									$id_string 				= '';
								}						
								$terms 						= get_terms($menu_tax, array('exclude' => $id_string));
								$count 						= count($terms);
								if ($count > 0) {
									$output .= '<div id="ts-isotope-posts-filter-trigger-' . $postsgrid_random . '" class="ts-isotope-posts-filter-trigger" data-dropdown="#ts-isotope-posts-filter-' . $postsgrid_random . '" data-horizontal-offset="0" data-vertical-offset="0"><span>' . $TS_VCSC_Isotope_Posts_Language['ButtonFilter'] . '</span></div>';
									$output .= '<div id="ts-isotope-posts-filter-' . $postsgrid_random . '" class="ts-dropdown ts-dropdown-tip ts-dropdown-relative ts-dropdown-anchor-left" style="left: 0px;">';
										$output .= '<ul id="" class="ts-dropdown-menu">';
											$output .= '<li><label style="font-weight: bold;"><input class="ts-isotope-posts-filter ts-isotope-posts-filter-all" type="checkbox" style="margin-right: 10px;" checked="checked" data-type="all" data-key="' . $postsgrid_random . '" data-filter="*">' . $TS_VCSC_Isotope_Posts_Language['SeeAll'] . '</label></li>';
											$output .= '<li class="ts-dropdown-divider"></li>';
											foreach ($terms as $term) {
												$output .= '<li><label><input class="ts-isotope-posts-filter ts-isotope-posts-filter-single" type="checkbox" style="margin-right: 10px;" data-type="single" data-key="' . $postsgrid_random . '" data-filter=".'. $term->slug .'">' . $term->name . '</label></li>';
											}
										$output .= '</ul>';
									$output .= '</div>';
								}
							}
							if ($layout_menu == 'true') {
								$output .= '<div id="ts-isotope-posts-layout-trigger-' . $postsgrid_random . '" class="ts-isotope-posts-layout-trigger" data-dropdown="#ts-isotope-posts-layout-' . $postsgrid_random . '" data-horizontal-offset="0" data-vertical-offset="0"><span>' . $TS_VCSC_Isotope_Posts_Language['ButtonLayout'] . '</span></div>';
								$output .= '<div id="ts-isotope-posts-layout-' . $postsgrid_random . '" class="ts-dropdown ts-dropdown-tip ts-dropdown-relative ts-dropdown-anchor-left" style="left: 0px;">';
									$output .= '<ul id="" class="ts-dropdown-menu">';
										$output .= '<li><label><input class="ts-isotope-posts-layout" type="radio" name="radio-group-' . $postsgrid_random . '" data-layout="masonry" style="margin-right: 10px;" ' . ($layout == 'masonry' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['Masonry'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-layout" type="radio" name="radio-group-' . $postsgrid_random . '" data-layout="spineTimeline" style="margin-right: 10px;" ' . ($layout == 'spineTimeline' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['Timeline'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-layout" type="radio" name="radio-group-' . $postsgrid_random . '" data-layout="fitRows" style="margin-right: 10px;" ' . ($layout == 'fitRows' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['FitRows'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-layout" type="radio" name="radio-group-' . $postsgrid_random . '" data-layout="straightDown" style="margin-right: 10px;" ' . ($layout == 'straightDown' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['StraightDown'] . '</label></li>';
									$output .= '</ul>';
								$output .= '</div>';
							}
							if ($sort_menu == 'true') {
								$output .= '<div id="ts-isotope-posts-sort-trigger-' . $postsgrid_random . '" class="ts-isotope-posts-sort-trigger" data-dropdown="#ts-isotope-posts-sort-' . $postsgrid_random . '" data-horizontal-offset="0" data-vertical-offset="0"><span>' . $TS_VCSC_Isotope_Posts_Language['ButtonSort'] . '</span></div>';
								$output .= '<div id="ts-isotope-posts-sort-' . $postsgrid_random . '" class="ts-dropdown ts-dropdown-tip ts-dropdown-relative ts-dropdown-anchor-left" style="left: 0px;">';
									$output .= '<ul id="" class="ts-dropdown-menu">';
										$output .= '<li><label><input class="ts-isotope-posts-sort" type="radio" name="radio-sort-' . $postsgrid_random . '" data-sort="postDate" style="margin-right: 10px;" ' . ($sort_by == 'postDate' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['Date'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-sort" type="radio" name="radio-sort-' . $postsgrid_random . '" data-sort="postModified" style="margin-right: 10px;" ' . ($sort_by == 'postModified' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['Modified'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-sort" type="radio" name="radio-sort-' . $postsgrid_random . '" data-sort="postName" style="margin-right: 10px;" ' . ($sort_by == 'postName' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['Title'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-sort" type="radio" name="radio-sort-' . $postsgrid_random . '" data-sort="postAuthor" style="margin-right: 10px;" ' . ($sort_by == 'postAuthor' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['Author'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-sort" type="radio" name="radio-sort-' . $postsgrid_random . '" data-sort="postID" style="margin-right: 10px;" ' . ($sort_by == 'postID' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['PostID'] . '</label></li>';
										$output .= '<li><label><input class="ts-isotope-posts-sort" type="radio" name="radio-sort-' . $postsgrid_random . '" data-sort="postComments" style="margin-right: 10px;" ' . ($sort_by == 'postComments' ? 'checked="checked"' : '') . '>' . $TS_VCSC_Isotope_Posts_Language['Comments'] . '</label></li>';
									$output .= '</ul>';
								$output .= '</div>';
							}
							echo $output;
						echo '</div>';
						echo '<div class="clearFixMe" style="clear:both;"></div>';
					echo '</div>';
					
					// Create Individual Post Output
					$postCounter 	= 0;
					$postMonths 	= array();
					if (post_type_exists($post_type) && $isoposts->have_posts()) { ?>
						<div class="ts-timeline-content">
							<div id="ts-timeline-spine-<?php echo $postsgrid_random; ?>" class="ts-timeline-spine ts-posts-spine-layout-<?php echo $layout; ?>"></div>
							<ul id="ts-isotope-posts-grid-<?php echo $postsgrid_random; ?>" class="ts-isotope-posts-grid ts-timeline-list" data-layout="<?php echo $layout; ?>" data-key="<? echo $postsgrid_random; ?>">
								<?php while ($isoposts->have_posts() ) :
									$isoposts->the_post();									
									$matched_terms				= 0;
									/*if ($limit_posts == 'true') {
										$limited_terms 			= explode(',', $limit_term);
										$present_terms			= get_the_category();
										foreach ($present_terms as $category) {
											if (in_array(trim($category->slug), $limited_terms)) {
												$matched_terms++;
											}
										}
									}*/
									if ($matched_terms == 0) {									
										$postCounter++;
										if ($postCounter < $posts_limit + 1) {
											if ($show_periods == 'true') {
												$postPeriod = get_post_time('F Y');
												if (!in_array($postPeriod, $postMonths)) {
													array_push($postMonths, $postPeriod);
													$breakMonth		= get_post_time('n');
													$breakYear		= get_post_time('Y');
													$breakStart 	= mktime(0, 0, 1, $breakMonth, 1, $breakYear);
													$breakEnd 		= mktime(23, 59, 59, $breakMonth, cal_days_in_month(CAL_GREGORIAN, $breakMonth, $breakYear), $breakYear);
													$output = '';
													$output .= '<li class="ts-timeline-list-item ts-timeline-break ts-timeline-list-supress" style="" data-visible="false" data-full="Break: ' . $postPeriod . '" data-date="' . ($sort_order == "asc" ? $breakStart : $breakEnd) . '" data-start="' . $breakStart . '" data-end="' . $breakEnd . '">';
														$output .= '<div class="ts-timeline-column" style="margin: 0;">';
															$output .= '<div class="ts-timeline-text-wrap" style="">';
																$output .= '<div class="ts-timeline-text-wrap-inner" style="width: 100%; left: 0;">';
																	$output .= '<h3 class="ts-timeline-title" style="text-align: center; margin: 0; padding: 0;">' . $postPeriod . '</h3>';
																$output .= '</div>';
																$output .= '<div class="clearFixMe"></div>';
															$output .= '</div>';
														$output .= '</div>';
													$output .= '</li>';
													echo $output;
												}
											}
											$postAttributes = 'data-visible="false" data-full="' . get_post_time($date_format) . '" data-author="' . get_the_author() . '" data-date="' . get_post_time('U') . '" data-modified="' . get_the_modified_time('U') . '" data-title="' . get_the_title() . '" data-comments="' . get_comments_number() . '" data-id="' .  get_the_ID() . '"';
										?>
										<li class="ts-timeline-list-item ts-timeline-date-true ts-isotope-posts-list-item <?php if ($filter_menu == 'true' && taxonomy_exists($menu_tax)) {foreach(get_the_terms($isoposts->post->ID, $menu_tax) as $term) echo $term->slug.' ';} ?>" <?php echo $postAttributes; ?>>
											<div class="ts-timeline-column">
												<div class="ts-timeline-text-wrap ts-timeline-text-wrap-date" style="">
													<?php
														// Post Date
														echo '<div class="ts-timeline-date"><span class="ts-timeline-date-connect"><span class="ts-timeline-date-text">';
															echo get_post_time($date_format);
														echo '</span></span></div>';
														// Post Thumbnail
														if ($show_featured == "true") {
															if ('' != get_the_post_thumbnail()) { 
																$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
																<div class="ts-isotope-posts-thumb">
																	<a href="<?php the_permalink() ?>"><img src="<?php echo $thumbnail[0]; ?>"></a>
																</div>
															<?php }
														}
													?>
													<h2 class="ts-isotope-posts-title" data-title="<?php the_title(); ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
													<div class="ts-isotope-posts-excerpt">
														<?php
															if ($show_content == "excerpt") {
																the_excerpt();
															} else if ($show_content == "cutcharacters") {
																$content = apply_filters('the_content', get_the_content());
																$excerpt = TS_VCSC_TruncateHTML($content, $cutoff_characters, '...', false, true);
																echo $excerpt;
															} else if ($show_content == "complete") {
																the_content();
															}
															if ($show_button == 'true') {
																echo '<a class="ts-isotope-posts-connect" href="' . get_permalink() . '" target="' . $content_target . '">' . $content_read . '</a>';
															}
														?>
													</div>
													<?php
														if ($show_share == 'true') {
															$postTitle = get_the_title();
															echo '<div class="ts-isotope-posts-social">';
																echo '<a href="http://pinterest.com/pin/create/link/?url=' . get_permalink() . '&amp;description=' . $postTitle . '" class="ts-isotope-posts-social-holder" rel="external" target="_blank"><span class="ts-isotope-posts-social-pinterest"></span></a>';
																echo '<a href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . get_permalink() . '&amp;name=' . $postTitle . '" class="ts-isotope-posts-social-holder" rel="external" target="_blank"><span class="ts-isotope-posts-social-google"></span></a>';
																echo '<a href="http://twitter.com/share?text=' . $postTitle . '&url=' . get_permalink() . '" class="ts-isotope-posts-social-holder" rel="external" target="_blank"><span class="ts-isotope-posts-social-twitter"></span></a>';
																echo '<a href="http://www.facebook.com/sharer.php?u=' . get_permalink() . '" class="ts-isotope-posts-social-holder" rel="external" target="_blank"><span class="ts-isotope-posts-social-facebook"></span></a>';
															echo '</div>';
														}
													?>
													<?php if (($show_categories == 'true') || ($show_tags == 'true')) {
														echo '<div class="ts-isotope-posts-metadata clearFixMe">';
															// Post Categories
															if ($show_categories == 'true') { ?>
																<div class="ts-isotope-posts-taxonomies">
																	<?php
																		$categories 	= get_the_category();
																		$separator 		= ' / ';
																		$output 		= '';
																		if ($categories){
																			echo '<span class="ts-isotope-posts-taxonomies-title">' . $TS_VCSC_Isotope_Posts_Language['Categories'] . ':<br/></span>';
																			foreach($categories as $category) {
																				$output .= '<a href="' . get_category_link($category->term_id) . '" class="ts-isotope-posts-categories" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>' . $separator;
																			}
																			echo trim($output, $separator);
																		}
																	?>
																</div>
															<?php }
															// Post Tags
															if ($show_tags == 'true') { ?>
																<div class="ts-isotope-posts-keywords">
																	<?php
																		$posttags 		= get_the_tags();
																		$separator 		= ' / ';
																		$output 		= '';
																		if ($posttags) {
																			echo '<span class="ts-isotope-posts-keywords-title">' . $TS_VCSC_Isotope_Posts_Language['Tags'] . ':<br/></span>';
																			foreach($posttags as $tag) {
																				$output .= '<a href="' . get_tag_link($tag->term_id) . '" class="ts-isotope-posts-tags" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $tag->name ) ) . '">'.$tag->name.'</a>' . $separator;
																			}
																			echo trim($output, $separator);
																		}
																	?>
																</div>
															<?php } ?>
														</div>
													<?php }
													// Post Time / Author / Type / Comments
													if ($show_metadata == 'true') { ?>
														<div class="ts-isotope-posts-metadata clearFixMe">
															<?php
																if ($show_avatar == 'true') {
																	//echo '<div class="ts-isotope-posts-avatar">';
																		echo get_avatar(get_the_author_meta('ID'), $size = '40');
																	//echo '</div>';
																}
															?>
															<div class="ts-isotope-posts-author">
																<?php echo get_the_author(); ?>
															</div>
															<?php
																$format = get_post_format();
																if (false === $format) {
																	$format 	= __( 'Standard', "ts_visual_composer_extend" );
																	$class 		= 'standard';
																} else {
																	$class		= strtolower($format);
																}
																echo '<div class="ts-isotope-posts-type ts-isotope-posts-type-' . $class . '">';
																	echo ucfirst($format);
																echo '</div><br/>';
															?>
															<div class="ts-isotope-posts-time">
																<?php echo get_post_time($time_format); ?>
															</div>
															<div class="ts-isotope-posts-comments">
																<?php echo get_comments_number(); ?>
															</div>
														</div>
													<?php }
													// Edit Links
													if (($show_editlinks == 'true') && (is_admin_bar_showing())) {
														echo '<div class="ts-isotope-posts-editlinks clearFixMe">';
															echo '<span class="ts-isotope-posts-edit"></span>';
															echo '<span class="ts-isotope-posts-links">';
																echo edit_post_link();
															echo '</span>';
														echo '</div>';
													} ?>
												</div>
											</div>
										</li>
									<?php }
								} endwhile; ?>
							</ul>
						</div>
						<?php
						if ($posts_lazy == "true") {
							echo '<div class="ts-load-more-wrap">';
								echo '<span class="ts-timeline-load-more">' . $posts_load . '</span>';
							echo '</div>';
						}
						wp_reset_postdata();
					} else {
						echo '<p>Nothing found. Please check back soon!</p>';
					}
				echo '</div>';
			}
            $myvariable = ob_get_clean();
            return $myvariable;
        }
    
        // Add Isotope Posts Grid Elements
        function TS_VCSC_Add_Posts_Grid_Elements() {
            global $VISUAL_COMPOSER_EXTENSIONS;
            // Add Standalone Posts Grid
            if (function_exists('vc_map')) {
                vc_map( array(
                    "name"                              => __( "TS Isotope Posts", "ts_visual_composer_extend" ),
                    "base"                              => "TS_VCSC_Posts_Grid_Standalone",
                    "icon" 	                            => "icon-wpb-ts_vcsc_isotope_posts",
					"class"                     		=> "ts_vcsc_main_isotope_posts",
                    "category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
                    "description"                       => __("Place an Isotope Posts element", "ts_visual_composer_extend"),
                    //"admin_enqueue_js"                => array(''),
                    //"admin_enqueue_css"               => array(''),
                    "params"                            => array(
                        // Isotope Posts Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Content Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Exclude Categories", "ts_visual_composer_extend" ),
                            "param_name"                => "limit_posts",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to exclude some post categories for the element.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"                      => "standardpostcat",
                            "heading"                   => __( "Select Excluded Categories", "ts_visual_composer_extend" ),
                            "param_name"                => "limit_term",
                            "posttype"                  => "post",
                            "posttaxonomy"              => "ts_logos_category",
							"taxonomy"              	=> "ts_logos_category",
							"postsingle"				=> "Post",
							"postplural"				=> "Posts",
							"postclass"					=> "post",
                            "value"                     => "",
							"admin_label"		        => true,
                            "description"               => __( "Please select the categories you want to use or exclude for the element.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "limit_posts", "value" 	=> "true"),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Total Number of Posts", "ts_visual_composer_extend" ),
                            "param_name"                => "posts_limit",
                            "value"                     => "25",
                            "min"                       => "1",
                            "max"                       => "250",
                            "step"                      => "1",
                            "unit"                      => '',
							"admin_label"		        => true,
                            "description"               => __( "Select the total number of posts to be retrieved from WordPress.", "ts_visual_composer_extend" ),
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Content Length", "ts_visual_composer_extend" ),
							"param_name"        		=> "show_content",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Excerpt', "ts_visual_composer_extend" )						=> "excerpt",
								__( 'Character Limited Content', "ts_visual_composer_extend" )		=> "cutcharacters",
								__( 'Full Content', "ts_visual_composer_extend" )					=> "complete",
							),
							"admin_label"		        => true,
							"description"       		=> __( "Select what part of the post content should be shown.", "ts_visual_composer_extend" )
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Character Limit", "ts_visual_composer_extend" ),
                            "param_name"                => "cutoff_characters",
                            "value"                     => "400",
                            "min"                       => "100",
                            "max"                       => "1200",
                            "step"                      => "1",
                            "unit"                      => '',
                            "description"               => __( "Select the number of characters to which the post content should be limited to.", "ts_visual_composer_extend" ),
                            "dependency" 				=> array("element" 	=> "show_content", "value" 	=> "cutcharacters")
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show 'Read Post' Button", "ts_visual_composer_extend" ),
                            "param_name"                => "show_button",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show a button with a link to read the full post.", "ts_visual_composer_extend" )
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Link Target", "ts_visual_composer_extend" ),
							"param_name"        		=> "content_target",
							"value"             		=> array(
								__( "Same Window", "ts_visual_composer_extend" )                    => "_parent",
								__( "New Window", "ts_visual_composer_extend" )                     => "_blank"
							),
							"description"       		=> __( "Define how the link should be opened.", "ts_visual_composer_extend" ),
							"dependency"        		=> array("element" 	=> "show_button", "value" 	=> "true"),
						),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "'Read Post' Text", "ts_visual_composer_extend" ),
                            "param_name"                => "content_read",
                            "value"                     => "Read Post",
                            "description"               => __( "Enter the text to be shown in the 'Read Post' Link.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "show_button", "value" 	=> "true"),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Month / Year Headers", "ts_visual_composer_extend" ),
                            "param_name"                => "show_periods",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show header elements for the timeline layout, grouping posts by month and year.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Date Format", "ts_visual_composer_extend" ),
                            "param_name"                => "date_format",
                            "value"                     => "F j, Y",
                            "description"               => __( "Enter the format in which dates should be shown. You can find more information here:", "ts_visual_composer_extend" ) . '<br/><a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">' . __( "WordPress Date + Time Formats", "ts_visual_composer_extend" ) . '</a>'
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Time Format", "ts_visual_composer_extend" ),
                            "param_name"                => "time_format",
                            "value"                     => "l, g:i A",
                            "description"               => __( "Enter the format in which times should be shown. You can find more information here:", "ts_visual_composer_extend" ) . '<br/><a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">' . __( "WordPress Date + Time Formats", "ts_visual_composer_extend" ) . '</a>'
                        ),
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
                            "value"                     => "LazyLoad Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "'Lazy Load' Effect", "ts_visual_composer_extend" ),
                            "param_name"                => "posts_lazy",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to imitate a lazy load effect for the posts.", "ts_visual_composer_extend" ),
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Lazy-Load Trigger", "ts_visual_composer_extend" ),
							"param_name"        		=> "posts_trigger",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Scroll', "ts_visual_composer_extend" )      	=> "scroll",
								__( 'Click', "ts_visual_composer_extend" )         	=> "click",
							),
							"description"       		=> __( "Select how the Lazy-Load Effect should be triggered for the posts.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "posts_lazy", 'value' => 'true' )
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Number of Posts per LazyLoad", "ts_visual_composer_extend" ),
                            "param_name"                => "posts_ajax",
                            "value"                     => "10",
                            "min"                       => "1",
                            "max"                       => "25",
                            "step"                      => "1",
                            "unit"                      => '',
							"dependency" 				=> array("element" 	=> "posts_lazy", "value" 	=> "true"),
                            "description"               => __( "Select the number of posts to be initially shown and then added per LazyLoad event.", "ts_visual_composer_extend" ),
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "'Show More' Text", "ts_visual_composer_extend" ),
                            "param_name"                => "posts_load",
                            "value"                     => "Show More",
                            "description"               => __( "Enter the text to be shown in the 'Show More' LazyLoad trigger.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "posts_lazy", "value" 	=> "true"),
                        ),
                        // Layout Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_4",
                            "value"                     => "Layout Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Layout", "ts_visual_composer_extend" ),
                            "param_name"                => "layout",
                            "value"                     => array(
								__( 'Centered Masonry', "ts_visual_composer_extend" )	=> "masonry",
                                __( 'Timeline', "ts_visual_composer_extend" )			=> "spineTimeline",
                                __( 'Fit Rows', "ts_visual_composer_extend" )          	=> "fitRows",
								__( 'Straight Down', "ts_visual_composer_extend" )		=> "straightDown",
                            ),
                            "description"               => __( "Select the layout in which the posts should initially be shown.", "ts_visual_composer_extend" ),
                            "admin_label"               => true,
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Column Width", "ts_visual_composer_extend" ),
                            "param_name"                => "column_width",
                            "value"                     => "275",
                            "min"                       => "100",
                            "max"                       => "1200",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the column width for the individual posts.", "ts_visual_composer_extend" ),
                            "dependency" 				=> array("element" 	=> "layout", "value" 	=> array("masonry", "fitRows")),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Masonry Breakpoint", "ts_visual_composer_extend" ),
                            "param_name"                => "layout_break",
                            "value"                     => "600",
                            "min"                       => "100",
                            "max"                       => "1200",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define a breakpoint in pixels at which the timeline should switch to a one column layout.", "ts_visual_composer_extend" ),
                            "dependency" 				=> array("element" 	=> "layout", "value" 	=> "spineTimeline"),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Sort Criterion", "ts_visual_composer_extend" ),
                            "param_name"                => "sort_by",
                            "value"                     => array(
                                __( 'Post Date', "ts_visual_composer_extend" )				=> "postDate",
                                __( 'Post Modified', "ts_visual_composer_extend" )			=> "postModified",
                                __( 'Post Name', "ts_visual_composer_extend" )          	=> "postName",
								__( 'Post Author', "ts_visual_composer_extend" )			=> "postAuthor",
								__( 'Post ID', "ts_visual_composer_extend" )				=> "postID",
								__( 'Number of Comments', "ts_visual_composer_extend" )		=> "postComments",
                            ),
                            "description"               => __( "Select the criterion by which posts should initially be sorted.", "ts_visual_composer_extend" ),
                            "admin_label"               => true,
                            "group" 			        => "Layout Settings",
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Initial Order", "ts_visual_composer_extend" ),
							"param_name"        		=> "sort_order",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Bottom to Top', "ts_visual_composer_extend" )			=> "asc",
								__( 'Top to Bottom', "ts_visual_composer_extend" )			=> "desc",
							),
							"admin_label"               => true,
							"description"       		=> __( "Select in which order the posts should initially be shown.", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
						),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Featured Image", "ts_visual_composer_extend" ),
                            "param_name"                => "show_featured",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the featured image for each post.", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Social Share Buttons", "ts_visual_composer_extend" ),
                            "param_name"                => "show_share",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show social share buttons for each post.", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Post Categories", "ts_visual_composer_extend" ),
                            "param_name"                => "show_categories",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the categories for each post.", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Post Tags", "ts_visual_composer_extend" ),
                            "param_name"                => "show_tags",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the tags for each post.", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Post Meta Data", "ts_visual_composer_extend" ),
                            "param_name"                => "show_metadata",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the meta data for each post.", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show User Avatar", "ts_visual_composer_extend" ),
                            "param_name"                => "show_avatar",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the meta data for each post.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "show_metadata", "value" 	=> "true"),
                            "group" 			        => "Layout Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Post Edit Links", "ts_visual_composer_extend" ),
                            "param_name"                => "show_editlinks",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the edit links for each post (visible only when logged in).", "ts_visual_composer_extend" ),
                            "group" 			        => "Layout Settings",
                        ),
						// User Control Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_5",
                            "value"                     => "User Control Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "User Control Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Filter Button", "ts_visual_composer_extend" ),
                            "param_name"                => "filter_menu",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show a category filter for the posts.", "ts_visual_composer_extend" ),
                            "group" 			        => "User Control Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Layout Button", "ts_visual_composer_extend" ),
                            "param_name"                => "layout_menu",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show a layout changer for the posts.", "ts_visual_composer_extend" ),
                            "group" 			        => "User Control Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Sort Criterion Button", "ts_visual_composer_extend" ),
                            "param_name"                => "sort_menu",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show a sort criterion changer for the posts.", "ts_visual_composer_extend" ),
                            "group" 			        => "User Control Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Directions Buttons", "ts_visual_composer_extend" ),
                            "param_name"                => "directions_menu",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show directions buttons for the posts.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "posts_lazy", "value" 	=> "false"),
                            "group" 			        => "User Control Settings",
                        ),
                        // Other Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_6",
                            "value"                     => "Other Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
                            "param_name"                => "margin_top",
                            "value"                     => "0",
                            "min"                       => "-50",
                            "max"                       => "500",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Margin: Bottom", "ts_visual_composer_extend" ),
                            "param_name"                => "margin_bottom",
                            "value"                     => "0",
                            "min"                       => "-50",
                            "max"                       => "500",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
                            "param_name"                => "el_id",
                            "value"                     => "",
                            "description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Extra Class Name", "ts_visual_composer_extend" ),
                            "param_name"                => "el_class",
                            "value"                     => "",
                            "description"               => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        // Load Custom CSS/JS File
                        array(
                            "type"                      => "load_file",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "el_file",
                            "value"                     => "",
                            "file_type"                 => "js",
                            "file_path"                 => "js/ts-visual-composer-extend-element.min.js",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                    ))
                );
            }
        }
	}
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_TS_VCSC_Posts_Grid_Standalone extends WPBakeryShortCode {
		public function singleParamHtmlHolder($param, $value) {
			$output 		= '';
			// Compatibility fixes
			$old_names 		= array('yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange');
			$new_names 		= array('alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning');
			$value 			= str_ireplace($old_names, $new_names, $value);
			//$value 		= __($value, "ts_visual_composer_extend");
			//
			$param_name 	= isset($param['param_name']) ? $param['param_name'] : '';
			$heading 		= isset($param['heading']) ? $param['heading'] : '';
			$type 			= isset($param['type']) ? $param['type'] : '';
			$class 			= isset($param['class']) ? $param['class'] : '';

            if (isset($param['holder']) === true && in_array($param['holder'], array('div', 'span', 'p'))) {
                $output .= '<'.$param['holder'].' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">'.$value.'</'.$param['holder'].'>';
            } else if (isset($param['holder']) === true && $param['holder'] == 'input') {
                $output .= '<'.$param['holder'].' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.$value.'">';
            } else if (isset($param['holder']) === true && in_array($param['holder'], array('img', 'iframe'))) {
				$output .= '<'.$param['holder'].' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="'.$value.'">';
			} else if (isset($param['holder']) === true && $param['holder'] == 'imagelist') {
				$images_ids = empty($value) ? array() : explode(',', trim($value));
				$output .= '<ul class="attachment-thumbnails'.(empty($images_ids) ? ' image-exists' : '' ).'" data-name="' . $param_name . '">';
					foreach($images_ids as $image) {
						$img = wpb_getImageBySize(array( 'attach_id' => (int)$image, 'thumb_size' => 'thumbnail' ));
						$output .= ( $img ? '<li>'.$img['thumbnail'].'</li>' : '<li><img width="150" height="150" test="'.$image.'" src="' . WPBakeryVisualComposer::getInstance()->assetURL('vc/blank.gif') . '" class="attachment-thumbnail" alt="" title="" /></li>');
					}
				$output .= '</ul>';
				$output .= '<a href="#" class="column_edit_trigger' . ( !empty($images_ids) ? ' image-exists' : '' ) . '" style="margin-bottom: 10px;">' . __( 'Add or Remove Featured Media', "ts_visual_composer_extend" ) . '</a>';
            }
			
            if (isset($param['admin_label']) && $param['admin_label'] === true) {
                $output .= '<span style="width: 100%; display: block;" class="vc_admin_label admin_label_'.$param['param_name'].(empty($value) ? ' hidden-label' : '').'"><label>'. $param['heading'] .'</label>: '.$value.'</span>';
            }

			return $output;
		}
	};
}

// Initialize "TS Skillsets" Class
if (class_exists('TS_Postgrids')) {
	$TS_Postgrids = new TS_Postgrids;
}