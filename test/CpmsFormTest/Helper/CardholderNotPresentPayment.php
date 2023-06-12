<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\CardholderNotPresentPaymentInterface;

/**
 * Class CardholderNotPresentPayment
 *
 * @package CpmsFormTest\Helper
 */
class CardholderNotPresentPayment implements CardholderNotPresentPaymentInterface
{
    use BasePaymentTrait;
}
