<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManager;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\PaymentStateOrdersType;

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
     * @var string
     */
    protected $orderClass;

    /**
     * @param EntityManager $entityManager
     * @param string $orderClass
     */
    public function __construct(EntityManager $entityManager, string $orderClass)
    {
        $this->entityManager = $entityManager;
        $this->orderClass = $orderClass;
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        $queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('o.payment_state as "Payment State"', 'COUNT(o.id) as "Number of orders"')
            ->from($this->entityManager->getClassMetadata($this->orderClass)->getTableName(), 'o')
            ->groupBy('payment_state')
        ;

        /** @var Statement $stmt */
        $stmt = $queryBuilder->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param array $configuration
     *
     * @return Data $data
     */
    public function fetch(array $configuration)
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
    public function getType()
    {
        return PaymentStateOrdersType::class;
    }
}
