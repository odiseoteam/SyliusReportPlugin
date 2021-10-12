<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;

interface RendererInterface
{
    public function render(ReportInterface $report, Data $data): string;

    public function getType(): string;
}
