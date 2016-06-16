			jQuery(document).ready(function() {

				jQuery(window).scroll(function() {
				      if (jQuery(this).scrollTop() > 200) {
				         jQuery('#scroll-to-top').fadeIn('slow');
				      } else {
				          jQuery('#scroll-to-top').fadeOut('slow');
				      }

					if (jQuery(this).scrollTop() > jQuery(document).height() - screen.height) {
				         jQuery('#scroll-to-bottom').fadeOut('slow');
				      } else {
				         jQuery('#scroll-to-bottom').fadeIn('slow');
				      }

				 });   
            });