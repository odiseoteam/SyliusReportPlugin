<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Model\ReportInterface;

/**
 * Delegating data fetcher.
 */
interface DelegatingDataFetcherInterface
{
    /**
     * Fetch data for given config.
     *
     * @param ReportInterface $report
     * @param array           $configuration
     *
     * @return Data
     */
    public function fetch(ReportInterface $report, array $configuration = []);
}
