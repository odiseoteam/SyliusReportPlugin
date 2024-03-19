<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterRenderersPassSpec extends ObjectBehavior
{
    function it_should_implement_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes_with_given_container(ContainerBuilder $container, Definition $rendererDefinition)
    {
        $container->hasDefinition('odiseo_sylius_report_plugin.registry.renderer')->willReturn(true);
        $container->getDefinition('odiseo_sylius_report_plugin.registry.renderer')->willReturn($rendererDefinition);

        $rendererServices = [
            'odiseo_sylius_report_plugin.form.type.renderer.test' => [
                ['renderer' => 'test', 'label' => 'Test renderer'],
            ],
        ];
        $container->findTaggedServiceIds('odiseo_sylius_report_plugin.renderer')->willReturn($rendererServices);

        $rendererDefinition->addMethodCall(
            'register',
            ['test', new Reference('odiseo_sylius_report_plugin.form.type.renderer.test')]
        )->willReturn($rendererDefinition);
        $container->setParameter('odiseo_sylius_report_plugin.renderers', ['test' => 'Test renderer'])->shouldBeCalled();

        $this->process($container);
    }

    function it_does_not_process_if_container_has_not_proper_definition(ContainerBuilder $container)
    {
        $container->hasDefinition('odiseo_sylius_report_plugin.registry.renderer')->willReturn(false);
        $container->getDefinition('odiseo_sylius_report_plugin.registry.renderer')->shouldNotBeCalled();

        $this->process($container);
    }

    function it_throws_exception_if_any_renderer_has_improper_attributes(ContainerBuilder $container, Definition $rendererDefinition)
    {
        $container->hasDefinition('odiseo_sylius_report_plugin.registry.renderer')->willReturn(true);
        $container->getDefinition('odiseo_sylius_report_plugin.registry.renderer')->willReturn($rendererDefinition);

        $rendererServices = [
            'odiseo_sylius_report_plugin.form.type.renderer.test' => [
                ['renderer' => 'test'],
            ],
        ];
        $container->findTaggedServiceIds('odiseo_sylius_report_plugin.renderer')->willReturn($rendererServices);
        $rendererDefinition->addMethodCall('register', ['test', new Reference('odiseo_sylius_report_plugin.form.type.renderer.test')])->shouldNotBeCalled();

        $this->shouldThrow(new \InvalidArgumentException('Tagged renderers needs to have `renderer` and `label` attributes.'))
            ->during('process', [$container]);
    }
}
