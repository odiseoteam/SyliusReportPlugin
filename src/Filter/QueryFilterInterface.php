<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

interface QueryFilterInterface
{
    public function getQueryBuilder(): QueryBuilder;

    public function getEntityManager(): EntityManager;

    public function reset(): void;

    public function addLeftJoin(string $join, string $alias): string;

    public function addTimePeriod(
        array $configuration = [],
        string $dateField = 'checkoutCompletedAt',
        ?string $rootAlias = null
    ): void;

    public function addChannel(
        array $configuration = [],
        ?string $field = null,
        ?string $rootAlias = null
    ): void;

    public function addUserGender(
        array $configuration = [],
        ?string $rootAlias = null
    ): void;

    public function addUserCountry(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null
    ): void;

    public function addUserProvince(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null
    ): void;

    public function addUserCity(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null
    ): void;

    public function addUserPostcode(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null
    ): void;

    public function addProduct(
        array $configuration = [],
        string $field = 'p.id'
    ): void;

    public function addProductCategory(
        array $configuration = [],
        string $field = 'pt.taxon'
    ): void;
}
