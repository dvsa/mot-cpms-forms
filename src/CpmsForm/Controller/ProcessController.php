<?php

namespace CpmsForm\Controller;

use CpmsClient\Service\ApiService;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Service\AbstractProcessService;
use CpmsForm\Service\CardPaymentCompleteTrait;
use CpmsForm\Service\Process;
use CpmsForm\Service\Process\Card;
use CpmsForm\Util;
use Laminas\Form\Form;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\Stdlib\Parameters;
use Laminas\View\Model\ViewModel;

/**
 * Class ProcessController
 * @method ApiService getCpmsRestClient()
 *
 * @package CpmsForm\Controller
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class ProcessController extends AbstractActionController
{
    /**
     * Handle data sent from schema forms and make payment
     *
     * @return \Laminas\Http\Response|ViewModel
     * @throws \Exception
     */
    public function formAction()
    {
        $data = $this->params()->fromQuery('data');
        $data = unserialize(base64_decode($data));

        if (empty($data) || !is_array($data)) {
            return $this->badDataAction();
        }

        $parameters        = new Parameters($data);
        $paymentType       = $parameters->get(PaymentForm::FIELD_NAME_PAYMENT_TYPE);
        $schemeRedirectUrl = $parameters->get(AbstractProcessService::QUERY_PARAM_REDIRECT);
        $processService    = $this->getProcessService($paymentType);
        $processUrl        = $this->getProcessUrl($schemeRedirectUrl);

        //Do the heavy lifting
        $return = $processService->processFormData($parameters, $processUrl);

        if ($return instanceof Form) {
            return new ViewModel(array('form' => $return));
        } else {
            return $this->redirect()->toUrl($return);
        }
    }

    /**
     * Handle response from CPMS.
     * Currently it just redirects to url provided by scheme unless CARD payment type is detected
     *
     * @return array|\Laminas\Http\Response
     */
    public function responseAction()
    {
        // receipt reference is set when processing the response from the API before gateway redirection.

        $session          = new Container('cpms_forms');
        $receiptReference = $session->offsetGet('receiptReference');
        $type             = $session->offsetGet('paymentType');
        $params           = (array)$this->params()->fromQuery();

        $cardScopes = [
            BasePaymentInterface::PAYMENT_TYPE_CARD        => ApiService::SCOPE_CARD,
            BasePaymentInterface::PAYMENT_TYPE_CARD_CHNP   => ApiService::SCOPE_CNP,
            BasePaymentInterface::PAYMENT_TYPE_STORED_CARD => ApiService::SCOPE_STORED_CARD,
        ];

        if (array_key_exists($type, $cardScopes)) {
            /** @var Card | CardPaymentCompleteTrait $processService */
            $processService = $this->getProcessService($type);
            $result         = (array)$processService->finalisePayment($receiptReference, $cardScopes[$type]);
            $params         = array_merge($params, $result);
        }

        if (!isset($params[AbstractProcessService::QUERY_PARAM_REDIRECT])) {
            return $this->badDataAction();
        }

        $redirectUrl = $params[AbstractProcessService::QUERY_PARAM_REDIRECT];
        unset($params[AbstractProcessService::QUERY_PARAM_REDIRECT]);

        $redirectUrl = Util::appendQueryString(urldecode($redirectUrl), $params);

        return $this->redirect()->toUrl($redirectUrl);
    }

    /**
     * @param $type
     *
     * @return AbstractProcessService
     */
    private function getProcessService($type)
    {
        $serviceName = 'cpms_forms\process\\' . $type;
        return $this->getServiceLocator()->get($serviceName);
    }

    /**
     * Get the URl to go to after processing has complete
     *
     * @param $redirectUri
     *
     * @return string
     */
    private function getProcessUrl($redirectUri)
    {
        return $this->url()->fromRoute(
            'cpms_forms/process_response',
            array(),
            array(
                'query'           => array(
                    AbstractProcessService::QUERY_PARAM_REDIRECT => urlencode($redirectUri)
                ),
                'force_canonical' => true,
            )
        );
    }

    public function badDataAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_401);

        $view = new ViewModel(
            array(
                'message' => 'Payment data not found in request'
            )
        );
        $view->setTemplate('cpms-form/process/bad-data.phtml');

        return $view;
    }
}
