<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use Odiseo\SyliusReportPlugin\Renderer\ChartRenderer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Renderer configuration form type.
 */
class ChartConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'sylius.form.report.chart.type',
                'choices' => ChartRenderer::getChartTypes(),
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'sylius.form.report.renderer.template',
                'choices' => [
                    'Default' => 'SyliusReportBundle:Chart:default.html.twig',
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report_renderer_chart';
    }
}
