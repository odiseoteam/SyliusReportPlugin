<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use Odiseo\SyliusReportPlugin\Renderer\ChartRenderer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ChartConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'odiseo_sylius_report_plugin.form.renderer.chart.type',
                'choices' => ChartRenderer::getChartTypes(),
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'odiseo_sylius_report_plugin.form.renderer.template',
                'choices' => [
                    'Default' => '@OdiseoSyliusReportPlugin/Chart/default.html.twig',
                ],
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_sylius_report_renderer_chart';
    }
}
