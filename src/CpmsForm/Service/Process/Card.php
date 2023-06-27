<?php
namespace CpmsForm\Service\Process;

use CpmsForm\Service\AbstractProcessService;
use CpmsForm\Service\CardPaymentCompleteTrait;

/**
 * Class CardService
 *
 * @package CpmsForm\Service\Process
 */
class Card extends AbstractProcessService
{
    use CardPaymentCompleteTrait;
}
