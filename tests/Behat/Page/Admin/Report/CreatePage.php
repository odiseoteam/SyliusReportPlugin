<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Behat\Page\Admin\Report;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\Odiseo\SyliusReportPlugin\Behat\Behaviour\ContainsErrorTrait;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    /**
     * @inheritdoc
     */
    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    /**
     * @inheritdoc
     */
    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    /**
     * @inheritdoc
     */
    public function fillDescription(string $description): void
    {
        $this->getDocument()->fillField('Description', $description);
    }

    /**
     * {@inheritdoc}
     */
    public function selectDataFetcher(string $dataFetcher): void
    {
        $this->getElement('data_fetcher')->selectOption($dataFetcher);
    }

    /**
     * {@inheritdoc}
     */
    public function selectStartDate(\DateTime $startDate): void
    {
        $this->getElement('day_start_date')->selectOption($startDate->format('j'));
        $this->getElement('month_start_date')->selectOption($startDate->format('M'));
        $this->getElement('year_start_date')->selectOption($startDate->format('Y'));
    }

    /**
     * {@inheritdoc}
     */
    public function selectEndDate(\DateTime $endDate): void
    {
        $this->getElement('day_end_date')->selectOption($endDate->format('j'));
        $this->getElement('month_end_date')->selectOption($endDate->format('M'));
        $this->getElement('year_end_date')->selectOption($endDate->format('Y'));
    }

    /**
     * {@inheritdoc}
     */
    public function selectTimePeriod(string $timePeriod): void
    {
        $this->getElement('time_period')->selectOption($timePeriod);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'data_fetcher' => '#odiseo_sylius_report_dataFetcher',
            'day_start_date' => '#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_start_day',
            'month_start_date' => '#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_start_month',
            'year_start_date' => '#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_start_year',
            'day_end_date' => '#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_end_day',
            'month_end_date' => '#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_end_month',
            'year_end_date' => '#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_end_year',
            'time_period' => '#odiseo_sylius_report_dataFetcherConfiguration_timePeriod_period'
        ]);
    }
}
