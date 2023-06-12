<?php

namespace CpmsForm\Controller\Plugin;

use CpmsForm\Form\PaymentForm;
use CpmsForm\Form\ProductFieldSet;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Payment\CardPaymentInterface;
use CpmsForm\Service\FormGeneratorService;
use Laminas\Form\Element;
use Laminas\Form\Element\Collection;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class GetCpmsPaymentForm
 * @method AbstractActionController getController()
 *
 * @package CpmsForm\Controller\Plugin
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class GetCpmsPaymentForm extends AbstractPlugin
{
    const CLASS_PATH = __CLASS__;
    /**
     * @param BasePaymentInterface $payment
     * @return PaymentForm|\CpmsForm\Form\StoredCardPaymentForm|\Laminas\Http\Response
     * @throws \CpmsForm\Exception\InvalidPaymentTypeException
     * @throws \CpmsForm\Exception\NoConfigException
     */
    public function __invoke(BasePaymentInterface $payment)
    {
        /** @var \Laminas\Mvc\Controller\AbstractActionController $controller */
        $controller = $this->getController();

        /**
         * @var FormGeneratorService $formFactory
         */
        $formFactory = $controller->getServiceLocator()->get(FormGeneratorService::CLASS_PATH);

        $form        = $formFactory->build($payment);

        //this is to directly redirect to 3rd party gateway for card payments.
        //A bit dirty, but in zf2 you can't get form data before validation
        if ($payment instanceof CardPaymentInterface) {

            $paymentData = [];
            /** @var \Laminas\Form\Element\Text $element */
            foreach ($form->getElements() as $element) {
                $paymentData[$element->getName()] = $element->getValue();
            }

            if ($form->has(PaymentForm::PAYMENT_DATA_KEY)) {

                /** @var Collection $productsCollection */
                $productsCollection = $form->get(PaymentForm::PAYMENT_DATA_KEY);

                /** @var ProductFieldSet $product */
                foreach ($productsCollection->getFieldsets() as $key => $product) {
                    /** @var Element $element */
                    foreach ($product->getElements() as $elementName => $element) {
                        $paymentData[PaymentForm::PAYMENT_DATA_KEY][$key][$elementName] = $element->getValue();
                    }
                }
            }

            $form->setData($paymentData);

            if ($form->isValid()) {
                return $this->redirectForm($form);
            }
        }

        /** @var \Laminas\Http\Request $request */
        $request = $controller->getRequest();

        if ($request->isPost()) {

            $form->setData($controller->params()->fromPost());

            if ($form->isValid()) {
                return $this->redirectForm($form);
            }
        }

        return $form;
    }

    private function redirectForm(PaymentForm $form)
    {
        $data = $form->getData();
        $data = base64_encode(serialize($data));

        return $this->getController()->redirect()->toRoute(
            'cpms_forms/process_form',
            [],
            ['query' => ['data' => $data]]
        );
    }
}
