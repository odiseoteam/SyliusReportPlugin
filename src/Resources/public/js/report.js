var handleTimePeriodEndTodayCheckboxChange = function () {
    var $timePeriodEndToday = $('#timePeriod_end_today');
    var isChecked = $timePeriodEndToday.get(0).checked;

    if (!isChecked) {
        var now = new Date();

        $('#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_end_year').val(now.getFullYear());
        $('#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_end_month').val(now.getMonth() + 1);
        $('#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_end_day').val(now.getDate());
    }

    $timePeriodEndToday.closest('.field').find('select').prop('disabled', isChecked);
};

var setupAutocomplete = function () {
    $('.sylius-autocomplete').reportAutoComplete();
};

var setupForms = function () {
    $('#odiseo_sylius_report_dataFetcher_configuration .ui.toggle.checkbox').checkbox();
    $('#odiseo_sylius_report_dataFetcher_configuration .ui.dropdown').dropdown();

    setupAutocomplete();
};

(function ($) {
    'use strict';

    $(document).ready(function()
    {
        var $dataFetcher = $('#odiseo_sylius_report_dataFetcher');
        var $renderer = $('#odiseo_sylius_report_renderer');

        setupForms();
        handleTimePeriodEndTodayCheckboxChange();

        $renderer.handlePrototypes({
            'prototypePrefix': 'odiseo_sylius_report_renderer_renderers',
            'containerSelector': '#odiseo_sylius_report_renderer_configuration'
        });
        $dataFetcher.handlePrototypes({
            'prototypePrefix': 'odiseo_sylius_report_dataFetcher_dataFetchers',
            'containerSelector': '#odiseo_sylius_report_dataFetcher_configuration'
        });
        $dataFetcher.on('change', function () {
            setupForms();
            handleTimePeriodEndTodayCheckboxChange();
        });
        $('#timePeriod_end_today').on('change', function () {
            handleTimePeriodEndTodayCheckboxChange();
        })
    });
})(jQuery);