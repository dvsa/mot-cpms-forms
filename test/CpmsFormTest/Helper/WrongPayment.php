<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Payment\BasePaymentTrait;

/**
 * Class WrongPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class WrongPayment implements BasePaymentInterface
{
    use BasePaymentTrait;
}
