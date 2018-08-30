<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Statement;
use Odiseo\SyliusReportPlugin\DataFetcher\TimePeriod;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\UserRegistrationType;
use Sylius\Component\User\Model\UserInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class UserRegistrationDataFetcher extends TimePeriod
{
    /**
     * {@inheritdoc}
     */
    protected function getData(array $configuration = [])
    {
        $groupBy = $this->getGroupBy($configuration);

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();
        $tableName = $this->entityManager->getClassMetadata(UserInterface::class)->getTableName();

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
