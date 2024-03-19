<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Filter\QueryFilterInterface;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\UserRegistrationType;

class UserRegistrationDataFetcher extends TimePeriodDataFetcher
{
    public function __construct(
        private string $userClass,
        QueryFilterInterface $queryFilter,
    ) {
        parent::__construct($queryFilter);
    }

    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $this->userClass;
        $qb
            ->select('DATE(u.createdAt) as date', 'count(u.id) as users_quantity')
            ->from($from, 'u')
            ->groupBy('date')
        ;

        $this->queryFilter->addTimePeriod($configuration, 'createdAt');
        $this->queryFilter->addChannel($configuration);
        $this->queryFilter->addUserGender($configuration);
    }

    public function getType(): string
    {
        return UserRegistrationType::class;
    }
}
