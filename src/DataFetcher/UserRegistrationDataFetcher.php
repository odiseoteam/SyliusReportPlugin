<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManager;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\UserRegistrationType;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class UserRegistrationDataFetcher extends TimePeriod
{
    /**
     * @var string
     */
    protected $shopUserClass;

    /**
     * @param EntityManager $entityManager
     * @param string $shopUserClass
     */
    public function __construct(EntityManager $entityManager, string $shopUserClass)
    {
        parent::__construct($entityManager);

        $this->shopUserClass = $shopUserClass;
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(array $configuration = [])
    {
        $groupBy = $this->getGroupBy($configuration);

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();
        $tableName = $this->entityManager->getClassMetadata($this->shopUserClass)->getTableName();

        $queryBuilder
            ->select('DATE(u.created_at) as date', ' count(u.id) as user_total')
            ->from($tableName, 'u')
            ->where($queryBuilder->expr()->gte('u.created_at', ':from'))
            ->andWhere($queryBuilder->expr()->lte('u.created_at', ':to'))
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
        return UserRegistrationType::class;
    }
}
