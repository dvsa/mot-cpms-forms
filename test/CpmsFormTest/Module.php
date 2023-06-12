<?php

namespace CpmsFormTest;

use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;

/**
 * Class Module
 *
 * @package CpmsFormsTest
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class Module
{
    /**
     * Bootstrap event
     *
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application         = $event->getApplication();
        $eventManager        = $application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        if (file_exists(__DIR__ . '/../test.local.php')) {
            return include __DIR__ . '/../test.local.php';
        }

        return include __DIR__ . '/../test.global.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
