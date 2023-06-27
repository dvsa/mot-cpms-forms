<?php

namespace CpmsFormTest\Helper;

use Laminas\Mvc\Controller\PluginManager;
use Laminas\Mvc\Router\Http\RouteMatch;
use Laminas\Mvc\Router\Http\TreeRouteStack;
use Laminas\Mvc\Router\RoutePluginManager;
use Laminas\Mvc\Router\SimpleRouteStack;

/**
 * Trait SetupControllerTrait
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
trait SetupControllerTrait
{
    protected function setupController()
    {
        $router     = TreeRouteStack::factory($this->serviceManager->get('Config')['router']);
        $routeMatch = new RouteMatch([]);

        $restOptions = $this->getMock('\stdClass', ['getDomain', 'getGrantType', 'getClientId', 'getClientSecret']);

        $restClient = $this->getMock('\stdClass', ['getOptions', 'post']);
        $restClient->expects($this->any())
            ->method('post')
            ->will($this->returnValue($this->apiResult));

        $restClient->expects($this->any())
            ->method('getOptions')
            ->will($this->returnValue($restOptions));

        /** @var \CpmsForm\Controller\ProcessController $controller */
        $controller = $this->getMock('CpmsForm\Controller\ProcessController', ['getCpmsRestClient']);

        $controller->expects($this->any())
            ->method('getCpmsRestClient')
            ->will($this->returnValue($restClient));

        $router->match($controller->getRequest());

        $event = $controller->getEvent();
        $event->setRouteMatch($routeMatch);
        $event->setRouter($router);
        $event->setResponse($controller->getResponse());

        $this->controller = $controller;
    }
}
