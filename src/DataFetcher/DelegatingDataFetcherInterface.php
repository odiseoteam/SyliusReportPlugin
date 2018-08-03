<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Model\ReportInterface;

/**
 * Delegating data fetcher.
 *
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
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

    /**
     * Return the DataFetcherInterface of the ReportInterface given
     *
     * @param ReportInterface $report
     *
     * @return DataFetcherInterface
     */
    public function getDataFetcher(ReportInterface $report);
}
