<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\EventListener;

use InvalidArgumentException;
use Odiseo\SyliusReportPlugin\DataFetcher\DataFetcherInterface;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class BuildReportDataFetcherFormSubscriber implements EventSubscriberInterface
{
    private ServiceRegistryInterface $dataFetcherRegistry;
    private FormFactoryInterface $factory;

    public function __construct(ServiceRegistryInterface $dataFetcherRegistry, FormFactoryInterface $factory)
    {
        $this->dataFetcherRegistry = $dataFetcherRegistry;
        $this->factory = $factory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preBind',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        $report = $event->getData();

        if (null === $report) {
            return;
        }

        if (!$report instanceof ReportInterface) {
            throw new UnexpectedTypeException($report, ReportInterface::class);
        }

        $this->addConfigurationFields(
            $event->getForm(),
            $report->getDataFetcher(),
            $report->getDataFetcherConfiguration()
        );
    }

    public function preBind(FormEvent $event): void
    {
        /** @var array $data */
        $data = $event->getData();

        if (count($data) === 0 || !array_key_exists('dataFetcher', $data)) {
            return;
        }

        $this->addConfigurationFields($event->getForm(), $data['dataFetcher']);
    }

    protected function addConfigurationFields(FormInterface $form, string $dataFetcherType, array $config = []): void
    {
        /** @var DataFetcherInterface $dataFetcher */
        $dataFetcher = $this->dataFetcherRegistry->get($dataFetcherType);
        $formType = $dataFetcher->getType();

        try {
            $configurationField = $this->factory->createNamed(
                'dataFetcherConfiguration',
                $formType,
                $config,
                ['auto_initialize' => false]
            );
        } catch (InvalidArgumentException $e) {
            return;
        }

        $form->add($configurationField);
    }
}
