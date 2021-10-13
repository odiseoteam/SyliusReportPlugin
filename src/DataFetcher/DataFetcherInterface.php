<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
interface DataFetcherInterface
{
    public function fetch(array $configuration): Data;

    public function getType(): string;
}
