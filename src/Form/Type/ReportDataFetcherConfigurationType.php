<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type;

use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcherInterface;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class ReportDataFetcherConfigurationType extends AbstractResourceType
{
    public function __construct(
        protected DelegatingDataFetcherInterface $delegatingDataFetcher,
        string $dataClass,
        array $validationGroups,
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ReportInterface $report */
        $report = $builder->getData();
        $dataFetcher = $this->delegatingDataFetcher->getDataFetcher($report);

        $builder->add('dataFetcherConfiguration', $dataFetcher->getType());
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_sylius_report';
    }
}
