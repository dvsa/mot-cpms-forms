<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\CardPaymentInterface;

/**
 * Class CardPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class CardPayment implements CardPaymentInterface
{
    use BasePaymentTrait;
}
