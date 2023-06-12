<?php

use CpmsClientTest\MockLogger;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Form\ProductFieldSet;
use Laminas\Form\Element\Collection;

return array(
    'cpms_api'      => array(
        'home_domain' => 'http://payment-app.psdv-ap01.ps.npm',
        'rest_client' => array(
            'adapter' => 'Laminas\Http\Client\Adapter\Test',
            'options' => array(
                'domain'             => 'http://payment-app.psdv-ap01.ps.npm',
                'client_id'          => 'U6Se"<V]Fpw3r5+',
                'client_secret'      => 'motsecret',
                'user_id'            => 56098,
                'customer_reference' => 'AE-DM123',
            ),
        ),
    ),
    'form_elements' => array(
        'initializers' => array(
            'setProductFieldSet' => \CpmsForm\Initializers\CpmsFormsInitializer::class
        ),
    ),
    'service_manager'   => array(
        'factories' => array(
            'cpms\client\logger' => function () {
                return new MockLogger();
            },
        ),
    ),
    'logger'        => [
        'location' => 'data/logs'
    ],
    'view_helpers' => array(
        'invokables' => array(
            'lowercase' => 'MyModule\View\Helper\LowerCase',
            'uppercase' => 'MyModule\View\Helper\UpperCase',
        ),
        'factories' => [
            'url' => \Laminas\View\Helper\Url::class
        ]
    ),
);
