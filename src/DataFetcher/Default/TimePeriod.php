<?php

namespace Odiseo\SyliusReportPlugin\DataFetcher;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;

/**
 * Abstract class to provide time periods logic.
 */
abstract class TimePeriod implements DataFetcherInterface
{
    const PERIOD_DAY = 'day';
    const PERIOD_MONTH = 'month';
    const PERIOD_YEAR = 'year';

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
     * @return array
     */
    public static function getPeriodChoices()
    {
        return [
            'Daily' => self::PERIOD_DAY,
            'Monthly' => self::PERIOD_MONTH,
            'Yearly' => self::PERIOD_YEAR,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(array $configuration)
    {
        $data = new Data();

        //There is added 23 hours 59 minutes 59 seconds to the end date to provide records for whole end date
        $configuration['end'] = $configuration['end']->add(new \DateInterval('PT23H59M59S'));
        //This should be removed after implementation hourly periods

        switch ($configuration['period']) {
            case self::PERIOD_DAY:
                $this->setExtraConfiguration($configuration, 'P1D', '%a', 'Y-m-d', ['date']);
                break;
            case self::PERIOD_MONTH:
                $this->setExtraConfiguration($configuration, 'P1M', '%m', 'F Y', ['month', 'year']);
                break;
            case self::PERIOD_YEAR:
                $this->setExtraConfiguration($configuration, 'P1Y', '%y', 'Y', ['year']);
                break;
            default:
                throw new \InvalidArgumentException('Wrong data fetcher period');
        }

        $rawData = $this->getData($configuration);

        if (empty($rawData)) {
            return $data;
        }

        $labels = array_keys($rawData[0]);
        $data->setLabels($labels);

        $fetched = [];

        if ($configuration['empty_records']) {
            $fetched = $this->fillEmptyRecords($fetched, $configuration);
        }
        foreach ($rawData as $row) {
            $date = new \DateTime($row[$labels[0]]);
            $fetched[$date->format($configuration['presentationFormat'])] = $row[$labels[1]];
        }

        $data->setData($fetched);

        return $data;
    }

    protected function addTimePeriodQueryBuilder(QueryBuilder $queryBuilder, array $configuration = [], $dateField = 'o.completed_at')
    {
        $groupBy = $this->getGroupBy($configuration);
        $queryBuilder
            ->andWhere($queryBuilder->expr()->gte($dateField, ':from'))
            ->andWhere($queryBuilder->expr()->lte($dateField, ':to'))
            ->setParameter('from', $configuration['start']->format('Y-m-d H:i:s'))
            ->setParameter('to', $configuration['end']->format('Y-m-d H:i:s'))
            ->groupBy($groupBy)
            ->orderBy($groupBy)
        ;

        return $queryBuilder;
    }

    /**
     * Return a concadenated string of all groupBy given in $configuration
     *
     * @param array $configuration
     * @return string
     */
    protected function getGroupBy(array $configuration = [])
    {
        $groupBy = '';

        foreach ($configuration['groupBy'] as $groupByElement) {
            $groupBy = $groupByElement.'(date)'.' '.$groupBy;
        }

        $groupBy = substr($groupBy, 0, -1);
        $groupBy = str_replace(' ', ', ', $groupBy);

        return $groupBy;
    }

    /**
     * Responsible for providing raw data to fetch, from the configuration (ie: start date, end date, time period,
     * empty records flag, interval, period format, presentation format, group by).
     *
     * @param array $configuration
     *
     * @return array
     */
    abstract protected function getData(array $configuration = []);

    /**
     * @param array  $configuration
     * @param string $interval
     * @param string $periodFormat
     * @param string $presentationFormat
     * @param array  $groupBy
     */
    private function setExtraConfiguration(
        array &$configuration,
        $interval,
        $periodFormat,
        $presentationFormat,
        array $groupBy
    ) {
        $configuration['interval'] = $interval;
        $configuration['periodFormat'] = $periodFormat;
        $configuration['presentationFormat'] = $presentationFormat;
        $configuration['groupBy'] = $groupBy;
    }

    /**
     * @param array $fetched
     * @param array $configuration
     *
     * @return array
     */
    private function fillEmptyRecords(array $fetched, array $configuration)
    {
        /** @var \DateTime $startDate */
        $startDate = $configuration['start'];
        /** @var \DateTime $startDate */
        $endDate = $configuration['end'];

        try {
            $dateInterval = new \DateInterval($configuration['interval']);
        } catch (\Exception $e) {
            return $fetched;
        }

        $numberOfPeriods = $startDate->diff($endDate);
        $formattedNumberOfPeriods = $numberOfPeriods->format($configuration['periodFormat']);

        for ($i = 0; $i <= $formattedNumberOfPeriods; ++$i) {
            $fetched[$startDate->format($configuration['presentationFormat'])] = 0;
            $startDate = $startDate->add($dateInterval);
        }

        return $fetched;
    }
}
