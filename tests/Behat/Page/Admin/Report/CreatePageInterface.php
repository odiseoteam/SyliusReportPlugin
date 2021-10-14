<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Behat\Page\Admin\Report;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;
use Tests\Odiseo\SyliusReportPlugin\Behat\Behaviour\ContainsErrorInterface;

interface CreatePageInterface extends BaseCreatePageInterface, ContainsErrorInterface
{
    /**
     * @param string $code
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillCode(string $code): void;

    /**
     * @param string $name
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillName(string $name): void;

    /**
     * @param string $description
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillDescription(string $description): void;

    /**
     * @param string $dataFetcher
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function selectDataFetcher(string $dataFetcher): void;

    /**
     * @param \DateTime $startDate
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function selectStartDate(\DateTime $startDate): void;

    /**
     * @param \DateTime $endDate
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function selectEndDate(\DateTime $endDate): void;

    /**
     * @param string $timePeriod
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function selectTimePeriod(string $timePeriod): void;
}
