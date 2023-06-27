<?php

namespace CpmsFormTest\Payment;

use CpmsFormTest\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * Class BasePaymentTraitTest
 *
 * @package CpmsFormTest\Payment
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class BasePaymentTraitTest extends TestCase
{
    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);
    }

    /**
     * @param $input
     * @param $output
     *
     * @dataProvider dataProvider
     */
    public function testSettersAndGetters($input, $output)
    {
        $mock = $this->getMockForTrait('CpmsForm\Payment\BasePaymentTrait');

        $fields = [
            'userId',
            'salesReference',
            'productReference',
            'amount',
            'redirectUri',
            'customerReference',
            'customerName',
            'costCentre',
        ];

        foreach ($fields as $field) {
            $field = ucfirst($field);
            $mock->{"set" . $field}($input);

            $this->assertEquals($output, $mock->{"get" . $field}());
        }
    }

    public function dataProvider()
    {
        return [
            ['string', 'string'],
            [1, 1],
            [true, true],
            [12312.23, 12312.23]
        ];
    }
}
