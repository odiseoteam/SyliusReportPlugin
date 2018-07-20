<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * Data fetcher choice type
 */
class DelegatingDataFetcher implements DelegatingDataFetcherInterface
{
    /**
     * DataFetcher registry.
     *
     * @var ServiceRegistryInterface
     */
    protected $registry;

    /**
     * @param ServiceRegistryInterface $registry
     */
    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If the report does not have a data fetcher.
     */
    public function fetch(ReportInterface $report, array $configuration = [])
    {
        if (null === $type = $report->getDataFetcher()) {
            throw new \InvalidArgumentException('Cannot fetch data for ReportInterface instance without DataFetcher defined.');
        }

        /** @var DataFetcherInterface $dataFetcher */
        $dataFetcher = $this->registry->get($type);
        $configuration = empty($configuration) ? $report->getDataFetcherConfiguration() : $configuration;

        return $dataFetcher->fetch($configuration);
    }
}
