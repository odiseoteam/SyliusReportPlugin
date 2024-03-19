<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin;

use Odiseo\SyliusReportPlugin\DependencyInjection\Compiler\RegisterDataFetchersPass;
use Odiseo\SyliusReportPlugin\DependencyInjection\Compiler\RegisterRenderersPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class OdiseoSyliusReportPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterDataFetchersPass());
        $container->addCompilerPass(new RegisterRenderersPass());
    }
}
