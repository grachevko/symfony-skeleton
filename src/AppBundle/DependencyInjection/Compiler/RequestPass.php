<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
class RequestPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('app.factory.request')) {
            return;
        }

        $definition = $container->getDefinition('app.factory.request');

        $taggedServices = $container->findTaggedServiceIds('app.request');

        foreach ($taggedServices as $id => $service) {
            $definition->addMethodCall('addRequest', [$id, $container->getDefinition($id)->getClass()]);
        }
    }
}
