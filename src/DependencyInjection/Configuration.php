<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DependencyInjection;

use Odiseo\SyliusReportPlugin\Controller\ReportController;
use Odiseo\SyliusReportPlugin\Repository\ReportRepository;
use Odiseo\SyliusReportPlugin\Form\Type\ReportType;
use Odiseo\SyliusReportPlugin\Model\Report;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('odiseo_sylius_report_plugin');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
                ->scalarNode('renderer_configuration_template')->defaultValue('@OdiseoSyliusReportPlugin/_rendererConfiguration.html.twig')->end()
                ->scalarNode('data_fetcher_configuration_template')->defaultValue('@OdiseoSyliusReportPlugin/_dataFetcherConfiguration.html.twig')->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('report')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Report::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(ReportInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ReportController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(ReportRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(ReportType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
