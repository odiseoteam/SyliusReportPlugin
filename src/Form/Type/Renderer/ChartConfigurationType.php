<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use Odiseo\SyliusReportPlugin\Renderer\ChartRenderer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Renderer configuration form type.
 *
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
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
                'label' => 'odiseo_sylius_report.form.renderer.chart.type',
                'choices' => ChartRenderer::getChartTypes(),
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.renderer.template',
                'choices' => [
                    'Default' => '@OdiseoSyliusReportPlugin/Chart/default.html.twig',
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
