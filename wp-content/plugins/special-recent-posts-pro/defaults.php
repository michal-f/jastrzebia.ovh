<?php
/**
 * PLUGIN DEFAULT PRESETS
 * NOTE: PLEASE DO NOT CHANGE THESE VALUES HERE!!!
 * USE THE WIDGET OPTIONS INSTEAD.
 *
 * This file contains all the plugin/widget default presets.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 *
 * @global array $srp_default_widget_values
 * @global array $srp_default_plugin_values
 */
global $srp_default_widget_values;
global $srp_default_plugin_values;

/**
 * @var array $srp_default_widget_values The array containing all the default widget presets.
 */
$srp_default_widget_values = array(

	// BASIC OPTIONS
	'post_limit'                        => 5,                      // The max number of posts to display
	'post_type'                         => 'post',                 // The displayed post type
	'show_all_posts'                    => 'no',                   // The 'Show All Posts/Pages' option value
	'show_sticky_posts'                 => 'no',                   // The 'Show Sticky Posts?' option value
	'vf_allpages'                       => 'no',                   // Displays the widget on all pages.
	'vf_allposts'                       => 'no',                   // Displays the widget on all posts.
	'vf_allarchives'                    => 'no',                   // Displays the widget on all archive pages.
	'vf_allcategories'                  => 'no',                   // Displays the widget on all category pages.
	'vf_everything'                     => 'yes',                  // Displays the widget on all site through.
	'vf_home'                           => 'no',                   // Displays the widget on home page.
	'vf_disable'                        => 'no',                   // The "Disable Visualization Filter" option value.
	'widget_title'                      => 'Special Recent Posts', // The widget title
	'widget_title_link'                 => '',                     // The widget title link
	
	// THUMBNAIL OPTIONS
	'author_archive_link'               => 'no',                   // The 'Link Author Avatar To The Author Post Archive?' option value
	'display_thumbnail'                 => 'yes',                  // The 'Display Thumbnails?' option value
	'post_thumbnail_index_adv'          => '',                     // The 'Select Thumbnail Index' advanced option value
	'post_thumbnail_index_basic'        => 'first',                // The 'Select Thumbnail Index' option value
	'thumbnail_custom_field'            => '',                     // The 'Thumbnail Custom Field' option value
	'thumbnail_height'                  => 100,                    // The custom thumbnail height
	'thumbnail_link'                    => 'yes',                  // The 'link thumbnails to post' option value
	'thumbnail_rotation'                => 'no',                   // The thumbnail rotation mode option value
	'thumbnail_type'                    => 'thumb-post',           // The 'Thumbnail Type' option value.
	'thumbnail_width'                   => 100,                    // The custom thumbnail width
	'wp_thumbnail_size'                 => 'none',                 // The 'Select Wordpress WP Thumbnail Size' option value
	
	// POST OPTIONS
	'ext_shortcodes_compatibility'      => 'no',                   // The 'Enable Shortcodes Compatibility' value
	'post_content_length'               => '100',                  // The post content length.
	'post_content_length_mode'          => 'chars',                // The post content length mode
	'post_content_type'                 => 'content',              // The post content type
	'post_link_excerpt'                 => 'no',                   // The 'Post Content Link' option value
	'post_noimage_skip'                 => 'no',                   // The 'Skip Posts Without Featured Image' option value
	'post_order'                        => 'DESC',                 // The 'Post/Pages Order' option value
	'post_order_by'                     => 'date',                 // The 'Posts/Pages Order By:' option value
	'post_title_length'                 => '100',                  // The post title length. 
	'post_title_length_mode'            => 'fulltitle',            // The post title length mode.
	'post_title_nolink'                 => 'no',                   // The 'Disable Post Title Link' option value
	'post_random'                       => 'no',                   // The 'Enable Random Mode' option value
	'wp_filters_enabled'                => 'no',                   // The 'Enable Wordpress Filters' option value.
	
	// ADVANCED POST OPTIONS 1
	'allowed_tags'                      => '',                     // The list of allowed tags to display in the post content
	'image_string_break'                => '',                     // The absolute path to the optional image string break
	'noposts_link'                      => '',                     // The 'No Posts Default Text' link
	'noposts_text'                      => 'No posts available',   // The 'No Posts Default Text' option value
	'post_current_hide'                 => 'yes',                  // The 'Hide Current Viewed Post' option value
	'post_offset'                       => 0,                      // The post offset.
	'string_break'                      => '[...]',                // The string break text.
	'string_break_link'                 => 'yes',                  // The 'Link String/Image Break To Post?' option value.
	'title_string_break'                => '...',                  // The 'Title String Break' option value

	// ADVANCED POST OPTIONS 2
	'date_format'                       => 'F jS, Y',              // The post date format.
	'date_timeago'                      => 'no',                   // The 'Use the 'Time Ago' mode' option value
	'nofollow_links'                    => 'no',                   // The 'Add 'rel=nofollow' Attribute On Links?' option value
	'post_author'                       => 'no',                   // The 'Display post author' option value
	'post_author_archive_link'          => 'no',                   // The 'Enable post author archive link' option value
	'post_author_prefix'                => 'Published by:',        // The 'Post author prefix:' option value
	'post_author_url'                   => 'yes',                  // The 'Enable post author URL link' option value
	'post_category'                     => 'no',                   // The 'Display post category' option value
	'post_category_link'                => 'yes',                  // The 'Enable post category link' option value
	'post_category_prefix'              => 'Category:',            // The 'Post category prefix:' option value
	'post_category_separator'           => ',',                    // The 'Category names separator:' option value
	'post_comments'                     => 'no',                   // The 'Display post comments' option value
	'post_comments_show_num'            => 'no',                   // The 'Display post comments number' option value
	'post_date'                         => 'yes',                  // The 'Display post date' option value
	'post_date_prefix'                  => '',                     // The 'Post date prefix:' option value
	'post_default_comments_string'      => 'Comments',             // The 'Default text for comments link' option value
	'post_multiple_comments_string'     => 'Comments',             // The 'Text for multiple comments' option value
	'post_no_comments_string'           => 'No Comments',          // The 'Text for no comments' option value
	'post_single_comments_string'       => '1 Comment',            // The 'Text for 1 comment' option value
	'post_tags'                         => 'no',                   // The 'Display post tags' option value
	'post_tags_prefix'                  => 'Tags:',                // The 'Post tags prefix:' option value

	'post_tags_separator'               => ',',                    // The 'Tags names separator' option value
	'targetblank_links'                 => 'no',                   // Add the 'target="_blank"' attribute to all widget links.

	// FILTERING OPTIONS 1
	'author_autofilter'                 => 'no',                   // The 'Enable Auto Author Filtering?' option value
	'category_autofilter'               => 'no',                   // The 'Enable Auto Category Filtering' option value
	'category_autofilter_single'        => 'no',                   // The 'Enable Auto Category Filtering On Single Posts/Pages' option value
	'category_exclude'                  => '',                     // Filter posts by excluding categories IDs.
	'category_include'                  => '',                     // The comma separated list of categories IDs to filter posts by.
	'category_include_exclusive'        => 'no',                   // The 'Exclusive Category Filter' option value
	'category_title'                    => 'no',                   // The 'Use Category Name As Widget Title?' option value
	'filter_sticky_posts_only'          => 'no',                   // The 'Show Sticky Posts Only?' option value
	'post_search_filter'                => '',                     // The 'Search Text Filter' option value
	'post_status'                       => 'publish',              // The 'Post Status Filter' option value

	// FILTERING OPTIONS 2
	'author_include'                    => '',                     // The comma separated list of author IDs to filter posts by.
	'date_filter_number'                => '',                     // The 'Date Filter' number parameter
	'date_filter_time'                  => 'day',                  // The 'Date Filter' format parameter
	'enable_date_filter'                => 'no',                   // The 'Date Filter' option value
	'post_exclude'                      => '',                     // The comma separated list of post IDs to be excluded.
	'post_include'                      => '',                     // The comma separated list of post IDs to be included.
	'post_include_sub'                  => 'no',                   // The 'Include Subpages' option value
	'post_meta_key'                     => '',                     // The 'Custom Field Filter - Meta Key' option value
	'post_meta_value'                   => '',                     // The 'Custom Field Filter - Meta Value' option value
	'preserve_post_include_order'       => 'no',                   // The 'Preserve Posts/Page ID Filter Order?' option value
	'tags_include'                      => '',                     // Filter post by Tags.

	// CUSTOM POST TYPES & TAXONOMIES
	'custom_post_type'                  => 'no-cpt',               // The 'Custom Post Type Filter' option value
	'include_custom_taxonomy'           => 'no',                   // The collection of custom taxonomies posts will be filtered by
	'taxonomy_bool'                     => 'AND',                  // The 'Custom Taxonomies Logical Switcher' option value

	// LAYOUT OPTIONS
	'layout_mode'                       => 'single_column',        // The 'Layout Mode' option value
	'layout_num_cols'                   => '2',                    // The 'Multi Columns Options' option value
	'post_content_expand'               => '',                     // The 'Selective Post Content Expansion' option value
	'post_content_mode'                 => 'titleexcerpt',         // The layout content mode
	'post_thumb_above_content'          => 'no',                   // The 'Post Thumbnail Above Content?' option value
	'post_title_above_thumb'            => 'no',                   // The 'Post Title Above Thumbnail' option value
	'post_title_header'                 => 'h4',                   // The post title HTML header.
	'widget_additional_classes'         => '',                     // The space separated list of additional widget container CSS classes
	'widget_css_id'                     => '',                     // The 'Widget Container CSS ID' option value
	'widget_title_header'               => 'h3',                   // The widget HTML header.
	'widget_title_header_classes'       => '',                     // The space separated list of additional widget title header classes.
	'widget_title_hide'                 => 'no',                   // The 'Hide Widget Title' option value
	'widget_title_show_default_wp'      => 'no',                   // This option lets SRP render the widget title as Wordpress would normally do. Without customization.

	// STYLES & COLOURS
	'style_color_post_title'            => '',                     // The post title colour.
	'style_color_widget_title'          => '',                     // The widget title colour.
	'style_font_size_post_title'        => '',                     // The post title font size
	'style_font_size_type_post_title'   => 'px',                   // The post title font size unit
	'style_font_size_type_widget_title' => 'px',                   // The widget title font size unit
	'style_font_size_widget_title'      => '',                     // The widget title font size

	// PAGINATION OPTIONS
	'enable_pagination'                 => 'no',                   // The 'Enable Pagination' option value
	'pagination_hide_prevnext'          => 'no',                   // The 'Hide Prev/Next Links' option value.
	'pagination_mid_size'               => '2',                    // The pagination mid_size parameter.
	'pagination_nextlink_text'          => 'Next Posts',           // The next link text
	'pagination_prevlink_text'          => 'Previous Posts',       // The previous link text
	'pagination_show_all'               => 'no',                   // The 'Show All' option value.
	'pagination_type'                   => 'prevnext_links',       // The 'Pagination Type' option value
	
	// CODE GENERATOR
	'phpcode_generator_area'            => '',                     // The generated PHP code.
	'shortcode_generator_area'          => ''                      // The generated shortcode.
);

/**
 * @var array $srp_default_plugin_values The array containing all the default plugin presets.
 */
$srp_default_plugin_values = array(
	
	'srp_compatibility_mode'     => 'yes',                 // The 'Compatibility Mode' option value
	'srp_custom_css'             => 'Default CSS Comment', // The default CSS Editor comment text
	'srp_disable_theme_css'      => 'no',                  // The 'Disable Plugin CSS?' option value
	'srp_log_errors_screen'      => 'no',                  // Log errors on screen?
	'srp_noimage_url'            => SRP_DEFAULT_THUMB,     // The absolute URL to the no-image placeholder
	'srp_thumbnail_jpeg_quality' => '80',                  // The thumbnails jpeg image quality ratio
	'srp_version'                => SRP_PLUGIN_VERSION,    // The Special Recent Post current version.
);