<?php

namespace spec\Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class ChartConfigurationTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Odiseo\SyliusReportPlugin\Form\Type\Renderer\ChartConfigurationType');
    }

    function it_should_be_abstract_type_object()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_has_block_prefix()
    {
        $this->getBlockPrefix()->shouldReturn('odiseo_sylius_report_renderer_chart');
    }

    function it_builds_form_with_type_choice_and_template_choice(FormBuilder $builder)
    {
        $builder->add('type', ChoiceType::class, Argument::any())->willReturn($builder);
        $builder->add('template', ChoiceType::class, Argument::any())->willReturn($builder);

        $this->buildForm($builder, []);
    }
}
