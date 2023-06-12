<?php

namespace CpmsForm\Form;

/**
 * Class ChipPinPaymentForm
 *
 * @package CpmsForm\Form
 */
class ChipPinPaymentForm extends PaymentForm
{
    /** @var array */
    protected $formValidators
        = [
            'chip_pin_auth_code' => [
                [
                    'name'    => 'Laminas\Validator\Regex',
                    'options' => [
                        'pattern'  => '/^[a-zA-Z0-9]+$/',
                        'messages' => [
                            'regexNotMatch' => 'Please enter a valid name (A-Z, 0-9 allowed)',
                        ],
                    ],
                ],
            ],
            'receipt_number'     => [
                [
                    'name' => 'Laminas\Validator\Digits',
                ],
            ],
        ];
}
