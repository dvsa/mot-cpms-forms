<?php
namespace CpmsForm\Service\Process;

use CpmsForm\DataAwareInterface;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Service\AbstractProcessService;
use Laminas\Stdlib\Parameters;

/**
 * Class ChipPinService
 *
 * @package CpmsForm\Service\Process
 */
class ChipPin extends AbstractProcessService implements DataAwareInterface
{
    /**
     * @param array      $payload
     * @param Parameters $parameters
     *
     * @return array|mixed
     */
    public function addData(array $payload, Parameters $parameters)
    {
        foreach (array_keys($payload[PaymentForm::PAYMENT_DATA_KEY]) as $key) {
            $payload[PaymentForm::PAYMENT_DATA_KEY][$key]['payment_reference'] = array(
                'receipt_number'     => $parameters->get('receipt_number'),
                'chip_pin_auth_code' => $parameters->get('chip_pin_auth_code'),
            );
        }

        return $payload;
    }
}
