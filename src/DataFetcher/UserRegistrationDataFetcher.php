<?php

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
    /**
     * @var string
     */
    private $orderClass;

    /**
     * @param QueryFilterInterface $queryFilter
     * @param string $orderClass
     */
    public function __construct(
        QueryFilterInterface $queryFilter,
        string $orderClass
    ) {
        parent::__construct($queryFilter);

        $this->orderClass = $orderClass;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $this->orderClass;
        $qb
            ->select('DATE(u.createdAt) as date', 'count(u.id) as users_quantity')
            ->from($from, 'u')
        ;

        $this->queryFilter->addTimePeriod($configuration, 'createdAt');
        $this->queryFilter->addChannel($configuration);
        $this->queryFilter->addUserGender($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return UserRegistrationType::class;
    }
}
