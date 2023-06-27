<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\ChipPinPaymentInterface;

/**
 * Class ChipPinPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class ChipPinPayment implements ChipPinPaymentInterface
{
    use BasePaymentTrait;
}
