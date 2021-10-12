<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
class TimePeriodChannelType extends BaseDataFetcherType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->queryFilterFormBuilder->addTimePeriod($builder);
        $this->queryFilterFormBuilder->addChannel($builder);
    }
}
