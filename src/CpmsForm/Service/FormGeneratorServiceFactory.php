<?php

namespace CpmsForm\Service;


use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class FormFactoryServiceFactory
 *
 * @package CpmsForm\Service
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class FormGeneratorServiceFactory implements FactoryInterface
{
    const CLASS_PATH = __CLASS__;

    const API_SERVICE_PATH = 'cpms\service\api';

    /**
     * Create FormFactoryService
     *
     * @param ContainerInterface $container
     *
     * @param $requestedName
     * @param array|null $options
     * @return FormGeneratorService
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var \CpmsClient\Service\ApiService $apiService
         */
        $apiService = $container->get(self::API_SERVICE_PATH);

        $service = new FormGeneratorService(
            $container->get('config')['cpms_forms'],
            $apiService
        );

        $service->setServiceLocator($container);

        return $service;
    }
}
