<?php

namespace Odiseo\SyliusReportPlugin\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Exception;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
interface QueryFilterInterface
{
    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder();

    /**
     * @return EntityManager
     */
    public function getEntityManager();

    public function reset();

    /**
     * @param string $join
     * @param string $alias
     *
     * @return string
     */
    public function addLeftJoin($join, $alias): string;

    /**
     * @param array $configuration
     * @param string $dateField
     * @param string $rootAlias
     * @throws Exception
     */
    public function addTimePeriod(array $configuration = [], $dateField = 'checkoutCompletedAt', $rootAlias = null): void;

    /**
     * @param array $configuration
     * @param string $field
     * @param string $rootAlias
     */
    public function addChannel(array $configuration = [], $field = null, $rootAlias = null): void;

    /**
     * @param array $configuration
     * @param string $rootAlias
     */
    public function addUserGender(array $configuration = [], $rootAlias = null): void;

    /**
     * @param array $configuration
     * @param string $addressType
     * @param string $rootAlias
     */
    public function addUserCountry(array $configuration = [], string $addressType = 'shipping', $rootAlias = null): void;

    /**
     * @param array $configuration
     * @param string $addressType
     * @param string $rootAlias
     */
    public function addUserProvince(array $configuration = [], string $addressType = 'shipping', $rootAlias = null): void;

    /**
     * @param array $configuration
     * @param string $addressType
     * @param string $rootAlias
     */
    public function addUserCity(array $configuration = [], string $addressType = 'shipping', $rootAlias = null): void;

    /**
     * @param array $configuration
     * @param string $addressType
     * @param string $rootAlias
     */
    public function addUserPostcode(array $configuration = [], string $addressType = 'shipping', $rootAlias = null): void;

    /**
     * @param array $configuration
     * @param string $field
     */
    public function addProduct(array $configuration = [], string $field = 'p.id'): void;

    /**
     * @param array $configuration
     * @param string $field
     */
    public function addProductCategory(array $configuration = [], string $field = 'pt.taxon'): void;
}
