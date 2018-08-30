<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Doctrine\DBAL\Statement;
use Odiseo\SyliusReportPlugin\DataFetcher\TimePeriod;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\SalesTotalType;
use Sylius\Component\Order\Model\OrderInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class SalesTotalDataFetcher extends TimePeriod
{
    /**
     * {@inheritdoc}
     */
    protected function getData(array $configuration = [])
    {
        $groupBy = $this->getGroupBy($configuration);

        $queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('DATE(o.checkout_completed_at) as date', 'COUNT(o.id) as "Number of orders"')
            ->from($this->entityManager->getClassMetadata(OrderInterface::class)->getTableName(), 'o')
            ->where($queryBuilder->expr()->gte('o.checkout_completed_at', ':from'))
            ->andWhere($queryBuilder->expr()->lte('o.checkout_completed_at', ':to'))
            ->setParameter('from', $configuration['start']->format('Y-m-d H:i:s'))
            ->setParameter('to', $configuration['end']->format('Y-m-d H:i:s'))
            ->groupBy($groupBy)
            ->orderBy($groupBy)
        ;

        $baseCurrencyCode = $configuration['baseCurrency'] ? 'in '.$configuration['baseCurrency'] : '';
        $queryBuilder
            ->select('DATE(o.checkout_completed_at) as date', 'TRUNCATE(SUM(o.total)/ 100,2) as "total sum '.$baseCurrencyCode.'"')
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
        return SalesTotalType::class;
    }
}
