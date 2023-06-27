<?php
namespace CpmsForm\Service\Process;

use CpmsForm\DataAwareInterface;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Service\AbstractProcessService;
use Laminas\Stdlib\Parameters;

/**
 * Class ChequeService
 *
 * @package CpmsForm\Service\Process
 */
class Cheque extends AbstractProcessService implements DataAwareInterface
{
    const PAYMENT_REFERENCE_KEY = 'payment_reference';

    /** @var array */
    private $referenceFields
        = [
            'batch_number',
            'cheque_number',
            'slip_number',
            'cheque_date',
            'receipt_date',
            'rule_start_date',
            'postal_order_number',
            'payer_details',
        ];

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
            foreach ($this->referenceFields as $field) {
                $fieldValue = $parameters->get($field);
                if (!empty($fieldValue)) {
                    $payload[PaymentForm::PAYMENT_DATA_KEY][$key][self::PAYMENT_REFERENCE_KEY][$field] = $fieldValue;

                }
            }
        }

        return $payload;
    }
}
