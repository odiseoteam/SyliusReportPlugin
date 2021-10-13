<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\TableConfigurationType;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Twig\Environment;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 * @author Rimas Kudelis <rimas.kudelis@adeoweb.biz>
 */
class TableRenderer implements RendererInterface
{
    private Environment $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    public function render(ReportInterface $report, Data $data): string
    {
        if ([] !== $data->getData()) {
            $data = [
                'report' => $report,
                'values' => $data->getData(),
                'labels' => $data->getLabels(),
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
