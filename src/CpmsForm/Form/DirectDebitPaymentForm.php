<?php

namespace CpmsForm\Form;

/**
 * Class CardPaymentForm
 *
 * @package CpmsForm\Form
 */
class DirectDebitPaymentForm extends PaymentForm
{
    /**
     * @var array
     */
    public static $allowedDays
        = [
            '05' => '05',
            '20' => '20',
        ];

    /** @var array */

    protected $formValidators
        = [
            'mandate_collection_day' => [
                [
                    'name' => 'Laminas\Validator\Digits',
                ],
                [
                    'name'    => 'Laminas\Validator\InArray',
                    'options' => [
                        'haystack' => [
                            '05' => '05',
                            '20' => '20',
                        ]
                    ]
                ]
            ]
        ];
}
