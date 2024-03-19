<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Filter;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\ProductInterface;

class QueryFilter implements QueryFilterInterface
{
    protected EntityManager $em;

    protected QueryBuilder $qb;

    protected array $joins = [];

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;

        $this->qb = $this->em->createQueryBuilder();
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->qb;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    public function reset(): void
    {
        $this->qb = $this->em->createQueryBuilder();
        $this->joins = [];
    }

    protected function getGroupByParts(
        QueryBuilder $qb,
        array $configuration = [],
        string $dateField = 'checkoutCompletedAt',
    ): array {
        if (false === strpos($dateField, '.')) {
            $rootAlias = $qb->getRootAliases()[0];
            $dateF = $rootAlias . '.' . $dateField;
        } else {
            $dateF = $dateField;
        }

        $selectPeriod = '';
        $selectGroupBy = '';
        foreach ($configuration['groupBy'] as $groupByElement) {
            if (strlen($selectPeriod) > 0) {
                $selectPeriod .= ', ';
                $selectGroupBy .= ',';
            }
            $salias = ucfirst(strtolower($groupByElement)) . 'Date';
            $selectPeriod .= $groupByElement . '(' . $dateF . ') as ' . $salias;

            $selectGroupBy .= $salias;
        }

        return [$selectPeriod, $selectGroupBy];
    }

    public function addLeftJoin(string $join, string $alias): string
    {
        if (!isset($this->joins[$join])) {
            $this->joins[$join] = $alias;

            $this->qb->leftJoin($join, $alias);
        }

        return $this->joins[$join];
    }

    public function addTimePeriod(
        array $configuration = [],
        string $dateField = 'checkoutCompletedAt',
        ?string $rootAlias = null,
    ): void {
        if (false === strpos($dateField, '.')) {
            if (null === $rootAlias) {
                $rootAlias = $this->qb->getRootAliases()[0];
            }

            $dateF = $rootAlias . '.' . $dateField;
        } else {
            $dateF = $dateField;
        }

        $groupByParts = $this->getGroupByParts($this->qb, $configuration, $dateField);

        /** @var DateTime $startDateTime */
        $startDateTime = $configuration['timePeriod']['start'];
        /** @var DateTime $endDateTime */
        $endDateTime = $configuration['timePeriod']['end'] !== null ?
            $configuration['timePeriod']['end'] : new DateTime()
        ;

        if ($groupByParts[0] && $groupByParts[1]) {
            $this->qb
                ->addSelect($groupByParts[0])
                ->andWhere($this->qb->expr()->gte($dateF, ':from'))
                ->andWhere($this->qb->expr()->lte($dateF, ':to'))
                ->setParameter('from', $startDateTime->format('Y-m-d H:i:s'))
                ->setParameter('to', $endDateTime->format('Y-m-d H:i:s'))
                ->addGroupBy($groupByParts[1])
                ->orderBy('date,' . $groupByParts[1])
            ;
        } else {
            $this->qb
                ->andWhere($this->qb->expr()->gte($dateF, ':from'))
                ->andWhere($this->qb->expr()->lte($dateF, ':to'))
                ->setParameter('from', $startDateTime->format('Y-m-d H:i:s'))
                ->setParameter('to', $endDateTime->format('Y-m-d H:i:s'))
                ->orderBy('date')
            ;
        }
    }

    public function addChannel(
        array $configuration = [],
        ?string $field = null,
        ?string $rootAlias = null,
    ): void {
        if (isset($configuration['channel']) && count($configuration['channel']) > 0) {
            $storeIds = [];

            if ($configuration['channel'] instanceof ChannelInterface) {
                $storeIds[] = $configuration['channel']->getId();
            } elseif (is_array($configuration['channel']) && !in_array(0, $configuration['channel'], true)) {
                $storeIds = $configuration['channel'];
            }

            if (!(count($storeIds) > 0)) {
                return;
            }

            if (null === $field) {
                if (null === $rootAlias) {
                    $rootAlias = $this->qb->getRootAliases()[0];
                }

                $field = $rootAlias . '.channel';
            }

            $this->qb
                ->andWhere($this->qb->expr()->in($field, $storeIds))
            ;
        }
    }

    public function addUserGender(array $configuration = [], ?string $rootAlias = null): void
    {
        if (isset($configuration['userGender']) && count($configuration['userGender']) > 0) {
            $cAlias = (string) $rootAlias;
            if (null === $rootAlias) {
                $rootAlias = $cAlias = $this->qb->getRootAliases()[0];
            }

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias . '.customer', 'c');
            }

            $this->qb
                ->andWhere($this->qb->expr()->in($cAlias . '.gender', $configuration['userGender']))
            ;
        }
    }

    public function addUserCountry(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null,
    ): void {
        $type = 'user' . ucfirst($addressType) . 'Country';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $cAlias = (string) $rootAlias;
            if (null === $rootAlias) {
                $rootAlias = $cAlias = $this->qb->getRootAliases()[0];
            }

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias . '.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias . '.addresses', 'c' . substr($addressType, 0, 1) . 'a');

            $this->qb
                ->andWhere($this->qb->expr()->in($caAlias . '.countryCode', $configuration[$type]))
            ;
        }
    }

    public function addUserProvince(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null,
    ): void {
        $type = 'user' . ucfirst($addressType) . 'Province';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $provinces = $configuration[$type]->map(function (AddressInterface $address): ?string {
                return $address->getProvinceCode() !== null ? $address->getProvinceCode() : $address->getProvinceName();
            })->toArray();

            $cAlias = (string) $rootAlias;
            if (null === $rootAlias) {
                $rootAlias = $cAlias = $this->qb->getRootAliases()[0];
            }

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias . '.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias . '.addresses', 'c' . substr($addressType, 0, 1) . 'a');

            $this->qb
                ->andWhere($this->qb->expr()->orX(
                    $this->qb->expr()->in($caAlias . '.provinceCode', $provinces),
                    $this->qb->expr()->in($caAlias . '.provinceName', $provinces),
                ))
            ;
        }
    }

    public function addUserCity(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null,
    ): void {
        $type = 'user' . ucfirst($addressType) . 'City';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $cities = $configuration[$type]->map(function (AddressInterface $address): ?string {
                return $address->getCity();
            })->toArray();

            $cAlias = (string) $rootAlias;
            if (null === $rootAlias) {
                $rootAlias = $cAlias = $this->qb->getRootAliases()[0];
            }

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias . '.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias . '.addresses', 'c' . substr($addressType, 0, 1) . 'a');

            $this->qb
                ->andWhere($this->qb->expr()->in($caAlias . '.city', $cities))
            ;
        }
    }

    public function addUserPostcode(
        array $configuration = [],
        string $addressType = 'shipping',
        ?string $rootAlias = null,
    ): void {
        $type = 'user' . ucfirst($addressType) . 'Postcode';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $codes = $configuration[$type]->map(function (AddressInterface $address): ?string {
                return $address->getPostcode();
            })->toArray();

            $cAlias = (string) $rootAlias;
            if (null === $rootAlias) {
                $rootAlias = $cAlias = $this->qb->getRootAliases()[0];
            }

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias . '.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias . '.addresses', 'c' . substr($addressType, 0, 1) . '2a');

            $this->qb
                ->andWhere($this->qb->expr()->in($caAlias . '.postcode', $codes))
            ;
        }
    }

    public function addProduct(array $configuration = [], string $field = 'p.id'): void
    {
        if (isset($configuration['product']) && count($configuration['product']) > 0) {
            $products = $configuration['product']->map(function (ProductInterface $product): array {
                return $product->getId();
            })->toArray();

            $this->qb
                ->andWhere($this->qb->expr()->in($field, $products))
            ;
        }
    }

    public function addProductCategory(array $configuration = [], string $field = 'pt.taxon'): void
    {
        if (isset($configuration['productCategory']) && count($configuration['productCategory']) > 0) {
            $this->qb
                ->andWhere($this->qb->expr()->in($field, $configuration['productCategory']))
            ;
        }
    }

    protected function hasRootEntity(string $rootEntityClassname): bool
    {
        return in_array($rootEntityClassname, $this->qb->getRootEntities(), true);
    }
}
