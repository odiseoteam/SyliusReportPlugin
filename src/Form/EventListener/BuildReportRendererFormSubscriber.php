<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\EventListener;

use InvalidArgumentException;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\RendererInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class BuildReportRendererFormSubscriber implements EventSubscriberInterface
{
    private ServiceRegistryInterface $rendererRegistry;
    private FormFactoryInterface $factory;

    public function __construct(ServiceRegistryInterface $rendererRegistry, FormFactoryInterface $factory)
    {
        $this->rendererRegistry = $rendererRegistry;
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

        $this->addConfigurationFields($event->getForm(), $report->getRenderer(), $report->getRendererConfiguration());
    }

    public function preBind(FormEvent $event): void
    {
        /** @var array $data */
        $data = $event->getData();

        if (count($data) === 0 || !array_key_exists('renderer', $data)) {
            return;
        }

        $this->addConfigurationFields($event->getForm(), $data['renderer']);
    }

    public function addConfigurationFields(FormInterface $form, string $rendererType, array $data = []): void
    {
        /** @var RendererInterface $renderer */
        $renderer = $this->rendererRegistry->get($rendererType);
        $formType = $renderer->getType();

        try {
            $configurationField = $this->factory->createNamed(
                'rendererConfiguration',
                $formType,
                $data,
                ['auto_initialize' => false]
            );
        } catch (InvalidArgumentException $e) {
            return;
        }

        $form->add($configurationField);
    }
}
