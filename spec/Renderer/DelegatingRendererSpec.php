<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\DefaultRenderers;
use Odiseo\SyliusReportPlugin\Renderer\DelegatingRenderer;
use Odiseo\SyliusReportPlugin\Renderer\DelegatingRendererInterface;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use Odiseo\SyliusReportPlugin\Renderer\TableRenderer;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class DelegatingRendererSpec extends ObjectBehavior
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

    function it_get_the_renderer_with_given_report(ReportInterface $report, ServiceRegistryInterface $registry, TableRenderer $tableRenderer)
    {
        $report->getRenderer()->willReturn(DefaultRenderers::TABLE);
        $registry->get(DefaultRenderers::TABLE)->willReturn($tableRenderer);
        $this->getRenderer($report)->shouldReturn($tableRenderer);
    }

    function it_render_with_given_report_configuration_and_data(ReportInterface $report, RendererInterface $renderer, Data $data, ServiceRegistryInterface $registry)
    {
        $report->getRenderer()->willReturn(DefaultRenderers::TABLE);
        $registry->get(DefaultRenderers::TABLE)->willReturn($renderer);
        $renderer->render($report, $data)->willReturn('<div>Table render</div>');

        $this->render($report, $data)->shouldReturn('<div>Table render</div>');
    }
}
