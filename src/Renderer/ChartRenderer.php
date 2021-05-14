<?php

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\ChartConfigurationType;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Twig\Environment;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class ChartRenderer implements RendererInterface
{
    const BAR_CHART = 'bar';
    const LINE_CHART = 'line';
    const RADAR_CHART = 'radar';
    const POLAR_CHART = 'polar';
    const PIE_CHART = 'pie';
    const DOUGHNUT_CHART = 'doughnut';

    /**
     * @var Environment
     */
    private $templating;

    /**
     * @param Environment $templating
     */
    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function render(ReportInterface $report, Data $data)
    {
        if (null !== $data->getData()) {
            $rendererData = [
                'report' => $report,
                'values' => $data->getData(),
                'labels' => $data->getLabels(),
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

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return ChartConfigurationType::class;
    }

    /**
     * @return array
     */
    public static function getChartTypes()
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
