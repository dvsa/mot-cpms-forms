<?php

namespace CpmsFormTest\Form;

use CpmsForm\Form\ChequePaymentForm;
use CpmsForm\Form\HiddenForm;
use CpmsForm\Form\StoredCardPaymentForm;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Payment\Product\Product;
use CpmsForm\Service\FormGeneratorService;
use CpmsForm\Service\FormGeneratorServiceFactory;
use CpmsFormTest\Bootstrap;
use CpmsFormTest\Helper\DirectDebitPayment;
use CpmsFormTest\Helper\StoredCardMultiPayment;
use CpmsFormTest\Helper\StoredCardPayment;
use PHPUnit\Framework\TestCase;

/**
 * Class StoredCardPaymentFormTest
 *
 * @package CpmsFormTest\Form
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class StoredCardPaymentFormTest extends TestCase
{

    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    /** @var StoredCardPaymentForm */
    protected $form;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        $this->form = $this->serviceManager->get('FormElementManager')->get('storedCardForm');
    }

    public function testPopulateRequiredFieldsWithInvalidPaymentObject()
    {
        $payment = new DirectDebitPayment();
        $this->expectException(\CpmsForm\Exception\InvalidPaymentTypeException::class);
        $this->form->populateRequiredFields($payment);
    }

    public function testPopulateRequiredFieldsWithNotFoundMatches()
    {
        $storedCards = [
            [
                'card_reference' => 'card_reference',
                'card_scheme'    => 'mastercard',
                'mask_pan'       => '****',
            ]
        ];

        $constructorArgs = [
            $this->serviceManager->get('config')['cpms_forms'],
            $this->serviceManager->get(FormGeneratorServiceFactory::API_SERVICE_PATH)
        ];

        $mock = $this->createMock(FormGeneratorService::CLASS_PATH, ['getStoredCards'], $constructorArgs);
        $mock->expects($this->any())
            ->method('getStoredCards')
            ->will($this->returnValue($storedCards));
        $this->form->setService($mock);

        $elementConf = $this->serviceManager->get('config')
        ['cpms_forms']
        ['payment_types']
        [BasePaymentInterface::PAYMENT_TYPE_STORED_CARD]
        ['form_elements']
        [StoredCardPaymentForm::FIELD_NAME_STORED_CARD];

        $elementConf['options']['format'] = 'format';
        $this->form->add($elementConf);

        $payment = new StoredCardPayment();
        $result  = $this->form->populateRequiredFields($payment);

        $this->assertInstanceOf('CpmsForm\Form\StoredCardPaymentForm', $result);
    }

    public function testMultiProductPayment()
    {
        $storedCards = [
            [
                'card_reference' => 'card_reference',
                'card_scheme'    => 'mastercard',
                'mask_pan'       => '****',
            ]
        ];

        $constructorArgs = [
            $this->serviceManager->get('config')['cpms_forms'],
            $this->serviceManager->get(FormGeneratorServiceFactory::API_SERVICE_PATH)
        ];

        $mock = $this->createMock(FormGeneratorService::CLASS_PATH, ['getStoredCards'], $constructorArgs);
        $mock->expects($this->any())
            ->method('getStoredCards')
            ->will($this->returnValue($storedCards));
        $this->form->setService($mock);

        $elementConf = $this->serviceManager->get('config')
        ['cpms_forms']
        ['payment_types']
        [BasePaymentInterface::PAYMENT_TYPE_STORED_CARD]
        ['form_elements']
        [StoredCardPaymentForm::FIELD_NAME_STORED_CARD];

        $elementConf['options']['format'] = 'format';
        $this->form->add($elementConf);

        $payment = new StoredCardMultiPayment();
        $payment->add((new Product()));

        $result = $this->form->populateRequiredFields($payment);

        $this->assertInstanceOf('CpmsForm\Form\StoredCardPaymentForm', $result);
    }

    public function testAttributes()
    {
        $form = new StoredCardPaymentForm();
        $form->setFormFilters([]);
        $this->assertEmpty($form->getFormFilters());

        $form->setFormValidators([]);
        $this->assertEmpty($form->getFormValidators());

        $validators = $form->getDefaultValidators();
        $this->assertNotEmpty($validators);

        $filters = $form->getDefaultFilters();
        $this->assertNotEmpty($filters);

        $form->setFormFilters(['test' => array()]);
        $form->add(array('name' => 'test'));

        $spec = $form->getInputFilterSpecification();
        $this->assertNotEmpty($spec);
    }

    public function testMergeValidators()
    {
        $form = new ChequePaymentForm();
        $form->getInputFilterSpecification();
        $spec = $form->getInputFilterSpecification();

        $this->assertEmpty($spec);
    }

    public function testHiddenForm()
    {
        $form = new HiddenForm();
        $this->assertInstanceOf('Laminas\Form\Form', $form);
    }
}
