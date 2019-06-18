<?php

namespace spec\Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\DataFetcher\DataFetcherInterface;
use Odiseo\SyliusReportPlugin\DataFetcher\DefaultDataFetchers;
use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcher;
use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcherInterface;
use Odiseo\SyliusReportPlugin\DataFetcher\UserRegistrationDataFetcher;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Registry\ServiceRegistryInterface;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class DelegatingDataFetcherSpec extends ObjectBehavior
{
    function let(ServiceRegistryInterface $registry)
    {
        $this->beConstructedWith($registry);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DelegatingDataFetcher::class);
    }

    function it_should_implement_delegating_data_fetcher_interface()
    {
        $this->shouldImplement(DelegatingDataFetcherInterface::class);
    }

    function it_get_the_data_fetcher_with_given_report(ReportInterface $report, ServiceRegistryInterface $registry, UserRegistrationDataFetcher $userRegistrationDataFetcher)
    {
        $report->getDataFetcher()->willReturn(DefaultDataFetchers::USER_REGISTRATION);
        $registry->get(DefaultDataFetchers::USER_REGISTRATION)->willReturn($userRegistrationDataFetcher);
        $this->getDataFetcher($report)->shouldReturn($userRegistrationDataFetcher);
    }

    function it_throws_an_exception_if_report_has_not_data_fetcher(ReportInterface $report, ServiceRegistryInterface $registry)
    {
        $report->getDataFetcher()->willReturn(null);
        $registry->get(DefaultDataFetchers::USER_REGISTRATION)->willReturn(UserRegistrationDataFetcher::class);

        $this
            ->shouldThrow(new \InvalidArgumentException('Cannot fetch data for ReportInterface instance without DataFetcher defined.'))
            ->during('getDataFetcher', [$report])
        ;
    }

    function it_fetch_the_data_with_given_report_configuration(ReportInterface $report, DataFetcherInterface $dataFetcher, ServiceRegistryInterface $registry)
    {
        $reportConfiguration = [
            'start' => new \DateTime('1918-01-01 00:00:00.000000'),
            'end' => new \DateTime(),
            'period' => 'month',
            'empty_records' => false
        ];
        $otherConfiguration = [
        ];
        $data = new Data();

        $report->getDataFetcher()->willReturn(DefaultDataFetchers::USER_REGISTRATION);
        $registry->get(DefaultDataFetchers::USER_REGISTRATION)->willReturn($dataFetcher);
        $report->getDataFetcherConfiguration()->willReturn($reportConfiguration);
        $dataFetcher->fetch($reportConfiguration)->willReturn($data);

        $this->fetch($report, $otherConfiguration)->shouldReturn($data);
    }

    function it_fetch_the_data_with_given_parameter_configuration(ReportInterface $report, DataFetcherInterface $dataFetcher, ServiceRegistryInterface $registry)
    {
        $reportConfiguration = [
            'start' => new \DateTime('1918-01-01 00:00:00.000000'),
            'end' => new \DateTime(),
            'period' => 'month',
            'empty_records' => false
        ];
        $otherConfiguration = [
            'otherConfiguration' => 'test'
        ];
        $data = new Data();

        $report->getDataFetcher()->willReturn(DefaultDataFetchers::USER_REGISTRATION);
        $registry->get(DefaultDataFetchers::USER_REGISTRATION)->willReturn($dataFetcher);
        $report->getDataFetcherConfiguration()->willReturn($reportConfiguration);
        $dataFetcher->fetch($otherConfiguration)->willReturn($data);

        $this->fetch($report, $otherConfiguration)->shouldReturn($data);
    }
}
