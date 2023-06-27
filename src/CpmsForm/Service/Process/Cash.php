<?php
namespace CpmsForm\Service\Process;

use CpmsForm\DataAwareInterface;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Service\AbstractProcessService;
use Laminas\Stdlib\Parameters;

/**
 * Class CashService
 *
 * @package CpmsForm\Service\Process
 */
class Cash extends AbstractProcessService implements DataAwareInterface
{
    /**
     * @param array      $payload
     * @param Parameters $parameters
     *
     * @return array|mixed
     */
    public function addData(array $payload, Parameters $parameters)
    {
        $keys = array_keys($payload[PaymentForm::PAYMENT_DATA_KEY]);
        foreach ($keys as $key) {
            $payload[PaymentForm::PAYMENT_DATA_KEY][$key]['payment_reference'] = array(
                'batch_number' => $parameters->get('batch_number'),
                'slip_number'  => $parameters->get('slip_number'),
                'receipt_date' => $parameters->get('receipt_date'),
            );
        }

        return $payload;
    }
}
