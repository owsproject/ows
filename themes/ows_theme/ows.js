var dialogs = 0;
var dialog_class = '';
var sweetalert_content = false;
var callbackInterval = false;

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
		jQuery(".sweet-alert, .ui-dialog").center();
	});
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

  	jQuery('.play-video.colorbox').colorbox({
  		inline:true, 
    	width: "80%", 
    	height: "auto", 
    	href: '.video-player-'+jQuery(this).attr('id')
  	});
});

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
	
	// increase zindex for opened windows
	jQuery.ui.dialogr.maxZ += 1;
	jQuery(dialog_class).css('z-index', jQuery.ui.dialogr.maxZ);
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
					callback = "scrollbar('.ui-dialog .dialog-contestant-"+id+"', false); inviteFriendForm('.dialog-contestant-"+id+" .invite-friend-form'); jQuery('.colorbox').colorbox({rel: 'gallery-item'}); jQuery.ui.dialogr.maxZ += 2; jQuery('.dialog-contestant-"+id+"').css('z-index', jQuery.ui.dialogr.maxZ); ";
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
	html += '<label for="edit-name">Your friend\'s name</label>';
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
		theme:"rounded-dark"
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
