import {Controller} from '@hotwired/stimulus';

/*
 * Inquiry form controller.
 * Implements switching between personal/company contact forms.
 */
export default class extends Controller {
    companyFieldsContainerId = "#company-inquiry-fields";
    personalFieldsContainerId = "#personal-inquiry-fields";
    selectBoxId = "#inquiry_form_type > input";

    personalFields = ["#inquiry_form_personalContact_name", "#inquiry_form_personalContact_surname"];
    companyFields = ["#inquiry_form_companyContact_name"]

    personalAlias;
    companyAlias;

    constructor(context) {
        super(context);

        this.personalAlias = "personal";
        this.companyAlias = "company";

        // Update fields state on load.
        this.updateFields();
    }

    updateFields() {
        let inquiryType = jQuery('#inquiry_form_type > input:checked').val();

        if (inquiryType === this.personalAlias) {
            return this.makeInquiryPersonal();
        } else if (inquiryType === this.companyAlias) {
            return this.makeInquiryCompany()
        }

        console.error("Unknown inquiry type.");
    }

    makeInquiryPersonal() {
        this.makeRequired(this.personalFields);
        this.makeUnrequired(this.companyFields);

        jQuery(this.personalFieldsContainerId).show();
        jQuery(this.companyFieldsContainerId).hide();
    }

    makeInquiryCompany() {
        this.makeRequired(this.companyFields);
        this.makeUnrequired(this.personalFields);

        jQuery(this.companyFieldsContainerId).show();
        jQuery(this.personalFieldsContainerId).hide();
    }

    makeRequired(fields){
        fields.forEach(function (value, index, array){
            jQuery(value).prop('required',true);
        });
    }

    makeUnrequired(fields){
        fields.forEach(function (value, index, array){
            jQuery(value).removeAttr('required');
        });
    }
}
