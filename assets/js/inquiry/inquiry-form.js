jQuery(document).ready(function (){
    updateInquiryType();
});

jQuery("#inquiry_form_type > input").change(function (){
    updateInquiryType();
})

function updateInquiryType(){
    let inquiryType = jQuery('#inquiry_form_type > input:checked').val();

    if(inquiryType === "personal"){
        jQuery("#personal-inquiry-fields").show();
        jQuery("#company-inquiry-fields").hide();
    }
    else if(inquiryType === "company"){
        jQuery("#company-inquiry-fields").show();
        jQuery("#personal-inquiry-fields").hide();
    }
}
