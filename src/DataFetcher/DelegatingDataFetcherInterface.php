<?php

declare(strict_types=1);

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
     */
    public function fetch(ReportInterface $report, array $configuration = []): Data;

    /**
     * Return the DataFetcherInterface of the ReportInterface given
     */
    public function getDataFetcher(ReportInterface $report): DataFetcherInterface;
}
