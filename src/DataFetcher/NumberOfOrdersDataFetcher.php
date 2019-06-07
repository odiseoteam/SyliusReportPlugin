<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\NumberOfOrdersType;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Order\Model\Order;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class NumberOfOrdersDataFetcher extends TimePeriodDataFetcher
{
    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $qb
            ->select('DATE(payment.updatedAt) as date', 'COUNT(o.id) as NumberOfOrders')
            ->from(Order::class, 'o')
        ;
        $this->queryFilter->addLeftJoin('o.items', 'oi');
        $this->queryFilter->addLeftJoin('oi.variant', 'v');
        $this->queryFilter->addLeftJoin('v.product', 'p');
        $this->queryFilter->addLeftJoin('p.productTaxons', 'pt');
        $this->queryFilter->addLeftJoin('o.customer', 'c');
        $this->queryFilter->addLeftJoin('c.user', 'user');
        $this->queryFilter->addLeftJoin('o.payments', 'payment');

        $qb
            ->where($qb->expr()->eq('o.payment_state', OrderPaymentStates::STATE_PAID))
        ;

        $this->queryFilter->addTimePeriod($configuration);
        $this->queryFilter->addChannel($configuration, 'o.channel');
        $this->queryFilter->addUserGender($configuration);
        $this->queryFilter->addUserCountry($configuration, 'shipping');
        $this->queryFilter->addUserCity($configuration, 'shipping');
        $this->queryFilter->addUserProvince($configuration, 'shipping');
        $this->queryFilter->addUserPostcode($configuration, 'shipping');
        $this->queryFilter->addUserCountry($configuration, 'billing');
        $this->queryFilter->addUserCity($configuration, 'billing');
        $this->queryFilter->addUserProvince($configuration, 'billing');
        $this->queryFilter->addUserPostcode($configuration, 'billing');
        $this->queryFilter->addProduct($configuration);
        $this->queryFilter->addProductCategory($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return NumberOfOrdersType::class;
    }
}
