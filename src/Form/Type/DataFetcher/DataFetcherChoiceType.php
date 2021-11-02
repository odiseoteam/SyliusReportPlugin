<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class DataFetcherChoiceType extends AbstractType
{
    protected array $dataFetchers;

    public function __construct(array $dataFetchers)
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->dataFetchers = array_combine(
            array_values($dataFetchers),
            array_keys($dataFetchers)
        ) !== false ?
            array_combine(array_values($dataFetchers), array_keys($dataFetchers)) : []
        ;
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
