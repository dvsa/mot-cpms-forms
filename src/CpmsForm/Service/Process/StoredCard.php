<?php
namespace CpmsForm\Service\Process;

use CpmsForm\DataAwareInterface;
use CpmsForm\Form\StoredCardPaymentForm;
use CpmsForm\Service\AbstractProcessService;
use CpmsForm\Service\CardPaymentCompleteInterface;
use CpmsForm\Service\CardPaymentCompleteTrait;
use Laminas\Stdlib\Parameters;

/**
 * Class StoredCardService
 *
 * @package CpmsForm\Service\Process
 */
class StoredCard extends AbstractProcessService implements DataAwareInterface, CardPaymentCompleteInterface
{
    use CardPaymentCompleteTrait;

    /**
     * @param array      $payload
     * @param Parameters $parameters
     *
     * @return array|mixed
     */
    public function addData(array $payload, Parameters $parameters)
    {
        $payload['card_reference'] = $parameters->get(StoredCardPaymentForm::FIELD_NAME_STORED_CARD);

        return $payload;
    }

    /**
     * @param $payload
     *
     * @return array|mixed
     */
    protected function callCpms($payload)
    {
        $endPoint = sprintf($this->getEndPoint(), $payload['card_reference']);
        unset($payload['card_reference']);

        return $this->getClient()->post($endPoint, $this->getScope(), $payload);
    }
}
