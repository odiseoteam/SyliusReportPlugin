<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Rimas Kudelis <rimas.kudelis@adeoweb.biz>
 */
class Data
{
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
}
