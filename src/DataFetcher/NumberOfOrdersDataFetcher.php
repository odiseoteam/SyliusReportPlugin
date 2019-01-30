<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManager;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\NumberOfOrdersType;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class NumberOfOrdersDataFetcher extends TimePeriod
{
    /** @var string */
    protected $orderClass;

    /**
     * @param EntityManager $entityManager
     * @param string $orderClass
     */
    public function __construct(EntityManager $entityManager, string $orderClass)
    {
        parent::__construct($entityManager);

        $this->orderClass = $orderClass;
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(array $configuration = [])
    {
        $groupBy = $this->getGroupBy($configuration);

        $queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('DATE(o.checkout_completed_at) as date', 'COUNT(o.id) as "Number of orders"')
            ->from($this->entityManager->getClassMetadata($this->orderClass)->getTableName(), 'o')
            ->where($queryBuilder->expr()->gte('o.checkout_completed_at', ':from'))
            ->andWhere($queryBuilder->expr()->lte('o.checkout_completed_at', ':to'))
            ->setParameter('from', $configuration['start']->format('Y-m-d H:i:s'))
            ->setParameter('to', $configuration['end']->format('Y-m-d H:i:s'))
            ->groupBy($groupBy)
            ->orderBy($groupBy)
        ;

        /** @var Statement $stmt */
        $stmt = $queryBuilder->execute();

        return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return NumberOfOrdersType::class;
    }
}
