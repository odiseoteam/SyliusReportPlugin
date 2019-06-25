<?php

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
    /**
     * @var ServiceRegistryInterface
     */
    protected $rendererRegistry;

    /**
     * @var ServiceRegistryInterface
     */
    protected $dataFetcherRegistry;

    /**
     * @param string $dataClass FQCN
     * @param string[] $validationGroups
     * @param ServiceRegistryInterface $rendererRegistry
     * @param ServiceRegistryInterface $dataFetcherRegistry
     */
    public function __construct(
        $dataClass,
        array $validationGroups,
        ServiceRegistryInterface $rendererRegistry,
        ServiceRegistryInterface $dataFetcherRegistry
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->rendererRegistry = $rendererRegistry;
        $this->dataFetcherRegistry = $dataFetcherRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->addEventSubscriber(new BuildReportDataFetcherFormSubscriber($this->dataFetcherRegistry, $builder->getFormFactory()))
            ->addEventSubscriber(new BuildReportRendererFormSubscriber($this->rendererRegistry, $builder->getFormFactory()))
            ->add('name', TextType::class, [
                'label' => 'odiseo_sylius_report.form.report.name',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'odiseo_sylius_report.form.report.description',
                'required' => true,
            ])
            ->add('dataFetcher', DataFetcherChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.data_fetcher',
            ])
            ->add('renderer', RendererChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.renderer.label',
            ])
        ;

        $prototypes = [
            'renderers' => [],
            'dataFetchers' => [],
        ];

        /** @var RendererInterface $renderer */
        foreach ($this->rendererRegistry->all() as $type => $renderer) {
            $formType = $renderer->getType();

            if (!$formType) {
                continue;
            }

            try {
                $prototypes['renderers'][$type] = $builder->create('rendererConfiguration', $formType)->getForm();
            } catch (InvalidArgumentException $e) {
                continue;
            }
        }

        /** @var DataFetcherInterface $dataFetcher */
        foreach ($this->dataFetcherRegistry->all() as $type => $dataFetcher) {
            $formType = $dataFetcher->getType();

            if (!$formType) {
                continue;
            }

            $prototypes['dataFetchers'][$type] = $builder->create('dataFetcherConfiguration', $formType)->getForm();
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['prototypes'] = [];

        foreach ($form->getConfig()->getAttribute('prototypes') as $group => $prototypes) {
            /** @var FormView $prototypes */
            foreach ($prototypes as $type => $prototype) {
                $view->vars['prototypes'][$group][$group.'_'.$type] = $prototype->createView($view);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report';
    }
}
