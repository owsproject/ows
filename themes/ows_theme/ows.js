var dialogs = 0;
var dialog_class = '';
var sweetalert_title = false;
var sweetalert_content = false;
var sweetalert_class = false;

jQuery(document).ready(function() {
	if (location.href.indexOf('/user/login') > -1) {
		location.href = "/home?login=window";
	}

	if (jQuery.ui.dialogr.maxZ == 0) {
		jQuery.ui.dialogr.maxZ = 1000;
	}

	// set height 100%;
	jQuery('body').css('height', jQuery(window).height());

	jQuery("body").delay(5000).vegas({
		overlay: drupalSettings.path.baseUrl+"themes/ows_theme/css/overlays/01.png",
		transitionDuration: 3000,
		preload: true,
		delay: 7000,
	    slides: [
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/background.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/background2.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/background.jpg" }
	        /*{ src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider2.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider3.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider4.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slider5.jpg" }*/
	    ]
	});

	// Prevent event on Home menu
	jQuery('li.home a').click(function(e) {
		e.preventDefault();
		displayWelcome();
	});

	// -------------
	// window resize
	jQuery(window).resize(function() {
		jQuery(".sweet-alert").center();

		jQuery(".ui-dialog").not(".dialogr-minimized").each(function() {
			if (jQuery(this).width() < 980) {
				jQuery(this).center();
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
		loader();
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
});

jQuery(document).ready(function() {
	user_option = jQuery.cookie('user.option');
	if (user_option === undefined) {
		if (jQuery('body').hasClass('path-frontpage')) {
			displayWelcome();
		}
	} else {
		displayWelcome();
	}

	// Check dialog active jQuery('.ui-dialog').length;
	// --------------------------------------------
	// extend dialog options
	drupalSettings.dialog.open = function(event) {
		console.log('Dialog Open');
		// get dialog class to parse for callback
		dialog_class = jQuery(event.target).parent().attr('class').match(/dialog-[\w-]*\b/);

		try {
			jQuery('.'+dialog_class.toString() + ' .ui-dialog').draggable();
			openOWSDialog(dialog_class.toString());
		} catch (e) {}

		// date picker, make sure element exist
		try {
			if (jQuery("input.form-date").length) {
				jQuery("input.form-date").attr('readonly', 'readonly').attr('data-language', 'en').datepicker({
					autoClose: true
				});
			}
		} catch (e) {}
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
		jQuery('.user-logged-in #block-mainmenu').parent().append('<div id="user-menu" class="user-menu dropdown"><div class="drop-ttl">&nbsp;</div><ul><li class="my-vote">My Vote</li><li class="my-account">My Account</li></ul></label></div></div>');
		jQuery('#block-mainmenu').parent().append('<a id="nav-toggle" href="#"><span></span></a>');

		// user menu click
		jQuery('#user-menu').click(function(e) {
			jQuery('#user-menu.dropdown ul').toggle();
		});

		// my vote click 
		jQuery('.my-vote').click(function(e) {
			jQuery.ajax({
				url: "/ajax-content",
				type: "POST",
				data: {type: "my-vote", r: Math.random()},
				async: false, 
				success: function(data) {
					loader(0);
					callback = "scrollbar('.ui-dialog .page-my-voting', false); jQuery.ui.dialogr.maxZ += 2; jQuery('.current-voting').css('z-index', jQuery.ui.dialogr.maxZ);";
					openDialog('.page-my-voting', 'My Vote', data, 600, 500, false, callback);
				}
			});
		});
		
		document.querySelector("#nav-toggle")
	  		.addEventListener( "click", function() {
	    	this.classList.toggle( "active" );
	    	jQuery('#block-mainmenu').slideToggle();
	  	});
	} catch(e) {}

	// menu click
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

		if (jQuery(this).parent().attr('class') == "social") {
			e.preventDefault();
			staticPage = false;


		}

		// skip logout
		if (jQuery(this).attr('href') != "/user/logout" && staticPage) {
			e.preventDefault();
			openStaticPage(jQuery(this));
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

// Welcome sweetalert box
// Box param: false = Welcome
function displayWelcome(box = false) {

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

			scrollbar('.welcome-text', false);

			jQuery('.sweet-alert').draggable({ containment: "html" });
			jQuery('.sweet-alert').center();

			// ------------------------------
			// blind click event for button - click this button will trigger drupal button 
			jQuery('.sweet-alert #swal-btn-register').on('click', function() {
				swal.close();
				loader();
		  		jQuery('.dialog-buttons-wrapper #btn-enter-contest').trigger('click');
			});

		    // ------------------------------
		    // Vote
		    jQuery('.sweet-alert #swal-btn-vote').on('click', function() {
		    	swal.close();
		    	loader();
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
		    	swal.close();
		    	loader();
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
		    	loader();
		  		jQuery('.dialog-buttons-wrapper #btn-men').trigger('click');
		  	});

		    // Browse women
		  	jQuery('.sweet-alert #swal-btn-women').on('click', function() {
		    	swal.close();
		    	loader();
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
		displayWelcome();
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
		scrollbar(dialog_class);
	}

	loader(false);

	// --------------------
	// dialog z-index	
	// for parent dialog
	if (dialog_class == ".dialog-browse") {
		jQuery(dialog_class).click(function(event) {
			tag = jQuery(event.target).prop("tagName");
			// only incease Zindex if user click on windows but not link to open new dialog
			if (jQuery(event.target).attr('class') != "field-content" && tag != "IMG") {
				jQuery.ui.dialogr.maxZ += 1;
				jQuery(this).css('z-index', jQuery.ui.dialogr.maxZ);
			}
		});

		// Add ghost text for filters
		jQuery('.dialog-browse .form-item-field-country-value option:first-child').text("- Country -");
		jQuery('.dialog-browse .form-item-field-eyes-color-value option:first-child').text("- Eyes Color -");
		jQuery('.dialog-browse .form-item-field-hair-color-value option:first-child').text("- Hair Color -");
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

	if (dialog_class == ".dialog-add-me") {

	}
}

// -------------------
// Dialog custom events
function dialog_restore(dialog_class) {
	window.maximized = false; /* reset both states (restored) */
	window.minimized = false;
	$this = jQuery(dialog_class);
	$this.find('.ui-dialog-content').show();
	jQuery('.ui-dialog-titlebar-rest', $this).hide();
	jQuery('.ui-dialog-titlebar-max', $this).show();
	jQuery('.ui-dialog-titlebar-min', $this).show();
		
	_width = 650;
	_height = 400;

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
	$this.center();
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
	  var $t = $(this);
	  $t.width(w);
	  $t.css('left', left);
	  left += w + 5;
	});
	/* end */
}
	  
	  /* Minimize to a custom position */
function dialog_minimize(dialog_class) {
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
	marginHDialog = jQuery(window).height() - marginHDialog;
	marginWDialog = jQuery('body').width() - marginWDialog;
	//console.log('maximize to '+marginWDialog+", $('body').width() : "+$('body').width());
	$this.css( {
		left : 10,
		top : jQuery(document).scrollTop() + 5,
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
		loader();
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
			loader();
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

/*function voting_contestant() {
	vote-button
}*/


// Invite friend
function inviteFriendForm(klass) {
	html = '<div class="form-item form-fullname">';
	html += '<label for="edit-name">Friend\'s name</label>';
	html += '<input type="text" class="form-name" maxlength="254" size="60" value="" name="name" id="edit-name">';
	html += '</div>';

	html += '<div class="form-item form-email">';
	html += '<label for="edit-email">Email Address</label>';
	html += '<input type="text" class="form-email" maxlength="254" size="60" value="" name="email" id="edit-email">';
	html += '</div>';

	html += '<div class="form-item form-content">';
	html += '<label for="edit-content">Content</label>';
	html += '<textarea class="form-content" maxlength="254" size="60" value="" name="content" id="edit-content"></textarea>';
	html += '</div>';

	html += '<div id="edit-actions" class="form-actions form-wrapper">';
	html += '<input type="button" class="button button--primary form-submit" value="Invite" name="op" id="edit-submit-invite">';
	html += '</div>';

	// append form. Drupal not allow form return by ajax
	jQuery(klass).html(html);

	// bind event for invite form
	jQuery(klass + ' #edit-actions .form-submit').click(function() {
		loader();
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
					
				} else {

				}
			}
		});
	});
}

function closeOWSDialog(dialog_class) {
	console.log(dialog_class);
	// dialog_class = "." + dialog_class;
	if (dialogs > 0) dialogs--;
	else dialogs = 0;
	//if (dialogs == 0) displayWelcome();
}

function anyDialogActive() {
	// no more dialog, display welcome mesasge
	if(dialogs == 0) {
		displayWelcome();
	}
}

function scrollbar(klass, is_dialog = true) {
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
function loader(flag = true) {
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
function openDialog(element, title, data, width = 500, height = 500, is_new = false, callback = false) {
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
			scrollbar(element + ' .ui-dialog-content');

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
});

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
