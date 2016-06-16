/*
 * Projekktor II Plugin: Share
 * VERSION: 1.0.1
 * DESC: Adds social share buttons to the video display, pe default: facebook, twitter
 * Also promotes the embed-code for iframed players.
 * Copyright 2010, Sascha Kluger, Spinning Airwhale Media, http://www.spinningairwhale.com
 * all rights reserved
 *
 * under GNU General Public License
 * http://www.projekktor.com/license/
 */
var projekktorShare = function () {};
jQuery(function ($) {
	projekktorShare.prototype = {

		_controlHideTimer: null,
		_wasPlaying: false,
		_noFadeIn: false,
		embPopup: null,

		socialLink: false,
		socialSidebar: null,
		socialPopup: null,
		socialButtons: [],

		config: {

			fadedelay: 1800,

			embed: {
				code: '<' + 'if' + 'rame id="%{embedid}" src="%{playerurl}#%{ID}" width="640" height="385" webkitAllowFullScreen mozallowfullscreen allowFullScreen frameborder="0"><' + '/if' + 'rame>',
				buttonText: 'embed',
				headlineText: 'Copy this:',
				closeText: 'Close Window',
				descText: 'This is the embed code for the current video which supports iPad, iPhone, Flash and native players.'
			},
			links: {
				'twitter': {
					buttonText: 'Twitter',
					text: 'I found a cool HTML5 video player. Check this out: http://www.projekktor.com',
					code: 'http://twitter.com/share?url=%{pageurl}&text=%{text}&via=projekktor'
				},

				'facebook': {
					buttonText: 'Facebook',
					text: 'I found a cool HTML5 video player. Check this out: http://www.projekktor.com',
					code: 'http://www.facebook.com/sharer.php?u=%{pageurl}&t=%{text}'
				}
			}
		},

		initialize: function () {

			this.drawSidebar();

			if (this.getConfig('iframe') == true) {
				this.drawPopup();
				this.addTool(this.getConfig('embed'), 'embed');
			}

			for (var i in this.getConfig('links')) {
				this.addTool(this.getConfig('links')[i], i);
			}

			this.pluginReady = true;

		},

		itemHandler: function () {
			this._noFadeIn = false;
			try {
				this.socialLink = (!this.getConfig('link')) ? this.pp.getIframeWindow().attr('location') : this.getConfig('link');
			} catch (e) {
				this.socialLink = this.getConfig('link');
			}

			for (var i = 0; i < this.getConfig('links').length; i++) {
				this.toggleTool(this.getConfig('links')[i].domId, this.socialLink === false);
			}
		},

		errorHandler: function () {
			this._noFadeIn = true;
			this.hideSidebar(true);
		},

		/*******************************
        DOM Monipulations
        *******************************/
		/* the sidebar */
		drawSidebar: function () {
			this.socialSidebar = this.applyToPlayer($('<div/>').addClass('socialbar'));
		},

		/* general popup */
		drawPopup: function () {
			this.socialPopup = this.applyToPlayer($('<div/>').addClass('socialpopup'), 'popup');
		},

		openWindow: function (name) {
			this._wasPlaying = (this.pp.getState() === 'PLAYING');
			if (this._wasPlaying === true) {
				this.pp.setPause();
			}
			this[name + "FillWindow"](this.socialPopup);
			this.socialSidebar.hide();
			this.socialPopup.show();
		},

		closeWindow: function () {
			this.socialSidebar.show();
			this.socialPopup.hide().html('');
			if (this._wasPlaying === true) {
				this.pp.setPlay();
			}

		},

		openURL: function (url) {
			var dest = window;
			dest.open(url);
			return false;
		},

		/*******************************
        stack button
        *******************************/
		addTool: function (config, domId) {

			var ref = this,
				button = null,
				callback = (domId == 'embed') ? 'embedClick' : 'buttonClick';

			if (config.code == false) return;

			if (this.playerDom.find("." + this.getCN('shareicn_' + domId)).length == 0) {

				if (this.socialSidebar && config.buttonText) {
					button = $('<div/>')
						.html(config.buttonText)
						.addClass(this.getCN('socialbutton'))
						.addClass(this.getCN(domId))
						.appendTo(this.socialSidebar)
				}

			} else {
				// grab an exisiting icon elsewise:
				button = this.playerDom.find("." + this.getCN('shareicn_' + domId));

			}

			// click event
			if (button) {
				button.click(function (evt) {
					evt.stopPropagation();
					ref[callback](config);
				});
			}

			// push
			this.socialButtons.push()
		},

		toggleTool: function (domId, block) {
			var ref = this;

			$.each(this.playerDom.find('.' + this.getCN('socialbutton')), function () {

				// hide button, if any:
				if ($(this).attr('id') === ref.pp.getId() + "_" + domId) {
					if (block === true) {
						$(this).hide();
					} else {
						$(this).show();
					}
				}
			});

		},

		/*******************************
        fade in / fade out
        *******************************/
		hideSidebar: function (instant) {
			clearTimeout(this._controlHideTimer);
			this.setActive(this.socialSidebar, false);
		},

		showSidebar: function () {
			var ref = this;

			if (this._noFadeIn) return;
			if (this.pp.getState('IDLE')) return;

			clearTimeout(this._controlHideTimer);
			this.setActive(this.socialSidebar, true);
			this._controlHideTimer = setTimeout(function () {
				ref.hideSidebar();
			}, this.getConfig('fadedelay'));
		},



		/*******************************
        Player-Event Handlers
        *******************************/
		mousemoveHandler: function () {
			this.showSidebar();
		},



		/*******************************
        EMBED TOOL
        *******************************/
		embedClick: function (useless) {
			this.openWindow('embed');
		},

		embedFillWindow: function (dest) {
			var ref = this;
			$('<p/>')
				.appendTo(dest)
				.html(this.getConfig('embed').descText);

			$('<p/>')
				.appendTo(dest)
				.html(this.getConfig('embed').headlineText);

			$('<textarea/>')
				.appendTo(dest)
				.attr('readonly', 'readonly')
				.val(this.getEmbedCode())
				.click(function () {
					this.select();
				})
				.focus(function () {
					this.select();
				})

			$('<a/>')
				.appendTo(dest)
				.html(this.getConfig('embed').closeText)
				.click(function () {
					ref.closeWindow();
				})
		},

		getEmbedCode: function () {
			// replace standard items
			var result = this.getConfig('embed').code,
				data = {};

			data.embedid = $p.utils.randomId(8);
			data.playerurl = window.location.href + window.location.hash;
			data.ID = this.pp.getItem()['ID'];

			for (var i in data) {
				result = $p.utils.parseTemplate(result, data);
			}
			return result;
		},

		/*******************************
        LINK CLICK TOOL
        *******************************/
		buttonClick: function (config) {

			// replace standard items
			var data = {};

			try {
				data.text = escape(config.text || '')
			} catch (e) {}
			data.pageurl = escape(this.socialLink || '');

			for (var i in data) {
				config.code = $p.utils.parseTemplate(config.code, data);
			}
			this.openURL(config.code);
		}
	}
});