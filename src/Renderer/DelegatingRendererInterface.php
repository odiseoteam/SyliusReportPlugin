<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;

interface DelegatingRendererInterface
{
    public function render(ReportInterface $report, Data $data): string;

    public function getRenderer(ReportInterface $report): RendererInterface;
}
