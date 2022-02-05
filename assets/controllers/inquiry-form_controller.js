import {Controller} from '@hotwired/stimulus';

/*
 * Inquiry form controller.
 * Implements switching between personal/company contact forms.
 */
export default class extends Controller {
    static targets = ["inquiryType", "companyFields", "personalFields"];

    personalAlias = "personal";
    companyAlias = "company";

    constructor(context) {
        super(context);

        // Update fields state on load.
        this.updateFields();
    }

    /**
     * Update dynamic contact fields according to selected inquiry type.
     */
    updateFields() {
        // Get inquiry type value.
        let inquiryType = jQuery(this.inquiryTypeTarget).find("input:checked").first().val();

        // Show correct fields and hide the other ones.
        if (inquiryType === this.personalAlias) {
            return this.makeInquiryPersonal();
        } else if (inquiryType === this.companyAlias) {
            return this.makeInquiryCompany()
        }

        // This should not happen
        console.error("Unknown inquiry type: " + inquiryType);
    }

    /** Returns all personal input fields. */
    getPersonalFields() {
        return this.getChildInputs(this.personalFieldsTarget);
    }

    /** Returns all company input fields. */
    getCompanyFields() {
        return this.getChildInputs(this.companyFieldsTarget);
    }

    /** Returns all <input> element in given element. */
    getChildInputs(element) {
        return jQuery(element).find("input");
    }

    /** Shows personal data and hides the others. */
    makeInquiryPersonal() {
        this.makeRequired(this.getPersonalFields());
        this.makeUnrequired(this.getCompanyFields());

        jQuery(this.personalFieldsTarget).show();
        jQuery(this.companyFieldsTarget).hide();
    }

    /** Shows company data and hides the others. */
    makeInquiryCompany() {
        this.makeRequired(this.getCompanyFields());
        this.makeUnrequired(this.getPersonalFields());

        jQuery(this.companyFieldsTarget).show();
        jQuery(this.personalFieldsTarget).hide();
    }

    /** Adds "required" attribute to given fields. */
    makeRequired(fields) {
        fields.each(function () {
            jQuery(this).attr('required', true);
        });
    }

    /** Removes "required" attribute of the given fields. */
    makeUnrequired(fields) {
        fields.each(function () {
            jQuery(this).removeAttr('required');
        });
    }
}
