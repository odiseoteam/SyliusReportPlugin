<?php

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\TableConfigurationType;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class TableRenderer implements RendererInterface
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function render(ReportInterface $report, Data $data)
    {
        if (null !== $data->getData()) {
            $data = [
                'report' => $report,
                'values' => $data->getData(),
                'labels' => $data->getLabels(),
                'fields' => array_keys($data->getData()),
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

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return TableConfigurationType::class;
    }
}
