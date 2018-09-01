<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

/**
 * Default data fetchers.
 *
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class DefaultDataFetchers
{
    /**
     * User registrations data fetcher
     */
    const USER_REGISTRATION = 'odiseo_sylius_report_data_fetcher_user_registration';

    /**
     * Sales total data fetcher
     */
    const SALES_TOTAL = 'odiseo_sylius_report_data_fetcher_sales_total';

    /**
     * Number of orders data fetcher
     */
    const NUMBER_OF_ORDERS = 'odiseo_sylius_report_data_fetcher_number_of_orders';

    /**
     * Payment state orders data fetcher
     */
    const PAYMENT_STATE_ORDERS = 'odiseo_sylius_report_data_fetcher_payment_state_orders';
}
