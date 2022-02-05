import {Controller} from '@hotwired/stimulus';

/*
 * Inquiry form controller.
 * Implements switching between personal/company contact forms.
 */
export default class extends Controller {
    static targets = [ "inquiryType", "companyFields", "personalFields" ];

    personalFields = ["#inquiry_form_personalContact_name", "#inquiry_form_personalContact_surname"];
    companyFields = ["#inquiry_form_companyContact_companyName"]

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
        let inquiryType = jQuery(this.inquiryTypeTarget).find("input:checked").first().val();

        if (inquiryType === this.personalAlias) {
            return this.makeInquiryPersonal();
        } else if (inquiryType === this.companyAlias) {
            return this.makeInquiryCompany()
        }

        // This should not happen
        console.error("Unknown inquiry type: " + inquiryType);
    }

    makeInquiryPersonal() {
        this.makeRequired(this.personalFields);
        this.makeUnrequired(this.companyFields);

        jQuery(this.personalFieldsTarget).show();
        jQuery(this.companyFieldsTarget).hide();
    }

    makeInquiryCompany() {
        this.makeRequired(this.companyFields);
        this.makeUnrequired(this.personalFields);

        jQuery(this.companyFieldsTarget).show();
        jQuery(this.personalFieldsTarget).hide();
    }

    makeRequired(fields) {
        fields.forEach(function (value) {
            jQuery(value).prop('required', true);
        });
    }

    makeUnrequired(fields) {
        fields.forEach(function (value) {
            jQuery(value).removeAttr('required');
        });
    }
}
