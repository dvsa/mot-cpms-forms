<?php


namespace CpmsForm\Service;

use CpmsClient\Service\ApiService;

/**
 * Class CardPaymentCompleteTrait
 *@method ApiService getClient()
 *
 * @package CpmsForm\Service
 */
trait CardPaymentCompleteTrait
{

    /**
     * Finalise Card
     *
     *
     * @param $receiptReference
     * @param $scope
     *
     * @return array|mixed
     */
    public function finalisePayment($receiptReference, $scope)
    {
        $endPoint = sprintf(AbstractProcessService::FINALIZE_ENDPOINT, $receiptReference);

        return $this->getClient()->put($endPoint, $scope, []);
    }
}
