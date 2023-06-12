<?php

namespace CpmsFormTest\Payment\Product;

use CpmsForm\Payment\Product\Product;
use CpmsFormTest\Bootstrap;
use PHPUnit\Framework\TestCase;

class MultiProductPaymentTest extends TestCase
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);
    }

    /**
     * @group multi
     */
    public function testSettersAndGetters()
    {
        /** @var \CpmsForm\Payment\Product\MultiProductPayment $multiPayment */
        $multiPayment = $this->getMockForAbstractClass('CpmsForm\Payment\Product\MultiProductPayment');

        $salesRef   = 'asd';
        $prodRef    = 'prod';
        $redirect   = 'http';
        $userId     = 1;
        $custRef    = 'ref';
        $costCentre = '12345,67809';
        $custName   = 'Company Name';

        $this->assertEquals(null, $multiPayment->getSalesReference());

        $product = new Product();
        $product->setAmount(100);
        $product->setProductReference($prodRef);

        $multiPayment->add($product);
        $multiPayment->setRedirectUri($redirect);
        $multiPayment->setUserId($userId);
        $multiPayment->setSalesReference($salesRef);
        $multiPayment->setCustomerReference($custRef);
        $multiPayment->setCostCentre($costCentre);
        $multiPayment->setCustomerName($custName);

        $this->assertEquals(1, count($multiPayment->getProducts()));
        $this->assertEquals($product->getAmount(), $multiPayment->getAmount());
        $this->assertEquals($salesRef, $multiPayment->getSalesReference());
        $this->assertEquals($prodRef, $multiPayment->getProductReference());
        $this->assertEquals($redirect, $multiPayment->getRedirectUri());
        $this->assertEquals($userId, $multiPayment->getUserId());
        $this->assertEquals($custRef, $multiPayment->getCustomerReference());
        $this->assertEquals($custName, $multiPayment->getCustomerName());
    }
}
