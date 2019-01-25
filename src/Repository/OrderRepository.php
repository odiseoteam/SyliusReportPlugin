<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository as BaseOrderRepository;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class OrderRepository extends BaseOrderRepository implements OrderRepositoryInterface
{
    public function findByNumberPart(string $number): array
    {
        return $this->createQueryBuilder('o')
            ->select('o.id, o.number')
            ->andWhere('o.number LIKE :number')
            ->setParameter('number', '%' . $number . '%')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
