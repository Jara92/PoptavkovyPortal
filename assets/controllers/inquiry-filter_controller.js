import {Controller} from '@hotwired/stimulus';

/*
 * Inquiry filter controller.
 * Implements switching between personal/company contact forms.
 */
export default class extends Controller {
    static targets = ["categories", "regions"];

    select2GlobalOptions = {};

    constructor(context) {
        super(context);

        this.initSelect2GlobalOptions();

        let regions = this.prepareSelect2Field(this.regionsTarget, {
            multiple: true
        });
    }

    getDropdownAdapter() {
        //Set Dropdown with SearchBox via dropdownAdapter option (https://stackoverflow.com/questions/35799854/how-to-add-selectall-using-dropdownadapter-on-select2-v4)
        let Utils = $.fn.select2.amd.require('select2/utils');
        let Dropdown = $.fn.select2.amd.require('select2/dropdown');
        let DropdownSearch = $.fn.select2.amd.require('select2/dropdown/search');
        let CloseOnSelect = $.fn.select2.amd.require('select2/dropdown/closeOnSelect');
        let AttachBody = $.fn.select2.amd.require('select2/dropdown/attachBody');

        return Utils.Decorate(Utils.Decorate(Utils.Decorate(Dropdown, DropdownSearch), CloseOnSelect), AttachBody);
    }

    initSelect2GlobalOptions() {
        let dropdownAdapter = this.getDropdownAdapter();

        this.select2GlobalOptions = {
            dropdownAdapter: dropdownAdapter,
            closeOnSelect: false,
            selectionCssClass: "form-control",
            placeholder: "Zvolte možnost"
        };
    }

    prepareSelect2Field(elem, options = {}) {
        let controller = this;

        // Init Select2 object using global and local options.
        let selection = jQuery(elem).select2({
            ...this.select2GlobalOptions,
            ...options
        }).on('select2:opening select2:closing', function (event) {
            //Disable original search (https://select2.org/searching#multi-select)
            var searchfield = jQuery(this).parent().find('.select2-search__field');
            searchfield.prop('disabled', true);
        }).on('select2:close', function (evt) {
            controller.showSelectedNumber(this);
        });

        return selection;
    }

    showSelectedNumber(obj) {
        var uldiv = $(obj).siblings('span.select2').find('ul');
        var count = $(obj).select2('data').length;
        if (count === 0) {
            uldiv.html("");
        } else {
            uldiv.addClass("p-2");
            uldiv.html("" + count + " položek vybráno" + "");
        }
    }
}