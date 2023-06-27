<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\Product\MultiProductPayment;
use CpmsForm\Payment\StoredCardPaymentInterface;

/**
 * Class StoredCardMultiPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class StoredCardMultiPayment extends MultiProductPayment implements StoredCardPaymentInterface
{
    public function getCustomerReference()
    {
        return 'kwikfit';
    }

    public function getCustomerName()
    {
        return 'kwikfit';
    }
}
