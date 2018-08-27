<?php

namespace spec\Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\TableConfigurationType;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use Odiseo\SyliusReportPlugin\Renderer\TableRenderer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class TableRendererSpec extends ObjectBehavior
{
    function let(EngineInterface $templating)
    {
        $this->beConstructedWith($templating);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TableRenderer::class);
    }

    function it_should_implement_renderer_interface()
    {
        $this->shouldImplement(RendererInterface::class);
    }

    function it_renders_data_with_given_configuration(ReportInterface $report, Data $reportData, EngineInterface $templating)
    {
        $reportData->getLabels()->willReturn(['month', 'user_total']);
        $reportData->getData()->willReturn(['month1' => '50', 'month2' => '40']);

        $renderData = [
            'report' => $report,
            'values' => ['month1' => '50', 'month2' => '40'],
            'labels' => ['month', 'user_total'],
            'fields' => ['month1', 'month2'],
        ];

        $report->getRendererConfiguration()->willReturn(['template' => '@OdiseoSyliusReportPlugin/Table/default.html.twig']);

        $templating->render('@OdiseoSyliusReportPlugin/Table/default.html.twig', [
            'data' => $renderData,
            'configuration' => ['template' => '@OdiseoSyliusReportPlugin/Table/default.html.twig'],
        ])->willReturn('<div>Table Report</div>');

        $this->render($report, $reportData)->shouldReturn('<div>Table Report</div>');
    }

    function it_renders_a_no_data_with_given_configuration(ReportInterface $report, Data $reportData, EngineInterface $templating)
    {
        $reportData->getLabels()->willReturn(['month', 'user_total']);
        $reportData->getData()->willReturn(null);

        $report->getRendererConfiguration()->willReturn(['template' => '@OdiseoSyliusReportPlugin/noDataTemplate.html.twig']);

        $templating->render('@OdiseoSyliusReportPlugin/noDataTemplate.html.twig', [
            'report' => $report,
        ])->willReturn('<div>No Data</div>');

        $this->render($report, $reportData)->shouldReturn('<div>No Data</div>');
    }

    function it_is_a_table_type()
    {
        $this->getType()->shouldReturn(TableConfigurationType::class);
    }
}
