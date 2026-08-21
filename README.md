<p align="center">
    <a href="https://odiseo.io/en?utm_source=github&utm_medium=readme&utm_campaign=sylius-report-plugin" target="_blank" title="Odiseo">
        <img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/sylius-report-plugin.png" alt="Sylius Report Plugin" />
    </a>
    <br />
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/odiseoteam/sylius-report-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/odiseoteam/sylius-report-plugin.svg" />
    </a>
    <a href="https://github.com/odiseoteam/SyliusReportPlugin/actions" title="Build Status" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/odiseoteam/SyliusReportPlugin/build.yml" />
    </a>
    <a href="https://scrutinizer-ci.com/g/odiseoteam/SyliusReportPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/odiseoteam/SyliusReportPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/odiseoteam/sylius-report-plugin/downloads" />
    </a>
    <a href="https://sylius-devs.slack.com" title="Slack" target="_blank">
        <img src="https://img.shields.io/badge/community%20chat-slack-FF1493.svg" />
    </a>
</p>
<p align="center"><a href="https://sylius.com/partners/odiseo/" target="_blank"><img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/badge-partner-by-sylius.png" width="140"></a></p>

## Description

This plugin adds data reports to the Sylius administration interface.

Support Sylius version 1.9+.

#### Premium features!
Do you want advanced features? Take a look at our [Report Pro Plugin](https://odiseo.io/en/products/sylius-plugins?utm_source=github&utm_medium=readme&utm_campaign=sylius-report-plugin), an extended version of this one.

## Architecture

Basically you have a **DataFetcherInterface** and **RendererInterface** interfaces. The first one defines how to fetch the **Data**
according to a configuration provided. And the second one uses the **Data** returned by the fetcher and returns a rendered view.

Some DataFetchers and Renderers come with this plugin, but you can create your own by implementing their interfaces.

<img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/screenshot_1.png" alt="Reports admin">

## Documentation

- [Installation](doc/installation.md)
- [Tests](doc/tests.md)

## Demo

Want a live walkthrough of this plugin? [Get in touch](https://odiseo.io/en/contact-us?utm_source=github&utm_medium=readme&utm_campaign=sylius-report-plugin) — or browse all our Sylius plugins at [odiseo.io](https://odiseo.io/en/products/sylius-plugins?utm_source=github&utm_medium=readme&utm_campaign=sylius-report-plugin).

## Credits

This plugin is maintained by [Odiseo](https://odiseo.io/en?utm_source=github&utm_medium=readme&utm_campaign=sylius-report-plugin). Want us to help you with this plugin or any Sylius project? [Get in touch](https://odiseo.io/en/contact-us?utm_source=github&utm_medium=readme&utm_campaign=sylius-report-plugin).
