<?php
namespace CpmsForm\Service\Process;

use CpmsForm\DataAwareInterface;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Service\AbstractProcessService;
use Laminas\Stdlib\Parameters;

/**
 * Class DirectDebitService
 *
 * @package CpmsForm\Service\Process
 */
class DirectDebit extends AbstractProcessService implements DataAwareInterface
{
    /**
     * @param array      $payload
     * @param Parameters $parameters
     *
     * @return array|mixed
     */
    public function addData(array $payload, Parameters $parameters)
    {
        unset ($payload[PaymentForm::PAYMENT_DATA_KEY]);
        $payload = array_merge(
            $payload,
            [
                'collection_day' => $parameters->get('mandate_collection_day'),
                'redirect_uri'   => $this->getProcessUri(),
            ]
        );

        return $payload;
    }
}
