<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\TableConfigurationType;
use Twig\Environment;

class TableRenderer implements RendererInterface
{
    public function __construct(
        private Environment $templating,
    ) {
    }

    public function render(ReportInterface $report, Data $data): string
    {
        /** @var array $labels */
        $labels = $data->getLabels();
        /** @var array $values */
        $values = $data->getData();

        if (count($values) > 0) {
            $data = [
                'report' => $report,
                'values' => $values,
                'labels' => $labels,
            ];

            $rendererConfiguration = $report->getRendererConfiguration();

            return $this->templating->render($rendererConfiguration['template'], [
                'data' => $data,
                'configuration' => $rendererConfiguration,
            ]);
        }

        return $this->templating->render('@OdiseoSyliusReportPlugin/noDataTemplate.html.twig', [
            'report' => $report,
        ]);
    }

    public function getType(): string
    {
        return TableConfigurationType::class;
    }
}
