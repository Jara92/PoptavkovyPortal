import {Controller} from '@hotwired/stimulus';

/*
 * Inquiry filter controller.
 * Implements switching between personal/company contact forms.
 */
export default class extends Controller {
    static targets = ["categories", "regions"];

    constructor(context) {
        super(context);

        console.log(this.regionsTarget);

        jQuery(this.regionsTarget);

      /*  $(document).ready(function() {
            $('#inquiry_filter_form_regions').multiselect({
                includeSelectAllOption: true,
            });
        });*/
    }
}