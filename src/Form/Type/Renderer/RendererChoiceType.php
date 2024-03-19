<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type\Renderer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendererChoiceType extends AbstractType
{
    protected array $renderers;

    public function __construct(array $renderers)
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->renderers = array_combine(array_values($renderers), array_keys($renderers));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => $this->renderers,
            ])
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_sylius_report_renderer_choice';
    }
}
