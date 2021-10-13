<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Exception;
use Odiseo\SyliusReportPlugin\Filter\QueryFilterInterface;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\UserRegistrationType;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class UserRegistrationDataFetcher extends TimePeriodDataFetcher
{
    private string $userClass;

    public function __construct(
        QueryFilterInterface $queryFilter,
        string $userClass
    ) {
        parent::__construct($queryFilter);

        $this->userClass = $userClass;
    }

    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $this->userClass;
        $qb
            ->select('DATE(u.createdAt) as date', 'count(u.id) as users_quantity')
            ->from($from, 'u')
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
