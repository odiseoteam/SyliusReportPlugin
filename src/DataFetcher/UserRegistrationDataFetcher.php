<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\UserRegistrationType;
use Sylius\Component\Order\Model\OrderInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class UserRegistrationDataFetcher extends TimePeriodDataFetcher
{
    protected function setupQueryFilter(array $configuration = []): void
    {
        $qb = $this->queryFilter->getQueryBuilder();

        $from = $qb->getEntityManager()->getClassMetadata(OrderInterface::class)->getName();
        $qb
            ->select('DATE(u.createdAt) as date', 'count(u.id) as user_total')
            ->from($from, 'u')
        ;

        $this->queryFilter->addTimePeriod($configuration, 'createdAt');
        $this->queryFilter->addChannel($configuration);
        $this->queryFilter->addUserGender($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return UserRegistrationType::class;
    }
}
