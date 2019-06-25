<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
final class ProductAutocompleteChoiceType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'remote_url',
            ])
            ->setDefaults([
                'resource' => 'sylius.product',
                'choice_value' => 'id',
            ])
        ;
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['remote_criteria_type'] = 'contains';
        $view->vars['remote_criteria_name'] = $options['choice_name'];
        $view->vars['remote_url'] = $view->vars['load_edit_url'] = $options['remote_url'];
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix(): string
    {
        return 'odiseo_product_autocomplete_choice';
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return ResourceAutocompleteChoiceType::class;
    }
}
