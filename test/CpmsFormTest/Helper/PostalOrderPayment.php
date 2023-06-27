<?php

namespace CpmsFormTest\Helper;

use CpmsForm\Payment\BasePaymentTrait;
use CpmsForm\Payment\PostalOrderPaymentInterface;

/**
 * Class PostalOrderPayment
 *
 * @package CpmsFormTest\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class PostalOrderPayment implements PostalOrderPaymentInterface
{
    use BasePaymentTrait;
}
