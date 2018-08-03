<?php

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;

/**
 * @author Mateusz Zalewski <zaleslaw@.gmail.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
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
