var handleTimePeriodEndTodayCheckboxChange = function () {
    var $timePeriodEndToday = $('#timePeriod_end_today');
    var isChecked = $timePeriodEndToday.get(0).checked;

    $timePeriodEndToday.closest('.field').find('select').prop('disabled', isChecked);
};

var setupForms = function () {
    $('#odiseo_sylius_report_dataFetcher_configuration .ui.toggle.checkbox').checkbox();
    $('#odiseo_sylius_report_dataFetcher_configuration .ui.dropdown').dropdown();
    $('.sylius-autocomplete').autoComplete();
    /*$('.ui.dropdown.remote.search').each(function (index, dropdown) {
        var $dropdown = $(dropdown);
        var url = $dropdown.find('select').data('remoteUrl');

        $dropdown.dropdown({
            delay: {
                search: 250
            },
            forceSelection: false,
            apiSettings: {
                url: url,
                dataType: 'JSON',
                cache: false,
                beforeSend: function (settings) {
                    settings.data[criteriaName] = settings.urlData.query;

                    return settings;
                },
                onResponse: function(response) {
                    return {
                        success: true,
                        results: response.map(function(item) {
                            return {
                                name: item[choiceName],
                                value: item[choiceValue]
                            };
                        })
                    };
                }
            }
        });
    });*/
};

(function ($) {
    'use strict';

    $(document).ready(function()
    {
        setupForms();
        handleTimePeriodEndTodayCheckboxChange();

        $('#odiseo_sylius_report_renderer').handlePrototypes({
            'prototypePrefix': 'odiseo_sylius_report_renderer_renderers',
            'containerSelector': '#odiseo_sylius_report_renderer_configuration'
        });
        $('#odiseo_sylius_report_dataFetcher').handlePrototypes({
            'prototypePrefix': 'odiseo_sylius_report_dataFetcher_dataFetchers',
            'containerSelector': '#odiseo_sylius_report_dataFetcher_configuration'
        });
        $('#odiseo_sylius_report_dataFetcher').on('change', function (e) {
            handleTimePeriodEndTodayCheckboxChange();
            setupForms();
        });
        $('#timePeriod_end_today').on('change', function () {
            handleTimePeriodEndTodayCheckboxChange();
        })
    });
})( jQuery );