<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

class Data
{
    public const DAY_FORMAT = '%Y-%m-%d';
    public const MONTH_FORMAT = '%Y-%m';
    public const YEAR_FORMAT = '%Y';

    private iterable $labels = [];

    private iterable $data = [];

    public function getLabels(): iterable
    {
        return $this->labels;
    }

    public function setLabels(iterable $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    public function getData(): iterable
    {
        return $this->data;
    }

    public function setData(iterable $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getFormatByGroupBy(array $groups): string
    {
        if (in_array('date', $groups, true)) {
            return self::DAY_FORMAT;
        }

        if (in_array('month', $groups, true)) {
            return self::MONTH_FORMAT;
        }

        return self::YEAR_FORMAT;
    }
}
