<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Repository;

use Sylius\Component\Core\Repository\OrderRepositoryInterface as BaseOrderRepositoryInterface;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
interface OrderRepositoryInterface extends BaseOrderRepositoryInterface
{
    public function findByNumberPart(string $number): array;
}
