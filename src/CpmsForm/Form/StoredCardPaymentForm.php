<?php

namespace CpmsForm\Form;

use CpmsForm\Exception\InvalidPaymentTypeException;
use CpmsForm\Payment\BasePaymentInterface;
use CpmsForm\Payment\StoredCardPaymentInterface;

/**
 * Class StoredCardPaymentForm
 *
 * @package CpmsForm\Form
 */
class StoredCardPaymentForm extends PaymentForm
{
    const CLASS_NAME             = __CLASS__;
    const FIELD_NAME_STORED_CARD = 'card_reference';
    const CARD_STATUS_ACTIVE     = 192;

    /**
     * @var string
     */
    private $customerReference;

    /**
     * Populate form within required data
     *
     * @param BasePaymentInterface $payment
     *
     * @throws InvalidPaymentTypeException
     * @return StoredCardPaymentForm
     */
    public function populateRequiredFields(BasePaymentInterface $payment)
    {
        parent::populateRequiredFields($payment);

        if (!$payment instanceof StoredCardPaymentInterface) {
            throw new InvalidPaymentTypeException(
                sprintf(
                    'Object of %s class is invalid for this form',
                    get_class($payment)
                )
            );
        }

        $this->customerReference = $payment->getCustomerReference();
        $this->addHiddenField('customer_reference', $payment->getCustomerReference());

        /** @var \Laminas\Form\Element\Select $element */
        $element = $this->get(self::FIELD_NAME_STORED_CARD);
        $format  = $element->getOption('format');

        $valueOptions = [];
        $storedCards  = $this->getService()->getStoredCards(self::CARD_STATUS_ACTIVE);

        foreach ($storedCards as $card) {
            $valueOptions[$card['card_reference']] = $this->parseValueLabel($card, $format);
        }

        $element->setValueOptions($valueOptions);

        return $this;
    }

    /**
     * Format value options based on stored card data
     *
     * @param array  $card
     * @param string $format
     *
     * @return mixed
     */
    private function parseValueLabel($card, $format)
    {
        preg_match_all('|(\{([a-z_]+)\})|', $format, $matches);

        if (count($matches[0]) == 0) {
            return $format;
        }

        foreach ($matches[0] as $key => $match) {
            $format = str_replace($match, $card[$matches[2][$key]], $format);
        }

        return $format;
    }
}
