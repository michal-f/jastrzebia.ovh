/*
 * Projekktor II Plugin: Logo
 * VERSION: 1.0.0
 * DESC: Provides a standard display for cover-art, video or html content
 * features startbutton, logo-overlay and buffering indicator
 * Copyright 2010-2012 Sascha Kluger, Spinning Airwhale Media, http://www.spinningairwhale.com
 *
 * under GNU General Public License
 * http://www.projekktor.com/license/
 */
var projekktorLogo = function(){};
jQuery(function($) {
projekktorLogo.prototype = {
    
    logo: null,

    config: {
	
	/* Plugin: Logo - URL to your logo file */
	image:	        false,
	
	/* Plugin: Logo - Seconds to be played back before logo fades in, 0=instantly */
	delay:	        1,
	
	/* if set clicking the logo will trigger the given callback function */
	logoClick:	false
	
    },
    
    
    /* triggered on plugin-instanciation */
    initialize: function() {
	var ref = this;

	this.logo = this.applyToPlayer(
	    $('<img/>')
		.addClass('logo')
		.addClass('inactive')
		.attr('src', $p.utils.imageDummy())
        )

	this.pluginReady = true;
    },



    /*****************************************
        EVENT HANDLERS
    *****************************************/

    stateHandler: function(state) {
        switch(state) {
            case 'ERROR':
                this.logo.addClass('inactive').removeClass('active');
                break;
        }
    },
  
    itemHandler: function() {
        this.setActive(
            this.logo
                .attr('src', $p.utils.imageDummy())
                .unbind() 
        , false);
    },    
    
    timeHandler: function() {

	// manage delayed logo fade:
	if (this.getConfig('image')==false) return;
	if (this.pp.getIsMobileClient('android')) return;
	
	// get required player data
	var timeIndex = this.pp.getPosition(),
	    itemDuration = this.pp.getDuration(),
            delay = this.getConfig('delay'),
	    ref = this;
    	    
	// ON
	// fade logo in after <this.config.logoDelay> seconds of playback
	if (!this.getActive(this.logo) && timeIndex+delay+1 < itemDuration) {	    
	    if (timeIndex>delay && itemDuration>(delay*2)) {			
		this.logo
		    .css({cursor: (this.getConfig('logoClick')!=false) ? 'pointer' : 'normal'})
		    .unbind()
		    .bind('touchstart', function() {ref.clickHandler('logo')})
                    .error(function() {
                        $(this).attr('src', $p.utils.imageDummy());
                        ref.setActive($(this), true);	
                    })
                    .load(function() {
                        ref.setActive(ref.logo, true);		    		    
                    })
		    .click(function() {ref.clickHandler('logo')})		    
		    .attr('src', this.getConfig('image')) 
	    }
	}
	
	// OFF
	// fade logo out <this.config.logoDelay> seconds before end of item
	if (this.getActive(this.logo)) {
	    if (timeIndex+delay+1 > itemDuration ) {
		this.setActive(this.logo, false);
	    }
	}
	
    }
}
});
