<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

class DelegatingRenderer implements DelegatingRendererInterface
{
    protected ServiceRegistryInterface $registry;

    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function render(ReportInterface $report, Data $data): string
    {
        $renderer = $this->getRenderer($report);

        return $renderer->render($report, $data);
    }

    public function getRenderer(ReportInterface $report): RendererInterface
    {
        /** @var RendererInterface $renderer */
        $renderer = $this->registry->get($report->getRenderer());

        return $renderer;
    }
}
