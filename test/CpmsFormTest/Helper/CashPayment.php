<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\CashPaymentInterface;

/**
 * Class CashPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class CashPayment implements CashPaymentInterface
{
    use BasePaymentTrait;
}
