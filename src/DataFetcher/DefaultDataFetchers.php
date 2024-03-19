<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

final class DefaultDataFetchers
{
    /**
     * User registrations data fetcher
     */
    public const USER_REGISTRATION = 'odiseo_sylius_report_plugin_data_fetcher_user_registration';

    /**
     * Sales total data fetcher
     */
    public const SALES_TOTAL = 'odiseo_sylius_report_plugin_data_fetcher_sales_total';

    /**
     * Number of orders data fetcher
     */
    public const NUMBER_OF_ORDERS = 'odiseo_sylius_report_plugin_data_fetcher_number_of_orders';
}
