<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\RequestPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RequestPass());
    }
}
