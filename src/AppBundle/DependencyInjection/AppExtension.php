<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $dir = __DIR__.'/../Resources/config';
        $loader = new Loader\YamlFileLoader($container, new FileLocator($dir));

        $finder = new Finder();
        $finder->files()->name('*.yml');
        foreach ($finder->in($dir) as $file) {
            $loader->load($file->getBasename());
        }
    }
}
