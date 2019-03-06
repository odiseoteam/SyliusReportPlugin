<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AddressAutocompleteChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'remote_url',
            ])
            ->setDefaults([
                'resource' => 'sylius.address',
                'choice_value' => 'id',
            ])
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['remote_criteria_type'] = 'contains';
        $view->vars['remote_criteria_name'] = $options['choice_name'];
        $view->vars['remote_url'] = $view->vars['load_edit_url'] = $options['remote_url'];
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_address_autocomplete_choice';
    }

    public function getParent(): string
    {
        return ResourceAutocompleteChoiceType::class;
    }
}
