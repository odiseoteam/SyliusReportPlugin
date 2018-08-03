<?php

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
    /**
     * @var array
     */
    protected $dataFetchers;

    /**
     * @param array $dataFetchers
     */
    public function __construct($dataFetchers)
    {
        $this->dataFetchers = array_combine(array_values($dataFetchers), array_keys($dataFetchers));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choices' => $this->dataFetchers,
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
        return 'odiseo_sylius_report_data_fetcher_choice';
    }
}
