var product_ids = quantities = '';
jQuery(document).ready(function() {
    jQuery('.page-free-quote .view-products .views-exposed-widgets').append('<div style="float:right;margin-top: 40px;"><a id="btn-add" class="q-button" type="button" value="Add to list" href="#add-to-list">Add to list</a><a id="btn-my-quote" type="button" value="My Quote" href="/my-quote">My Quote</a><a id="btn-submit-quote" type="button" value="Submit Quote" href="#quote-form">Submit Quote</a></div><div style="display:none;"><div class="quote-form" id="quote-form"><form id="form-quote-form" name="form-quote-form"><div class="form-item"><span class="label">Full name:</span> <input type="text" name="full_name" id="edit_full_name" required></div><div class="form-item"><span class="label">Email:</span> <input type="email" name="email" id="edit_email" required></div><div class="form-item"><span class="label">Comment: </span><textarea id="comment"></textarea></div><div class="form-item"><span class="label">&nbsp;</span><input type="submit" value="Submit" id="submit-quote-button"></form></div></div>');
    // jQuery('.page-free-quote .view-products .views-field-nothing').each(fucntion() {  });
    jQuery('.product-checkbox').change(function() {
        t = jQuery(this).parent().find('.wrapper-quantity');
        if (t.css('display') == "none") {
            t.css('display', 'block');
        } else {
            t.css('display', 'none');
        }
    });

    var $form = jQuery('#form-quote-form').validate({
        rules: {
            full_name: "required",
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            full_name: "",
            email: ""
        }
    });
    // show colorbox
    jQuery("#btn-submit-quote").colorbox({
        inline: true,
        width: "700px",
        height: "350px"
    });

    jQuery('#btn-submit-quote').click(function() {
        jQuery('.page-free-quote .product-checkbox').each(function() {
            if (jQuery(this).is(':checked')) {
                product_ids += jQuery(this).val() + '|';
                quantities += jQuery(this).parent().find('.quantity-input').val() + '|';
            }
        });
    });

    jQuery('#form-quote-form').submit(function(e) {
        //if ($form.valid()) {
            if (product_ids != "") {
                jQuery.ajax({
                    url: Drupal.settings.basePath + 'free-quote-submit',
                    type: "POST",
                    data: 'ids=' + product_ids + '&quantities=' + quantities + '&name=' + jQuery('#edit_full_name').val() + '&email=' + jQuery('#edit_email').val() + '&comment=' + encodeURIComponent(jQuery('#comment').val()),
                    dataType: 'json',
                    async: false,
                    success: function(result) {
                        if (result == 1) {
                            alert("Thank you, your info has been submit.");
                        } else {
                            alert("There is an error while submitting quote, please try again.");
                        }
                        product_ids = quantities = '';
                        jQuery('.product-checkbox').attr('checked', false);
                        jQuery('.quantity-input').val(1);
                        jQuery('.wrapper-quantity').hide();
                        jQuery.colorbox.close();
                    }
                });
            } else {
                e.preventDefault();
                alert("You didn't select product!");
                return;
            }
        //}
    });
    // =============================
    jQuery('#add_to_quote').click(function() {
        id = jQuery('#product_pid').val();
        quantity = jQuery('#product_quantity').val();
        jQuery.ajax({
            url: Drupal.settings.basePath + 'add-to-quote',
            type: "POST",
            data: 'id=' + id + '&quantity=' + quantity,
            dataType: 'json',
            async: false,
            success: function(result) {
                if (result == 1) {
                    alert("Product has been added to quote list.");
                }
                if (!result) {
                    alert("There is an error while adding product to quote list.");
                }
            }
        });
    });
    jQuery("#submit_quote").colorbox({
        inline: true,
        width: "700px",
        height: "350px"
    });
    var $form = jQuery('#form-quote-form2').validate({
        rules: {
            full_name: "required",
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            full_name: "",
            email: ""
        }
    });
    jQuery('#form-quote-form2').submit(function() {
        if ($form.valid()) {
            jQuery.ajax({
                url: Drupal.settings.basePath + 'submit-quote',
                type: "POST",
                data: 'name=' + jQuery('#edit_full_name').val() + '&email=' + jQuery('#edit_email').val() + '&comment=' + encodeURIComponent(jQuery('#comment').val()),
                dataType: 'json',
                async: false,
                success: function(result) {
                    if (result == 1) {
                        alert("Thank you, your info has been submit.");
                    } else {
                        alert("There is an error while submitting quote, please try again.");
                    }
                }
            });
        }
    });
    jQuery('#tb-megamenu-main-menu > .tb-megamenu-button').click(function() {
        if (jQuery(window).width() <= 480) {
            jQuery("#tb-megamenu-main-menu > .nav-collapse").show();
        }
    });
    jQuery('#tb-megamenu-main-menu .tb-megamenu-item a.dropdown-toggle').click(function(e) {
        if (jQuery(this).parent().find('.tb-megamenu-column').hasClass('hidden-collapse')) {
            jQuery(this).parent().find('.tb-megamenu-column').removeClass('hidden-collapse');
        } else {
            jQuery(this).parent().find('.tb-megamenu-column').addClass('hidden-collapse');
        }
        e.preventDefault();
        return;
    });

    jQuery(".tb-megamenu-column-inner").mCustomScrollbar({
        scrollButtons: {
            enable: true
        },
        theme: "3d"
    });

    jQuery('#btn-add').click(function(e) {
        flag = jQuery(this).is(':checked');
        act = 'remove';
        if (flag) act = 'add';
        pid = jQuery(this).val();
        quan = jQuery(this).parent().find('.wrapper-quantity .quantity-input').val();

        jQuery('.page-free-quote .product-checkbox').each(function() {
            if (jQuery(this).is(':checked')) {
                product_ids += jQuery(this).val() + '|';
                quantities += jQuery(this).parent().find('.quantity-input').val() + '|';
            }
        });

        jQuery.ajax({
            url: Drupal.settings.basePath + 'add-to-list',
            type: "POST",
            //data: 'act=' + act + '&pid=' + pid + '&quan=' + quan,
            data: 'ids=' + product_ids + '&quantities=' + quantities,
            dataType: 'json',
            async: false,
            success: function(result) {
            }
        });
    });
});
