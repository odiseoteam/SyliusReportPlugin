<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * Data fetcher choice type
 *
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
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
     */
    public function fetch(ReportInterface $report, array $configuration = []): Data
    {
        $dataFetcher = $this->getDataFetcher($report);
        $configuration = empty($configuration) ? $report->getDataFetcherConfiguration() : $configuration;

        return $dataFetcher->fetch($configuration);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If the report does not have a data fetcher.
     */
    public function getDataFetcher(ReportInterface $report): DataFetcherInterface
    {
        if (null === $type = $report->getDataFetcher()) {
            throw new \InvalidArgumentException('Cannot fetch data for ReportInterface instance without DataFetcher defined.');
        }

        /** @var DataFetcherInterface $dataFetcher */
        $dataFetcher = $this->registry->get($type);

        return $dataFetcher;
    }
}
