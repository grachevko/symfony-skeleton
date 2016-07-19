<?php

namespace AppBundle\Factory;

use AppBundle\Request\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
final class RequestFactory
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class
     *
     * @return Request
     */
    public function create($class)
    {
        if (!array_key_exists($class, $this->map)) {
            throw new \RuntimeException(sprintf('Undefined request class "%s"', $class));
        }

        return $this->container->get($this->map[$class]);
    }

    /**
     * @param $id
     * @param $class
     */
    public function addRequest($id, $class)
    {
        $this->map[$class] = $id;
    }
}
