<?php

namespace CpmsForm\Form;

/**
 * Class HiddenForm
 *
 * @package CpmsForm\Form
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class HiddenForm extends AbstractBaseForm
{
    const FORM_NAME = 'payment';

    /**
     * Override constructor
     *
     * @param null  $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        if ($name === null) {
            $name = self::FORM_NAME;
        }
        parent::__construct($name, $options);
    }
}
