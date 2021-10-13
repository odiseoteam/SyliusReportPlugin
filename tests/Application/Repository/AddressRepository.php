<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Application\Repository;

use Odiseo\SyliusReportPlugin\Repository\AddressRepositoryInterface;
use Odiseo\SyliusReportPlugin\Repository\AddressRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\AddressRepository as BaseAddressRepository;

class AddressRepository extends BaseAddressRepository implements AddressRepositoryInterface
{
    use AddressRepositoryTrait;
}
