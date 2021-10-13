<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use InvalidArgumentException;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
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
     */
    protected ServiceRegistryInterface $registry;

    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
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
