<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

interface DataFetcherInterface
{
    /**
     * @param array $configuration
     *
     * @return Data $data
     */
    public function fetch(array $configuration);

    /**
     * @return string
     */
    public function getType();
}
