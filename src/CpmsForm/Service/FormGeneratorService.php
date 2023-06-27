<?php

namespace CpmsForm\Service;

use CpmsClient\Service\ApiService;
use CpmsForm\Exception\NoConfigException;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Form\StoredCardPaymentForm;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\PaymentTypeResolverTrait;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill;

/**
 * Class FormFactoryService
 *
 * @package CpmsForm\Service
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class FormGeneratorService
{
    use PaymentTypeResolverTrait;

    const CLASS_PATH                     = __CLASS__;
    const ROUTE_PARAM_CUSTOMER_REFERENCE = 'customer';
    const ENDPOINT_STORED_CARD_LIST      = 'stored_card_list';
    const FORM_DEFAULT                   = PaymentForm::CLASS_NAME;
    const STORED_CARD_STATUS             = 'status';

    /**
     * @var array
     */
    private $config;

    /**
     * @var ApiService
     */
    private $restClint;

    private $serviceLocator;

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param array      $formConfig
     * @param ApiService $restClient
     */
    public function __construct(array $formConfig, ApiService $restClient)
    {
        $this->config    = $formConfig;
        $this->restClint = $restClient;
    }

    /**
     * Build form based on payment object and populate it with object data
     * Not every schema is allowed to use each type of payment.
     *
     * @param BasePaymentInterface $payment
     * @return PaymentForm|StoredCardPaymentForm
     * @throws NoConfigException
     * @throws \CpmsForm\Exception\InvalidPaymentTypeException
     */
    public function build(BasePaymentInterface $payment)
    {
        $paymentType = $this->getPaymentType($payment);

        if (!isset($this->config['payment_types'][$paymentType])) {
            throw new NoConfigException(sprintf('No configuration provided for \'%s\' payment type', $paymentType));
        }

        $form = $this->getForm($paymentType);

        foreach ($this->config['payment_types'][$paymentType]['form_elements'] as $element) {
            $form->add($element);
        }

        $form->populateRequiredFields($payment);
        $form->prepare();

        return $form;
    }

    /**
     * Get form object based on payment type
     *
     * @param $paymentType
     *
     * @return PaymentForm|StoredCardPaymentForm
     */
    private function getForm($paymentType)
    {
        if (isset($this->config['payment_types'][$paymentType]['form'])) {
            $formAlias = $this->config['payment_types'][$paymentType]['form'];
        } else {
            $formAlias = $paymentType . 'Form';
        }

        /** @var FormElementManagerV3Polyfill $formElementManager */
        $formElementManager = $this->getServiceLocator()->get('FormElementManager');

        /** @var PaymentForm $form */
        $form = $formElementManager->get($formAlias);
        $form->setPaymentType($paymentType)
            ->setService($this);

        return $form;
    }

    /**
     * Get Stored Cards Collection for given customer
     *
     * @param string $cardStatus
     *
     * @return array
     */
    public function getStoredCards($cardStatus)
    {
        $endpoint = $this->config['endpoints'][self::ENDPOINT_STORED_CARD_LIST];
        $endpoint = str_replace(':' . self::STORED_CARD_STATUS, $cardStatus, $endpoint);
        $list     = $this->restClint->get($endpoint, ApiService::SCOPE_STORED_CARD);

        return isset($list['items']) ? $list['items'] : array();
    }
}
