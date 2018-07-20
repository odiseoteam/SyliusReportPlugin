<?php

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;

interface RendererInterface
{
    /**
     * @param ReportInterface $report
     * @param Data            $data
     *
     * @return string
     */
    public function render(ReportInterface $report, Data $data);

    /**
     * @return string
     */
    public function getType();
}
