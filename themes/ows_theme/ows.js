var dialogs = 0;
var dialog_class = '';
var sweetalert_content = false;

jQuery(document).ready(function() {
	jQuery("body").delay(5000).vegas({
		overlay: drupalSettings.path.baseUrl+"themes/ows_theme/css/overlays/01.png",
		transitionDuration: 3000,
		preload: true,
		delay: 5000,
	    slides: [
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide1.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide2.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide3.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide4.jpg" },
	    ]
	});

	// Prevent event on Home menu
	jQuery('li.home a').click(function(e) {
		e.preventDefault();
		displayWelcome();
	});

	jQuery(window).resize(function() {
		jQuery(".sweet-alert").center();

		jQuery(".ui-dialog").each(function() {
			if (jQuery(this).width() < 980) {
				jQuery(this).center();
			}
		});
	});

	videojs.options.flash.swf = "/themes/ows_theme/video-js.swf";
});

jQuery(document).ready(function() {
	// nice scrollbar

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
		jQuery('#block-userlogin').center().fadeIn();
		return;
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

	jQuery('#block-mainmenu').parent().append('<a id="nav-toggle" href="#"><span></span></a>');
	document.querySelector("#nav-toggle")
  		.addEventListener( "click", function() {
    	this.classList.toggle( "active" );
    	jQuery('#block-mainmenu').slideToggle();
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
	if (!box) {
		console.log("welcome box");
		/*if (!sweetalert_content && jQuery('#block-ows-theme-content #welcome-box').length) {
			sweetalert_content = jQuery('#block-ows-theme-content #welcome-box');
		}*/

		if (!sweetalert_content && jQuery('#block-ows-theme-content #welcome-box').length) {
			sweetalert_content = jQuery('#block-ows-theme-content #welcome-box');
		}

		// remove enter contest button for those who registered
		if (jQuery.cookie('enter-contest')) {
			sweetalert_content.find('a#swal-btn-register').remove();
		}

		if (sweetalert_content) {
			swal({
				title: 'Welcome to OWS <span class="swal-close"></span>',
				text: sweetalert_content.html(),
				html: true,
				customClass: 'twitter',
				showConfirmButton: false,
				allowEscapeKey: true, // turn on for debug only
				allowOutsideClick: false
				//allowEscapeKey: false
			});

			jQuery('.sweet-alert').center();
			jQuery('.sweet-alert').draggable({ containment: "parent" });

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
			jQuery('.sweet-alert').draggable({ containment: "parent" });

			// ------------------------------
		    // Browse
		    jQuery('.sweet-alert #swal-btn-men').on('click', function() {
		    	swal.close();
		    	loader();
		  		jQuery('.dialog-buttons-wrapper #btn-men').trigger('click');
		  	});

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
	
	scrollbar(dialog_class);
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
				data: {type: "view-contestant", id: id},
				async: false, 
				success: function(data) {
					loader(0);
					callback = "scrollbar('.ui-dialog .dialog-contestant-"+id+"', false); inviteFriendForm('.dialog-contestant-"+id+" .invite-friend-form'); jQuery('.colorbox').colorbox({rel: 'gallery-item'}); jQuery.ui.dialogr.maxZ += 2; jQuery('.dialog-contestant-"+id+"').css('z-index', jQuery.ui.dialogr.maxZ); contestant_video(); ";
					openDialog('.dialog-contestant-'+id, full_name, data, 600, 500, false, callback);
				}
			});
		}

		swal.close();
	});
}


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
	if (dialogs == 0) displayWelcome();
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
			console.log("Dialogr" + callback);
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
    this.css("top", Math.max(0, ((jQuery(window).height() - jQuery(this).outerHeight()) / 2) + 
                                                jQuery(window).scrollTop()) + "px");
    this.css("left", Math.max(0, ((jQuery(window).width() - jQuery(this).outerWidth()) / 2) + 
                                                jQuery(window).scrollLeft()) + "px");
    return this;
}
