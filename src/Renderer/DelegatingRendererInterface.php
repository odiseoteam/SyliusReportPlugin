<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;

/**
 * @author Mateusz Zalewski <zaleslaw@.gmail.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 * @author Rimas Kudelis <rimas.kudelis@adeoweb.biz>
 */
interface DelegatingRendererInterface
{
    public function render(ReportInterface $report, Data $data): string;

    public function getRenderer(ReportInterface $report): RendererInterface;
}
