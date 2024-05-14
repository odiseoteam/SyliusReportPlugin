<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\DataFetcher\Data as DataHelper;
use Odiseo\SyliusReportPlugin\Filter\QueryFilterInterface;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\SalesTotalType;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Payment\Model\PaymentInterface;

class SalesTotalDataFetcher extends TimePeriodDataFetcher
{
    public function __construct(
        private string $orderClass,
        QueryFilterInterface $queryFilter,
        private DataHelper $reportHelper,
    ) {
        parent::__construct($queryFilter);
    }

    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $this->orderClass;
        $qb
            ->select(
                'DATE_FORMAT(payment.updatedAt, :format) as date',
                'COUNT(DATE(payment.updatedAt)) as orders_quantity',
                'ROUND(SUM(o.total/100), 2) as gross_total_money',
            )
            ->from($from, 'o')
            ->groupBy('date')
        ;
        $qb->setParameter('format', $this->reportHelper->getFormatByGroupBy($configuration['groupBy'] ?? []));

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

    public function getType(): string
    {
        return SalesTotalType::class;
    }
}
