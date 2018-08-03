<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin;

use Odiseo\SyliusReportPlugin\DependencyInjection\Compiler\RegisterDataFetchersPass;
use Odiseo\SyliusReportPlugin\DependencyInjection\Compiler\RegisterRenderersPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class OdiseoSyliusReportPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    protected $mappingFormat = ResourceBundleInterface::MAPPING_YAML;

    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container) :void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterDataFetchersPass());
        $container->addCompilerPass(new RegisterRenderersPass());
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelNamespace(): ?string
    {
        return 'Odiseo\SyliusReportPlugin\Model';
    }
}
