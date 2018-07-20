<?php

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;

interface DelegatingRendererInterface
{
    /**
     * @param ReportInterface $report
     * @param Data            $data
     *
     * @return int
     */
    public function render(ReportInterface $report, Data $data);

    /**
     * Return the RendererInterface of the ReportInterface given
     *
     * @param ReportInterface $report
     *
     * @return RendererInterface
     */
    public function getRenderer(ReportInterface $report);
}
