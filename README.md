<h1 align="center">
    <a href="https://odiseo.com.ar/" target="_blank" title="Odiseo">
        <img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/logo_odiseo.png" alt="Odiseo" width="300px" />
    </a>
    <br />
    Odiseo Sylius Report Plugin
    <br />
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/odiseoteam/sylius-report-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/odiseoteam/sylius-report-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/odiseoteam/SyliusReportPlugin" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/odiseoteam/SyliusReportPlugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/odiseoteam/SyliusReportPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/odiseoteam/SyliusReportPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/odiseoteam/sylius-report-plugin/downloads" />
    </a>
</h1>

## Description

This plugin add data reports to the Sylius administration interface.
It is highly inspired on the past [SyliusReportBundle](https://github.com/Sylius/SyliusReportBundle) bundle and 
[Report](https://github.com/Sylius/Report) component using its good architecture.

### Architecture

Basically you have a **DataFetcherInterface** and **RendererInterface** interfaces. The first one defines how to fetch the **Data**
according on a configuration provided. And the second one uses the **Data** returned by the fetcher and returns a rendered view.

Some DataFetchers and Renderers come with this plugin but you can create your own by implementing their interfaces.

<img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/screenshot_1.png" alt="Reports admin">

## Demo

You can see this plugin in action in our Sylius Demo application.

- Frontend: [sylius-demo.odiseo.com.ar](https://sylius-demo.odiseo.com.ar). 
- Administration: [sylius-demo.odiseo.com.ar/admin](https://sylius-demo.odiseo.com.ar/admin) with `odiseo: odiseo` credentials.
Next, you can enter to the [reports](https://sylius-demo.odiseo.com.ar/admin/reports/) page.

## Installation

1. Run `composer require odiseoteam/sylius-report-plugin`.

2. Add the plugin to the AppKernel but add it before SyliusResourceBundle. To do that you need change the registerBundles like this:

```php
public function registerBundles(): array
{
    $preResourceBundles = [
        new \Odiseo\SyliusReportPlugin\OdiseoSyliusReportPlugin(),
    ];

    $bundles = [
        ...
    ];

    return array_merge($preResourceBundles, parent::registerBundles(), $bundles);
}
```
 
3. Import the configurations on your config.yml:
 
```yml
    - { resource: "@OdiseoSyliusReportPlugin/Resources/config/config.yml" }
```

5. Add the admin routes:

```yml
odiseo_sylius_report_plugin_admin:
    resource: "@OdiseoSyliusReportPlugin/Resources/config/routing/admin.yml"
    prefix: /admin
```

6. Finish the installation updatating the database schema and installing assets:
   
```
php bin/console doctrine:schema:update --force
php bin/console assets:install
php bin/console sylius:theme:assets:install
```

## Test the plugin

You can follow the instructions to test this plugins in the proper documentation page: [Test the plugin](doc/tests.md).
    
## Credits

This plugin is maintained by <a href="https://odiseo.com.ar">Odiseo</a>. Want us to help you with this plugin or any Sylius project? Contact us on <a href="mailto:team@odiseo.com.ar">team@odiseo.com.ar</a>.
