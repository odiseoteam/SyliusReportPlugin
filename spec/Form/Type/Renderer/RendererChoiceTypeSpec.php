<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RendererChoiceTypeSpec extends ObjectBehavior
{
    function let()
    {
        $choices = [
            'table' => 'Table renderer',
            'chart' => 'Chart renderer',
        ];

        $this->beConstructedWith($choices);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Odiseo\SyliusReportPlugin\Form\Type\Renderer\RendererChoiceType');
    }

    function it_should_be_abstract_type_object()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_sets_default_options(OptionsResolver $resolver)
    {
        $choices = [
            'Table renderer' => 'table',
            'Chart renderer' =>'chart',
        ];

        $resolver->setDefaults(['choices' => $choices])->willReturn($resolver);

        $this->configureOptions($resolver);
    }

    function it_has_parent()
    {
        $this->getParent()->shouldReturn(ChoiceType::class);
    }

    function it_has_block_prefix()
    {
        $this->getBlockPrefix()->shouldReturn('odiseo_sylius_report_renderer_choice');
    }
}
