jQuery( function ( $ ) {

    //JS Based on Rent Type : Day/Hour
    var selected_rent_type = $(".icrmw_rent_type").children("option:selected").val();  
    if (selected_rent_type == 'hour') {
        $(".icrmw_product_rent_type_fields_box").removeClass("active");
    } else {
        $(".icrmw_product_rent_type_fields_box").addClass("active");
    }

    //rent type on change
    jQuery(".icrmw_rent_type").on( "change", function () {
        var selected_rent_type1 = $(this).children("option:selected").val();  

        if (selected_rent_type1 == 'hour') {
            $(".icrmw_product_rent_type_fields_box").removeClass("active");
        } else {
            $(".icrmw_product_rent_type_fields_box").addClass("active");
        }
    });
 


    // Click event handler to the anchor tag
    $('.icrmw_reset_email_template').click(function(e) {
        e.preventDefault();

        // Reset the email template textarea content
        $('#icrmw_request_form_email_body').val('Hi {full_name},<br /><br />Thank you for your interest in our {icrmw_product_name}. We have received your inquiry and will get back to you shortly to assist with your rental request.<br /><br />Here are the details you provided:<br /><br /><b>Name :</b>{full_name}<br /><b>Email :</b>{user_email}<br /><b>Phone Number :</b>{user_phone}<br /><b>Check-in :</b>{icrmw_check_in}<br /><b>Checkout :</b>{icrmw_check_out}<br /><br /><br />Our team will review your request and reach out to you with more information, answers to your questions, and assistance in planning your upcoming trip. We appreciate your interest in our services and look forward to helping you with your rental needs.');
    });




});