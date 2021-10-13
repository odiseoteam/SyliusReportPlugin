<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\DependencyInjection\Compiler;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers all reports renderers in renderer registry service.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class RegisterRenderersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('odiseo_sylius_report_plugin.registry.renderer')) {
            return;
        }

        $registry = $container->getDefinition('odiseo_sylius_report_plugin.registry.renderer');
        $renderers = [];

        foreach ($container->findTaggedServiceIds('odiseo_sylius_report_plugin.renderer') as $id => $attributes) {
            if (!isset($attributes[0]['renderer']) || !isset($attributes[0]['label'])) {
                throw new InvalidArgumentException('Tagged renderers needs to have `renderer` and `label` attributes.');
            }

            $name = $attributes[0]['renderer'];
            $renderers[$name] = $attributes[0]['label'];
            $registry->addMethodCall('register', [$name, new Reference($id)]);
        }

        $container->setParameter('odiseo_sylius_report_plugin.renderers', $renderers);
    }
}
