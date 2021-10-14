<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\Form\EventListener;

use Odiseo\SyliusReportPlugin\Form\EventListener\BuildReportRendererFormSubscriber;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class BuildReportRendererFormSubscriberSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BuildReportRendererFormSubscriber::class);
    }

    function it_implements_data_fetcher_interface()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function let(ServiceRegistryInterface $rendererRegistry, FormFactoryInterface $factory, RendererInterface $renderer)
    {
        $rendererRegistry->get('test_renderer')->willReturn($renderer);
        $renderer->getType()->willReturn('odiseo_sylius_report_renderer_test_type');

        $this->beConstructedWith($rendererRegistry, $factory);
    }

    function it_adds_configuration_fields_in_pre_set_data(
        $factory,
        ReportInterface $report,
        FormEvent $event,
        Form $form,
        Form $field)
    {
        $report->getRenderer()->willReturn('test_renderer');
        $report->getRendererConfiguration()->willReturn([]);

        $event->getData()->willReturn($report);
        $event->getForm()->willReturn($form);

        $factory->createNamed(
            'rendererConfiguration',
            'odiseo_sylius_report_renderer_test_type',
            Argument::cetera()
        )->willReturn($field);

        $form->add($field)->shouldBeCalled();

        $this->preSetData($event);
    }

    function it_adds_configuration_fields_in_pre_bind(
        $factory,
        FormEvent $event,
        Form $form,
        Form $field)
    {
        $data = ['renderer' => 'test_renderer'];

        $event->getData()->willReturn($data);
        $event->getForm()->willReturn($form);

        $factory->createNamed(
            'rendererConfiguration',
            'odiseo_sylius_report_renderer_test_type',
            Argument::cetera()
        )->willReturn($field);

        $form->add($field)->shouldBeCalled();

        $this->preBind($event);
    }

    function it_does_not_allow_to_confidure_fields_in_pre_set_data_for_other_class_then_report(FormEvent $event)
    {
        $report = '';
        $event->getData()->willReturn($report);
        $this->shouldThrow(new UnexpectedTypeException($report, ReportInterface::class))
            ->duringPreSetData($event);
    }
}
