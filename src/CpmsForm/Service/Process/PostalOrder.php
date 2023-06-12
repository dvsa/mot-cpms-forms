<?php
namespace CpmsForm\Service\Process;

use CpmsForm\DataAwareInterface;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Service\AbstractProcessService;
use Laminas\Stdlib\Parameters;

/**
 * Class PostalOrderService
 *
 * @package CpmsForm\Service\Process
 */
class PostalOrder extends AbstractProcessService implements DataAwareInterface
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
                'postal_order_number' => $parameters->get('postal_order_number'),
            );
        }

        return $payload;
    }
}
