<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\ChequePaymentInterface;

/**
 * Class ChequePayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class ChequePayment implements ChequePaymentInterface
{
    use BasePaymentTrait;
}
