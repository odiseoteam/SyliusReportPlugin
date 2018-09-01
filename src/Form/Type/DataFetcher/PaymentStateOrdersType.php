<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Symfony\Component\Form\AbstractType;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class PaymentStateOrdersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report_data_fetcher_payment_state_orders';
    }
}
