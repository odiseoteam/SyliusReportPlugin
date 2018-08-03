<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Renderer choice type.
 *
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class RendererChoiceType extends AbstractType
{
    /**
     * @var array
     */
    protected $renderers;

    public function __construct(array $renderers)
    {
        $this->renderers = array_combine(array_values($renderers), array_keys($renderers));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choices' => $this->renderers,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report_renderer_choice';
    }
}
