<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

class DelegatingDataFetcher implements DelegatingDataFetcherInterface
{
    public function __construct(
        protected ServiceRegistryInterface $registry,
    ) {
    }

    public function fetch(ReportInterface $report, array $configuration = []): Data
    {
        $dataFetcher = $this->getDataFetcher($report);
        $configuration = [] === $configuration ? $report->getDataFetcherConfiguration() : $configuration;

        return $dataFetcher->fetch($configuration);
    }

    public function getDataFetcher(ReportInterface $report): DataFetcherInterface
    {
        /** @var DataFetcherInterface $dataFetcher */
        $dataFetcher = $this->registry->get($report->getDataFetcher());

        return $dataFetcher;
    }
}
