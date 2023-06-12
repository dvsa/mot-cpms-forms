<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentInterface;

/**
 * Trait PaymentTypeProviderTrait
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
trait PaymentTypeProviderTrait
{
    public function paymentTypeProvider()
    {
        $paymentTypes = array_merge(
            $this->redirectionPaymentDataProvider(),
            $this->nonRedirectionPaymentDataProvider()
        );

        return $paymentTypes;
    }

    public function redirectionPaymentDataProvider()
    {
        $types = [
            [new DirectDebitPayment(), BasePaymentInterface::PAYMENT_TYPE_DIRECT_DEBIT],
            //[new CardPayment(), BasePaymentInterface::PAYMENT_TYPE_CARD],
            [new CardholderNotPresentPayment(), BasePaymentInterface::PAYMENT_TYPE_CARD_CHNP],
            [new StoredCardPayment(), BasePaymentInterface::PAYMENT_TYPE_STORED_CARD],
        ];

        foreach ($types as &$type) {
            $type[0]
                ->setUserId(123)
                ->setSalesReference(124)
                ->setProductReference(125)
                ->setCostCentre('12345,98765')
                ->setAmount(126)
                ->setRedirectUri(127)
                ->setCustomerReference(128);
        }

        return $types;
    }

    public function nonRedirectionPaymentDataProvider()
    {
        $types = [
            [new CashPayment(), BasePaymentInterface::PAYMENT_TYPE_CASH],
            [new ChequePayment(), BasePaymentInterface::PAYMENT_TYPE_CHEQUE],
            [new ChipPinPayment(), BasePaymentInterface::PAYMENT_TYPE_CHIP_PIN],
            [new PostalOrderPayment(), BasePaymentInterface::PAYMENT_TYPE_POSTAL_ORDER],
        ];

        foreach ($types as &$type) {
            $type[0]
                ->setUserId(223)
                ->setSalesReference(224)
                ->setProductReference(225)
                ->setAmount(226.00)
                ->setRedirectUri(227)
                ->setCustomerReference(228);
        }

        return $types;
    }
}
