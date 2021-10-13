<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use InvalidArgumentException;
use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 * @author Rimas Kudelis <rimas.kudelis@adeoweb.biz>
 */
class DelegatingRenderer implements DelegatingRendererInterface
{
    /**
     * Renderer registry.
     */
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
