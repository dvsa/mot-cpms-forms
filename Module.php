<?php

namespace CpmsForm;

use CpmsForm\Form\AbstractBaseForm;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Form\ProductFieldSet;
use CpmsForm\Initializers\CpmsFormsInitializer;
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Csrf;

/**
 * Class Module
 *
 * @package CpmsForms
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class Module
{
    public function getConfig()
    {
        $formConfig   = include __DIR__ . '/config/form.config.php';
        $moduleConfig = include __DIR__ . '/config/module.config.php';
        $config       = array_merge($formConfig, $moduleConfig);

        return $config;
    }

    public function getAutoloaderConfig()
    {
        return [
            'Laminas\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * CPMS Form Elements Configuration for the different payment types
     *
     * @return array
     */
    public function getFormElementConfig()
    {
        return array(
            'invokables'   => array(
                'cardForm'                 => 'CpmsForm\Form\CardPaymentForm',
                'cardholderNotPresentForm' => 'CpmsForm\Form\CardholderNotPresentPaymentForm',
                'cashForm'                 => 'CpmsForm\Form\CashPaymentForm',
                'chequeForm'               => 'CpmsForm\Form\ChequePaymentForm',
                'chipPinForm'              => 'CpmsForm\Form\ChipPinPaymentForm',
                'directDebitForm'          => 'CpmsForm\Form\DirectDebitPaymentForm',
                'postalOrderForm'          => 'CpmsForm\Form\PostalOrderPaymentForm',
                'storedCardForm'           => 'CpmsForm\Form\StoredCardPaymentForm',
                'hiddenForm'               => 'CpmsForm\Form\HiddenForm',
                'productFieldSet'          => 'CpmsForm\Form\ProductFieldSet'
            ),
            'initializers' => array(
                'setProductFieldSet' => CpmsFormsInitializer::class
            ),
        );
    }
}
