<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataFetcherChoiceType extends AbstractType
{
    public function __construct(
        protected array $dataFetchers,
    ) {
        $this->dataFetchers = array_combine(array_values($dataFetchers), array_keys($dataFetchers));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => $this->dataFetchers,
            ])
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_sylius_report_data_fetcher_choice';
    }
}
