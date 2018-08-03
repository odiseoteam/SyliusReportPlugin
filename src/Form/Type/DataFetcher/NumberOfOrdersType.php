<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class NumberOfOrdersType extends TimePeriodType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report_data_fetcher_number_of_orders';
    }
}
