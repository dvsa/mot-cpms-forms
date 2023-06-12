<?php

namespace CpmsForms;

use CpmsForm\Controller\Plugin\GetCpmsPaymentForm;
use CpmsForm\Controller\Plugin\ServiceLocatorPluginFactory;
use CpmsForm\Service\FormGeneratorService;
use CpmsForm\Service\FormGeneratorServiceFactory;
use CpmsForm\View\Helper\RenderCpmsForm;
use Laminas\View\HelperPluginManager;

return [

    'router'             => [
        'routes' => [
            'cpms_forms' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/cpms-forms',
                    'defaults' => [
                        'controller' => 'CpmsForm\Controller\Process',
                    ],
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'process_form'     => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/process-form',
                            'defaults' => [
                                'action' => 'form',
                            ],
                        ],
                    ],
                    'process_response' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/process-response',
                            'defaults' => [
                                'action' => 'response',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers'        => [
        'invokables' => [
            'CpmsForm\Controller\Process' => 'CpmsForm\Controller\ProcessController',
        ],
    ],

    'controller_plugins' => [
        'invokables' => [
            'getCpmsPaymentForm' => GetCpmsPaymentForm::CLASS_PATH,
            'viewHelperManager' => HelperPluginManager::class
        ],
        'factories' => [
            'getServiceLocator' => ServiceLocatorPluginFactory::class,
        ]
    ],

    'service_manager'    => [
        'factories'          => [
            FormGeneratorService::CLASS_PATH      => FormGeneratorServiceFactory::CLASS_PATH,
            'CpmsForm\Service\FormProcessService' => 'CpmsForm\Service\FormProcessServiceFactory',
            'viewHelperManager' => HelperPluginManager::class
        ],
        'abstract_factories' => array(
            'CpmsForm\AbstractProcessServiceFactory',
        )
    ],

    'view_manager'       => [
        'template_path_stack' => [
            'CpmsForms' => __DIR__ . '/../view',
        ],
    ],

    'view_helpers'       => array(
        'invokables' => array(
            'renderCpmsForm' => RenderCpmsForm::CLASS_PATH,
            'translate' => \Laminas\I18n\View\Helper\Translate::class
        ),
    ),
];
