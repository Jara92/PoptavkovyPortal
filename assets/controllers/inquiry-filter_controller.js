import {Controller} from '@hotwired/stimulus';

/*
 * Inquiry filter controller.
 * Implements switching between personal/company contact forms.
 */
export default class extends Controller {
    static targets = ["categories", "regions", "values", "types"];

    select2GlobalOptions = {};

    constructor(context) {
        super(context);

        let dropdownSearchAdapter = this.getDropdownAdapter();
        // dropdownSearchAdapter = null;
        this.initSelect2GlobalOptions();

        let categories = this.prepareSelect2Field(this.categoriesTarget, {
            multiple: true,
            dropdownAdapter: dropdownSearchAdapter
        });

        let regions = this.prepareSelect2Field(this.regionsTarget, {
            multiple: true,
            dropdownAdapter: dropdownSearchAdapter
        });

        let values = this.prepareSelect2Field(this.valuesTarget, {
            multiple: false,
            minimumResultsForSearch: Infinity,
        });

        let types = this.prepareSelect2Field(this.typesTarget, {
            multiple: true,
            minimumResultsForSearch: -1, // disable searchBox,
        });
    }

    getDropdownAdapter() {
        //Set Dropdown with SearchBox via dropdownAdapter option (https://stackoverflow.com/questions/35799854/how-to-add-selectall-using-dropdownadapter-on-select2-v4)
        let Utils = $.fn.select2.amd.require('select2/utils');
        let Dropdown = $.fn.select2.amd.require('select2/dropdown');
        let DropdownSearch = $.fn.select2.amd.require('select2/dropdown/search');
        // let CloseOnSelect = $.fn.select2.amd.require('select2/dropdown/closeOnSelect');
        let AttachBody = $.fn.select2.amd.require('select2/dropdown/attachBody');

        return Utils.Decorate(Utils.Decorate(Dropdown, DropdownSearch), AttachBody);
    }

    initSelect2GlobalOptions() {
        this.select2GlobalOptions = {
            closeOnSelect: false,
            language: "cs",
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
        })   // On value changed
            .on('change.select2', function (evt) {
                controller.showSelectedNumber(this);
            });

        // Hide given options in input and replate it by number of selected options.
        this.showSelectedNumber(selection);

        return selection;
    }

    showSelectedNumber(obj) {
        var uldiv = $(obj).siblings('span.select2').find('ul');
        var count = $(obj).select2('data').length;
        if (count === 0) {
            uldiv.html("");
        } else {
            uldiv.html("" + count + " položek vybráno" + "");
        }
    }
}