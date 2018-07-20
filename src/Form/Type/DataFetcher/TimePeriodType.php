<?php

namespace Odiseo\SyliusReportPlugin\Form\Type\DataFetcher;

use Odiseo\SyliusReportPlugin\DataFetcher\TimePeriod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class TimePeriodType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', DateType::class, [
                'label' => 'odiseo_sylius_report.form.report.user_registration.start',
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->add('end', DateType::class, [
                'label' => 'odiseo_sylius_report.form.report.user_registration.end',
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->add('period', ChoiceType::class, [
                'choices' => TimePeriod::getPeriodChoices(),
                'multiple' => false,
                'label' => 'odiseo_sylius_report.form.report.user_registration.period',
            ])
            ->add('empty_records', CheckboxType::class, [
                'label' => 'odiseo_sylius_report.form.report.user_registration.empty_records',
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'odiseo_sylius_report_data_fetcher_time_period';
    }
}
