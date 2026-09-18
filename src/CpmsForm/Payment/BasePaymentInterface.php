<?php

namespace CpmsForm\Payment;

/**
 * Interface BasePaymentInterface
 *
 * @package CpmsForm\Payment
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
interface BasePaymentInterface
{
    public const PAYMENT_TYPE_CARD         = 'card';
    public const PAYMENT_TYPE_CARD_CHNP    = 'cardholderNotPresent';
    public const PAYMENT_TYPE_STORED_CARD  = 'storedCard';
    public const PAYMENT_TYPE_DIRECT_DEBIT = 'directDebit';
    public const PAYMENT_TYPE_CASH         = 'cash';
    public const PAYMENT_TYPE_CHEQUE       = 'cheque';
    public const PAYMENT_TYPE_POSTAL_ORDER = 'postalOrder';
    public const PAYMENT_TYPE_CHIP_PIN     = 'chipPin';

    public function getUserId();

    public function getSalesReference();

    public function getProductReference();

    public function getAmount();

    public function getRedirectUri();

    public function getCustomerReference();

    public function getCustomerName();

    public function getCostCentre();
}
