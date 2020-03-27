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
    <a href="http://travis-ci.org/odiseoteam/SyliusReportPlugin" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/odiseoteam/SyliusReportPlugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/odiseoteam/SyliusReportPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/odiseoteam/SyliusReportPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/odiseoteam/sylius-report-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/odiseoteam/sylius-report-plugin/downloads" />
    </a>
    <a href="https://sylius.com/partners/odiseo/" target="_blank"><img src="https://github.com/odiseoteam/SyliusReportPlugin/blob/master/badge-partner-by-sylius.png" width="140"></a>
</p>

## Description

This plugin add data reports to the Sylius administration interface.
It's highly inspired on the old [SyliusReportBundle](https://github.com/Sylius/SyliusReportBundle) and 
[Report](https://github.com/Sylius/Report) component using it's good architecture.

Now supporting Sylius 1.4+ and Symfony 4.


#### Premium features!
Do you want advanced features? Take a look at our [Report Pro Plugin](https://odiseo.com.ar/plugins-and-bundles/premium/sylius-report-pro-plugin), an extended version of this one.

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

1. Run `composer require odiseoteam/sylius-report-plugin`

2. Enable the plugin in bundles.php but add it before SyliusResourceBundle like follows:

```php
<?php

return [
    // ...
    Odiseo\SyliusReportPlugin\OdiseoSyliusReportPlugin::class => ['all' => true],
    Sylius\Bundle\ResourceBundle\SyliusResourceBundle::class => ['all' => true],
    // ...
];
```
 
3. Import the plugin configurations
 
```yml
imports:
    - { resource: "@OdiseoSyliusReportPlugin/Resources/config/config.yml" }
```

This plugin use [DoctrineExtensions](https://github.com/beberlei/DoctrineExtensions) to create the different DataFetcher's queries.
For example you will need to add the Doctrine DQL functions as follows if you are using Mysql:

```yml
doctrine:
    orm:
        # ...
        dql:
            datetime_functions:
                date: DoctrineExtensions\Query\Mysql\Date
                month: DoctrineExtensions\Query\Mysql\Month
                year: DoctrineExtensions\Query\Mysql\Year
            numeric_functions:
                round: DoctrineExtensions\Query\Mysql\Round
```

4. Add the admin routes

```yml
odiseo_sylius_report_plugin_admin:
    resource: "@OdiseoSyliusReportPlugin/Resources/config/routing/admin.yml"
    prefix: /admin
```

5. Finish the installation updating the database schema and installing assets
   
```
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
php bin/console sylius:theme:assets:install --symlink
```

## Test the plugin

You can follow the instructions to test this plugins in the proper documentation page: [Test the plugin](doc/tests.md).
    
## Credits

This plugin is maintained by <a href="https://odiseo.com.ar">Odiseo</a>. Want us to help you with this plugin or any Sylius project? Contact us on <a href="mailto:team@odiseo.com.ar">team@odiseo.com.ar</a>.
