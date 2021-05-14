## Installation

1. Run `composer require odiseoteam/sylius-report-plugin --no-scripts`

2. Enable the plugin in bundles.php but add it before SyliusResourceBundle like follows

```php
<?php
// config/bundles.php

return [
    // ...
    Odiseo\SyliusReportPlugin\OdiseoSyliusReportPlugin::class => ['all' => true],
    Sylius\Bundle\ResourceBundle\SyliusResourceBundle::class => ['all' => true],
    // ...
];
```

3. Import the plugin configurations

```yml
# config/packages/_sylius.yaml
imports:
    ...

    - { resource: "@OdiseoSyliusReportPlugin/Resources/config/config.yml" }
```

This plugin use [DoctrineExtensions](https://github.com/beberlei/DoctrineExtensions) to create the different DataFetcher's queries.
For example, you will need to add the Doctrine DQL functions as follows if you are using Mysql:

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
php bin/console doctrine:schema:update --force
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```
