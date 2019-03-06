<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\AddressRepository as BaseAddressRepository;
use Sylius\Component\Addressing\Model\Province;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class AddressRepository extends BaseAddressRepository implements AddressRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function findByCityName(string $cityName): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.city LIKE :city')
            ->setParameter('city', '%' . $cityName . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @inheritdoc
     */
    public function findByProvinceName(string $provinceName): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin(Province::class, 'p', 'WITH', 'p.code = o.provinceCode')
            ->where('o.provinceName LIKE :province')
            ->orWhere('p.name LIKE :province')
            ->setParameter('province', '%' . $provinceName . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @inheritdoc
     */
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
