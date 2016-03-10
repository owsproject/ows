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
	
	if (jQuery('body').hasClass('path-frontpage')) {
		swal({
			title: 'Welcome to OWS',
			text: jQuery('#welcome-box').html(),
			html: true,
			customClass: 'twitter',
			showConfirmButton: false
			//allowEscapeKey: false
		});

		jQuery('#block-ows-theme-content #welcome-box').remove();
		jQuery('.sweet-alert').center();
	}


    // enter contest
    jQuery('#btn-enter-contest').on('click', function() {
    	jQuery.ajax({
			url: "/ajax-content",
			data: {type: "register"},
			async: false, 
			success: function(data) {
				jQuery('.dialog').html(data);
			}
		});

    	swal.close();
    	jQuery('.dialog').dialogr({
    		open: function( event, ui ) {}
    	});
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
