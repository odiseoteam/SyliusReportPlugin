<p align="center">
    <a href="https://odiseo.com.ar/" target="_blank" title="Odiseo">
        <img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/sylius-report-plugin.png" alt="Sylius Report Plugin" />
    </a>
    <br />
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/odiseoteam/sylius-report-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/odiseoteam/sylius-report-plugin.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/odiseoteam/SyliusReportPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/odiseoteam/SyliusReportPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/odiseoteam/sylius-report-plugin/downloads" />
    </a>
</p>
<p align="center"><a href="https://sylius.com/partners/odiseo/" target="_blank"><img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/badge-partner-by-sylius.png" width="140"></a></p>

## Description

This plugin adds data reports to the Sylius administration interface.
It's highly inspired on the old [SyliusReportBundle](https://github.com/Sylius/SyliusReportBundle) and 
[Report](https://github.com/Sylius/Report) component using its good architecture.

Support Sylius version 1.6+.

#### Premium features!
Do you want advanced features? Take a look at our [Report Pro Plugin](https://odiseo.com.ar/plugins-and-bundles/premium/sylius-report-pro-plugin), an extended version of this one.

### Architecture

Basically you have a **DataFetcherInterface** and **RendererInterface** interfaces. The first one defines how to fetch the **Data**
according to a configuration provided. And the second one uses the **Data** returned by the fetcher and returns a rendered view.

Some DataFetchers and Renderers come with this plugin, but you can create your own by implementing their interfaces.

<img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/screenshot_1.png" alt="Reports admin">

## Demo

You can see this plugin in action in our Sylius Demo application.

- Frontend: [sylius-demo.odiseo.com.ar](https://sylius-demo.odiseo.com.ar).
- Administration: [sylius-demo.odiseo.com.ar/admin](https://sylius-demo.odiseo.com.ar/admin) with `odiseo: odiseo` credentials.

## Documentation

- [Installation](doc/installation.md)
- [Tests](doc/tests.md)

## Credits

This plugin is maintained by <a href="https://odiseo.io">Odiseo</a>. Want us to help you with this plugin or any Sylius project? Contact us on <a href="mailto:team@odiseo.com.ar">team@odiseo.com.ar</a>.
