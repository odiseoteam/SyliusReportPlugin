<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Odiseo\SyliusReportPlugin\Form\Builder\QueryFilterFormBuilderInterface;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\SalesTotalType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilder;

final class SalesTotalTypeSpec extends ObjectBehavior
{
    function let(QueryFilterFormBuilderInterface $queryFilterFormBuilder)
    {
        $this->beConstructedWith($queryFilterFormBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SalesTotalType::class);
    }

    function it_should_be_abstract_type_object()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_has_block_prefix()
    {
        $this->getBlockPrefix()->shouldReturn('odiseo_sylius_report_data_fetcher_sales_total');
    }

    function it_builds_the_form(FormBuilder $builder)
    {
        $builder->add('start', DateType::class, Argument::any())->willReturn($builder);
        $builder->add('end', DateType::class, Argument::any())->willReturn($builder);
        $builder->add('period', ChoiceType::class, Argument::any())->willReturn($builder);
        $builder->add('empty_records', CheckboxType::class, Argument::any())->willReturn($builder);

        $this->buildForm($builder, []);
    }
}
