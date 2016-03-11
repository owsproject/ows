jQuery(document).ready(function() {
	// nice scrollbar
    jQuery("html").niceScroll();

	// splash screen

	/*
	jQuery('#splash-img').splashScreen({
		textLayers : [
			'/themes/ows_theme/images/thinner.png',
			'/themes/ows_theme/images/more_elegant.png',
			'/themes/ows_theme/images/our_new.png'
		]
	});	*/
	
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
				jQuery('.dialog').html(data);
			}
		});

    	swal.close();
    });

    // ------------------------------
    // Vote

    // ------------------------------
    // Browse

    // ------------------------------
    // dialog content
    // read jquery ui dialog documentation
	jQuery('.dialog').dialogr({
		autoResize: true,
		width: 500,
		height: 500,
		fluid: true,
		minWidth: 470,
		//width: 'auto',
        //height: 'auto',
		open: function( event, ui ) {
			w = jQuery('.ui-dialog-content').width();
			jQuery('.ui-dialog').width(w);

			// title
			t = jQuery('.dialog-title').html();
			if (t) {
				jQuery('.ui-dialog-titlebar > span').html(t);
			}

			// title for register dialog
			if (jQuery("#user-register-form").length) {
				jQuery('.ui-dialog-titlebar > span').html('Enter Contest');
			}

			jQuery(".ui-dialog-content").niceScroll();
			jQuery("#user-register-form #edit-submit").val("Enter");
		},
		dragStop: function(event, ui) {
			jQuery(".ui-dialog-content").getNiceScroll().resize();	
		}
	});
});

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, ((jQuery(window).height() - jQuery(this).outerHeight()) / 2) + 
                                                jQuery(window).scrollTop()) + "px");
    this.css("left", Math.max(0, ((jQuery(window).width() - jQuery(this).outerWidth()) / 2) + 
                                                jQuery(window).scrollLeft()) + "px");
    return this;
}
