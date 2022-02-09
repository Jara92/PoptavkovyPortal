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

        //Set Dropdown with SearchBox via dropdownAdapter option (https://stackoverflow.com/questions/35799854/how-to-add-selectall-using-dropdownadapter-on-select2-v4)
        var Utils = $.fn.select2.amd.require('select2/utils');
        var Dropdown = $.fn.select2.amd.require('select2/dropdown');
        var DropdownSearch = $.fn.select2.amd.require('select2/dropdown/search');
        var CloseOnSelect = $.fn.select2.amd.require('select2/dropdown/closeOnSelect');
        var AttachBody = $.fn.select2.amd.require('select2/dropdown/attachBody');

        var dropdownAdapter = Utils.Decorate(Utils.Decorate(Utils.Decorate(Dropdown, DropdownSearch), CloseOnSelect), AttachBody);

        function showSelectedNumber(obj) {
            var uldiv = $(obj).siblings('span.select2').find('ul');
            var count = $(obj).select2('data').length;
            if (count === 0) {
                uldiv.html("");
            } else {
                uldiv.addClass("p-2");
                uldiv.html("" + count + " položek vybráno" + "");
            }
        }

        jQuery(document).ready(function () {
            let regions = jQuery('#inquiry_filter_form_regions').select2({
                dropdownAdapter: dropdownAdapter,
                placeholder: "Zvolte možnost",
                multiple: true,
                closeOnSelect: false,
                selectionCssClass: "form-control"
            }).on('select2:opening select2:closing', function (event) {
                //Disable original search (https://select2.org/searching#multi-select)
                var searchfield = jQuery(this).parent().find('.select2-search__field');
                searchfield.prop('disabled', true);
            }).on('select2:close', function (evt) {
                showSelectedNumber(this);
            });

            showSelectedNumber(regions);
        });
    }
}