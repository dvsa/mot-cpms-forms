<?php

namespace CpmsForm\Payment;

/**
 * Trait BasePaymentTrait
 *
 * @package CpmsForm\Payment
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
trait BasePaymentTrait
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $salesReference;

    /**
     * @var string
     */
    protected $productReference;

    /**
     * @var string
     */
    protected $amount;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $customerReference;

    /**
     * @var string
     */
    protected $customerName;

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

    /**
     * @return string
     */
    public function getSalesReference()
    {
        return $this->salesReference;
    }

    /**
     * @param string $salesReference
     *
     * @return $this
     */
    public function setSalesReference($salesReference)
    {
        $this->salesReference = $salesReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductReference()
    {
        return $this->productReference;
    }

    /**
     * @param string $productReference
     *
     * @return $this
     */
    public function setProductReference($productReference)
    {
        $this->productReference = $productReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
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
     *
     * @return $this
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }
}
