import {Controller} from '@hotwired/stimulus';

/*
 * Inquiring rating form controller.
 * Implements supplier rating fields dynamic display.
 */
export default class extends Controller {
    static targets = ["supplierField", "supplierDynamic"];

    constructor(context) {
        super(context);

        // Update city field visibility.
        this.updateFields(false);
    }

    /**
     * Shows/hides city field according to region field value.
     */
    updateFields(animate = true) {
        // Get current region field value.
        let supplier = jQuery(this.supplierFieldTarget).val();

        // Hide city field if supplier is not set.
        if (supplier) {
            this.showElement(this.supplierDynamicTarget, animate);
        } else {
            this.hideElement(this.supplierDynamicTarget, animate)
        }
    }

    showElement(elem, animate) {
        if (animate) {
            jQuery(elem).slideDown();
        } else {
            jQuery(elem).show();
        }
    }

    hideElement(elem, animate) {
        if (animate) {
            jQuery(elem).slideUp();
        } else {
            jQuery(elem).hide();
        }
    }
}
