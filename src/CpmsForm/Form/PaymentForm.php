<?php

namespace CpmsForm\Form;

use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Payment\Product\MultiProductPayment;
use CpmsForm\Payment\Product\Product;
use CpmsForm\Service\FormGeneratorService;
use Laminas\Form\Element\Collection;

/**
 * Class PaymentForm
 *
 * @package CpmsForm\Form
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
abstract class PaymentForm extends AbstractBaseForm
{
    const CLASS_NAME              = __CLASS__;
    const FORM_NAME               = 'payment';
    const FIELD_NAME_PAYMENT_TYPE = 'payment_type';
    const PAYMENT_DATA_KEY        = 'payment_data';

    /**
     * @var string
     */
    protected $paymentType;

    /**
     * @var FormGeneratorService
     */
    protected $service;

    /** @var  Collection */
    protected $productCollection;

    /**
     * Override constructor

     */
    public function init()
    {
        $this->setName(self::FORM_NAME);

        parent::init();
    }

    /**
     * Populate form with required data
     *
     * @param BasePaymentInterface $payment
     */
    public function populateRequiredFields(BasePaymentInterface $payment)
    {
        $paymentData        = [];
        $productsCollection = clone $this->getProductCollection();
        $this->add($productsCollection);

        if ($payment instanceof MultiProductPayment) {

            $productsCollection->setCount(count($payment->getProducts()));

            /** @var Product $product */
            foreach ($payment->getProducts() as $product) {
                $paymentData[] = [
                    'amount'            => $product->getAmount(),
                    'sales_reference'   => $product->getSalesReference(),
                    'product_reference' => $product->getProductReference(),
                ];
            }

        } else {
            $productsCollection->setCount(1);

            $paymentData[] = [
                'amount'            => $payment->getAmount(),
                'sales_reference'   => $payment->getSalesReference(),
                'product_reference' => $payment->getProductReference(),
            ];
        }

        $this->setData(
            [
                PaymentForm::PAYMENT_DATA_KEY => $paymentData
            ]
        );

        $this
            ->addHiddenField('redirect_uri', $payment->getRedirectUri())
            ->addHiddenField('customer_reference', $payment->getCustomerReference())
            ->addHiddenField('cost_centre', $payment->getCostCentre())
            ->addHiddenField('user_id', $payment->getUserId())
            ->addHiddenField('total_amount', number_format((float)$payment->getAmount(), 2, '.', ''));

        $paymentName = $payment->getCustomerName();

        if (!empty($paymentName)) {
            $this->addHiddenField('customer_name', $payment->getCustomerName());
        }
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     *
     * @return PaymentForm
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        $this->remove(self::FIELD_NAME_PAYMENT_TYPE);

        $this->addHiddenField(self::FIELD_NAME_PAYMENT_TYPE, $paymentType);

        return $this;
    }

    /**
     * @return FormGeneratorService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param FormGeneratorService $service
     *
     * @return $this
     */
    public function setService(FormGeneratorService $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductCollection()
    {
        if (empty($this->productCollection))
        {
            $this->productCollection = new Collection('default', []);
        }
        return $this->productCollection;
    }

    /**
     * @param Collection $productCollection
     */
    public function setProductCollection($productCollection)
    {
        $this->productCollection = $productCollection;
    }
}
