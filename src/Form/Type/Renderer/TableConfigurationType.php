<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Renderer configuration form type.
 */
class TableConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('template', ChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.renderer.template',
                'choices' => [
                    'Default' => 'OdiseoSyliusReportPlugin:Table:default.html.twig',
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report_renderer_table';
    }
}
