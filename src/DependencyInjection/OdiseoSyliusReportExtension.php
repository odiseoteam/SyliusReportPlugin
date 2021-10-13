<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DependencyInjection;

use Exception;
use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class OdiseoSyliusReportExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->registerResources('odiseo_sylius_report', $config['driver'], $config['resources'], $container);

        $configFiles = [
            'services.yml',
        ];

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }

        $container
            ->getDefinition('odiseo_sylius_report.form.type.report')
            ->addArgument(new Reference('odiseo_sylius_report.registry.renderer'))
            ->addArgument(new Reference('odiseo_sylius_report.registry.data_fetcher'))
            ->addArgument($config['renderer_configuration_template'])
            ->addArgument($config['data_fetcher_configuration_template'])
        ;
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'Odiseo\SyliusReportPlugin\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@OdiseoSyliusReportPlugin/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [];
    }
}
