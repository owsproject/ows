var dialog_title;

jQuery(document).ready(function() {
	// nice scrollbar
    jQuery("html").niceScroll();

	user_option = jQuery.cookie('user.option');

	if (user_option === undefined) {
		if (jQuery('body').hasClass('path-frontpage')) {
			swal({
				title: 'Welcome to OWS',
				text: jQuery('#welcome-box').html(),
				html: true,
				customClass: 'twitter',
				showConfirmButton: false,
				allowEscapeKey: true, // turn on for debug only
				allowOutsideClick: false
				//allowEscapeKey: false
			});

			jQuery('#block-ows-theme-content #welcome-box').remove();
			jQuery('.sweet-alert').center();
		}
	} else {
		// browse website

		jQuery.ajax({
			url: "/ajax-content",
			data: {type: "browse"},
			async: false, 
			success: function(data) {
				dialog_title = 'Browse';
				jQuery('.dialog').html(data);
			}
		});
	}

	// ------------------------------
    // enter contest
    jQuery('#btn-enter-contest').on('click', function() {
    	// write cookie
    	jQuery.cookie('user.option', 'enter-contest');

    	jQuery.ajax({
			url: "/ajax-content",
			data: {type: "register"},
			async: false, 
			success: function(data) {
				dialog_title = 'Enter Contest';
				jQuery('.dialog').html(data);
			}
		});

    	swal.close();
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
				dialog_title = 'Browse';
				jQuery('.dialog').html(data);
			}
		});	

    	swal.close();
    });

    // default dialog
    openDialog();
 

	Drupal.ajax({
		url: 'page/browse',
		data: 'html',
		success: function(response) {
	  	console.log(response);

	  	// fetch object to get ajax response data
	  	jQuery.each(response, function( key, value ) {			
		  	if (value.command == 'insert' && value.method === null) {
		    	content = jQuery('<div class="page-browse-dialog">' + value.data + '<a href="#nojs" class="use-ajax">Test</a></div>').appendTo('body');
		    	jQuery('body').append(content);

		    	openDialog('.page-browse-dialog', 'Browse', 750, 500, true, 'test();');
			}
		});
	  }
	}).execute();
});

function test() {
	alert(1);
}

// ------------------------------
// dialog content
// read jquery ui dialog documentation
function openDialog(element, title, width = 500, height = 500, is_new = false, callback = false) {
	// default dialog
	if (!is_new) {
		jQuery('.dialog').dialogr({
			title: dialog_title,
			autoResize: true,
			width: width,
			height: height,
			fluid: true,
			minWidth: 470,
			open: function( event, ui ) {
				w = jQuery('.ui-dialog-content').width();
				jQuery('.ui-dialog').width(w);

				jQuery(".ui-dialog-content").niceScroll();
				jQuery("#user-register-form #edit-submit").val("Enter");
			},
			dragStop: function(event, ui) {
				// refresh scrollbar
				jQuery(".ui-dialog-content").getNiceScroll().resize();	
			}
		});
	} else {
		jQuery(element).dialogr({
			title: title,
			autoResize: true,
			width: width,
			height: height,
			fluid: true,
			minWidth: 470,
			open: function( event, ui ) {
				w = jQuery('.ui-dialog-content').width();
				jQuery('.ui-dialog').width(w);

				jQuery(".ui-dialog-content").niceScroll();

				// execute callback
				eval(callback);
			},
			dragStop: function(event, ui) {
				// refresh scrollbar
				jQuery(".ui-dialog-content").getNiceScroll().resize();	
			}
		});
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
