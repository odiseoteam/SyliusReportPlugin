<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\SalesTotalType;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Order\Model\OrderInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class SalesTotalDataFetcher extends TimePeriodDataFetcher
{
    /**
     * {@inheritdoc}
     */
    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $qb->getEntityManager()->getClassMetadata(OrderInterface::class)->getName();
        $qb
            ->select('DATE(payment.updatedAt) as date', 'ROUND(SUM(o.total/100), 2) as total_'.$configuration['baseCurrency'])
            ->from($from, 'o')
        ;
        $this->queryFilter->addLeftJoin('o.items', 'oi');
        $this->queryFilter->addLeftJoin('oi.variant', 'v');
        $this->queryFilter->addLeftJoin('v.product', 'p');
        $this->queryFilter->addLeftJoin('p.productTaxons', 'pt');
        $this->queryFilter->addLeftJoin('o.customer', 'c');
        $this->queryFilter->addLeftJoin('c.user', 'user');
        $this->queryFilter->addLeftJoin('o.payments', 'payment');

        $qb
            ->andWhere('o.paymentState = :paymentState')
            ->setParameter('paymentState', OrderPaymentStates::STATE_PAID)
        ;

        $this->queryFilter->addTimePeriod($configuration, 'payment.updatedAt');
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
        $this->queryFilter->addProductBrand($configuration);
        $this->queryFilter->addProductCategory($configuration);
        $this->queryFilter->addOrderNumbers($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return SalesTotalType::class;
    }
}
