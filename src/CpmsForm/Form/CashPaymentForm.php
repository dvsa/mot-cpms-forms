<?php

namespace CpmsForm\Form;

use Laminas\Validator\Date;

/**
 * Class CardPaymentForm
 *
 * @package CpmsForm\Form
 */
class CashPaymentForm extends PaymentForm
{
    const DATE_FORMAT = 'd-M-Y';

    /** @var array */
    protected $formValidators
        = [
            'receipt_date' => [
                [
                    'name'    => 'Laminas\Validator\Date',
                    'options' => [
                        'format'   => self::DATE_FORMAT,
                        'messages' => [
                            Date::FALSEFORMAT  => 'Please enter a valid date, like 01-JAN-2015',
                            Date::INVALID_DATE => 'Please enter a valid date, like 01-JAN-2015',
                        ],
                    ]
                ],
            ],
        ];
}
