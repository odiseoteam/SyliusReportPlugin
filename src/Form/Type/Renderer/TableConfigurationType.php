<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Renderer configuration form type.
 *
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class TableConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('template', ChoiceType::class, [
                'label' => 'odiseo_sylius_report_plugin.form.renderer.template',
                'choices' => [
                    'Default' => '@OdiseoSyliusReportPlugin/Table/default.html.twig',
                ],
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_sylius_report_renderer_table';
    }
}
