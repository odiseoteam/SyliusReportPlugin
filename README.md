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

This plugin add a reports interface to the Sylius administration. Some reports comes with this bundle but you can create your custom reports.

<img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/screenshot_1.png" alt="Reports admin">

## Installation

1. Run `composer require odiseoteam/sylius-report-plugin`.

2. Add the plugin to the AppKernel but add it before SyliusResourceBundle. To do that you need change the registerBundles like this:

```php
public function registerBundles(): array
{
    $preResourceBundles = [
        new \Odiseo\SyliusVendorPlugin\OdiseoSyliusReportPlugin(),
    ];

    $bundles = [
        ...
    ];

    return array_merge($preResourceBundles, parent::registerBundles(), $bundles);
}
```
 
3. Import the configurations on your config.yml:
 
```yml
    - { resource: "@OdiseoSyliusReportPlugin/Resources/config/app/config.yml" }
```

5. Add the admin routes:

```yml
odiseo_sylius_admin_report:
    resource: "@OdiseoSyliusReportPlugin/Resources/config/routing/admin_report.yml"
    prefix: /admin
```

6. Finish the installation installing assets:
   
```
bin/console assets:install
bin/console sylius:theme:assets:install
```

## Credits

This plugins is maintained by <a href="https://odiseo.com.ar">Odiseo</a>, a team of senior developers. Contact us: <a href="mailto:team@odiseo.com.ar">team@odiseo.com.ar</a>.
