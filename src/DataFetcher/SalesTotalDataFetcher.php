<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Exception;
use Odiseo\SyliusReportPlugin\Filter\QueryFilterInterface;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\SalesTotalType;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\OrderPaymentStates;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class SalesTotalDataFetcher extends TimePeriodDataFetcher
{
    /**
     * @var string
     */
    private $orderClass;

    /**
     * @param QueryFilterInterface $queryFilter
     * @param string $orderClass
     */
    public function __construct(
        QueryFilterInterface $queryFilter,
        string $orderClass
    ) {
        parent::__construct($queryFilter);

        $this->orderClass = $orderClass;
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $this->orderClass;
        $qb
            ->select('DATE(payment.updatedAt) as date', 'COUNT(DATE(payment.updatedAt)) as orders_quantity', 'ROUND(SUM(o.total/100), 2) as gross_total_money')
            ->from($from, 'o')
        ;

        $this->queryFilter->addLeftJoin('o.customer', 'c');
        $this->queryFilter->addLeftJoin('c.user', 'user');
        $this->queryFilter->addLeftJoin('o.payments', 'payment');

        $qb
            ->andWhere('o.paymentState = :paymentState')
            ->andWhere('payment.state = :state')
            ->setParameter('paymentState', OrderPaymentStates::STATE_PAID)
            ->setParameter('state', PaymentInterface::STATE_COMPLETED)
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
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return SalesTotalType::class;
    }
}
