<?php

namespace spec\Odiseo\SyliusReportPlugin\Form\Type;

use Odiseo\SyliusReportPlugin\DataFetcher\DataFetcherInterface;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\DataFetcherChoiceType;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\RendererChoiceType;
use Odiseo\SyliusReportPlugin\Model\Report;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Odiseo\SyliusReportPlugin\Form\EventListener\BuildReportDataFetcherFormSubscriber;
use Odiseo\SyliusReportPlugin\Form\EventListener\BuildReportRendererFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class ReportTypeSpec extends ObjectBehavior
{
    function let(
        ServiceRegistryInterface $rendererRegistry,
        ServiceRegistryInterface $dataFetcherRegistry
    ) {
        $this->beConstructedWith(
            Report::class,
            ['sylius'],
            $rendererRegistry,
            $dataFetcherRegistry,
            '@OdiseoSyliusReportPlugin/_rendererConfiguration.html.twig',
            '@OdiseoSyliusReportPlugin/_dataFetcherConfiguration.html.twig'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Odiseo\SyliusReportPlugin\Form\Type\ReportType');
    }

    function it_should_be_abstract_resource_type_object()
    {
        $this->shouldHaveType(AbstractResourceType::class);
    }

    function it_build_form_with_proper_fields(
        FormBuilderInterface $builder,
        FormFactoryInterface $factory,
        $dataFetcherRegistry,
        $rendererRegistry,
        RendererInterface $renderer,
        DataFetcherInterface $dataFetcher
    ) {
        $builder->getFormFactory()->willReturn($factory);

        $builder->add('name', TextType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('description', TextareaType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('renderer', RendererChoiceType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('dataFetcher', DataFetcherChoiceType::class, Argument::any())->shouldBeCalled()->willReturn($builder);

        $builder->addEventSubscriber(Argument::type(BuildReportRendererFormSubscriber::class))->shouldBeCalled()->willReturn($builder);
        $builder->addEventSubscriber(Argument::type(BuildReportDataFetcherFormSubscriber::class))->shouldBeCalled()->willReturn($builder);

        $builder
            ->addEventSubscriber(Argument::type(AddCodeFormSubscriber::class))
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $renderer->getType()->willReturn('odiseo_sylius_report_renderer_test');
        $rendererRegistry->all()->willReturn(['test_renderer' => $renderer]);
        $builder->create('rendererConfiguration', 'odiseo_sylius_report_renderer_test')->willReturn($builder);
        $builder->getForm()->shouldBeCalled()->willReturn(Argument::type(Form::class));

        $dataFetcher->getType()->willReturn('odiseo_sylius_report_data_fetcher_test');
        $dataFetcherRegistry->all()->willReturn(['test_data_fetcher' => $dataFetcher]);
        $builder->create('dataFetcherConfiguration', 'odiseo_sylius_report_data_fetcher_test')->willReturn($builder);
        $builder->getForm()->shouldBeCalled()->willReturn(Argument::type(Form::class));

        $prototypes = [
            'renderers' => [
                'test_renderer' => Argument::type(Form::class),
                ],
            'dataFetchers' => [
                'test_data_fetcher' => Argument::type(Form::class),
                ],
            ];
        $builder->setAttribute('prototypes', $prototypes)->shouldBeCalled();

        $this->buildForm($builder, []);
    }

    function it_builds_view(
        FormConfigInterface $config,
        FormView $view,
        FormInterface $form,
        FormInterface $formTable,
        FormInterface $formUserRegistration
    ) {
        $prototypes = [
            'dataFetchers' => ['user_registration' => $formUserRegistration],
            'renderers' => ['table' => $formTable],
        ];
        $config->getAttribute('prototypes')->willReturn($prototypes);
        $form->getConfig()->willReturn($config);

        $formTable->createView($view)->shouldBeCalled();
        $formUserRegistration->createView($view)->shouldBeCalled();

        $this->buildView($view, $form, []);
    }

    function it_has_block_prefix()
    {
        $this->getBlockPrefix()->shouldReturn('odiseo_sylius_report');
    }
}
