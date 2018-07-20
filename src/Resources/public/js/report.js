(function ($) {
    'use strict';

    $(document).ready(function() {
        $('#odiseo_sylius_report_renderer').handlePrototypes({
            'prototypePrefix': 'odiseo_sylius_report_renderer_renderers',
            'containerSelector': '#odiseo_sylius_report_renderer_configuration'
        });
        $('#odiseo_sylius_report_dataFetcher').handlePrototypes({
            'prototypePrefix': 'odiseo_sylius_report_dataFetcher_dataFetchers',
            'containerSelector': '#odiseo_sylius_report_dataFetcher_configuration'
        });
        $('#odiseo_sylius_report_dataFetcher').on('change', function (e) {
            $('#odiseo_sylius_report_dataFetcher_configuration .ui.toggle.checkbox').checkbox();
            $('#odiseo_sylius_report_dataFetcher_configuration .ui.dropdown').dropdown();
        });
    });
})( jQuery );