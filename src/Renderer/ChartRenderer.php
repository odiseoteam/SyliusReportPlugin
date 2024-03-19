<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\ChartConfigurationType;
use Twig\Environment;

class ChartRenderer implements RendererInterface
{
    public const BAR_CHART = 'bar';

    public const LINE_CHART = 'line';

    public const RADAR_CHART = 'radar';

    public const POLAR_CHART = 'polar';

    public const PIE_CHART = 'pie';

    public const DOUGHNUT_CHART = 'doughnut';

    private Environment $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    public function render(ReportInterface $report, Data $data): string
    {
        /** @var array $labels */
        $labels = $data->getLabels();
        /** @var array $values */
        $values = $data->getData();

        if (count($values) > 0) {
            $rendererData = [
                'report' => $report,
                'values' => $values,
                'labels' => $labels,
            ];

            $rendererConfiguration = $report->getRendererConfiguration();

            return $this->templating->render($rendererConfiguration['template'], [
                'data' => $rendererData,
                'configuration' => $rendererConfiguration,
            ]);
        }

        return $this->templating->render('@OdiseoSyliusReportPlugin/noDataTemplate.html.twig', [
            'report' => $report,
        ]);
    }

    public function getType(): string
    {
        return ChartConfigurationType::class;
    }

    public static function getChartTypes(): array
    {
        return [
            'Bar chart' => self::BAR_CHART,
            'Line chart' => self::LINE_CHART,
            'Radar chart' => self::RADAR_CHART,
            'Polar chart' => self::POLAR_CHART,
            'Pie chart' => self::PIE_CHART,
            'Doughnut chart' => self::DOUGHNUT_CHART,
        ];
    }
}
