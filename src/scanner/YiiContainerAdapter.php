<?php

namespace grigor\generator\scanner;

use Psr\Container\ContainerInterface;

class YiiContainerAdapter implements ContainerInterface
{
    private $container;

    /**
     * YiiContainerAdapter constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->has($id);
    }
}