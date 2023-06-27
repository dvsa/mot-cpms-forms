<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\StoredCardPaymentInterface;

/**
 * Class StoredCardPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class StoredCardPayment implements StoredCardPaymentInterface
{
    use BasePaymentTrait;
}
