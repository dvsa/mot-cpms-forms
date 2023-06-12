<?php

namespace CpmsForm\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\ServiceManager\ServiceManager;

class ServiceLocatorPlugin extends AbstractPlugin
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __invoke()
    {
        return $this->container;
    }
}