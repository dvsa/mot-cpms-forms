<?php
namespace CpmsForm;

use CpmsForm\Exception\InvalidPaymentTypeException;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Payment\CardholderNotPresentPaymentInterface;
use CpmsForm\Payment\CardPaymentInterface;
use CpmsForm\Payment\CashPaymentInterface;
use CpmsForm\Payment\ChequePaymentInterface;
use CpmsForm\Payment\ChipPinPaymentInterface;
use CpmsForm\Payment\DirectDebitPaymentInterface;
use CpmsForm\Payment\PostalOrderPaymentInterface;
use CpmsForm\Payment\StoredCardPaymentInterface;

/**
 * Class PaymentTypeResolverTrait
 *
 * @package CpmsForm
 */
trait PaymentTypeResolverTrait
{

    /**
     * Get Payment type based on payment object
     *
     * @param BasePaymentInterface $payment
     *
     * @throws InvalidPaymentTypeException
     * @return string
     */
    protected function getPaymentType(BasePaymentInterface $payment)
    {
        if ($payment instanceof CardholderNotPresentPaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_CARD_CHNP;
        } elseif ($payment instanceof CardPaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_CARD;

        } elseif ($payment instanceof DirectDebitPaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_DIRECT_DEBIT;

        } elseif ($payment instanceof StoredCardPaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_STORED_CARD;

        } elseif ($payment instanceof ChequePaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_CHEQUE;

        } elseif ($payment instanceof CashPaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_CASH;

        } elseif ($payment instanceof PostalOrderPaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_POSTAL_ORDER;

        } elseif ($payment instanceof ChipPinPaymentInterface) {

            return BasePaymentInterface::PAYMENT_TYPE_CHIP_PIN;
        }

        throw new InvalidPaymentTypeException(
            sprintf('Object of %s class does not implement valid payment interface', get_class($payment))
        );
    }
}
