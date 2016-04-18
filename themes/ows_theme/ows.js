var dialogs = 0;
var welcome_box_content = false;

jQuery(document).ready(function() {
	// nice scrollbar
    jQuery("html").niceScroll();

	user_option = jQuery.cookie('user.option');
	if (user_option === undefined) {
		if (jQuery('body').hasClass('path-frontpage')) {
			displayWelcome();
		}
	} else {
		displayWelcome();
	}

	// Check dialog active jQuery('.ui-dialog').length;
});

function test() {
	alert(1);
}

function niceScrollActivate(id) {
	jQuery(id).niceScroll();
}

function displayWelcome() {
	if (!welcome_box_content && jQuery('#block-ows-theme-content #welcome-box').length) {
		welcome_box_content = jQuery('#block-ows-theme-content #welcome-box').html();

		// enter contest button
		// replace button ID
		//welcome_box_content = welcome_box_content.replace('btn-enter-contest', 'sa-btn-enter-contest');

		// replace href to avoid dialog ajax error
		// welcome_box_content = welcome_box_content.replace('/enter-contest', '#enter-contest');
		// jQuery('#block-ows-theme-content #welcome-box').remove();
	}

	if (welcome_box_content) {
		swal({
			title: 'Welcome to OWS',
			text: welcome_box_content,
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
	  		jQuery('.dialog-buttons-wrapper #btn-enter-contest').trigger('click');
	  		
	  		setTimeout(function () {
	  			openOWSDialog();
	  		}, 2000);
		});

	    // ------------------------------
	    // Vote

	    // ------------------------------
	    // Browse
	    jQuery('#btn-browse').on('click', function() {
	    	// write cookie
	    	jQuery.cookie('user.option', 'browse');

	    	// browse website
			jQuery.ajax({
				url: "/ajax-content",
				data: {type: "browse"},
				async: false, 
				success: function(data) {
					openDialog('.dialog-browse', 'Browse', data);
				}
			});	

	    	swal.close();
	    });
	}
}

function openOWSDialog() {
	dialogs++;
	// get dialog object
	jQuery('#drupal-modal').niceScroll();
}

function closeOWSDialog() {
	dialogs--;
	if (dialogs == 0) displayWelcome();
}

/*
Param for callback from dialog modal must be int.
1: enter contest dialog
*/
function owsDialogCallback(dialog) {
	dialog_class = '';

	if (dialog == 1) {
		dialog_class = '.dialog-enter-contest';
	}

	
	jQuery(dialog_class + ' .ui-dialog-titlebar-close').on('click', function() {
		closeOWSDialog();
	});
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
	// check dialog element 
	if (!jQuery('.ui-dialog ' +element).length) {
		jQuery('.ui-dialog ' +element).remove();
	} else {
		//jQuery(element).dialogr("open");
	}
	
	// create dialog with content
	jQuery('<div class="'+element.replace('.', '')+'" style="display:none;">' + data + '</div>').appendTo('body'); //<a href="#nojs" class="use-ajax">Test</a>

	jQuery(element).dialogr({
		title: title,
		autoResize: true,
		width: width,
		height: height,
		fluid: true,
		minWidth: 470,
		dialogClass: element.replace('.', ''),
		'maximizable': true,
		open: function( event, ui ) {
			w = jQuery(element + ' .ui-dialog-content').width();
			jQuery(element + ' .ui-dialog').width(w);

			jQuery(element + " .ui-dialog-content").niceScroll();

			// execute callback
			if (callback) eval(callback);
			dialogs++;
		},
		dragStop: function(event, ui) {
			// refresh scrollbar
			jQuery(element + " .ui-dialog-content").getNiceScroll().resize();	
		},
		close: function(event, ui) {
			if (dialogs > 0) dialogs--;
			anyDialogActive();

			// destroy dialog when close
			jQuery(element).remove()
		}
	});
}

function anyDialogActive() {
	// no more dialog, display welcome mesasge
	if(dialogs == 0) {
		displayWelcome();
	}
}

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, ((jQuery(window).height() - jQuery(this).outerHeight()) / 2) + 
                                                jQuery(window).scrollTop()) + "px");
    this.css("left", Math.max(0, ((jQuery(window).width() - jQuery(this).outerWidth()) / 2) + 
                                                jQuery(window).scrollLeft()) + "px");
    return this;
}

/*close: function (event) {
      Drupal.detachBehaviors(event.target, null, 'unload');
      closeOWSDialog(event.target);
    },
    open: function (event) {
      openOWSDialog(event.target);
    }*/
