<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Symfony\Component\Form\FormBuilderInterface;

class UserRegistrationType extends TimePeriodChannelType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->queryFilterFormBuilder->addUserGender($builder);
    }

    public function getBlockPrefix(): string
    {
        return 'odiseo_sylius_report_data_fetcher_user_registration';
    }
}
