# CPMS Forms

## Introduction

A module to provide re-usable HTML Forms that can be used by scheme to process payments.

## Installation

### Main Setup

#### With composer

The recommended way to install is through [Composer](https://getcomposer.org/).

```
composer require dvsa/mot-cpms-forms
```

#### Post installation

1. Enable it in your application.config.php file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'CpmsForms',
        ),
        // ...
    );
    ```
2. Copy configuration file to your autoload config folder (optional)

    ```bash
    cp vendor/dvsa/mot-cpms-forms/config/cpms-forms.global.php.dist config/autoload/cpms-forms.global.php
    ```

## Usage

For payment form generation please use controller plugin:

```php
$form = $this->getCpmsPaymentForm($payment);
```
The plugin takes one argument, which must be an object that implements one of the following interfaces:
* CpmsForms\Payment\CardPaymentInterface
* CpmsForms\Payment\StoredCardPaymentInterface
* CpmsForms\Payment\DirectDebitPaymentInterface
* CpmsForms\Payment\CashPaymentInterface
* CpmsForms\Payment\ChequePaymentInterface
* CpmsForms\Payment\ChipPinPaymentInterface
* CpmsForms\Payment\PostalOrderPaymentInterface

As it might be obvious, payment interface determines required data, that needs to be provided by scheme and tells Form Factory Service to build specific for that interface form.

Each payment type always requires common information (amount, user id, etc.), so it's convenient to use ``CpmsForms\Payment\BasePaymentTrait`` trait in your payment classes.

The cpms-forms plugin validates post data and redirects to cpms-forms controller for processing. In regard to redirection, the proper usage should be:

```php
$form = $this->getCpmsPaymentForm($payment);
if ($form instanceof \Laminas\Http\Response) {
    return $form;
}
```

The form should be passed to ViewModel and be rendered via view cpms-forms helper plugin:
```
<?php echo $this->renderCpmsForm($form); ?>
```

## Configuration

### View scripts
If custom view script is needed to render payment form, please add this in your configuration file:

```
<?php
return [
    'cpms_forms' => [
        'partials' => [
            'form' => 'custom-path/custom-script.phtml',
        ],
    ],
];
```
It's also possible to provide view script for specific payment type:
```
return [
    'cpms_forms' => [
        'partials' => [
            'form' => 'custom-path/custom-script.phtml',
            'stored_card' => 'custom-path/stored-card-script.phtml',
        ],
    ],
];
```

### Form customization
Custom form class can be provided for specific payment type. This class must extends ``CpmsForms\Form\PaymentForm`` class to be considered as a valid.
See example:
```
return [
    'cpms_forms' => [
        'payment_types' => [
            'direct_debit' => [
                'form' => 'Scheme\Form\CustomDirectDebitPaymentForm'
            ],
        ],
    ],
];
```
Form elements can be also customized. The following example sets html class of ``mandate_collection_day`` element and adds e-mail field:
```
return [
    'cpms_forms' => [
        'payment_types' => [
            'direct_debit' => [
                'form_elements' => [
                    'mandate_collection_day' => [
                        'attributes' => [
                            'class' => 'form-control olcs-form-element',
                        ],
                    ],
                    [
                        'name' => 'email',
                        'attributes' => [
                            'type'  => 'email',
                            'class' => 'form-control',
                        ],
                        'options' => [
                            'label' => 'Customer E-mail',
                            'required' => true,
                        ],
                    ]
                ],
            ],
        ],
    ],
];
```
For more options, payment types and form elements please see ``config/module.config.php``

## Contributing

Please refer to our [Contribution Guide](/CONTRIBUTING.md).

TO DO
------------
* Check if client is authorized to use a payment type
* Implement event manager for pre and post payment actions
* Integrate CPMS Miscellaneous Payments Module
