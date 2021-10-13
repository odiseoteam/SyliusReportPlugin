<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Repository;

trait AddressRepositoryTrait
{
    abstract public function createQueryBuilder($alias, $indexBy = null);

    public function findByCityName(string $cityName): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.city LIKE :city')
            ->setParameter('city', '%' . $cityName . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByProvinceName(string $provinceName): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.province', 'province', 'WITH', 'province.code = o.provinceCode')
            ->where('o.provinceName LIKE :province')
            ->orWhere('province.name LIKE :province')
            ->setParameter('province', '%' . $provinceName . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPostcode(string $postcode): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.postcode LIKE :postcode')
            ->setParameter('postcode', '%' . $postcode . '%')
            ->getQuery()
            ->getResult()
        ;
    }
}
