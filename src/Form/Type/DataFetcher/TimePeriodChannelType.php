<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
class TimePeriodChannelType extends BaseDataFetcherType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->queryFilterFormBuilder->addTimePeriod($builder);
        $this->queryFilterFormBuilder->addChannel($builder);
    }
}
