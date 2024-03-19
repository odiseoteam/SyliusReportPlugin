<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterDataFetchersPassSpec extends ObjectBehavior
{
    function it_should_implement_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes_with_given_container(ContainerBuilder $container, Definition $dataFetcherDefinition)
    {
        $container->hasDefinition('odiseo_sylius_report_plugin.registry.data_fetcher')->willReturn(true);
        $container->getDefinition('odiseo_sylius_report_plugin.registry.data_fetcher')->willReturn($dataFetcherDefinition);

        $dataFetcherServices = [
            'odiseo_sylius_report_plugin.form.type.data_fetcher.test' => [
                ['fetcher' => 'test', 'label' => 'Test data fetcher'],
            ],
        ];
        $container->findTaggedServiceIds('odiseo_sylius_report_plugin.data_fetcher')->willReturn($dataFetcherServices);

        $dataFetcherDefinition->addMethodCall(
            'register',
            ['test', new Reference('odiseo_sylius_report_plugin.form.type.data_fetcher.test')]
        )->willReturn($dataFetcherDefinition);
        $container->setParameter('odiseo_sylius_report_plugin.data_fetchers', ['test' => 'Test data fetcher'])->shouldBeCalled();

        $this->process($container);
    }

    function it_does_not_process_if_container_has_not_proper_definition(ContainerBuilder $container)
    {
        $container->hasDefinition('odiseo_sylius_report_plugin.registry.data_fetcher')->willReturn(false);
        $container->getDefinition('odiseo_sylius_report_plugin.registry.data_fetcher')->shouldNotBeCalled();

        $this->process($container);
    }

    function it_throws_exception_if_any_data_fetcher_has_improper_attributes(ContainerBuilder $container, Definition $dataFetcherDefinition)
    {
        $container->hasDefinition('odiseo_sylius_report_plugin.registry.data_fetcher')->willReturn(true);
        $container->getDefinition('odiseo_sylius_report_plugin.registry.data_fetcher')->willReturn($dataFetcherDefinition);

        $dataFetcherServices = [
            'odiseo_sylius_report_plugin.form.type.data_fetcher.test' => [
                ['data_fetcher' => 'test'],
            ],
        ];
        $container->findTaggedServiceIds('odiseo_sylius_report_plugin.data_fetcher')->willReturn($dataFetcherServices);
        $dataFetcherDefinition->addMethodCall('register', ['test', new Reference('odiseo_sylius_report_plugin.form.type.data_fetcher.test')])->shouldNotBeCalled();

        $this->shouldThrow(new \InvalidArgumentException('Tagged report data fetchers needs to have `fetcher` and `label` attributes.'))
            ->during('process', [$container]);
    }
}
