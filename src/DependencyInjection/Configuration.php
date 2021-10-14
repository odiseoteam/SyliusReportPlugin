<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('odiseo_sylius_report_plugin');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('renderer_configuration_template')
                    ->defaultValue('@OdiseoSyliusReportPlugin/_rendererConfiguration.html.twig')->end()
                ->scalarNode('data_fetcher_configuration_template')
                    ->defaultValue('@OdiseoSyliusReportPlugin/_dataFetcherConfiguration.html.twig')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
