<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
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
