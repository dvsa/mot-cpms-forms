<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\DirectDebitPaymentInterface;

/**
 * Class DirectDebitPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class DirectDebitPayment implements DirectDebitPaymentInterface
{
    use BasePaymentTrait;
}
