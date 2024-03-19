<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Entity\ReportInterface;

interface DelegatingDataFetcherInterface
{
    /**
     * Fetch data for given config.
     */
    public function fetch(ReportInterface $report, array $configuration = []): Data;

    /**
     * Return the DataFetcherInterface of the ReportInterface given
     */
    public function getDataFetcher(ReportInterface $report): DataFetcherInterface;
}
