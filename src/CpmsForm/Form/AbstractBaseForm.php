<?php

namespace CpmsForm\Form;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Form as LaminasForm;
use Laminas\InputFilter\InputFilterProviderInterface;

/**
 * Class BaseForm
 *
 * @package CpmsForm\Form
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
abstract class AbstractBaseForm extends LaminasForm implements InputFilterProviderInterface
{
    const CSRF_NAME = 'csrf';

    /** @var array */
    protected $defaultFilters
        = array(
            array('name' => 'Laminas\Filter\StringTrim'),
            array('name' => 'Laminas\Filter\StripTags'),
        );

    /** @var array */
    protected $defaultValidators
        = array(
            'slip_number'  => [
                [
                    'name' => 'Laminas\Validator\Digits',
                ],
            ],
            'batch_number' => [
                [
                    'name' => 'Laminas\Validator\Digits',
                ],
            ],
        );

    /** @var array */
    protected $formValidators = [];

    /** @var array */
    protected $formFilters = [];

    /**
     * Add hidden field
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function addHiddenField($name, $value, $required = true)
    {
        $this->add(
            [
                'name'       => $name,
                'attributes' => [
                    'type'  => 'hidden',
                    'value' => $value,
                ],
                'options'    => [
                    'required' => $required,
                ],
            ]
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $inputSpecification = [];
        $this->mergeValidators();

        /** @var \Laminas\Form\Element\Text $element */
        foreach ($this->getElements() as $name => $element) {
            $options                   = $element->getOptions();
            $filters                   = array_merge($this->defaultFilters, $this->getFormInputFilters($name));
            $inputSpecification[$name] = [
                'filters'    => $filters,
                'validators' => $this->getFormInputValidators($name),
            ];

            if (isset($options['required']) && $options['required'] == true) {
                $inputSpecification[$name]['required'] = true;
            } else {
                $inputSpecification[$name]['required']    = false;
                $inputSpecification[$name]['allow_empty'] = true;

            }
        }

        return $inputSpecification;
    }

    /**
     * Merge default validators with form specific onces
     */
    private function mergeValidators()
    {
        foreach ($this->defaultValidators as $name => $validator) {
            if (isset($this->formValidators[$name])) {
                $this->formValidators[$name] = array_merge($this->formValidators[$name], $validator);
            } else {

                $this->formValidators[$name] = $validator;
            }
        }
    }

    /**
     * Get validator for form inputs
     *
     * @param $name
     *
     * @return mixed
     */
    protected function getFormInputValidators($name)
    {
        if (isset($this->formValidators[$name])) {
            return $this->formValidators[$name];
        }

        return [];
    }

    /**
     * Get validator for form inputs
     *
     * @param $name
     *
     * @return array
     */
    protected function getFormInputFilters($name)
    {
        if (isset($this->formFilters[$name])) {
            return $this->formFilters[$name];
        }

        return [];
    }

    /**
     * @return array
     */
    public function getDefaultValidators()
    {
        return $this->defaultValidators;
    }

    /**
     * @return array
     */
    public function getDefaultFilters()
    {
        return $this->defaultFilters;
    }

    /**
     * @return array
     */
    public function getFormFilters()
    {
        return $this->formFilters;
    }

    /**
     * @return array
     */
    public function getFormValidators()
    {
        return $this->formValidators;
    }

    /**
     * @param array $formValidators
     */
    public function setFormValidators($formValidators)
    {
        $this->formValidators = $formValidators;
    }

    /**
     * @param array $formFilters
     */
    public function setFormFilters($formFilters)
    {
        $this->formFilters = $formFilters;
    }
}
