var dialogs = 0;
var dialog_class = '';
var sweetalert_title = false;
var sweetalert_content = false;
var sweetalert_class = false;
var fullscreen_interval = false;

jQuery(document).ready(function() {

});

jQuery(document).ready(function() {
	// hide error
	jQuery(".layout-container header > div > div").each(function(index, el) {
		if (jQuery(this).attr("aria-label") == "Error message") {
			jQuery(this).hide();
		}
	});
 
	// show login windows
	if (location.href.indexOf('/user/login') > -1) {
		location.href = "/home?login=window";
	}

	if (jQuery.ui.dialogr.maxZ == 0) {
		jQuery.ui.dialogr.maxZ = 1000;
	}

	// set height 100%;
	jQuery('body').css('height', jQuery(window).height());

	// ---------------------
	// front page slideshow
	if (jQuery("body").hasClass('path-frontpage')) {
		$body = jQuery("body");
		$body.delay(5000).vegas({
			overlay: drupalSettings.path.baseUrl+"themes/ows_theme/css/overlays/01.png",
			transitionDuration: 3000,
			preload: true,
			delay: 7000,
		    slides: [
		        /*{ src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/background.jpg" },
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/background2.jpg" },
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/background.jpg" }*/
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider1.jpg", cover: false },
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider2.jpg", cover: false },
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider3.jpg", cover: false },
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider4.jpg", cover: false },
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider5.jpg", cover: false }
		    ]
		});
	} else {
		jQuery("body").delay(5000).vegas({
			overlay: drupalSettings.path.baseUrl+"themes/ows_theme/css/overlays/01.png",
			transitionDuration: 3000,
			preload: true,
			delay: 7000,
			cover: false,
		    slides: [
		        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/background.jpg" }
		    ]
		});
	}

	// Prevent event on Home menu
	jQuery('li.home a').click(function(e) {
	    var title = jQuery(document).attr('title');
		if (title.indexOf("Page not found") == -1) {
			// console.log(e);
			e.preventDefault();
			displayWelcome(false);
		}
	});

	// -------------
	// window resize
	jQuery(window).resize(function() {
		jQuery(".sweet-alert").center();

		jQuery(".ui-dialog").not(".dialogr-minimized").each(function() {
			if (jQuery(this).width() < 980) {
				//jQuery(this).center(); // enable this will cause the windows looks like it resize 4 edges
			}
		});
	});

	videojs.options.flash.swf = "/themes/ows_theme/video-js.swf";

	// Cookie popup
	jQuery("body").addClass('eupopup eupopup-top');
	jQuery("document").euCookieLawPopup().init({
		cookiePolicyUrl : '/cookie-policy',
		popupPosition : 'top',
		colorStyle : 'default',
		compactStyle : false,
		popupTitle : 'This website is using cookies',
		popupText : 'We use cookies to ensure that we give you the best experience on our website. If you continue without changing your settings, we\'ll assume that you are happy to receive all cookies on this website.',
		buttonContinueTitle : 'Continue',
		buttonLearnmoreTitle : 'Learn more',
		buttonLearnmoreOpenInNewWindow : true,
		agreementExpiresInDays : 30,
		autoAcceptCookiePolicy : false,
		htmlMarkup : null
	});

	// cookie info
	jQuery(".eupopup-buttons .eupopup-button_2").click(function(e) {
		swal.close();
		obj = jQuery(this);
		e.preventDefault();
		loader(true);
		page = 6;
		jQuery.ajax({
			url: "/ajax-content",
			data: {type: "view-page", page: page, r: Math.random()},
			type: "POST",
			async: false, 
			success: function(data) {
				loader(0);
				callback = "scrollbar('.ui-dialog .page-"+page+"', false); jQuery.ui.dialogr.maxZ += 2; jQuery('.page-"+page+"').css('z-index', jQuery.ui.dialogr.maxZ);";
				openDialog('.page-'+page,  obj.attr('href').replace('/', '').capitalize(), data, 600, 500, false, callback);
			}
		});
	});

	// Fullscreen button
	jQuery("body").prepend('<a href="#fullscreen" class="fullscreen"><span></span></a>');
	jQuery('.fullscreen').click(function() {
		if (!jQuery(this).hasClass('f-activated')) {
			jQuery(this).addClass('f-activated');
		} else {
			jQuery(this).removeClass('f-activated');
		}

		toggleFullScreen(document.body);
		fullscreen_interval = setInterval(function() {checkFullScreen();}, 1000);
	});

	// ----------------------
	// Search
	jQuery("body").prepend('<div class="search-box"><div class="search-key-wrapper"><input type="text" id="search-key" name="search-key" /></div><a href="#search" class="search-icon"><span></span></a></div>');
	// search action
	jQuery(".search-icon").click(function() {
		if (jQuery('.search-box .search-key-wrapper').css('display') == "none") {
			jQuery('.search-box .search-key-wrapper').toggle({ direction: "left" }, 200);
		} else {
			loader(1);
			// close exist search dialog, if not use modal (see dialog type on controller file)
			if (jQuery(".dialog-search").length) {
				jQuery('.dialog-search .ui-dialog-titlebar-close').trigger('click');
			}

			dialog_options = jQuery("#btn-search-user").attr('data-dialog-options').replace('[key]', jQuery("#search-key").val());
			jQuery("#btn-search-user").attr('data-dialog-options', dialog_options);

			// send key before trigger dialog
			jQuery.ajax({
				url: "/ajax-content",
				type: "POST",
				data: {type: "search-key", key: jQuery("#search-key").val()},
				async: false, 
				success: function(data) {
					loader(0);
					jQuery("#btn-search-user").attr('href', '/search-user?_wrapper_format=' + jQuery("#search-key").val()).click();
				}
			});
			
		}
	});

	// enable tooltip
	tooltip_settings = { placement: "top" };
	if (jQuery(window).width() <= 640) {
		// jQuery('[data-toggle="tooltip"]').tooltip({
			
		// });
	} else {
		jQuery('[data-toggle="tooltip"]').tooltip({
			placement: "top"
		});
	}

	// --------------------
	// dialog for not-front
	if (!jQuery("body").hasClass('path-frontpage')) {
		// Page not found
		if (jQuery(".layout-container #block-ows-theme-page-title h1").html() == "Page not found") {
			jQuery("#block-ows-theme-page-title, #block-ows-theme-content").hide();
			swal({
				title: jQuery(".layout-container #block-ows-theme-page-title h1").html(),
				text: jQuery("#block-ows-theme-content").html(),
				html: true,
				customClass: 'twitter',
				showConfirmButton: false,
				allowEscapeKey: true, // turn on for debug only
				allowOutsideClick: false
				//allowEscapeKey: false
			});

			jQuery('.sweet-alert').draggable({ containment: "html" });
			jQuery('.sweet-alert').center();
		} else {
			jQuery("#block-ows-theme-page-title, #block-ows-theme-content").hide();
			callback = "scrollbar('.ui-dialog .dialog-content', false); jQuery.ui.dialogr.maxZ += 2;";
			openDialog('.dialog-content', jQuery(".layout-container #block-ows-theme-page-title h1").html(), jQuery("#block-ows-theme-content").html(), 650, 500, 	false, callback);
		}
	} else {
		// show login windows
		if (jQuery("#login_message").length > 0) {
			//  || jQuery(".layout-container header div").html().indexOf('Unrecognized username or password.') > -1
			jQuery("#block-ows-theme-page-title, #block-ows-theme-content, .layout-container header").hide();
			swal.close();
			
			jQuery.ui.dialogr.maxZ += 1;
			jQuery('#block-userlogin').css('z-index', jQuery.ui.dialogr.maxZ).center().fadeIn();			
			jQuery("#user-login-form #edit-actions").prepend('<div class="login-fail">Unrecognized username or password.</div>')
		} 
	}

	// price pool
	if (jQuery(".price-pool-holder").html() != null) {
		jQuery("body").prepend('<div class="price-pool"><span class="text">Prize Pool: <strong>$'+jQuery(".price-pool-holder").html()+'</strong></span></div>');
	}
});

function checkFullScreen() {
	if ((!document.mozFullScreen && !document.webkitIsFullScreen)) {
		jQuery('.fullscreen').removeClass('f-activated');
		clearInterval(fullscreen_interval);
	} else {
		jQuery('.fullscreen').addClass('f-activated');
	}
}

document.onkeydown = function(evt) {
    evt = evt || window.event;
    var isEscape = false;
    if ("key" in evt) {
        isEscape = evt.key == "Escape";
    } else {
        isEscape = evt.keyCode == 27;
    }

    if (isEscape) {
    	jQuery('.fullscreen').removeClass('f-activated');
    }
};

jQuery(document).ready(function() {
	user_option = jQuery.cookie('user.option');
	if (user_option === undefined) {
		if (jQuery('body').hasClass('path-frontpage')) {
			displayWelcome(false);
		}
	} else {
		displayWelcome(false);
	}

	// Check dialog active jQuery('.ui-dialog').length;
	// --------------------------------------------
	// extend dialog options
	drupalSettings.dialog.open = function(event) {
		console.log('Dialog Open');
		// get dialog class to parse for callback
		dialog_class = jQuery(event.target).parent().attr('class').match(/dialog-[\w-]*\b/);

		try {
			jQuery('.'+dialog_class.toString() + ' .ui-dialog').draggable({ containment: "html" });
			openOWSDialog(dialog_class.toString());
		} catch (e) {}

		// date picker, make sure element exist
		try {
			if (jQuery("input.form-date").length) {

				jQuery("input.form-date").val('1/1/1999').attr('readonly', 'readonly').attr('data-language', 'en').datepicker({
					autoClose: true,
					yearRange: "-180:+0",
					changeMonth: true,
            		changeYear: true
				});

				// $("input.form-date").datepicker("option", "defaultDate", '1/1/1999');
			}
		} catch (e) {console.log(e);}
	};

	drupalSettings.dialog.close = function(event) {
		console.log('Dialog Close');
		dialog_class = jQuery(event.target).parent().attr('class').match(/dialog-[\w-]*\b/);

		try {
			closeOWSDialog(dialog_class.toString());
		} catch (e) {}
	};

	// ---------------------
	// main menu
	jQuery('#block-mainmenu a').html('');
	jQuery('#block-mainmenu .login a').click(function(e) {
		e.preventDefault();
		jQuery.ui.dialogr.maxZ += 1;
		jQuery('#block-userlogin').css('z-index', jQuery.ui.dialogr.maxZ).center().fadeIn();
		return;
	});

	jQuery('#block-userlogin').click(function() {
		jQuery.ui.dialogr.maxZ += 1;
		jQuery('#block-userlogin').css('z-index', jQuery.ui.dialogr.maxZ);
	});

	// ---------------------
	// Login block
	// make login block draggable
	jQuery('#block-userlogin').center().draggable({ containment: "html" });
	// append close button to login
	jQuery('#block-userlogin').prepend('<span class="button-close"></span>');
	jQuery('#block-userlogin .button-close').click(function() {
		jQuery('#block-userlogin').fadeOut();
	});

	try {
		var slide_state = 'play';
		// slide navigation
		jQuery('#block-mainmenu').parent().append('<div class="slideshow-nav-buttons"><a href="#prev" class="prev"></a><a href="#play-pause" class="play-pause"></a><a href="#next" class="next"></a></div>');
		jQuery(".slideshow-nav-buttons .prev").on('click', function () {
    		$body.vegas('previous');
		});

		jQuery(".slideshow-nav-buttons .next").on('click', function () {
    		$body.vegas('next');
		});

		jQuery(".slideshow-nav-buttons .play-pause").on('click', function () {
			if (slide_state == "play") {
    			$body.vegas('pause');
    			jQuery(".slideshow-nav-buttons .play-pause").addClass('pause');
    			slide_state = 'pause';
    		} else {
    			$body.vegas('play');
    			jQuery(".slideshow-nav-buttons .play-pause").removeClass('pause');
    			slide_state = 'play';
    		}
		});

		jQuery('.user-logged-in #block-mainmenu').parent().append('<div id="user-menu" class="user-menu dropdown"><div class="drop-ttl">&nbsp;</div><ul><li class="my-vote">My Vote</li><li class="my-account">My Account</li></ul></label></div></div>');
		jQuery('#block-mainmenu').parent().append('<a id="nav-toggle" href="#"><span></span></a>');

		// user menu click
		jQuery('#user-menu').click(function(e) {
			jQuery('#user-menu.dropdown ul').fadeToggle();
		});

		// my vote click 
		jQuery('.my-vote').click(function(e) {
			//swal.close();
			jQuery.ajax({
				url: "/ajax-content",
				type: "POST",
				data: {type: "my-vote", r: Math.random()},
				async: false, 
				success: function(data) {
					loader(0);
					callback = "scrollbar('.ui-dialog .page-my-voting', false); jQuery.ui.dialogr.maxZ += 1; jQuery('.current-voting').css('z-index', jQuery.ui.dialogr.maxZ);";
					openDialog('.page-my-voting', 'My Vote', data, 600, 500, false, callback);
				}
			});
		});

		// my account click 
		jQuery('.my-account').click(function(e) {
			loader(1);
			//swal.close();
			jQuery.ajax({
				url: "/ajax-content",
				type: "POST",
				data: {type: "my-account", r: Math.random()},
				async: false, 
				success: function(data) {
					loader(0);
					callback = "scrollbar('.ui-dialog .dialog-my-account', false); jQuery('.colorbox').colorbox({rel: 'gallery-item'}); jQuery.ui.dialogr.maxZ += 1; jQuery('.dialog-my-account').css('z-index', jQuery.ui.dialogr.maxZ); edit_account();";
					openDialog('.dialog-my-account', 'My Account', data, 600, 500, false, callback);
				}
			});
		});
		
		document.querySelector("#nav-toggle")
	  		.addEventListener( "click", function() {
	    	this.classList.toggle( "active" );
	    	jQuery('#block-mainmenu').slideToggle();

	    	// hide white windows
	    	// swal.close();
	  	});
	} catch(e) {}

	// -------------
	// main menu click
	jQuery('#block-mainmenu li a').click(function(e) {
		staticPage = true;
		if (jQuery(this).parent().attr('class') == "current-voting") {
			e.preventDefault();

			staticPage = false;
			jQuery.ajax({
				url: "/ajax-content",
				type: "POST",
				data: {type: "vote-list", r: Math.random()},
				async: false, 
				success: function(data) {
					loader(0);
					callback = "scrollbar('.ui-dialog .page-current-voting', false); jQuery.ui.dialogr.maxZ += 2; jQuery('.current-voting').css('z-index', jQuery.ui.dialogr.maxZ);";
					openDialog('.page-current-voting', 'Current Voting', data, 600, 500, false, callback);
				}
			});
		}

		/*if (jQuery(this).parent().attr('class') == "social") {
			e.preventDefault();
			staticPage = false;
		}*/

		if (jQuery(this).parent().attr('class') == "buy-ticket") {
			e.preventDefault();
			staticPage = false;
			//swal.close();
		    loader(1);
		  	jQuery('.dialog-buttons-wrapper #btn-buy-ticket').trigger('click');
		  	setTimeout(function() {
		  		loader(0);
		  	}, 2000);
		}

		if (jQuery(this).parent().attr('class') == "donate") {
			e.preventDefault();
			staticPage = false;
			//swal.close();
		    loader(1);
		  	jQuery('.dialog-buttons-wrapper #btn-donate').trigger('click');
		  	setTimeout(function() {
		  		loader(0);
		  	}, 2000);
		}

		// skip logout
		if (jQuery(this).attr('href') != "/" && jQuery(this).attr('href') != "/user/logout" && staticPage) {
			openStaticPage(jQuery(this));
			e.preventDefault();
			console.log("Open static page");
		}
	});		
});

function contestant_video() {
  	jQuery(".video-player").each(function() {
  		_id = jQuery(this).attr('video-id');
		_type = jQuery(this).attr('video-type');
		_file = jQuery(this).attr('video-file');
		_thumb = jQuery(this).attr('video-thumb')
  		var appendVideo = '<video id="contestant_video_player_'+_id+'" class="video-js vjs-default-skin" controls preload="none" width="400" height="300" poster="'+_thumb+'" data-setup="{ "html5":{"nativeTextTracks":false}}"><source src="'+_file+'" type="'+_type+'" /><p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p></video>';
            jQuery("#video-player-"+_id).append(appendVideo);
        videojs('contestant_video_player_'+_id);
  	});

	/*jQuery('.play-video.colorbox').colorbox({
		width: "650px", 
		height: "274",
		inline: true,
		rel: 'video',
		onLoad: function() {
			vid = jQuery(this).attr('vid');
				vtype = jQuery(this).attr('type');
				vfile = jQuery(this).attr('vfile');
            var appendVideo = '<video id="contestant_video_player" class="video-js vjs-default-skin" controls preload="none" width="598" height="478" poster="/video/thumbnail/'+vid+'" data-setup="{ "html5":{"nativeTextTracks":false}}"><source src="'+vfile+'" type="'+vtype+'" /><p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p></video>';
            jQuery("#video-player").append(appendVideo);
        },
        onComplete: function() {
            videojs('contestant_video_player');
        },
        onClosed: function() {
            videojs('contestant_video_player').dispose();
        }
	});*/
}

function edit_account() {
	jQuery(".my-account-edit").click(function() {
		jQuery("#btn-edit-account").trigger('click');
		loader(1);
		jQuery('.dialog-my-account').remove();
	});
}

// Welcome sweetalert box. Box param: false = Welcome
function displayWelcome(box) {
	if ((jQuery("#block-userlogin").length && jQuery("#block-userlogin").css('display') != "none")) {
		return;
	}

	// show login window
	if (jQuery.urlParam('login')) {
		jQuery.ui.dialogr.maxZ += 1;
		jQuery('#block-userlogin').css('z-index', jQuery.ui.dialogr.maxZ).center().fadeIn();
		return;
	}

	if (!box) {
		console.log("welcome box");
		/*if (!sweetalert_content && jQuery('#block-ows-theme-content #welcome-box').length) {
			sweetalert_content = jQuery('#block-ows-theme-content #welcome-box');
		}*/
		
		if (!sweetalert_content && jQuery('#block-ows-theme-content #welcome-box').length) {
			sweetalert_title = jQuery('#block-ows-theme-content #welcome-box').attr('title');
			sweetalert_content = jQuery('#block-ows-theme-content #welcome-box');
			sweetalert_class = jQuery('#block-ows-theme-content #welcome-box').attr('class');
		}

		// remove enter contest button for those who registered
		if (jQuery.cookie('enter-contest')) {
			sweetalert_content.find('a#swal-btn-register').remove();
		}

		if (sweetalert_content) {
			//if (sweetalert_class != "swal-add-me")
				sweetalert_title += ' <span class="swal-close"></span>';
			
			swal({
				title: sweetalert_title,
				text: sweetalert_content.html(),
				html: true,
				customClass: 'twitter',
				showConfirmButton: false,
				allowEscapeKey: true, // turn on for debug only
				allowOutsideClick: false
				//allowEscapeKey: false
			});

			jQuery(".sweet-alert").css('z-index', ++jQuery.ui.dialogr.maxZ);

			scrollbar('.welcome-text', false);

			jQuery('.sweet-alert').draggable({ containment: "html" });
			jQuery('.sweet-alert').center();

			// ------------------------------
			// blind click event for button - click this button will trigger drupal button 
			jQuery('.sweet-alert #swal-btn-register').on('click', function() {
				//swal.close();
				loader(1);
		  		jQuery('.dialog-buttons-wrapper #btn-enter-contest').trigger('click');
			});

		    // ------------------------------
		    // Vote
		    jQuery('.sweet-alert #swal-btn-vote').on('click', function() {
		    	//swal.close();
		    	loader(1);
		  		jQuery('.dialog-buttons-wrapper #btn-vote').trigger('click');
		  	});

		    // ------------------------------
		    // Browse
		    jQuery('.sweet-alert #swal-btn-browse').on('click', function() {
		    	/*swal.close();
		    	loader();
		  		jQuery('.dialog-buttons-wrapper #btn-browse').trigger('click');
		  		*/
		  		displayWelcome('browse-box');
		  	});

		  	// ------------------------------
		    // Add my name
		    jQuery('.sweet-alert #swal-btn-add-me').on('click', function() {
		    	//swal.close();
		    	loader(1);
		  		jQuery('.dialog-buttons-wrapper #btn-add-me').trigger('click');
		  	});
		}
	} else if (box == 'browse-box') {
		if (jQuery('#block-ows-theme-content #browse-box').length) {
			box_content = jQuery('#block-ows-theme-content #browse-box');
		}

		if (box_content) {
			swal({
				title: '<span class="swal-back" title="Back"></span> Browse <span class="swal-close" title="Close"></span>',
				text: box_content.html(),
				html: true,
				customClass: 'twitter',
				showConfirmButton: false,
				allowEscapeKey: true, // turn on for debug only
				allowOutsideClick: false
				//allowEscapeKey: false
			});

			jQuery('.sweet-alert').center();
			jQuery('.sweet-alert').draggable({ containment: "html" });

			// ------------------------------
		    // Browse
		    jQuery('.sweet-alert #swal-btn-men').on('click', function() {
		    	swal.close();
		    	loader(1);
		  		jQuery('.dialog-buttons-wrapper #btn-men').trigger('click');
		  	});

		    // Browse women
		  	jQuery('.sweet-alert #swal-btn-women').on('click', function() {
		    	swal.close();
		    	loader(1);
		  		jQuery('.dialog-buttons-wrapper #btn-women').trigger('click');
		  	});
		}
	}

	// swal bind event close event 
	jQuery('.swal-close').click(function() {
		swal.close();
	});

	// swal bind event close event 
	jQuery('.swal-back').click(function() {
		displayWelcome(false);
	});

	// z-index
	jQuery('.sweet-alert').click(function(event) {
		// only incease z-index if user click on windows but not link to open new dialog
		jQuery.ui.dialogr.maxZ += 1;
		jQuery(this).css('z-index', jQuery.ui.dialogr.maxZ);
	});
}

/*function owsDialogCallback(action, klass) {
	if (action == "close") {
		jQuery(klass).dialog().dialog('close');
	}
}*/


function dialogOpened(dialog_class) {
	return jQuery(dialog_class + ' #drupal-modal').length;
}

function openOWSDialog(dialog_class) {
	jQuery('.ui-widget-overlay').remove();
	dialog_class = "." + dialog_class;

	dialogs++;
	// get dialog object
	console.log('Dialog open callback:' + dialog_class);
	
	// make dialog draggable
	/*jQuery(dialog_class + ".ui-dialog").draggable({
		drag: function (event, ui) {
			setTimeout(function() {
				//scrollbar(dialog_class);
			}, 500);
		}
	});*/

	// contestant detail event, already binded in ajaxcompleted
	// if (dialog_class == '.dialog-browse') { browseContestant(dialog_class); }
	
	// skip scrollbar
	if (dialog_class != ".dialog-add-me") {
		scrollbar(dialog_class, true);
	}

	loader(false);

	// --------------------
	// dialog z-index	
	// for parent dialog
	if (dialog_class == ".dialog-browse") {
		jQuery(dialog_class).click(function(event) {
			tag = jQuery(event.target).prop("tagName");
			// only incease z-index if user click on windows but not link to open new dialog
			if (jQuery(event.target).attr('class') != "field-content" && tag != "IMG") {
				jQuery.ui.dialogr.maxZ += 1;
				jQuery(this).css('z-index', jQuery.ui.dialogr.maxZ);
			}
		});

		browser_fields_ghost_text();

		/*var filter_interval = setInterval(function() {
			if (jQuery(".dialog-browse .ui-dialog-buttonpane .form-actions").length > 0) {
				jQuery(".dialog-browse .ui-dialog-buttonpane .form-actions").prepend('<button type="button" class="button filter-button">Filter</button>');
				jQuery(".dialog-browse .filter-button").click(function(event) {
					jQuery("#views-exposed-form-browse-page-browse").toggle();
				});
				clearInterval(filter_interval);
			}
		}, 200);*/
	} 
	
	// --------------
	// increase zindex for opened windows
	jQuery.ui.dialogr.maxZ += 1;
	jQuery(dialog_class).css('z-index', jQuery.ui.dialogr.maxZ);

	// --------------
	// append minimize, maximize for default dialog
	if (jQuery(dialog_class).hasClass('dialog-default')) {
		maximize = '<a class="ui-dialog-titlebar-max" id="dialog-maximize" href="#"><span>Max</span></a>';
		minimize = '<a class="ui-dialog-titlebar-min" id="dialog-minimize" href="#"><span>Min</span></a>';
		restore = '<a class="ui-dialog-titlebar-rest" id="dialog-restore" href="#" style="display: none;"><span>Restore</span></a>';
		jQuery(dialog_class + ' .ui-dialog-titlebar').append(maximize + minimize + restore);

		// bind event for new icons.
		jQuery(dialog_class + ' .ui-dialog-titlebar-max').click(function(event) {
			dialog_maximize(dialog_class);
		});

		jQuery(dialog_class + ' .ui-dialog-titlebar-min').click(function(event) {
			dialog_minimize(dialog_class);
		});

		jQuery(dialog_class + ' .ui-dialog-titlebar-rest').click(function(event) {
			dialog_restore(dialog_class);
		});
	}

	// --------------------
	if (dialog_class == ".dialog-add-me") {

	}

	// --------------------
	// Measurement
	if (dialog_class == ".dialog-enter-contest") {
		jQuery(".form-item-measurement select").change(function() {
			if (jQuery(this).val() == "US Standard") {
				jQuery(".form-item-height .measurement-suffix").html("inch");
				jQuery(".form-item-weight .measurement-suffix").html("pound");
				jQuery(".form-item-bust .measurement-suffix").html("inch");
				jQuery(".form-item-waist .measurement-suffix").html("inch");
				jQuery(".form-item-hips .measurement-suffix").html("inch");
			} else if (jQuery(this).val() == "Metric") {
				jQuery(".form-item-height .measurement-suffix").html("cm");
				jQuery(".form-item-weight .measurement-suffix").html("kg");
				jQuery(".form-item-bust .measurement-suffix").html("cm");
				jQuery(".form-item-waist .measurement-suffix").html("cm");
				jQuery(".form-item-hips .measurement-suffix").html("cm");
			} else {
				jQuery(".form-item-height .measurement-suffix").html("");
				jQuery(".form-item-weight .measurement-suffix").html("");
				jQuery(".form-item-bust .measurement-suffix").html("");
				jQuery(".form-item-waist .measurement-suffix").html("");
				jQuery(".form-item-hips .measurement-suffix").html("");
			}
		});

		// gender change
		jQuery(".form-item-gender .form-radio").change(function() {
			jQuery(".form-item-gender .form-radio").each(function(index, el) {
				if (jQuery(this).is(":checked")) {
					if(jQuery(this).val() == "Male") {
						jQuery(".form-item-bust label").html("Chest");
					} else {
						jQuery(".form-item-bust label").html("Bust");
					}
				}
			});
		});
	}

	if (dialog_class == ".dialog-my-account") {
		
	}

	// ---------------
	// dialog resize
	jQuery('.ui-dialog').resize(function() {
		// browse
		if (jQuery(this).hasClass("dialog-browse")) {
			if (jQuery(this).width() < 600) {
				if (jQuery(this).width() < 400) {
					jQuery(this).find('.views-row').addClass('col-2').removeClass('col-3').removeClass('col-5');
				} else {
					jQuery(this).find('.views-row').addClass('col-3').removeClass('col-2').removeClass('col-5');
				}
			} else {
				jQuery(this).find('.views-row').addClass('col-5').removeClass('col-2').removeClass('col-3');
			}
		}
	});
}

// -------------------
// Dialog custom events
function dialog_restore(dialog_class) {
	console.log("Restore");
	jQuery(dialog_class).draggable("enable");
	jQuery(dialog_class + " .ui-dialog-titlebar").css("cursor", "move");

	window.maximized = false; /* reset both states (restored) */
	window.minimized = false;
	$this = jQuery(dialog_class);

	// get saved state
	var _w = _h = _t = _l = false;
	if (jQuery(dialog_class).attr('w') != undefined) {
		_w = jQuery(dialog_class).attr('w');
	}

	if (jQuery(dialog_class).attr('h') != undefined) {
		_h = jQuery(dialog_class).attr('h');
	}

	if (jQuery(dialog_class).attr('t') != undefined) {
		_t = jQuery(dialog_class).attr('t');
	}

	if (jQuery(dialog_class).attr('l') != undefined) {
		_l = jQuery(dialog_class).attr('l');
	}

	$this.find('.ui-dialog-content').show();
	jQuery('.ui-dialog-titlebar-rest', $this).hide();
	jQuery('.ui-dialog-titlebar-max', $this).show();
	jQuery('.ui-dialog-titlebar-min', $this).show();
		
	_width = 650;
	_height = 400;

	if (_w)  _width = _w;
	if (_h)  _height = _h;

	if ($this.attr('dialog_width')) _width = $this.attr('dialog_width');
	if ($this.attr('dialog_height')) _height = $this.attr('dialog_height');

	$this.css( {
		position : 'absolute',
		width : _width,
		height : _height
	});

	// set height for #drupal-modal incase there is submit button
	if (jQuery(dialog_class).find('.ui-dialog-buttonpane').length) {
		_h = _height - (jQuery(dialog_class).find('.ui-dialog-buttonpane').height() + 70);
		$this.find('.ui-dialog-content').css('height', _h + 'px');
	}

	// $this.position(this.options.position);
	
	// center this dialog
	// $this.center();

	$this.find('#dialog-restore').css('right', '1.5em');
	//$this._setOption("resizable", true);
	//$this._setOption("draggable", true);
	$this.removeClass("dialogr-minimized");
	//$('.ui-dialog-titlebar ').css('background', 'none repeat scroll 0 0 #FFFFFF');
	
	/* Temporary close
	$this.originalSize();
	$this.changeTheSize();
	$this.adjustScrollContent();
	$this.moveToTop(true);*/

	/*
	* FORMALTIS :
	* auto position minimized windows (taskbar style).
	* eventually resize windows
	*/
	// total width available
	var tw = $this.parent().width();
	// Compute windows' width
	var nb = jQuery('.dialogr-minimized:visible').size();
	var w = Math.min((tw - 2 * 10 - (nb - 1) * 5) / nb, 250);
	// and do it !
	var left = 10;
	jQuery('.dialogr-minimized:visible').each(function() {
	  var $t = jQuery(this);
	  //$t.width(w);
	  $t.css('left', left);
	  left += w + 5;
	});

	// reposition
	if (_t) $this.css('top', _t);
	if (_l) $this.css('left', _l);

	/* end */
}
	  
/* Minimize to a custom position */
function dialog_minimize(dialog_class) {
	console.log("Minimize");
	jQuery(dialog_class).draggable('disable');
	jQuery(dialog_class + " .ui-dialog-titlebar").css("cursor", "default");
	
	// save width, height and position
	jQuery(dialog_class).attr('w', jQuery(dialog_class).width());
	jQuery(dialog_class).attr('h', jQuery(dialog_class).height());
	jQuery(dialog_class).attr('t', jQuery(dialog_class).css('top'));
	jQuery(dialog_class).attr('l', jQuery(dialog_class).css('left'));

	window.minimized = true; /* save the current state: minimized */
	window.maximized = false;
	$this = jQuery(dialog_class);
	$this.find('.ui-dialog-content').hide();
	//this._setOption("resizable", false);
	//this._setOption("draggable", false);
	$this.addClass("dialogr-minimized");
	jQuery('.ui-dialog-titlebar-rest', $this).show();
	jQuery('.ui-dialog-titlebar-max', $this).show();
	jQuery('.ui-dialog-titlebar-min', $this).hide();
	jQuery('.ui-dialog-titlebar-rest', $this).css('right', '2.8em');
	$this.css('top', 'auto'); /* needed because top has a default value and this breaks bottom value */
	$this.size();

	$this.css({
		position : "absolute",
		left : 10,
		width : 250,
		height : 100,
		bottom : "-60px"
	});

	$this.css('position', 'fixed'); /* sticky the dialog at the page to avoid scrolling */
	jQuery('.ui-dialog-titlebar-rest', $this).css('display', 'block');

	/*
	* FORMALTIS :
	* auto position minimized windows (taskbar style).
	* eventually resize windows
	*/
	// total width available
	var tw = $this.parent().width();
	// Compute windows' width
	var nb = jQuery('.dialogr-minimized:visible').size();
	var w = Math.min((tw - 2 * 10 - (nb - 1) * 5) / nb, 250);
	// and do it !
	var left = 10;
	jQuery('.dialogr-minimized:visible').each(function() {
	  var $t = jQuery(this);
	  $t.width(w);
	  $t.css('left', left);
	  left += w + 5;
	});
}

function dialog_maximize(dialog_class) {
	jQuery(dialog_class).draggable('disable');
	jQuery(dialog_class + " .ui-dialog-titlebar").css("cursor", "default");

	window.maximized = true; /* save the current state: maximized */
	window.minimized = false;
	$this = jQuery(dialog_class);
	$this.find('.ui-dialog-content').show();

	// store size of current dialog
	$this.attr('dialog_width', $this.width());
	$this.attr('dialog_height', $this.height());

	/* A different width and height for each browser...wondering why? */
	marginHDialog = 25;
	marginWDialog = 25;
	if (jQuery.browser.msie && $.browser.version == 8) {
	  marginHDialog = 25;
	  marginWDialog = 52;
	}

	// let windows maximize full
	marginHDialog = marginWDialog = 0;
	left = 0; // left = 10;

	marginHDialog = jQuery(window).height() - marginHDialog;
	marginWDialog = jQuery('body').width() - marginWDialog;
	//console.log('maximize to '+marginWDialog+", $('body').width() : "+$('body').width());
	$this.css( {
		left : left,
		top : jQuery(document).scrollTop() + 0, // 5
		width : marginWDialog + "px",
		height : marginHDialog + "px"
	});

	// set height for #drupal-modal incase there is submit button
	if (jQuery(dialog_class).find('.ui-dialog-buttonpane').length) {
		_h = marginHDialog - (jQuery(dialog_class).find('.ui-dialog-buttonpane').height() + 70);
		$this.find('.ui-dialog-content').css('height', _h + 'px');
	}

	if (jQuery.ui.dialogr.maxZ < 9999) jQuery.ui.dialogr.maxZ = 10000;

	$this.css('z-index', jQuery.ui.dialogr.maxZ);
	console.log($this.css('z-index'));

	//$('.ui-dialog').trigger("resize");
	$this.removeClass("dialogr-minimized");
	//$('.ui-dialog-titlebar ').css('background', 'none repeat scroll 0 0 #FFFFFF');

	jQuery('.ui-dialog-titlebar-rest', $this).show();
	jQuery('.ui-dialog-titlebar-max', $this).show();
	jQuery('.ui-dialog-titlebar-min', $this).show();

	jQuery('.ui-dialog-titlebar-rest', this).css('right', '1.5em');

	$this.size();
	$this.css('position', 'absolute');
	// this.adjustScrollContent();
	//this.moveToTop(true);

	/*
	* FORMALTIS :
	* auto position minimized windows (taskbar style).
	* eventually resize windows
	*/
	// total width available
	var tw = $this.parent().width();
	// Compute windows' width
	var nb = jQuery('.dialogr-minimized:visible').size();
	var w = Math.min((tw - 2 * 10 - (nb - 1) * 5) / nb, 250);
	// and do it !
	var left = 10;
	jQuery('.dialogr-minimized:visible').each(function() {
	  var $t = jQuery(this);
	  $t.width(w);
	  $t.css('left', left);
	  left += w + 5;
	});
}

function openStaticPage(obj) {
	page = obj.attr('data-drupal-link-system-path').replace('node/', '');

	if (!jQuery('.page-'+page).length) {
		loader(1);
		jQuery.ajax({
			url: "/ajax-content",
			type: "POST",
			data: {type: "view-page", page: page, r: Math.random()},
			async: false, 
			success: function(data) {
				loader(0);
				callback = "scrollbar('.ui-dialog .page-"+page+"', false); jQuery.ui.dialogr.maxZ += 2; jQuery('.page-"+page+"').css('z-index', jQuery.ui.dialogr.maxZ);";
				openDialog('.page-'+page,  obj.attr('href').replace('/', '').capitalize(), data, 600, 500, false, callback);
			}
		});
	}
}

// open contestant window
function browseContestant(dialog_class) {
	// -----------------
	// view contestant dialog
	jQuery(dialog_class + ' .browse-contestant').on('click', function() {
		id = jQuery(this).attr('id').replace('contestant-', '');

		if (!jQuery('.dialog-contestant-'+id).length) {
			full_name = jQuery(this).find('.views-field-field-first-name .field-content').html() + ' ' + jQuery(this).find('.views-field-field-last-name .field-content').html();
			loader(1);
	    	// browse website
			jQuery.ajax({
				url: "/ajax-content",
				type: "POST",
				data: {type: "view-contestant", id: id, r: Math.random()},
				async: false, 
				success: function(data) {
					loader(0);
					callback = "scrollbar('.ui-dialog .dialog-contestant-"+id+"', false); inviteFriendForm('.dialog-contestant-"+id+" .invite-friend-form'); jQuery('.colorbox').colorbox({rel: 'gallery-item'}); jQuery.ui.dialogr.maxZ += 2; jQuery('.dialog-contestant-"+id+"').css('z-index', jQuery.ui.dialogr.maxZ); contestant_video(); voting('.dialog-contestant-"+id+"', "+id+");";
					openDialog('.dialog-contestant-'+id, full_name, data, 600, 500, false, callback);
				}
			});
		}

		swal.close();
	});
}

function voting(klass, contestant) {
	// append voting slider
	jQuery(klass + ' .voting-contestant .voting-container').append('<input class="voting-slider" type="hidden" value="0" />');
	
	jQuery('.voting-slider').jRange({
	    from: 1,
	    to: 100,
	    step: 1,
	    scale: [0,25,50,75,100],
	    format: '%s',
	    showLabels: true,
	    snap: true,
	});
	
	jQuery('.voting-slider').jRange('setValue', jQuery(".voting-container").attr("score"));
	
	jQuery(klass + ' .voting-contestant .voting-container').append('<button type="button" value="Vote" id="vote-button" class="button">Vote</button>');
	jQuery(klass + ' .personal-information .add-to-favorite').append('<button type="button" value="Add to favorite" id="favorite-button" class="button">+ favorite</button>');
	jQuery(klass + ' .personal-information .my-favorite').append('<button type="button" value="My favorite" class="btn-my-favorite button">My favorite</button>');
	
	jQuery("#favorite-button").click(function(event) {
		addToFavorite(contestant, klass);
	});

	jQuery(".btn-my-favorite").click(function(event) {
		myFavorite();
	});
	
	jQuery('#vote-button').click(function() {
		jQuery.ajax({
			url: "/ajax-content",
			data: {type: "voting", contestant: contestant, score: jQuery('.voting-slider').val(), r: Math.random()},
			type: "POST",
			dataType: "json",
			async: false, 
			success: function(data) {
				loader(0);
				console.log(data);
				if (data.code == 2) {
					if (confirm(data.message)) {
						jQuery.ajax({
							url: "/ajax-content",
							data: {type: "voting-update", contestant: contestant, score: jQuery('.voting-slider').val(), r: Math.random()},
							type: "POST",
							dataType: "json",
							async: false, 
							success: function(data) {
							}
						});
					}
				}

				//callback = "scrollbar('.ui-dialog .page-"+page+"', false); jQuery.ui.dialogr.maxZ += 2; jQuery('.page-"+page+"').css('z-index', jQuery.ui.dialogr.maxZ);";
				//openDialog('.page-'+page,  obj.attr('href').replace('/', '').capitalize(), data, 600, 500, false, callback);
			}
		});
	});

	/*jQuery('.vote-result').click(function(event) {
		jQuery.ajax({
			url: "/ajax-content",
			data: {type: "vote-list", r: Math.random()},
			type: "POST",
			async: false, 
			success: function(data) {
				console.log(data);
				openDialog('.dialog-vote-list', 'Vote Result', data, 600, 500, false, false);
			}
		});
	});*/

}

function addToFavorite(contestant, klass) {
	jQuery.ajax({
		url: "/ajax-content",
		data: {type: "favorite", contestant: contestant, r: Math.random()},
		type: "POST",
		dataType: "json",
		async: false, 
		success: function(data) {
			loader(0);
			if (data.code == 1) {
				jQuery(".add-to-favorite").remove();
			}
		}
	});
}

function myFavorite() {
	jQuery.ajax({
		url: "/ajax-content",
		type: "POST",
		data: {type: "my-favorite", r: Math.random()},
		async: false, 
		success: function(data) {
			loader(0);
			callback = "scrollbar('.ui-dialog .page-current-voting', false); jQuery.ui.dialogr.maxZ += 2; jQuery('.current-voting').css('z-index', jQuery.ui.dialogr.maxZ);";
			openDialog('.page-my-favorite', 'My favorite', data, 600, 500, false, callback);
		}
	});
}

/*function voting_contestant() {
	vote-button
}*/

// Invite friend
function inviteFriendForm(klass) {
	html = '<div class="validate"></div>';
	html += '<div class="form-item form-fullname">';
	html += '<label for="edit-name">Friend\'s name</label>';
	html += '<input type="text" class="form-name form-text" maxlength="254" size="60" value="" name="name" id="edit-name">';
	html += '</div>';

	html += '<div class="form-item form-email">';
	html += '<label for="edit-email">Email Address</label>';
	html += '<input type="text" class="form-email form-text" maxlength="254" size="60" value="" name="email" id="edit-email">';
	html += '</div>';

	html += '<div class="form-item form-content">';
	html += '<label for="edit-content">Content</label>';
	html += '<textarea class="form-content form-text" maxlength="254" size="60" value="" name="content" id="edit-content"></textarea>';
	html += '</div>';

	html += '<div id="edit-actions" class="form-actions form-wrapper">';
	html += '<input type="button" class="button button--primary form-submit" value="Invite" name="op" id="edit-submit-invite">';
	html += '</div>';

	// append form. Drupal not allow form return by ajax
	jQuery(klass).html(html);

	// bind event for invite form
	jQuery(klass + ' #edit-actions .form-submit').click(function() {

		loader(1);
		_name = jQuery(klass + ' #edit-name').val();
		_email = jQuery(klass + ' #edit-email').val();
		_content = jQuery(klass + ' #edit-content').val();

		jQuery.ajax({
			url: "/invite-friend",
			data: {name: _name, email: _email, content: _content},
			async: false, 
			success: function(data) {
				loader(0);
				if (data == 1) {
					swal("Thank you", "Invite has been sent.", "success");					
				} else if (data == 0) {
					swal("Error", "There is an error while sending invite, please try again later.", "error");
				} else {
					swal("Error", data, "error");
				}

				jQuery(".sweet-alert").center();
			}
		});
	});
}

function closeOWSDialog(dialog_class) {
	console.log(dialog_class);
	// dialog_class = "." + dialog_class;
	if (dialogs > 0) dialogs--;
	else dialogs = 0;
	//if (dialogs == 0) displayWelcome(false);

	// show welcome message if user close Add Me form
	if (dialog_class == "dialog-add-me") displayWelcome(false);
}

function anyDialogActive() {
	// no more dialog, display welcome mesasge
	if(dialogs == 0) {
		// displayWelcome(false);
	}
}

function scrollbar(klass, is_dialog) {
	if (is_dialog) klass = klass + ' .ui-dialog-content';
	else _k = klass;

	jQuery(klass).mCustomScrollbar({
		live:true,
		//theme:"inset-dark"
		theme:"rounded-dark",
		scrollInertia: 4
	});
}

// loading
function loader(flag) {
	if (flag) {
		jQuery('.load-container').center().show();
	} else jQuery('.load-container').hide();
}

/*
------------------------------
dialog content
read jquery ui dialog documentation
element: element class 
title: dialog title
data: dialog data
width & height: dialog size
is_new: create new dialog
callback: callback for opened dialog
*/
function openDialog(element, title, data, width, height, is_new, callback) {
	// default dialog
	/* // check dialog element 
	if (!jQuery('.ui-dialog ' +element).length) {
		jQuery('.ui-dialog ' +element).remove();
	} else {
		//jQuery(element).dialogr("open");
	}*/
	
	// create dialog with content
	jQuery('<div class="'+element.replace('.', '')+'" style="display:none;">' + data + '</div>').appendTo('body');
	//<a href="#nojs" class="use-ajax">Test</a>

	jQuery(element).dialogr({
		title: title,
		autoResize: true,
		width: width,
		height: height,
		fluid: true,
		minWidth: 470,
		dialogClass: element.replace('.', ''),
		maximizable: true,
		open: function( event, ui ) {
			w = jQuery(element + ' .ui-dialog-content').width();
			jQuery(element + ' .ui-dialog').width(w);
			scrollbar(element + ' .ui-dialog-content', true);

			// execute callback
			console.log("Dialogr: " + callback);
			if (callback) eval(callback);
			dialogs++;
		},
		dragStop: function(event, ui) {
			// refresh scrollbar
		},
		close: function(event, ui) {
			if (dialogs > 0) dialogs--;
			anyDialogActive();
			// destroy dialog when close
			jQuery(element).remove();
		}
	});
}

// ajax complete event
jQuery(document).ajaxComplete(function(event, xhr, settings) {
	console.log("AjaxComplete");

	// user clicks on pager
	try {
		if (dialog_class.toString() == 'dialog-browse') {
			browseContestant('.dialog-browse');

			/*
			jQuery('.' + dialog_class.toString() + ' .browse-contestant').on('click', function() {
				id = jQuery(this).attr('id').replace('contestant-', '');

				if (!jQuery('.dialog-contestant-'+id).length) {
					full_name = jQuery(this).find('.views-field-field-first-name .field-content').html() + ' ' + jQuery(this).find('.views-field-field-last-name .field-content').html();
					
			    	// browse website
					jQuery.ajax({
						url: "/ajax-content",
						data: {type: "view-contestant", id: id},
						async: false, 
						success: function(data) {
							callback = "scrollbar('.ui-dialog .dialog-contestant-"+id+"', false);";
							openDialog('.dialog-contestant-'+id, full_name, data, 600, 500, false, callback);
						}
					});
				}

		    	swal.close();
		    });*/
		}
	} catch (e) {}

	// validate
	if (jQuery(".validate.error").length) {
		jQuery(".ui-dialog .ui-dialog-content").mCustomScrollbar("scrollTo", "top");
	}
});

function browser_fields_ghost_text() {
	// Add ghost text for filters
	// jQuery('.dialog-browse .form-item-gender option:first-child').text("- Gender -");
	jQuery('.dialog-browse .form-item-field-country-value option:first-child').text("- Country -");
	jQuery('.dialog-browse .form-item-eyes-color option:first-child').text("- Eyes Color -");
	jQuery('.dialog-browse .form-item-field-hair-color-value option:first-child').text("- Hair Color -");

	// Height
	jQuery(".dialog-browse .form-item-field-height-value input").val("Height").focus(function() {
		if (jQuery(this).val() == "Height") jQuery(this).val("");
	}).focusout(function() {
		if (jQuery(this).val() == "") jQuery(this).val("Height");
	});

	// Weight
	jQuery(".dialog-browse .form-item-field-weight-value input").val("Weight").focus(function() {
		if (jQuery(this).val() == "Weight") jQuery(this).val("");
	}).focusout(function() {
		if (jQuery(this).val() == "") jQuery(this).val("Weight");
	});

	// Age
	jQuery(".dialog-browse .form-item-age input").val("Age").focus(function() {
		if (jQuery(this).val() == "Age") jQuery(this).val("");
	}).focusout(function() {
		if (jQuery(this).val() == "") jQuery(this).val("Age");
	});

	// Bust
	jQuery(".dialog-browse .form-item-bust input").val("Bust/Chest").focus(function() {
		if (jQuery(this).val() == "Bust/Chest") jQuery(this).val("");
	}).focusout(function() {
		if (jQuery(this).val() == "") jQuery(this).val("Bust/Chest");
	});
}

function toggleFullScreen(elem) {
    // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (elem.requestFullScreen) {
            elem.requestFullScreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullScreen) {
            elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

/* jQuery lib */
jQuery.fn.isBound = function(type, fn) {
    var data = this.data('events')[type];

    if (data === undefined || data.length === 0) {
        return false;
    }

    return (-1 !== $.inArray(fn, data));
};

jQuery.fn.center = function () {
   this.css("position","absolute");
   this.css("top", ( jQuery(window).height() - this.height() ) / 2  + "px");
   this.css("left", ( jQuery(window).width() - this.width() ) / 2 + "px");
   return this;
}

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

jQuery.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}

if (!window.console) window.console = {};
if (!window.console.log) window.console.log = function () {
	alert(1);
};
