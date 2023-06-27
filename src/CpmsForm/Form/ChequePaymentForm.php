<?php

namespace CpmsForm\Form;

use CpmsCommon\Validator\DateRange;
use Laminas\Validator\Date;

/**
 * Class ChequePaymentForm
 *
 * @package CpmsForm\Form
 */
class ChequePaymentForm extends PaymentForm
{
    const DATE_FORMAT = 'd-M-Y';
    /** @var array */
    protected $formValidators
        = [
            'cheque_number'   => [
                [
                    'name' => 'Laminas\Validator\Digits',
                ],
            ],
            'cheque_date'     => [
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
                [
                    'name'    => 'CpmsCommon\Validator\ChequeDate',
                    'options' => [
                        'format'   => self::DATE_FORMAT,
                        'messages' => [
                            DateRange::NOT_AFTER            => 'Cheques older than 6 months cannot be processed.',
                            DateRange::NOT_AFTER_INCLUSIVE  => 'Cheques older than 6 months cannot be processed.',
                            DateRange::NOT_BEFORE           => 'Future-dated cheques cannot be processed.',
                            DateRange::NOT_BEFORE_INCLUSIVE => 'Future-dated cheques cannot be processed.',
                        ]
                    ]
                ]
            ],
            'rule_start_date' => [
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
            'payer_details'   => [
                [
                    'name'    => 'Laminas\Validator\StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 50
                    ]
                ],
                [
                    'name'    => 'Laminas\Validator\Regex',
                    'options' => [
                        'pattern'  => '/^[a-zA-Z\-\s]+$/',
                        'messages' => [
                            'regexNotMatch' => 'Please enter a valid name (A-Z, - & space allowed)',
                        ],
                    ],
                ],
            ],
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
