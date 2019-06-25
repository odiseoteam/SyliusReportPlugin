<?php

namespace Odiseo\SyliusReportPlugin\DependencyInjection\Compiler;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers all reports dataFetchers in dataFetcher registry service.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class RegisterDataFetchersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('odiseo_sylius_report.registry.data_fetcher')) {
            return;
        }

        $registry = $container->getDefinition('odiseo_sylius_report.registry.data_fetcher');
        $dataFetchers = [];

        foreach ($container->findTaggedServiceIds('odiseo_sylius_report.data_fetcher') as $id => $attributes) {
            if (!isset($attributes[0]['fetcher']) || !isset($attributes[0]['label'])) {
                throw new InvalidArgumentException(
                    'Tagged report data fetchers needs to have `fetcher` and `label` attributes.'
                );
            }

            $name = $attributes[0]['fetcher'];
            $dataFetchers[$name] = $attributes[0]['label'];

            $registry->addMethodCall('register', [$name, new Reference($id)]);
        }

        $container->setParameter('odiseo_sylius_report.data_fetchers', $dataFetchers);
    }
}
