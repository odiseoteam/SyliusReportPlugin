<?php

namespace Odiseo\SyliusReportPlugin\Form\Type;

use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcherInterface;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class ReportDataFetcherConfigurationType extends AbstractResourceType
{
    /**
     * @var DelegatingDataFetcherInterface
     */
    protected $delegatingDataFetcher;

    /**
     * @var string
     */
    protected $dataFetcherConfigurationTemplate;

    public function __construct(
        string $dataClass,
        array $validationGroups,
        DelegatingDataFetcherInterface $delegatingDataFetcher
    )
    {
        parent::__construct($dataClass, $validationGroups);

        $this->delegatingDataFetcher = $delegatingDataFetcher;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var ReportInterface $report */
        $report = $builder->getData();
        $dataFetcher = $this->delegatingDataFetcher->getDataFetcher($report);

        $builder->add('dataFetcherConfiguration', $dataFetcher->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report';
    }
}
