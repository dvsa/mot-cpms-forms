<?php

namespace CpmsFormTest\Service;

use CpmsForm\Service\FormGeneratorService;
use CpmsForm\Service\FormGeneratorServiceFactory;
use CpmsFormTest\Bootstrap;
use CpmsFormTest\Helper\PaymentTypeProviderTrait;
use CpmsFormTest\Helper\WrongPayment;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class FormGeneratorServiceTest
 *
 * @package CpmsFormTest\Service
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class FormGeneratorServiceTest extends AbstractHttpControllerTestCase
{
    use PaymentTypeProviderTrait;

    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    /**
     * @var FormGeneratorService
     */
    protected $service;
    /** @var  array The original config */
    private $originalConfig;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        $this->service        = $this->getService();
        $this->originalConfig = $this->serviceManager->get('Config');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // ensure the config is put back together for subsequent tests.
        $this->serviceManager->setService('Config', $this->originalConfig);
    }

    /**
     * @param $payment
     * @param $type
     *
     * @medium
     * @dataProvider paymentTypeProvider
     */
    public function testPaymentTypes($payment, $type)
    {
        $form = $this->service->build($payment);

        $this->assertEquals($type, $form->getPaymentType());
        $this->assertInstanceOf(get_class($this->service), $form->getService());

    }

    /**
     * @param $payment
     * @param $type
     *
     * @medium
     * @dataProvider paymentTypeProvider
     */
    public function testPaymentTypeWithNoConfig($payment, $type)
    {
        $config = $this->serviceManager->get('Config');
        unset($config['cpms_forms']['payment_types'][$type]);
        $this->serviceManager->setService('Config', $config);

        $this->expectException(\CpmsForm\Exception\NoConfigException::class);
        $this->getService()->build($payment);
    }

    /**
     * @medium
     */
    public function testWrongPaymentType()
    {
        $payment = new WrongPayment();
        $this->expectException(\CpmsForm\Exception\InvalidPaymentTypeException::class);
        $this->service->build($payment);
    }

    /**
     * @medium
     */
    public function testNoStoredCardReturned()
    {
        $storedCards = $this->getService()->getStoredCards('invalidreference');
        $this->assertEmpty($storedCards);
    }

    /**
     * @return FormGeneratorService
     */
    private function getService()
    {
        $service = new FormGeneratorService(
            $this->serviceManager->get('Config')['cpms_forms'],
            $this->serviceManager->get(FormGeneratorServiceFactory::API_SERVICE_PATH)
        );
        $service->setServiceLocator($this->serviceManager);

        return $service;
    }
}
