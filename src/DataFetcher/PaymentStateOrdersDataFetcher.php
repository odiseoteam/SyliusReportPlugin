<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManager;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\PaymentStateOrdersType;
use Sylius\Component\Order\Model\OrderInterface;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class PaymentStateOrdersDataFetcher implements DataFetcherInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        $queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('o.payment_state as "Payment State"', 'COUNT(o.id) as "Number of orders"')
            ->from($this->entityManager->getClassMetadata(OrderInterface::class)->getTableName(), 'o')
            ->groupBy('payment_state')
        ;

        /** @var Statement $stmt */
        $stmt = $queryBuilder->execute();

        return $stmt->fetchAll();
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(array $configuration): Data
    {
        $data = new Data();

        $rawData = $this->getData();

        if (empty($rawData)) {
            return $data;
        }

        $labels = array_keys($rawData[0]);
        $data->setLabels($labels);

        $fetched = [];
        foreach ($rawData as $row) {
            $fetched[$row[$labels[0]]] = $row[$labels[1]];
        }

        $data->setData($fetched);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return PaymentStateOrdersType::class;
    }
}
