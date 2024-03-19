<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
