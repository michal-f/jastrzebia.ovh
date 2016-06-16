/**
* SRP INIT JS
* Package: special-recent-posts-pro
* Version: 3.0.3
* Author: Luca Grandicelli <lgrandicelli@gmail.com>
* Copyright (C) 2011-2014 Luca Grandicelli
* The SRP jQuery init file.
*/

/**
 * The jQuery DOM Ready Event.
 */
(function($) {

	/**
	 * srpInitAdminSettingsTabs()
	 *
	 * This function handles the switching admin tabs.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @param {jQuery Object} tab The menu tab.
	 * @version 3.0.3
	 */
	function srpInitAdminSettingsTabs( tab ) {
		
		$('.srp-tabs-menu a').on( 'click', function( e ) {

			// Prevent default behaviour.
	        e.preventDefault();

	        // Adding the 'current' class to the parent().
	        $(this).parent().addClass( 'current' );

	        // Removing old 'current' classes.
	        $(this).parent().siblings().removeClass( 'current' );

	        // Fetching current tab link href attribute.
	        var tab = $(this).attr( 'href' );

	        // Hide all others panel but the current one.
	        $( '.srp-tab-content' ).not( tab ).css( 'display', 'none' );

	        // Fade in the current content panel.
	        $( tab ).fadeIn();

	        // Returning false.
			return false;

	    });
	}

	/**
	 * srpInitAccordion()
	 *
	 * This function handles the widget accordion animations
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 */
	function srpInitAccordion() {

		// Main logic for accordion headers links.
		$( '.srp-wdg-accordion dt a' ).on( 'click', function( e ) {

			// Preventing default behaviour.
			e.preventDefault();

			// Preventing double click on the same accordion tab.
			if( $(this).hasClass( 'active' ) ) return;

			// Removing highlight from all headers links.
			$( '.srp-wdg-accordion-item' ).removeClass( 'active' );
			
			// Highlighting current header link.
			$(this).addClass( 'active' );
			
			// Hide previously open accordion tab.
			$( 'dl.srp-wdg-accordion dd' ).slideUp();

			// Show current accordion tab.
			$(this).parent().next().slideDown();
			
			// Returning false.
			return false;
		});
		
		// Main logic for textareas highlighting.
		$( 'dl.srp-wdg-accordion textarea' ).on( 'click', function( e ) {
			
			// Preventing default behaviour.
			e.preventDefault();

			// Setting focus on clicked textarea.
			this.focus();
			
			// Highlighting inner text.
			this.select();
			
			// Returning false.
			return false;

		});
	}

	/**
	 * multiselect
	 *
	 * This function handles the multi select checkboxes
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 */
	$.fn.multiselect = function() {

		// Looping through each multiselect instance.
	    $(this).each(function() {

	    	// Fetching all checkboxes.
	        var checkboxes = $(this).find( 'input:checkbox' );

	        // Looping through each found checkbox.
	        checkboxes.each( function() {

	        	// Fetching checkobx instance.
	            var checkbox = $(this);

	            // Highlighting pre-selected checkboxes
	            if ( checkbox.prop( 'checked' ) ) {

	            	// Adding highlight class if the checkbox is checked.
	                checkbox.parent().addClass( 'multiselect-on' );
	            }

	            checkbox.on( 'click', function( e ) {

					// If the user has checked a checkbox, highlight it.
					if ( checkbox.prop( 'checked' ) ) {

						// Adding the highlighting class.
	                    checkbox.parent().addClass( 'multiselect-on' );

					} else {

						// Removing the highlighting class.
	                    checkbox.parent().removeClass('multiselect-on');

					}

	            });
	        });
	    });
	};

	/**
	 * srpInitTaxonomyManager
	 *
	 * This function handles the taxonomy manager in the 'Custom Post Types & Taxonomy' widget tab.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @version 3.0.3
	 */
	function srpInitTaxonomyManager() {

		// Enabling multiselect.
		$( '.srp-widget-multiselect' ).multiselect();

		// Handling the Custom Post Type switcher.
		$( '.srp-cpt-switch' ).on( 'change', function( e ) {

			// If the user has selected the default label, do nothing.
			if ( 'no-cpt' == this.value ) {

				// Hiding all taxonomies panels.
				$( '.srp-taxonomy-list' ).removeClass( 'srp-cpt-taxonomy-show' );

				// Show Taxonomy Text when no CPT are selected.
				$( '.srp-cpt-taxonomy-desc-init' ).show();

				// Hide Taxonomy Description.
				$( '.srp-cpt-taxonomy-desc-choose' ).hide();

				// Returning.
				return;
			}

			// Hide Taxonomy Description.
			$( '.srp-cpt-taxonomy-desc-init' ).hide();

			// Show Taxonomy Description.
			$( '.srp-cpt-taxonomy-desc-choose' ).show();

			// Hiding all taxonomies panels.
			$( '.srp-taxonomy-list' ).removeClass( 'srp-cpt-taxonomy-show' );

			// Showing only the current taxonomy panel.
			$( '.srp-cpt-' + this.value ).addClass( 'srp-cpt-taxonomy-show' );
			
			// Returning false.
			return false;

		});
	}

	/**
	 * srpInitColorPicker
	 *
	 * This function initializes the WP Color Picker object.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @param {object} The widget instance.
	 * @version 3.0.3
	 */
	function srpInitColorPicker( widget ) {

		// Fetching & initializing all color pickers.
        widget.find( '.srp-color-picker' ).wpColorPicker( {

            change: _.throttle( function() {

            	// Retrigging the change event.
            	$(this).trigger( 'change' );

            }, 3000 )

        });
	}

	/**
	 * srpOnWidgetUpdate()
	 *
	 * This function is called when the widget is updated.
	 * Page is not reloaded but the widget re-builded, so we need to re call
	 * the initialization functions.
	 *
	 * @author Luca Grandicelli <lgrandicelli@gmail.com>
	 * @copyright (C) 2011-2014 Luca Grandicelli
	 * @package special-recent-posts-pro
	 * @param {event} event The on widgets update event.
	 * @param {Object} widget The current widget instance.
	 * @version 3.0.3
	 */
	function srpOnWidgetUpdate( event, widget ) {

		// Reinitializing accordion.
		srpInitAccordion();

		// Reinitializing WP color picker.
        srpInitColorPicker( widget );

        // Reinitializing Taxonomy Manager.
		srpInitTaxonomyManager();
	}

	// The widget update WP event.
	$( document ).on( 'widget-added widget-updated', srpOnWidgetUpdate );

	// Initializing admin settings panel.
	srpInitAdminSettingsTabs();

	// Initializing Accordion.
	srpInitAccordion();

	// Initializing Taxonomy Manager.
	srpInitTaxonomyManager();

	// Looping through each WP color picker instance.
	$( '#widgets-right .widget:has(.srp-color-picker)' ).each( function () {

		// Initializing WP color picker.
		srpInitColorPicker( $( this ) );

	});

})(jQuery);