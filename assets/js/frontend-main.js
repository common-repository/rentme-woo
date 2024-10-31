jQuery(function($){

var validate_email = function(email){
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if (!regex.test(email)) {
      return false;
  }
  else {
      return true;
  }
}

const convertTime12to24 = (time12h) => {
  const [time, modifier] = time12h.split(' ');

  let [hours, minutes] = time.split(':');

  if (hours === '12') {
    hours = '00';
  }

  if (modifier === 'PM') {
    hours = parseInt(hours, 10) + 12;
  }

  return `${hours}:${minutes}`;
}



$(".icrmw_loader").removeClass("active");
$(".icrmw_rloader").removeClass("active");
$(".icrmw_booking_form").removeClass("icrmw_loader_effect");
$(".icrmw-request-tab").removeClass("icrmw_loader_effect");



//tab 1 booking form datetimepicker start
var icrmw_timepicker = $("#icrmw_bdatetimepicker").data("timepicker");
var icrmw_cintime = $("#icrmw_bdatetimepicker").data("cintime");
var icrmw_couttime = $("#icrmw_bdatetimepicker").data("couttime");
var icrmw_current_date = $("#icrmw_bdatetimepicker").data("date");
var icrmw_localeFormat = (icrmw_timepicker == true) ? '{"format": "MMMM DD hh:mm A"}' : '{"format": "MMMM DD"}';
icrmw_localeFormat = JSON.parse(icrmw_localeFormat);

$('#icrmw_bdatetimepicker').daterangepicker({
    "timePicker": icrmw_timepicker,
    "minDate": icrmw_current_date, //moment().startOf('hour')
    "startDate": icrmw_current_date,
    "endDate": icrmw_current_date, //moment().startOf('hour').add(32, 'hour')
    "applyClass": "applyBtn btn btn-sm btn-primary ",
    "cancelClass": 'cancelBtn btn btn-sm btn-default ',
    "locale": icrmw_localeFormat, // "format": 'MMMM DD hh:mm A',
    "opens": "center"
}, function(start, end, label) {

    var icrmw_bcheck_in, icrmw_bcheck_out;

    if (icrmw_timepicker == true) {
      icrmw_bcheck_in = start.format('YYYY-MM-DD') + ' ' + convertTime12to24(start.format('hh:mm A'));
      icrmw_bcheck_out = end.format('YYYY-MM-DD') + ' ' + convertTime12to24(end.format('hh:mm A'));
    }
    else{
      icrmw_bcheck_in = start.format('YYYY-MM-DD');
      icrmw_bcheck_out = end.format('YYYY-MM-DD');
    }
    
    $(".icrmw_bcheck_in").val(icrmw_bcheck_in);
    $(".icrmw_bcheck_out").val(icrmw_bcheck_out);
    
});


$("#icrmw_bdatetimepicker").val('');

if (icrmw_timepicker == false && icrmw_cintime != '' && icrmw_couttime != '') {
  var cin_cout_html = '<div class="icrmw_cin_cout"><div class="icrmw_checkin_time"><span class="label">Checkin</span><div class="cin_cout_content">'+icrmw_cintime+'</div></div><div class="icrmw_checkout_time"><span class="label">Checkout</span><div class="cin_cout_content">'+icrmw_couttime+'</div></div></div>';
  $( ".daterangepicker .drp-buttons" ).prepend(cin_cout_html);
}

if (icrmw_timepicker == false && (icrmw_cintime == '' || icrmw_couttime == '') ) {
  $( ".daterangepicker .drp-buttons" ).prepend( '<div class="icrmw_cin_cout"><div class="icrmw_checkin_time"><span class="label">Checkin</span><div class="cin_cout_content">9:00 AM</div></div><div class="icrmw_checkout_time"><span class="label">Checkout</span><div class="cin_cout_content">5:00 PM</div></div></div>' );
}
//tab 1 booking form datetimepicker end



//tab 2 request form datetimepicker start
var icrmw_current_date2 = $("#icrmw_rdatetimepicker").data("date");
$('#icrmw_rdatetimepicker').daterangepicker({
    "timePicker": true,
    "minDate": icrmw_current_date2, //moment().startOf('hour'),
    "startDate": icrmw_current_date2,
    "endDate": icrmw_current_date2,
    "applyClass": "applyBtn btn btn-sm btn-primary ",
    "cancelClass": 'cancelBtn btn btn-sm btn-default ',
    "locale": {
      "format": 'MMMM DD hh:mm A',
      
    },
    "opens": "center"
}, function(start, end, label) {
   
    var icrmw_rcheck_in = start.format('YYYY-MM-DD') + ' ' + convertTime12to24(start.format('hh:mm A'));
    var icrmw_rcheck_out = end.format('YYYY-MM-DD') + ' ' + convertTime12to24(end.format('hh:mm A'));
    $(".icrmw_rcheck_in").val(icrmw_rcheck_in);
    $(".icrmw_rcheck_out").val(icrmw_rcheck_out);
    
});


$("#icrmw_rdatetimepicker").val('');
//tab 2 request form datetimepicker end



const tabButtons = document.querySelectorAll(".icrmw-tab");
const tabContents = document.querySelectorAll(".icrmw-tab-content");

function showTab(tabIndex) {
  tabButtons.forEach((button, index) => {
    if (index === tabIndex) {
      button.classList.add("active");
    } else {
      button.classList.remove("active");
    }
  });

  tabContents.forEach((content, index) => {
    if (index === tabIndex) {
      content.classList.add("active");
    } else {
      content.classList.remove("active");
    }
  });
}

// Show the first tab by default
showTab(0);

// Add click event listener to each tab button
tabButtons.forEach((button, index) => {
  button.addEventListener("click", () => {
    showTab(index);
  });
});



//adult & chidren count js 
$('.icrmw_minus_child').click(function () {

  $(".icrmw_plus_child").removeClass("icrmw_disabled");
  
  var $input = $(this).parent().find('input');
  var count = parseInt($input.val()) - 1;
  count = count < 0 ? 0 : count;
  $input.val(count);

  if ($input[0].min == parseInt($input.val()) ) { $(".icrmw_minus_child").addClass("icrmw_disabled"); }
  $input.change();
  return false;
});

$('.icrmw_minus_adult').click(function () {

  $(".icrmw_plus_adult").removeClass("icrmw_disabled");

  var $input = $(this).parent().find('input');
  var count = parseInt($input.val()) - 1;
  count = count < 1 ? 1 : count;
  $input.val(count);

  if ($input[0].min == parseInt($input.val()) ) { $(".icrmw_minus_adult").addClass("icrmw_disabled"); }
  $input.change();
  return false;
});

$('.icrmw_plus_adult').click(function () {
  var $input = $(this).parent().find('input.icrmw_adults');
  if($input[0].max != ''){
    if ($input[0].max != parseInt($input.val()) ) {
        $input.val(parseInt($input.val()) + 1); 
        $(".icrmw_plus_adult").removeClass("icrmw_disabled");
        $(".icrmw_minus_adult").removeClass("icrmw_disabled");
    }
    else{ $input.val(parseInt($input.val())); 
        // $(".icrmw_plus_adult").addClass("icrmw_disabled");
    }

    if ($input[0].max == parseInt($input.val()) ) { $(".icrmw_plus_adult").addClass("icrmw_disabled"); }
  }
  else{
    $input.val(parseInt($input.val()) + 1); 
    $(".icrmw_minus_adult").removeClass("icrmw_disabled");
  }
  $input.change();
  return false;
});

$('.icrmw_plus_child').click(function () {
  var $input = $(this).parent().find('input.icrmw_childrens');
  if($input[0].max != ''){
    if ($input[0].max != parseInt($input.val()) ) {
        $input.val(parseInt($input.val()) + 1); 
        $(".icrmw_plus_child").removeClass("icrmw_disabled");
        $(".icrmw_minus_child").removeClass("icrmw_disabled");
    }
    else{ $input.val(parseInt($input.val())); }

    if ($input[0].max == parseInt($input.val()) ) { $(".icrmw_plus_child").addClass("icrmw_disabled"); }
  }
  else{
      $input.val(parseInt($input.val()) + 1);
      $(".icrmw_minus_child").removeClass("icrmw_disabled");
  }
  
  $input.change();
  return false;
});



//on change on single product page booking form
jQuery( ".icrmw_booking_form" ).on( "change", ":input", function() {

  var booking_nonce = $('input[name=icrmw_bookingform_nonce]').val();

  var icrmw_bcheck_in = $(".icrmw_bcheck_in").val();
  var icrmw_bcheck_out = $(".icrmw_bcheck_out").val();

  if (icrmw_bcheck_in == '' || icrmw_bcheck_out == '') {
    $("#icrmw_bdatetimepicker").val('');
  }

  if (!$(this).hasClass('icrmw_exclude') && icrmw_bcheck_in != '' && icrmw_bcheck_out != '') {
      
      $(".icrmw_loader").addClass("active");
      $(".icrmw_booking_form").addClass("icrmw_loader_effect");

      var product_id = $(".icrmw_bproduct_id").val();

      var dataMain = new FormData(); 
      dataMain.append('merge_check_in', icrmw_bcheck_in);
      dataMain.append('merge_check_out', icrmw_bcheck_out);
      dataMain.append('product_id', product_id);
      dataMain.append('icrmw_bookingform_nonce', booking_nonce);
      // dataMain.append('totalPrice', totalPrice);
      dataMain.append('action', 'icrmw_product_price_calculation');

      jQuery.ajax({
              url: ajax_object.ajax_url,
              type: 'post',
              contentType: false,
              processData: false,
              data: dataMain,
              success: function (response) {

                $(".icrmw_loader").removeClass("active");
                $(".icrmw_booking_form").removeClass("icrmw_loader_effect");
                $('.icrmw_total_amount .icrmw_total_price').text(response.price);

              }
      });

  }

   


});



// on click single product page button tab 1
jQuery( ".icrmw_booking_btn" ).on( "click", function(e) {

    var icrmw_bcheck_in = $(".icrmw_bcheck_in").val();
    var icrmw_bcheck_out = $(".icrmw_bcheck_out").val();

    if (icrmw_bcheck_in != '' && icrmw_bcheck_out != '') {
      // it will not open date picker, also not to scroll
    }
    else{
      e.preventDefault();
      //scroll
      document.getElementById("icrmw_booking_form_main").scrollIntoView( {behavior: "smooth" });
      $( "#icrmw_bdatetimepicker" ).trigger( "click" );
    }


     $("form").submit(function(){
        // console.log("Booking Form Submitted");
      });


} );



//on change on single product page request form
jQuery( "#icrmw_rdatetimepicker" ).on( "change", function() {
    var icrmw_rcheck_in = $(".icrmw_rcheck_in").val();
    var icrmw_rcheck_out = $(".icrmw_rcheck_out").val();
    if (icrmw_rcheck_in == '' && icrmw_rcheck_out == '') {
      $("#icrmw_rdatetimepicker").val('');
    }
});


// on click single product page button tab 2
jQuery( ".icrmw_request_btn" ).on( "click", function(e) {

    var request_nonce = $('input[name=icrmw_requestform_nonce]').val();
   
    $('.icrmw_success_msg').removeClass("active");
    $('.icrmw_mail_error_msg').removeClass("active");

    var product_id = $(".icrmw_rproduct_id").val();

    var icrmw_req_name = $(".icrmw_req_name").val();
    if (icrmw_req_name != '') {
     
      $(".icrmw_name_field .icrmw_error_msg" ).removeClass("active");
    }
    else{
      e.preventDefault();
     
      $(".icrmw_name_field .icrmw_error_msg" ).addClass("active");
    }

    var icrmw_req_email = $(".icrmw_req_email").val();
    var is_success = true;
    is_success = validate_email(icrmw_req_email);   
    if (is_success) {
     
      $(".icrmw_email_field .icrmw_error_msg" ).removeClass("active");
    }
    else{
      e.preventDefault();
      $(".icrmw_email_field .icrmw_error_msg" ).addClass("active");
    
    }

    var icrmw_req_phone = $(".icrmw_req_phone").val();
    if (icrmw_req_phone != '') {
     
      $(".icrmw_phone_field .icrmw_error_msg" ).removeClass("active");
    }
    else{
      e.preventDefault();
     
      $(".icrmw_phone_field .icrmw_error_msg" ).addClass("active");
    }

    var icrmw_rcheck_in = $(".icrmw_rcheck_in").val();
    var icrmw_rcheck_out = $(".icrmw_rcheck_out").val();
    if (icrmw_rcheck_in != '' && icrmw_rcheck_out != '') {

      $(".icrmw_rdate_picker .icrmw_error_msg" ).removeClass("active");
    }
    else{
      e.preventDefault();
    
      $(".icrmw_rdate_picker .icrmw_error_msg" ).addClass("active");
    }


    //new ajax code for request tab start
    if (icrmw_req_name != '' && is_success && icrmw_req_phone != '' && icrmw_rcheck_in != '' && icrmw_rcheck_out != '') {
      e.preventDefault();

     
      $(".icrmw_rloader").addClass("active");
      $(".icrmw-request-tab").addClass("icrmw_loader_effect");

      var dataMain = new FormData(); 
      dataMain.append('icrmw_req_name', icrmw_req_name);
      dataMain.append('icrmw_req_email', icrmw_req_email);
      dataMain.append('icrmw_req_phone', icrmw_req_phone);
      dataMain.append('icrmw_rcheck_in', icrmw_rcheck_in);
      dataMain.append('icrmw_rcheck_out', icrmw_rcheck_out);
      dataMain.append('product_id', product_id);
      dataMain.append('icrmw_requestform_nonce', request_nonce);
      dataMain.append('action', 'icrmw_request_form_submit');

      jQuery.ajax({
            url: ajax_object.ajax_url,
            type: 'post',
            contentType: false,
            processData: false,
            data: dataMain,
            success: function (response) {
              
              if(response.mailSts == true){
              
                $(".icrmw_rloader").removeClass("active");
                $(".icrmw-request-tab").removeClass("icrmw_loader_effect");

                $('.icrmw_success_msg').addClass("active");
                $(".icrmw_req_name").val('');
                $(".icrmw_req_email").val('');
                $(".icrmw_req_phone").val('');
                $("#icrmw_rdatetimepicker").val('');
                $(".icrmw_rcheck_in").val('');
                $(".icrmw_rcheck_out").val('');
              }
              else{
                $(".icrmw_rloader").removeClass("active");
                $(".icrmw-request-tab").removeClass("icrmw_loader_effect");
                $('.icrmw_mail_error_msg').addClass("active");

              }

            }
      }); 
    }
    //new ajax code for request tab end


} );


});