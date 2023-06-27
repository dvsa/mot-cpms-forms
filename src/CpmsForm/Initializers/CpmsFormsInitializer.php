<?php
/**
 * Created by PhpStorm.
 * User: gregw
 * Date: 2019-03-13
 * Time: 13:26
 */

namespace CpmsForm\Initializers;

use CpmsForm\Form\AbstractBaseForm;
use CpmsForm\Form\PaymentForm;
use CpmsForm\Form\ProductFieldSet;
use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Csrf;
use Laminas\ServiceManager\Initializer\InitializerInterface;

class CpmsFormsInitializer implements InitializerInterface
{
    public function __invoke(ContainerInterface $container, $form)
    {
        if ($form instanceof PaymentForm) {
            $productsCollection = new Collection(PaymentForm::PAYMENT_DATA_KEY);
            $formElementManager = $container->get('FormElementManager');
            /** @var ProductFieldSet $fieldSet */
            $fieldSet = $formElementManager->get('productFieldSet');
            $productsCollection->setTargetElement($fieldSet);
            $form->setProductCollection($productsCollection);
        }

        if ($form instanceof AbstractBaseForm) {
            //Add CSRF form element
            $element = new Csrf(AbstractBaseForm::CSRF_NAME);
            $form->add($element);
        }
    }
}