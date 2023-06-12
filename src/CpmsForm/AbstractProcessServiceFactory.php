<?php

namespace CpmsForm;

use Interop\Container\ContainerInterface;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class FormProcessServiceFactory
 *
 * @package CpmsForm\Service
 */
class AbstractProcessServiceFactory implements AbstractFactoryInterface
{
    const API_SERVICE_PATH = 'cpms\service\api';

    /*    /** @var string */
    protected $configPrefix = 'cpms_forms\process\\';

    /**
     * Determine if we can create a service with name
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        // there is another class in SlotPurchase that uses the same name
        if ($requestedName === 'cpms_forms\process\card') {
            return false;
        }

        if (substr($requestedName, 0, strlen($this->configPrefix)) != $this->configPrefix) {
            return false;
        }

        return true;
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var \CpmsForm\Service\AbstractProcessService $service
         */
        $filter      = new UnderscoreToCamelCase();
        $paymentType = str_replace($this->configPrefix, '', $requestedName);
        $serviceName = $filter->filter($paymentType);
        $className   = __NAMESPACE__ . '\Service\Process\\' . $serviceName;
        $apiService  = $container->get(self::API_SERVICE_PATH);
        $service     = new $className($apiService);
        $config      = $container->get('config');

        $service->setServiceLocator($container);
        $service->setScope($config['cpms_forms']['payment_types'][$paymentType]['scope']);
        $service->setEndPoint($config['cpms_forms']['payment_types'][$paymentType]['endpoint']);
        $service->setPaymentType($paymentType);

        return $service;
    }
}
