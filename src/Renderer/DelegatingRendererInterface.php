<?php

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;

interface DelegatingRendererInterface
{
    /**
     * @param ReportInterface $subject
     * @param Data            $data
     *
     * @return int
     */
    public function render(ReportInterface $subject, Data $data);
}
