<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\DataFetcherChoiceType;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DataFetcherChoiceTypeSpec extends ObjectBehavior
{
    function let()
    {
        $choices = [
            'dataFetcher1' => 'DataFetcher 1',
            'dataFetcher2' => 'DataFetcher 2',
        ];

        $this->beConstructedWith($choices);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DataFetcherChoiceType::class);
    }

    function it_should_be_abstract_type_object()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_sets_default_options(OptionsResolver $resolver)
    {
        $choices = [
            'DataFetcher 1' => 'dataFetcher1',
            'DataFetcher 2' => 'dataFetcher2',
        ];

        $resolver->setDefaults(['choices' => $choices])->shouldBeCalled();

        $this->configureOptions($resolver);
    }

    function it_has_parent()
    {
        $this->getParent()->shouldReturn(ChoiceType::class);
    }

    function it_has_block_prefix()
    {
        $this->getBlockPrefix()->shouldReturn('odiseo_sylius_report_data_fetcher_choice');
    }
}
