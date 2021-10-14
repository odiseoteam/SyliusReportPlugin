<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type;

use InvalidArgumentException;
use Odiseo\SyliusReportPlugin\DataFetcher\DataFetcherInterface;
use Odiseo\SyliusReportPlugin\Form\EventListener\BuildReportDataFetcherFormSubscriber;
use Odiseo\SyliusReportPlugin\Form\EventListener\BuildReportRendererFormSubscriber;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\DataFetcherChoiceType;
use Odiseo\SyliusReportPlugin\Form\Type\Renderer\RendererChoiceType;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class ReportType extends AbstractResourceType
{
    protected ServiceRegistryInterface $rendererRegistry;
    protected ServiceRegistryInterface $dataFetcherRegistry;
    protected string $rendererConfigurationTemplate;
    protected string $dataFetcherConfigurationTemplate;

    public function __construct(
        string $dataClass,
        array $validationGroups,
        ServiceRegistryInterface $rendererRegistry,
        ServiceRegistryInterface $dataFetcherRegistry,
        string $rendererConfigurationTemplate,
        string $dataFetcherConfigurationTemplate
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->rendererRegistry = $rendererRegistry;
        $this->dataFetcherRegistry = $dataFetcherRegistry;
        $this->rendererConfigurationTemplate = $rendererConfigurationTemplate;
        $this->dataFetcherConfigurationTemplate = $dataFetcherConfigurationTemplate;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->addEventSubscriber(
                new BuildReportDataFetcherFormSubscriber($this->dataFetcherRegistry, $builder->getFormFactory())
            )
            ->addEventSubscriber(
                new BuildReportRendererFormSubscriber($this->rendererRegistry, $builder->getFormFactory())
            )
            ->add('name', TextType::class, [
                'label' => 'odiseo_sylius_report_plugin.form.report.name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'odiseo_sylius_report_plugin.form.report.description',
                'required' => false,
            ])
            ->add('dataFetcher', DataFetcherChoiceType::class, [
                'label' => 'odiseo_sylius_report_plugin.form.data_fetcher',
            ])
            ->add('renderer', RendererChoiceType::class, [
                'label' => 'odiseo_sylius_report_plugin.form.renderer.label',
            ])
        ;

        $prototypes = [
            'renderers' => [],
            'dataFetchers' => [],
        ];

        /** @var RendererInterface $renderer */
        foreach ($this->rendererRegistry->all() as $type => $renderer) {
            $formType = $renderer->getType();

            try {
                $prototypes['renderers'][$type] = $builder->create('rendererConfiguration', $formType)->getForm();
            } catch (InvalidArgumentException $e) {
                continue;
            }
        }

        /** @var DataFetcherInterface $dataFetcher */
        foreach ($this->dataFetcherRegistry->all() as $type => $dataFetcher) {
            $formType = $dataFetcher->getType();

            $prototypes['dataFetchers'][$type] = $builder->create('dataFetcherConfiguration', $formType)->getForm();
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['prototypes'] = [];
        $view->vars['rendererConfigurationTemplate'] = $this->rendererConfigurationTemplate;
        $view->vars['dataFetcherConfigurationTemplate'] = $this->dataFetcherConfigurationTemplate;

        /**
         * @var string $group
         * @var FormView $prototypes
         */
        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            /** @var FormInterface $prototype */
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototypes'][$group][$group . '_' . $type] = $prototype->createView($view);
            }
        }
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_sylius_report';
    }
}
