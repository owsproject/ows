var dialogs = 0;
var dialog_class = '';
var sweetalert_content = false;
var callbackInterval = false;
var scrollOptions = {horizrailenabled: false};

jQuery(document).ready(function() {
	jQuery("body").vegas({
		overlay: drupalSettings.path.baseUrl+"themes/ows_theme/css/overlays/01.png",
		transitionDuration: 4000,
		preload: true,
		delay: 5000,
	    slides: [
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide1.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide2.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide3.jpg" },
	        { src: drupalSettings.path.baseUrl+"themes/ows_theme/css/sliders/slide4.jpg" },
	    ]
	});

	jQuery('li.home a').click(function(e) {
		e.preventDefault();
		displayWelcome();
	});
});

jQuery(document).ready(function() {
	// nice scrollbar
    // jQuery("html").niceScroll(scrollOptions);

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
		jQuery('.'+dialog_class.toString() + ' .ui-dialog').draggable();
		openOWSDialog(dialog_class.toString());
	};

	drupalSettings.dialog.close = function(event) {
		console.log('Dialog Close');
		dialog_class = jQuery(event.target).parent().attr('class').match(/dialog-[\w-]*\b/);
		closeOWSDialog(dialog_class.toString());
	};
});

// Welcome sweetalert box
function displayWelcome() {
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
			title: 'Welcome to OWS',
			text: sweetalert_content.html(),
			html: true,
			customClass: 'twitter',
			showConfirmButton: false,
			allowEscapeKey: true, // turn on for debug only
			allowOutsideClick: false
			//allowEscapeKey: false
		});

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

	    // ------------------------------
	    // Browse
	    jQuery('.sweet-alert #swal-btn-browse').on('click', function() {
	    	swal.close();
	    	loader();
	  		jQuery('.dialog-buttons-wrapper #btn-browse').trigger('click');
	  	});
	}
}

function dialogOpened(dialog_class) {
	return jQuery(dialog_class + ' #drupal-modal').length;
}

function openOWSDialog(dialog_class) {
	jQuery('.ui-widget-overlay').remove();
	dialog_class = "." + dialog_class;

	dialogs++;
	// get dialog object
	console.log('Dialog Open callback:' + dialog_class);
	
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
					callback = "scrollbar('.ui-dialog .dialog-contestant-"+id+"', false); inviteFriendForm('.dialog-contestant-"+id+" .invite-friend-form');";
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
	html += '<label for="edit-name">Your friend name</label>';
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
			//jQuery(element + ' .ui-dialog-content').niceScroll(scrollOptions);
			scrollbar(element + ' .ui-dialog-content');

			// execute callback
			console.log(callback);
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
