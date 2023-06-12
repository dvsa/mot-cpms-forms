<?php

namespace CpmsForm\Payment;

/**
 * Interface BasePaymentInterface
 *
 * @package CpmsForm\Payment
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
interface
BasePaymentInterface
{
    const PAYMENT_TYPE_CARD         = 'card';
    const PAYMENT_TYPE_CARD_CHNP    = 'cardholderNotPresent';
    const PAYMENT_TYPE_STORED_CARD  = 'storedCard';
    const PAYMENT_TYPE_DIRECT_DEBIT = 'directDebit';
    const PAYMENT_TYPE_CASH         = 'cash';
    const PAYMENT_TYPE_CHEQUE       = 'cheque';
    const PAYMENT_TYPE_POSTAL_ORDER = 'postalOrder';
    const PAYMENT_TYPE_CHIP_PIN     = 'chipPin';

    public function getUserId();

    public function getSalesReference();

    public function getProductReference();

    public function getAmount();

    public function getRedirectUri();

    public function getCustomerReference();

    public function getCustomerName();

    public function getCostCentre();
}
