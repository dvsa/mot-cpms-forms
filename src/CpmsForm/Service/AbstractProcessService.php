<?php

namespace CpmsForm\Service;

use CpmsClient\Service\ApiService;
use CpmsForm\DataAwareInterface;
use CpmsForm\Form\HiddenForm;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Util;
use Interop\Container\ContainerInterface;
use Laminas\Session\Container;
use Laminas\Stdlib\Parameters;

/**
 * Class FormProcessService
 *
 * @package CpmsForm\Service
 */
abstract class AbstractProcessService
{
    const QUERY_PARAM_REDIRECT = 'redirect_uri';
    const CODE_SUCCESSFUL      = 801;
    const CODE_FAILURE         = 701;
    const FINALIZE_ENDPOINT    = '/api/gateway/%s/complete';

    /** @var  ApiService */
    protected $client;
    /** @var  string */
    protected $endPoint;
    /** @var  string */
    protected $scope;
    /** @var  string */
    protected $paymentType;
    /** @var  string */
    protected $processUri;
    /**
     * @var array Payment types, that require redirection to 3rd party gateway
     */
    protected $redirectTypes
        = [
            BasePaymentInterface::PAYMENT_TYPE_CARD,
            BasePaymentInterface::PAYMENT_TYPE_CARD_CHNP,
            BasePaymentInterface::PAYMENT_TYPE_STORED_CARD,
            BasePaymentInterface::PAYMENT_TYPE_DIRECT_DEBIT,
        ];

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
     * @param $client ApiService
     */
    public function __construct($client)
    {
        $this->setClient($client);
    }

    /**
     * @return string
     */
    public function getProcessUri()
    {
        return $this->processUri;
    }

    /**
     * @param string $processUri
     */
    public function setProcessUri($processUri)
    {
        $this->processUri = $processUri;
    }

    /**
     * Recursively remove empty values from array
     *
     * @param array $input
     *
     * @return array
     */
    public static function arrayFilterRecursive($input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = self::arrayFilterRecursive($value);
            }
        }

        return array_filter($input);
    }

    /**
     * Translate sent data to appropriate payload format for CPMS api requests
     * Possibly move to other class and use adapter pattern
     *
     * @param Parameters $parameters
     * @param            $processRedirectUri
     *
     * @return array
     */
    public function translateToPayload(Parameters $parameters, $processRedirectUri)
    {
        if ($parameters->get('cheque_date')) {
            $parameters->set('cheque_date', date('Y-m-d', strtotime($parameters->get('cheque_date'))));
        }
        if ($parameters->get('receipt_date')) {
            $parameters->set('receipt_date', date('Y-m-d', strtotime($parameters->get('receipt_date'))));
        }

        $payload = [
            'customer_reference'          => $parameters->get('customer_reference'),
            'customer_name'               => $parameters->get('customer_name'),
            'user_id'                     => $parameters->get('user_id'),
            'redirect_uri'                => (string)$processRedirectUri,
            'scope'                       => $this->getScope(),
            'cost_centre'                 => $parameters->get('cost_centre'),
            'total_amount'                => $parameters->get('total_amount'),
            'language'                    => $parameters->get('language'),
            PaymentForm::PAYMENT_DATA_KEY => $parameters->get(
                PaymentForm::PAYMENT_DATA_KEY,
                [
                    [
                        'amount'            => $parameters->get('amount'),
                        'sales_reference'   => $parameters->get('sales_reference'),
                        'product_reference' => $parameters->get('sales_reference'),
                    ]
                ]
            ),
        ];

        if ($parameters->get('language')) {
        }

        foreach ($payload[PaymentForm::PAYMENT_DATA_KEY] as &$paymentData) {
            $paymentData['amount'] = number_format((float)$paymentData['amount'], 2, '.', '');
        }

        /** @var $this  DataAwareInterface | AbstractProcessService */
        if ($this instanceof DataAwareInterface) {
            $this->setProcessUri($processRedirectUri);

            return self::arrayFilterRecursive($this->addData($payload, $parameters));
        } else {
            return self::arrayFilterRecursive($payload);
        }
    }

    /**
     * Appends result as query params to provided uri
     *
     * @param string     $baseUri
     * @param            $result
     *
     * @return mixed|string
     */
    public function prepareRedirectUrl($baseUri, $result)
    {
        if (isset($result['receipt_reference'])) {
            $params = [
                'code'              => self::CODE_SUCCESSFUL,
                'message'           => 'Success',
                'receipt_reference' => $result['receipt_reference'],
            ];
        } else {
            $params = [
                'code' => self::CODE_FAILURE,
            ];
        }

        $params      = array_merge($params, $result);
        $redirectUrl = Util::appendQueryString($baseUri, $params);

        return $redirectUrl;
    }

    /**
     * @param Parameters $parameters
     * @param string     $processUrl
     *
     * @return HiddenForm|mixed|string
     */
    public function processFormData(Parameters $parameters, $processUrl)
    {
        try {
            $payload = $this->translateToPayload($parameters, $processUrl);
            $result  = $this->callCpms($payload);

            return $this->handleApiResponse($result, $parameters);
        } catch (\Exception $exception) {
            $result = array(
                'code'    => self::CODE_FAILURE,
                'message' => 'CPMS Client - An Error occurred while processing form data'
            );
            $this->getClient()->getLogger()->err($exception->getMessage());
            $redirectUrl = $parameters->get('redirect_uri');

            return $this->prepareRedirectUrl($redirectUrl, $result);
        }
    }

    /**
     * @param $payload
     *
     * @return array|mixed
     */
    protected function callCpms($payload)
    {
        return $this->getClient()->post(
            $this->getEndPoint(), $this->getScope(), $payload
        );
    }

    /**
     * @param $result
     * @param $parameters
     *
     * @return mixed|string
     */
    public function handleApiResponse($result, $parameters = [])
    {
        if (!is_array($result)) {
            throw new \UnexpectedValueException("API Response was not a json array");
        }
        // if a receipt_reference is present, store it for future use (E.g. performing a post-gateway update to the api)
        if (!empty($result['receipt_reference'])) {
            $session = new Container('cpms_forms');
            $session->offsetSet('receiptReference', $result['receipt_reference']);
            $session->offsetSet('paymentType', $this->getPaymentType());
        }

        // response contains a gateway url, ie needs to issue a 3rd party redirect
        if (!empty($result['gateway_url'])) {
            return $result['gateway_url'];
        }

        // otherwise, prepare the supplied redirect uri by appending the result.
        $redirectUrl = $parameters->get('redirect_uri');

        return $this->prepareRedirectUrl($redirectUrl, $result);
    }

    /**
     * Payment types, that require redirection to 3rd party gateway
     *
     * @param $paymentType
     *
     * @return bool
     */
    public function requireFormPost($paymentType)
    {
        return in_array($paymentType, $this->redirectTypes);
    }

    /**
     * @return ApiService
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ApiService $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @param string $endPoint
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }
}
