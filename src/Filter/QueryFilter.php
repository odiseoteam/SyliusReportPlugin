<?php

namespace Odiseo\SyliusReportPlugin\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Customer;

class QueryFilter
{
    /** @var EntityManager */
    protected $em;

    /** @var QueryBuilder */
    protected $qb;

    /** @var array */
    protected $joins = [];

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;

        $this->qb = $this->em->createQueryBuilder();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function reset()
    {
        $this->qb = $this->em->createQueryBuilder();
        $this->joins = [];
    }

    /**
     * @param QueryBuilder $qb
     * @param array $configuration
     * @param string $dateField
     *
     * @return array
     */
    protected function getGroupByParts(QueryBuilder $qb, array $configuration = [], $dateField = 'checkoutCompletedAt')
    {
        if (false === strpos($dateField, '.')) {
            $rootAlias = $qb->getRootAliases()[0];
            $dateF = $rootAlias.'.'.$dateField;
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
            $salias = ucfirst(strtolower($groupByElement)).'Date';
            $selectPeriod .= $groupByElement.'('.$dateF.') as '.$salias;

            $selectGroupBy .= $salias;
        }

        return [$selectPeriod, $selectGroupBy];
    }

    /**
     * @param $join
     * @param $alias
     *
     * @return string
     */
    public function addLeftJoin($join, $alias): string
    {
        if (!isset($this->joins[$join])) {
            $this->joins[$join] = $alias;

            $this->qb->leftJoin($join, $alias);
        }

        return $this->joins[$join];
    }

    /**
     * @param array $configuration
     * @param string $dateField
     */
    public function addTimePeriod(array $configuration = [], $dateField = 'checkoutCompletedAt'): void
    {
        if (false === strpos($dateField, '.')) {
            $rootAlias = $this->qb->getRootAliases()[0];
            $dateF = $rootAlias.'.'.$dateField;
        } else {
            $dateF = $dateField;
        }

        $groupByParts = $this->getGroupByParts($this->qb, $configuration, $dateField);

        /** @var \DateTime $startDateTime */
        $startDateTime = $configuration['timePeriod']['start'];
        /** @var \DateTime $endDateTime */
        $endDateTime = $configuration['timePeriod']['end']?:new \DateTime();

        $this->qb
            ->addSelect($groupByParts[0])
            ->andWhere($this->qb->expr()->gte($dateF, ':from'))
            ->andWhere($this->qb->expr()->lte($dateF, ':to'))
            ->setParameter('from', $startDateTime->format('Y-m-d H:i:s'))
            ->setParameter('to', $endDateTime->format('Y-m-d H:i:s'))
            ->groupBy($groupByParts[1])
            ->orderBy('date,'.$groupByParts[1])
        ;
    }

    /**
     * @param array $configuration
     * @param null $field
     */
    public function addChannel(array $configuration = [], $field = null): void
    {
        if (isset($configuration['channel']) && count($configuration['channel']) > 0) {
            $storeIds = [];

            if ($configuration['channel'] instanceof ChannelInterface) {
                $storeIds[] = $configuration['channel']->getId();
            } elseif (is_array($configuration['channel']) && !in_array(0, $configuration['channel'])) {
                $storeIds = $configuration['channel'];
            }

            if (!(count($storeIds) > 0)) {
                return;
            }

            if (!$field) {
                $alias = $this->qb->getRootAliases()[0];
                $field = $alias.'.channel';
            }

            $this->qb
                ->andWhere($this->qb->expr()->in($field, $storeIds))
            ;
        }
    }

    /**
     * @param array $configuration
     */
    public function addUserGender(array $configuration = []): void
    {
        if (isset($configuration['userGender']) && count($configuration['userGender']) > 0) {
            $rootAlias = $cAlias = $this->qb->getRootAliases()[0];

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias.'.customer', 'c');
            }

            $this->qb
                ->andWhere($this->qb->expr()->in($cAlias.'.gender', $configuration['userGender']))
            ;
        }
    }

    /**
     * @param array $configuration
     * @param string $addressType
     */
    public function addUserCountry(array $configuration = [], string $addressType = 'shipping'): void
    {
        $type = 'user'.ucfirst($addressType).'Country';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $rootAlias = $cAlias = $this->qb->getRootAliases()[0];

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias.'.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias.'.addresses', 'c'.substr($addressType, 0, 1).'a');

            $this->qb
                ->andWhere($this->qb->expr()->in($caAlias.'.countryCode', $configuration[$type]))
            ;
        }
    }

    /**
     * @param array $configuration
     * @param string $addressType
     */
    public function addUserProvince(array $configuration = [], string $addressType = 'shipping'): void
    {
        $type = 'user'.ucfirst($addressType).'Province';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $provinces = $configuration[$type]->map(function (AddressInterface $address) {
                return $address->getProvinceCode() ?: $address->getProvinceName();
            })->toArray();
            $rootAlias = $cAlias = $this->qb->getRootAliases()[0];

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias.'.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias.'.addresses', 'c'.substr($addressType, 0, 1).'a');

            $this->qb
                ->andWhere($this->qb->expr()->orX(
                    $this->qb->expr()->in($caAlias.'.provinceCode', $provinces),
                    $this->qb->expr()->in($caAlias.'.provinceName', $provinces)
                ))
            ;
        }
    }

    /**
     * @param array $configuration
     * @param string $addressType
     */
    public function addUserCity(array $configuration = [], string $addressType = 'shipping'): void
    {
        $type = 'user'.ucfirst($addressType).'City';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $cities = $configuration[$type]->map(function (AddressInterface $address) {
                return $address->getCity();
            })->toArray();
            $rootAlias = $cAlias = $this->qb->getRootAliases()[0];

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias.'.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias.'.addresses', 'c'.substr($addressType, 0, 1).'a');

            $this->qb
                ->andWhere($this->qb->expr()->in($caAlias.'.city', $cities))
            ;
        }
    }

    /**
     * @param array $configuration
     * @param string $addressType
     */
    public function addUserPostcode(array $configuration = [], string $addressType = 'shipping'): void
    {
        $type = 'user'.ucfirst($addressType).'Postcode';

        if (isset($configuration[$type]) && count($configuration[$type]) > 0) {
            $codes = $configuration[$type]->map(function (AddressInterface $address) {
                return $address->getPostcode();
            })->toArray();
            $rootAlias = $cAlias = $this->qb->getRootAliases()[0];

            if (!$this->hasRootEntity(Customer::class)) {
                $cAlias = $this->addLeftJoin($rootAlias.'.customer', 'c');
            }

            $caAlias = $this->addLeftJoin($cAlias.'.addresses', 'c'.substr($addressType, 0, 1).'2a');

            $this->qb
                ->andWhere($this->qb->expr()->in($caAlias.'.postcode', $codes))
            ;
        }
    }

    /**
     * @param array $configuration
     * @param string $field
     */
    public function addProduct(array $configuration = [], string $field = 'p.id'): void
    {
        if (isset($configuration['product']) && count($configuration['product']) > 0) {
            $this->qb
                ->andWhere($this->qb->expr()->in($field, $configuration['product']))
            ;
        }
    }

    /**
     * @param array $configuration
     * @param string $field
     */
    public function addProductCategory(array $configuration = [], string $field = 'pt.id'): void
    {
        if (isset($configuration['productCategory']) && count($configuration['productCategory']) > 0) {
            $this->qb
                ->andWhere($this->qb->expr()->in($field, $configuration['productCategory']))
            ;
        }
    }

    /**
     * @param $rootEntityClassname
     *
     * @return bool
     */
    protected function hasRootEntity($rootEntityClassname): bool
    {
        return in_array($rootEntityClassname, $this->qb->getRootEntities());
    }
}
