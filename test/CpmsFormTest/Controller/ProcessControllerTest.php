<?php

namespace CpmsFormTest\Controller;

use CpmsForm\Controller\Plugin\GetCpmsPaymentForm;
use CpmsForm\Controller\Plugin\ServiceLocatorPlugin;
use CpmsForm\Controller\ProcessController;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Service\AbstractProcessService;
use CpmsForm\Service\FormGeneratorService;
use CpmsFormTest\Bootstrap;
use CpmsFormTest\Helper\PaymentTypeProviderTrait;
use CpmsFormTest\Mock\MockProcessService;
use PaymentTest\Controller\AbstractHttpControllerTestCase;
use PaymentTest\Controller\ControllerSetupTrait;
use Laminas\Form\Form;
use Laminas\Mvc\Controller\Plugin\Redirect;
use Laminas\Session\Container;

/**
 * Class ProcessControllerTest
 *
 * @package CpmsFormTest\Controller
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class ProcessControllerTest extends AbstractHttpControllerTestCase
{
    use PaymentTypeProviderTrait;
    use ControllerSetupTrait;

    public $useJson = false;

    /** @var array */
    protected $apiResult = [];

    /**
     * @return Bootstrap|\PaymentTest\Test\BootstrapTrait
     */
    public function getBootstrap()
    {
        return Bootstrap::getInstance();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->setApplicationConfig(Bootstrap::getConfig());
        $bootstrap = Bootstrap::getInstance();

        $serviceManager = $bootstrap->getServiceManager();
        $serviceManager->setAllowOverride(true);
        $matched = [
            'controller' => 'report'
        ];

        $serviceLocatorPlugin = new ServiceLocatorPlugin($serviceManager);

        $this->setupController(
            $serviceManager, new ProcessController(), $matched, [
                'getCpmsPaymentForm' => new GetCpmsPaymentForm(),
                'redirect'           => new Redirect(),
                'getServiceLocator'  => $serviceLocatorPlugin
            ]
        );
    }

    public function testFormActionWithNoData()
    {
        $this->dispatch('/cpms-forms/process-form', 'GET');
        $this->assertMatchedRouteName('cpms_forms/process_form');
        $this->assertResponseStatusCode(401);
    }

    public function testFormActionWithInvalidData()
    {
        $params = ['data' => base64_encode(serialize('not_an_array'))];
        $this->dispatch('/cpms-forms/process-form', 'POST', $params);
        $this->assertMatchedRouteName('cpms_forms/process_form');
        $this->assertResponseStatusCode(401);
    }

    /**
     * @dataProvider redirectionPaymentDataProvider
     * @param $payment
     * @param $type
     * @throws \Exception
     */
    public function testFormActionForRedirectionPayments($payment, $type)
    {
        $formService = $this->serviceManager->get(FormGeneratorService::CLASS_PATH);
        $form        = $formService->build($payment);

        $data = $this->getFormData($form, $type);

        $params = ['data' => base64_encode(serialize($data))];
        $this->dispatch('/cpms-forms/process-form', 'GET', $params);
        $this->assertMatchedRouteName('cpms_forms/process_form');
        $this->assertIsRedirect();
    }

    /**
     * @dataProvider nonRedirectionPaymentDataProvider
     * @param $payment
     * @param $type
     * @throws \Exception
     */
    public function testFormActionForRestPaymentsWithFailure($payment, $type)
    {
        $formService = $this->serviceManager->get(FormGeneratorService::CLASS_PATH);
        $form        = $formService->build($payment);

        $data = $this->getFormData($form, $type);

        $params = ['data' => base64_encode(serialize($data))];
        $this->dispatch('/cpms-forms/process-form', 'GET', $params);
        $this->assertMatchedRouteName('cpms_forms/process_form');
        $this->assertIsRedirect();
    }

    /**
     * @dataProvider nonRedirectionPaymentDataProvider
     *
     * @param $payment
     * @param $type
     * @throws \Exception
     */
    public function testFormActionForRestPaymentsWithSuccess($payment, $type)
    {
        $this->apiResult = ['receipt_reference' => 'receipt_reference'];

        $formService = $this->serviceManager->get(FormGeneratorService::CLASS_PATH);
        $form        = $formService->build($payment);

        $data = $this->getFormData($form, $type);

        $params = ['data' => base64_encode(serialize($data))];
        $this->dispatch('/cpms-forms/process-form', 'GET', $params);
        $this->assertMatchedRouteName('cpms_forms/process_form');
        $this->assertIsRedirect();
    }

    public function testResponseActionWithNoRedirectParam()
    {
        $this->dispatch('/cpms-forms/process-response', 'GET', []);

        $this->assertMatchedRouteName('cpms_forms/process_response');
        $this->assertResponseStatusCode(401);
    }

    public function testResponseAction()
    {
        $params = [AbstractProcessService::QUERY_PARAM_REDIRECT => 'http://redirect.dvla.mot'];
        $this->dispatch('/cpms-forms/process-response', 'GET', $params);

        $this->assertMatchedRouteName('cpms_forms/process_response');
        $this->assertIsRedirect();
    }

    public function testResponseActionWithSessionParams()
    {
        $container = new Container('cpms_forms');

        $container->offsetSet('paymentType', 'cash');
        $container->offsetSet('receiptReference', 'MOT2-01-20150903-090807-89098768');
        $params = [AbstractProcessService::QUERY_PARAM_REDIRECT => 'http://redirect.dvla.mot'];
        $this->dispatch('/cpms-forms/process-response', 'GET', $params);

        $this->assertMatchedRouteName('cpms_forms/process_response');
        $this->assertIsRedirect();
    }

    /**
     * @param $payment
     * @param $type
     * @throws \Exception
     * @dataProvider nonRedirectionPaymentDataProvider
     */
    public function testFormActionReturnFormOnError($payment, $type)
    {
        $formService        = $this->serviceManager->get(FormGeneratorService::CLASS_PATH);
        $form               = $formService->build($payment);
        $mockProcessService = new MockProcessService();
        $manager            = $this->getApplication()->getServiceManager();
        $manager->setAllowOverride(true);

        $service = $manager->get('cpms_forms\process\cash');
        $manager->setService('cpms_forms\process\cash', $mockProcessService);
        $data = $this->getFormData($form, $type);

        $params = ['data' => base64_encode(serialize($data))];
        $this->dispatch('/cpms-forms/process-form', 'GET', $params);
        $this->assertMatchedRouteName('cpms_forms/process_form');
        $this->assertInstanceOf('Laminas\Form\Form', $form);
        $manager->setService('cpms_forms\process\cash', $service);
    }

    private function getFormData(Form $form, $type)
    {
        $data = [
            'sales_reference' => uniqid('samp')
        ];
        foreach ($form->getElements() as $name => $element) {
            $data[$name] = 1;
        }

        $data[PaymentForm::FIELD_NAME_PAYMENT_TYPE] = $type;

        $data['payment_data'][] = [
            'amount'          => 1,
            'sales_reference' => 'SALES' . rand()
        ];

        $form->setData($data);
        $form->isValid();

        return $form->getData();
    }
}
