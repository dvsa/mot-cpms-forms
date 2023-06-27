<?php
namespace CpmsFormTest\Process;

use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Service\Process\Card;
use CpmsFormTest\Bootstrap;
use PHPUnit\Framework\TestCase;
use Laminas\Stdlib\Parameters;

/**
 * Class CardTest
 *
 * @package CpmsFormTest\Process
 */
class CardTest extends TestCase
{
    /**
     * @var Card
     */
    protected $service;
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->markTestSkipped('Skipped test - we are preventing creation of this service:  \CpmsForm\AbstractProcessServiceFactory::canCreate');

        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        $this->service = $this->serviceManager->get('cpms_forms\process\card');
    }

    public function testExceptionCaught()
    {
        $this->markTestSkipped('Skipped test - we are preventing creation of this service:  \CpmsForm\AbstractProcessServiceFactory::canCreate');
        $params = new Parameters();

        $return = $this->service->processFormData($params, new \StdClass());
        $this->assertNotEmpty($return);
        $this->assertTrue(is_string($return));
    }

    public function testSuccess()
    {
        $this->markTestSkipped('Skipped test - we are preventing creation of this service:  \CpmsForm\AbstractProcessServiceFactory::canCreate');

        $result  = array('receipt_reference' => 'reference');
        $baseUrl = 'http://payment-app.in';
        $return  = $this->service->prepareRedirectUrl($baseUrl, $result);
        $this->assertTrue(strpos($return, 'receipt_reference') !== false);
    }

    public function testHandleApiResponseNoGatewayUrl()
    {
        $this->markTestSkipped('Skipped test - we are preventing creation of this service:  \CpmsForm\AbstractProcessServiceFactory::canCreate');

        $result = [
            'receipt_reference' => 'MOT-2-01-20150903-090808-90908765'
        ];
        $param  = new Parameters();
        $return = $this->service->handleApiResponse($result, $param);
        $this->assertNotEmpty($return);
    }

    public function testHandleApiResponseWithGatewayUrl()
    {
        $this->markTestSkipped('Skipped test - we are preventing creation of this service:  \CpmsForm\AbstractProcessServiceFactory::canCreate');

        $url    = 'http://payment-app.in';
        $result = [
            'receipt_reference' => 'MOT-2-01-20150903-090808-90908765',
            'gateway_url'       => $url
        ];
        $param  = new Parameters();
        $return = $this->service->handleApiResponse($result, $param);
        $this->assertSame($url, $return);
    }

    public function testRequireFormPost()
    {
        $this->markTestSkipped('Skipped test - we are preventing creation of this service:  \CpmsForm\AbstractProcessServiceFactory::canCreate');

        $value = $this->service->requireFormPost(BasePaymentInterface::PAYMENT_TYPE_CARD);
        $this->assertTrue($value);
    }
}
