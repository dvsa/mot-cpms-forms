<?php

namespace CpmsForm\View\Helper;

use CpmsForm\Form\PaymentForm;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Helper\AbstractHelper;

/**
 * Class RenderCpmsForm
 *
 * @package CpmsForm\View\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class RenderCpmsForm extends AbstractHelper
{
    const CLASS_PATH = __CLASS__;

    /** @var ContainerInterface */
    private $serviceLocator;

    /** @var array  */
    private $partials = [];

    /**
     * Added because config information is no longer available from the HelperPluginManager
     * This has the nice side-effect that the serviceLocator is no longer needed.
     *
     * Add an array of partial .phtml templates
     *
     * @param array $partials
     */
    public function setPartials(array $partials = []) {
        $this->partials = $partials;
    }

    /**
     * Render form. Use default partial or provided in config
     *
     * @param PaymentForm $form
     *
     * @return string
     */
    public function __invoke(PaymentForm $form)
    {
        $partial = isset($this->partials[$form->getPaymentType()])
            ? $this->partials[$form->getPaymentType()]
            : $this->partials['form'];

        return $this->getView()->render($partial, ['form' => $form]);
    }
}
