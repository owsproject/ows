var dialogs = 0;
var sweetalert_content = false;
var callbackInterval = false;
var scrollOptions = {horizrailenabled: false};

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
		jQuery('.ui-dialog').draggable();
		// get dialog class to parse for callback
		dialog_class = jQuery(event.target).parent().attr('class').match(/dialog-[\w-]*\b/);
		openOWSDialog('.'+dialog_class);
	};

	drupalSettings.dialog.close = function(event) {
		console.log('Dialog Close');
		_dialog_class = jQuery(event.target).parent().attr('class').split(' ').pop();
		closeOWSDialog();
	};

	drupalSettings.dialog.draggable = true;
	drupalSettings.dialog.resizable = true;
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
	  		jQuery('.dialog-buttons-wrapper #btn-enter-contest').trigger('click');
	  		
	  		// we still need interval for default dialog!
	  		callbackInterval = setInterval(function () {
	  			dialog_class = '.dialog-enter-contest';
	  			if (dialogOpened(dialog_class)) {
	  				openOWSDialog(dialog_class);
	  		
		  			// bind close dialog
		  			jQuery(dialog_class + ' .ui-dialog-titlebar-close').on('click', function() {
						closeOWSDialog();
					});

					clearInterval(callbackInterval);
				}
	  		}, 2000);
		});

	    // ------------------------------
	    // Vote

	    // ------------------------------
	    // Browse
	    /*jQuery('#btn-browse, #swal-btn-browse').on('click', function() {
	    	// write cookie
	    	jQuery.cookie('user.option', 'browse');

	    	// browse website
			jQuery.ajax({
				url: "/ajax-content",
				data: {type: "browse"},
				async: false, 
				success: function(data) {
					openDialog('.dialog-browse', 'Browse', data, 600, 500);
				}
			});	

	    	swal.close();
	    });*/

	    // ------------------------------
	    // Browse
	    jQuery('.sweet-alert #swal-btn-browse').on('click', function() {
	    	swal.close();
	  		jQuery('.dialog-buttons-wrapper #btn-browse').trigger('click');
	  		
	  		/* //  we can remove interval, the callback works fine with dialogr
	  		callbackInterval = setInterval(function () {
	  			if (dialogOpened()) {
	  				openOWSDialog('.dialog-browse');
	  				// fix dialog zindex for parent dialog
	  				jQuery('.dialog-browse').click(function() {
	  					jQuery(this).css('z-index', ++jQuery.ui.dialogr.maxZ);
	  				});

	  				// bind close dialog
		  			jQuery('.dialog-browse .ui-dialog-titlebar-close').on('click', function() {
						closeOWSDialog();
					});

					// view contestant dialog
					jQuery('.dialog-browse .browse-contestant').on('click', function() {
						id = jQuery(this).attr('id').replace('contestant-', '');

						if (!jQuery('.dialog-contestant-'+id).length) {
					    	// browse website
							jQuery.ajax({
								url: "/ajax-content",
								data: {type: "view-contestant", id: id},
								async: false, 
								success: function(data) {
									openDialog('.dialog-contestant-'+id, 'Test', data, 600, 500);
								}
							});
						}

				    	swal.close();
				    });

					clearInterval(callbackInterval);
	  			}
	  		}, 2000);*/
	  	});
	}
}

function dialogOpened(dialog_class) {
	return jQuery(dialog_class + ' #drupal-modal').length;
}

function openOWSDialog(dialog_class) {
	dialogs++;
	// get dialog object
	console.log('Dialog Open callback');
	// refresh nicescroll
	jQuery(dialog_class + ' #drupal-modal').niceScroll(scrollOptions);
	// make dialog draggable
	jQuery(dialog_class + ".ui-dialog").draggable({
		drag: function (event, ui) {
			setTimeout(function() {
				jQuery(dialog_class + '#drupal-modal').niceScroll(scrollOptions).resize();
			}, 500);
		}
	});

	// -------
	console.log(dialog_class);
	if (dialog_class == '.dialog-browse') {
		// fix dialog zindex for parent dialog
			jQuery('.dialog-browse').click(function() {
				jQuery(this).css('z-index', ++jQuery.ui.dialogr.maxZ);
			});

			// bind close dialog
			jQuery('.dialog-browse .ui-dialog-titlebar-close').on('click', function() {
			closeOWSDialog();
		});

		// view contestant dialog
		jQuery('.dialog-browse .browse-contestant').on('click', function() {
			id = jQuery(this).attr('id').replace('contestant-', '');

			if (!jQuery('.dialog-contestant-'+id).length) {
		    	// browse website
				jQuery.ajax({
					url: "/ajax-content",
					data: {type: "view-contestant", id: id},
					async: false, 
					success: function(data) {
						openDialog('.dialog-contestant-'+id, 'Test', data, 600, 500);
					}
				});
			}

	    	swal.close();
	    });
	}
}

function closeOWSDialog() {
	if (dialogs > 0) dialogs--;
	else dialogs = 0;
	console.log('Close');
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
		jQuery.cookie('enter-contest', true);
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
	/*// check dialog element 
	if (!jQuery('.ui-dialog ' +element).length) {
		jQuery('.ui-dialog ' +element).remove();
	} else {
		//jQuery(element).dialogr("open");
	}*/
	
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
		maximizable: true,
		open: function( event, ui ) {
			w = jQuery(element + ' .ui-dialog-content').width();
			jQuery(element + ' .ui-dialog').width(w);

			jQuery(element + " .ui-dialog-content").niceScroll(scrollOptions);

			// execute callback
			if (callback) eval(callback);
			dialogs++;
		},
		dragStop: function(event, ui) {
			// refresh scrollbar
			jQuery(element + " .ui-dialog-content").niceScroll(scrollOptions).resize();	
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
