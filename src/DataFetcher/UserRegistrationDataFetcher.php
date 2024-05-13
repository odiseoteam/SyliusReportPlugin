<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\DataFetcher\Data as DataHelper;
use Odiseo\SyliusReportPlugin\Filter\QueryFilterInterface;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\UserRegistrationType;

class UserRegistrationDataFetcher extends TimePeriodDataFetcher
{
    public function __construct(
        private string $userClass,
        QueryFilterInterface $queryFilter,
        private DataHelper $reportHelper,
    ) {
        parent::__construct($queryFilter);
    }

    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $this->userClass;
        $qb
            ->select('DATE_FORMAT(u.createdAt, :format) as date', 'count(u.id) as users_quantity')
            ->from($from, 'u')
            ->groupBy('date')
        ;
        $qb->setParameter('format', $this->reportHelper->getFormatByGroupBy($configuration['groupBy'] ?? []));

        $this->queryFilter->addTimePeriod($configuration, 'createdAt');
        $this->queryFilter->addChannel($configuration);
        $this->queryFilter->addUserGender($configuration);
    }

    public function getType(): string
    {
        return UserRegistrationType::class;
    }
}
