<?php

namespace CpmsForm\Form;

use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;

/**
 * Class ProductField set
 *
 * @package CpmsForm\Form
 */
class ProductFieldSet extends Fieldset implements InputFilterProviderInterface
{
    const CLASS_PATH = __CLASS__;

    public function init()
    {
        parent::init(PaymentForm::PAYMENT_DATA_KEY);

        $this->add(
            [
                'name'       => 'sales_reference',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'product_reference',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'amount',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]
        );
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'product_reference' => array(
                'required' => true,
            ),
            'sales_reference'   => array(
                'required' => true,
            ),
            'amount'            => array(
                'required' => true,
            )
        );
    }
}
