<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\ChartConfigurationType;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\ChartRenderer;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use PhpSpec\ObjectBehavior;
use Twig\Environment;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class ChartRendererSpec extends ObjectBehavior
{
    function let(Environment $templating)
    {
        $this->beConstructedWith($templating);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChartRenderer::class);
    }

    function it_should_implement_renderer_interface()
    {
        $this->shouldImplement(RendererInterface::class);
    }

    function it_renders_data_with_given_configuration(ReportInterface $report, Data $reportData, $templating)
    {
        $reportData->getData()->willReturn(['month1' => '50', 'month2' => '40']);
        $reportData->getLabels()->willReturn(['month', 'sales']);

        $renderData = [
            'report' => $report,
            'values' => ['month1' => '50', 'month2' => '40'],
            'labels' => ['month', 'sales'],
        ];

        $report->getRendererConfiguration()->willReturn(['template' => '@OdiseoSyliusReportPlugin/Chart/default.html.twig']);

        $templating->render('@OdiseoSyliusReportPlugin/Chart/default.html.twig', [
            'data' => $renderData,
            'configuration' => ['template' => '@OdiseoSyliusReportPlugin/Chart/default.html.twig'],
        ])->willReturn('<div>Chart Report</div>');

        $this->render($report, $reportData)->shouldReturn('<div>Chart Report</div>');
    }

    function it_is_a_chart_type()
    {
        $this->getType()->shouldReturn(ChartConfigurationType::class);
    }
}
