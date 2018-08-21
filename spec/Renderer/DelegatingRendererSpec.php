<?php

namespace spec\Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\DefaultRenderers;
use Odiseo\SyliusReportPlugin\Renderer\DelegatingRenderer;
use Odiseo\SyliusReportPlugin\Renderer\DelegatingRendererInterface;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use Odiseo\SyliusReportPlugin\Renderer\TableRenderer;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class DelegatingRendererSpec extends ObjectBehavior
{
    function let(ServiceRegistryInterface $registry)
    {
        $this->beConstructedWith($registry);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DelegatingRenderer::class);
    }

    function it_should_implement_delegating_renderer_interface()
    {
        $this->shouldImplement(DelegatingRendererInterface::class);
    }

    function it_get_the_renderer_with_given_report(ReportInterface $report, ServiceRegistryInterface $registry)
    {
        $report->getRenderer()->willReturn(DefaultRenderers::TABLE);
        $registry->get(DefaultRenderers::TABLE)->willReturn(TableRenderer::class);
        $this->getRenderer($report)->shouldReturn(TableRenderer::class);
    }

    function it_throws_an_exception_if_report_has_not_renderer(ReportInterface $report, ServiceRegistryInterface $registry)
    {
        $report->getRenderer()->willReturn(null);
        $registry->get(DefaultRenderers::TABLE)->willReturn(TableRenderer::class);

        $this
            ->shouldThrow(new \InvalidArgumentException('Cannot render data for ReportInterface instance without renderer defined.'))
            ->during('getRenderer', [$report])
        ;
    }

    function it_render_with_given_report_configuration_and_data(ReportInterface $report, RendererInterface $renderer, Data $data, ServiceRegistryInterface $registry)
    {
        $report->getRenderer()->willReturn(DefaultRenderers::TABLE);
        $registry->get(DefaultRenderers::TABLE)->willReturn($renderer);
        $renderer->render($report, $data)->willReturn('<div>Table render</div>');

        $this->render($report, $data)->shouldReturn('<div>Table render</div>');
    }
}
