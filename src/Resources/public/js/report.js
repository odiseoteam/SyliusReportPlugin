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
    $('.sylius-autocomplete').each(function(idx, el) {
        var element = $(el);
        var criteriaName = element
            .data('criteria-name');
        var choiceName = element
            .data('choice-name');
        var choiceValue = element
            .data('choice-value');
        var autocompleteValue = element
            .find('input.autocomplete')
            .val();
        var loadForEditUrl = element
            .data('load-edit-url');

        element
            .dropdown({
                delay: {
                    search: 250
                },
                minCharacters: 3,
                forceSelection: false,
                apiSettings: {
                    dataType: 'JSON',
                    cache: false,
                    beforeSend: function beforeSend(settings) {
                        /* eslint-disable-next-line no-param-reassign */
                        settings.data[criteriaName] = settings.urlData.query;

                        return settings;
                    },
                    onResponse: function onResponse(response) {
                        return {
                            success: true,
                            results: response.map(function (item) {
                                return {
                                    name: item[choiceName],
                                    value: item[choiceValue]
                                };
                            })
                        };
                    }
                }
            });

        if (autocompleteValue.split(',')
            .filter(String).length > 0) {
            var menuElement = element
                .find('div.menu');

            menuElement.api({
                on: 'now',
                method: 'GET',
                url: loadForEditUrl,
                beforeSend: function beforeSend(settings) {
                    /* eslint-disable-next-line no-param-reassign */
                    settings.data[choiceValue] = autocompleteValue.split(',')
                        .filter(String);

                    return settings;
                },
                onSuccess: function onSuccess(response) {
                    response.forEach(function (item) {
                        menuElement.append($('<div class="item" data-value="' + item[choiceValue] + '">' + item[choiceName] + '</div>'));
                    });
                }
            });
        }

        window.setTimeout(function () {
            element
                .dropdown('set selected', element
                    .find('input.autocomplete')
                    .val()
                    .split(',')
                    .filter(String));
        }, 5000);
    });
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