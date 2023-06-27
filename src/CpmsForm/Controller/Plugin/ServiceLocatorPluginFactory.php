<?php

namespace CpmsForm\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ServiceLocatorPluginFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $name, array $args = null)
    {
        $serviceLocatorPlugin = new ServiceLocatorPlugin($container);
        return $serviceLocatorPlugin;
    }
}