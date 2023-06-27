<?php

namespace CpmsForm\Payment\Product;

use Laminas\Stdlib\AbstractOptions;

/**
 * Class Product
 *
 * @package CpmsForm\Payment\Product
 */
class Product extends AbstractOptions
{
    protected $amount;

    protected $productReference;

    protected $salesReference;

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getProductReference()
    {
        return $this->productReference;
    }

    /**
     * @param mixed $productReference
     */
    public function setProductReference($productReference)
    {
        $this->productReference = $productReference;
    }

    /**
     * @return mixed
     */
    public function getSalesReference()
    {
        return $this->salesReference;
    }

    /**
     * @param mixed $salesReference
     */
    public function setSalesReference($salesReference)
    {
        $this->salesReference = $salesReference;
    }
}
