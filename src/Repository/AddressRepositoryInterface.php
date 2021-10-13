<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Repository;

use Sylius\Component\Core\Repository\AddressRepositoryInterface as BaseAddressRepositoryInterface;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
interface AddressRepositoryInterface extends BaseAddressRepositoryInterface
{
    public function findByCityName(string $cityName): array;

    public function findByProvinceName(string $provinceName): array;

    public function findByPostcode(string $postcode): array;
}
