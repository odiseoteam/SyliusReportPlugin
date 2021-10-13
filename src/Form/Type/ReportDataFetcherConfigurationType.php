<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type;

use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcherInterface;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class ReportDataFetcherConfigurationType extends AbstractResourceType
{
    protected DelegatingDataFetcherInterface $delegatingDataFetcher;

    protected string $dataFetcherConfigurationTemplate;

    public function __construct(
        string $dataClass,
        array $validationGroups,
        DelegatingDataFetcherInterface $delegatingDataFetcher
    )
    {
        parent::__construct($dataClass, $validationGroups);

        $this->delegatingDataFetcher = $delegatingDataFetcher;
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
