<?php


namespace CpmsForm\Service;

/**
 * Interface CardPaymentCompleteInterface
 *
 * @package CpmsForm\Service
 */
interface CardPaymentCompleteInterface
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
    public function finalisePayment($receiptReference, $scope);
}
