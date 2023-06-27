<?php

use CpmsClient\Service\ApiService;
use CpmsForm\Form\DirectDebitPaymentForm;
use CpmsForm\Form\StoredCardPaymentForm;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Service\FormGeneratorService;

$languageElement = [
    'type'       => 'Laminas\Form\Element\Select',
    'name'       => 'language',
    'attributes' => [
        'class' => 'form-control',
    ],
    'options'    => [
        'label'         => 'Language',
        'value_options' => [
            'en' => 'English',
            'cy' => 'Welsh',
        ],
        'required'      => false,
    ],
];

return [
    'cpms_forms' => [

        'endpoints'     => [
            FormGeneratorService::ENDPOINT_STORED_CARD_LIST
            => '/api/stored-card?filters[storedCardStatusCode]=:status',
        ],

        'partials'      => [
            'form'        => 'partial/cpms-form.phtml',
            'stored_card' => 'partial/cpms-form.phtml',
        ],

        'payment_types' => [
            BasePaymentInterface::PAYMENT_TYPE_CARD         => [
                'scope'         => ApiService::SCOPE_CARD,
                'endpoint'      => '/api/payment/card',
                'form_elements' => [
                    'language' => $languageElement,

                ],
            ],

            BasePaymentInterface::PAYMENT_TYPE_CARD_CHNP    => [
                'scope'         => ApiService::SCOPE_CNP,
//                'form'          => 'CardholderNotPresentPaymentForm',
                'endpoint'      => '/api/payment/cardholder-not-present',
                'form_elements' => [],
            ],

            BasePaymentInterface::PAYMENT_TYPE_DIRECT_DEBIT => [
                'scope'         => ApiService::SCOPE_DIRECT_DEBIT,
                'endpoint'      => '/api/mandate',
                'form_elements' => [
                    'mandate_collection_day' => [
                        'type'       => 'Laminas\Form\Element\Select',
                        'name'       => 'mandate_collection_day',
                        'attributes' => [
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'         => 'Collection Day',
                            'value_options' => DirectDebitPaymentForm::$allowedDays,
                            'required'      => true,
                        ],
                    ],
                    'confirmation'           => [
                        'type'    => 'Laminas\Form\Element\Checkbox',
                        'name'    => 'confirmation',
                        'options' => [
                            'label'              => 'Confirmation of eligibility',
                            'use_hidden_element' => false,
                            'required'           => true,
                        ]
                    ],
                ],
            ],

            BasePaymentInterface::PAYMENT_TYPE_STORED_CARD  => [
                'scope'         => ApiService::SCOPE_STORED_CARD,
                'endpoint'      => '/api/payment/stored-card/%s',
                'form'          => 'storedCardForm',
                'form_elements' => [
                    StoredCardPaymentForm::FIELD_NAME_STORED_CARD => [
                        'type'       => 'Laminas\Form\Element\Select',
                        'name'       => StoredCardPaymentForm::FIELD_NAME_STORED_CARD,
                        'attributes' => [
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'        => 'Select stored card',
                            'empty_option' => 'Please select',
                            'format'       => '{card_scheme} - {mask_pan}',
                        ],
                    ],
                ],
            ],

            BasePaymentInterface::PAYMENT_TYPE_CHEQUE       => [
                'scope'         => ApiService::SCOPE_CHEQUE,
                'endpoint'      => '/api/payment/cheque',
                'form_elements' => [
                    'cheque_number'   => [
                        'name'       => 'cheque_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Cheque number',
                            'required' => true,
                        ],
                    ],
                    'cheque_date'     => [
                        'name'       => 'cheque_date',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Cheque Date',
                            'required' => true,
                        ],
                    ],
                    'rule_start_date' => [
                        'name'              => 'rule_start_date',
                        'attributes'        => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'           => [
                            'label'    => 'Rule Start Date',
                            'required' => false
                        ],
                    ],
                    'payer_details'   => [
                        'name'       => 'payer_details',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Payer name',
                            'required' => true,
                        ],
                    ],
                    'slip_number'     => [
                        'name'       => 'slip_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label' => 'Paying in slip number',
                        ],
                    ],
                    'batch_number'    => [
                        'name'       => 'batch_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label' => 'Batch number',
                        ],
                    ],
                    'receipt_date'     => [
                        'name'       => 'receipt_date',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Receipt Date',
                            'required' => false,
                        ],
                    ],
                ],
            ],

            BasePaymentInterface::PAYMENT_TYPE_CASH         => [
                'scope'         => ApiService::SCOPE_CASH,
                'endpoint'      => '/api/payment/cash',
                'form_elements' => [
                    'slip_number'  => [
                        'name'       => 'slip_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Paying in slip reference',
                            'required' => true,
                        ],
                    ],
                    'batch_number' => [
                        'name'       => 'batch_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Batch number',
                            'required' => true,
                        ],
                    ],
                    'receipt_date'     => [
                        'name'       => 'receipt_date',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Receipt Date',
                            'required' => false,
                        ],
                    ],
                ],
            ],

            BasePaymentInterface::PAYMENT_TYPE_POSTAL_ORDER => [
                'scope'         => ApiService::SCOPE_POSTAL_ORDER,
                'endpoint'      => '/api/payment/postal-order',
                'form_elements' => [
                    'postal_order_number' => [
                        'name'       => 'postal_order_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Postal order serial number(s)',
                            'required' => true,
                        ],
                    ],
                    'slip_number'  => [
                        'name'       => 'slip_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Paying in slip reference',
                            'required' => true,
                        ],
                    ],
                    'batch_number' => [
                        'name'       => 'batch_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Batch number',
                            'required' => true,
                        ],
                    ],
                    'receipt_date'     => [
                        'name'       => 'receipt_date',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Receipt Date',
                            'required' => false,
                        ],
                    ],
                ],
            ],

            BasePaymentInterface::PAYMENT_TYPE_CHIP_PIN     => [
                'scope'         => ApiService::SCOPE_CHIP_PIN,
                'endpoint'      => '/api/payment/chip-and-pin',
                'form_elements' => [
                    'chip_pin_auth_code' => [
                        'name'       => 'chip_pin_auth_code',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Chip & PIN authorization code',
                            'required' => true,
                        ],
                    ],
                    'receipt_number'     => [
                        'name'       => 'receipt_number',
                        'attributes' => [
                            'type'  => 'text',
                            'class' => 'form-control',
                        ],
                        'options'    => [
                            'label'    => 'Payment receipt number',
                            'required' => true,
                        ],
                    ],
                ],
            ],
        ],
    ]
];
