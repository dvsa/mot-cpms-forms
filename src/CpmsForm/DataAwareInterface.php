<?php
namespace CpmsForm;

use Laminas\Stdlib\Parameters;

/**
 * Interface DataAwareInterface
 *
 * @package CpmsForm
 */
interface DataAwareInterface
{
    /**
     * @param    array   $payload
     * @param Parameters $parameter
     * @param array      $payload
     * @param Parameters $parameter
     *
     * @return mixed
     */
    public function addData(array $payload, Parameters $parameter);
}
