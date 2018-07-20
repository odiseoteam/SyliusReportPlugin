<?php

namespace Odiseo\SyliusReportPlugin\Renderer;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

class DelegatingRenderer implements DelegatingRendererInterface
{
    /**
     * Renderer registry.
     *
     * @var ServiceRegistryInterface
     */
    protected $registry;

    /**
     * @param ServiceRegistryInterface $registry
     */
    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If the report subject does not have a renderer.
     */
    public function render(ReportInterface $subject, Data $data)
    {
        if (null === $type = $subject->getRenderer()) {
            throw new \InvalidArgumentException('Cannot render data for ReportInterface instance without renderer defined.');
        }

        /** @var RendererInterface $renderer */
        $renderer = $this->registry->get($type);

        return $renderer->render($subject, $data);
    }
}
