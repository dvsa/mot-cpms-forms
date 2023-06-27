<?php
if (!defined('APPLICATION_ENV')) {
    if (isset($_SERVER['APPLICATION_ENV'])) {
        $env = $_SERVER['APPLICATION_ENV'];
    } elseif (!$env = getenv('APPLICATION_ENV')) {
        $env = 'production';
    }

    define('APPLICATION_ENV', $env);
}

return array(
    'modules'                 => array(
        'Laminas\Cache',
        'Laminas\I18n',
        'Laminas\Session',
        'Laminas\Paginator',
        'Laminas\Form',
        'Laminas\InputFilter',
        'Laminas\Hydrator',
        'Laminas\Filter',
        'Laminas\Log',
        'Laminas\Router',
        'Laminas\Validator',
        'CpmsClient',
        'CpmsForm',
        'DoctrineModule',
        'DoctrineORMModule',
        'Laminas\ApiTools\Versioning',
        'Laminas\Cache\Storage\Adapter\Memory',
        'Laminas\Cache\Storage\Adapter\Filesystem',
        'Laminas\Cache\Storage\Adapter\Apcu',
    ),
    'module_listener_options' => array(
        'module_paths'      => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,' . APPLICATION_ENV . ',local}.php',
        ),
    )
);
