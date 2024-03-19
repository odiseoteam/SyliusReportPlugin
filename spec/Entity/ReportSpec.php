<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Entity;

use Odiseo\SyliusReportPlugin\DataFetcher\DefaultDataFetchers;
use Odiseo\SyliusReportPlugin\Entity\Report;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\DefaultRenderers;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

final class ReportSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Report::class);
    }

    function it_implements_report_interface(): void
    {
        $this->shouldImplement(ReportInterface::class);
    }

    function it_implements_resource_interface(): void
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_implements_code_aware_interface(): void
    {
        $this->shouldImplement(CodeAwareInterface::class);
    }

    function it_implements_timestamplable_interface(): void
    {
        $this->shouldImplement(TimestampableInterface::class);
    }

    function it_has_no_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_has_no_code_by_default(): void
    {
        $this->getCode()->shouldReturn(null);
    }

    function it_has_no_description_by_default(): void
    {
        $this->getDescription()->shouldReturn(null);
    }

    function it_has_a_default_renderer(): void
    {
        $this->getRenderer()->shouldReturn(DefaultRenderers::TABLE);
    }

    function it_has_a_default_data_fetcher(): void
    {
        $this->getDataFetcher()->shouldReturn(DefaultDataFetchers::USER_REGISTRATION);
    }

    function it_has_a_default_renderer_configuration(): void
    {
        $this->getRendererConfiguration()->shouldHaveCount(0);
    }

    function it_has_a_default_data_fetcher_configuration(): void
    {
        $this->getDataFetcherConfiguration()->shouldHaveCount(1);
    }

    function it_is_timestampable(): void
    {
        $dateTime = new \DateTime();
        $this->setCreatedAt($dateTime);
        $this->getCreatedAt()->shouldReturn($dateTime);
        $this->setUpdatedAt($dateTime);
        $this->getUpdatedAt()->shouldReturn($dateTime);
    }

    function it_allows_access_via_properties(): void
    {
        $this->setCode('report');
        $this->getCode()->shouldReturn('report');

        $this->setName('Report');
        $this->getName()->shouldReturn('Report');

        $this->setDescription('Report description');
        $this->getDescription()->shouldReturn('Report description');

        $this->setDataFetcher(DefaultDataFetchers::NUMBER_OF_ORDERS);
        $this->getDataFetcher()->shouldReturn(DefaultDataFetchers::NUMBER_OF_ORDERS);

        $this->setDataFetcherConfiguration(['test' => 'yes']);
        $this->getDataFetcherConfiguration()->shouldReturn(['test' => 'yes']);

        $this->setRenderer(DefaultRenderers::CHART);
        $this->getRenderer()->shouldReturn(DefaultRenderers::CHART);

        $this->setRendererConfiguration(['test' => 'yes']);
        $this->getRendererConfiguration()->shouldReturn(['test' => 'yes']);
    }
}
