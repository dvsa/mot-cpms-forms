<?php

namespace CpmsForm\Payment\Product;

/**
 * Class MultiProductPayment
 *
 * @package CpmsForm\Payment\Product
 */
abstract class MultiProductPayment
{
    /** @var array */
    protected $products = [];

    /**
     * @var string
     */
    protected $customerName;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $customerReference;


    /** @var  string */
    protected $costCentre;

    /**
     * @return string
     */
    public function getCostCentre()
    {
        return $this->costCentre;
    }

    /**
     * @param $costCentre
     *
     * @return $this
     */
    public function setCostCentre($costCentre)
    {
            $this->costCentre = $costCentre;

            return $this;
    }
    
    public function add(Product $product)
    {
        $this->products[] = $product;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getAmount()
    {
        $amount = 0;

        /** @var Product $product */
        foreach ($this->products as $product) {
            $amount += $product->getAmount();
        }

        return $amount;
    }

    public function getSalesReference()
    {
        if (count($this->products) == 0) {
            return null;
        }
        /** @var Product $product */
        $product = $this->products[0];

        return $product->getSalesReference();
    }

    public function setSalesReference($salesReference)
    {
        /** @var Product $product */
        foreach ($this->products as $product) {
            $product->setSalesReference($salesReference);
        }

        return $this;
    }

    public function getProductReference()
    {
        $references = [];
        /** @var Product $product */
        foreach ($this->products as $product) {
            $references[] = $product->getProductReference();
        }

        return implode(';', $references);
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     *
     * @return $this
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->customerReference;
    }

    /**
     * @param string $customerReference
     *
     * @return $this
     */
    public function setCustomerReference($customerReference)
    {
        $this->customerReference = $customerReference;

        return $this;
    }

    /**
     * @param $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }
}
