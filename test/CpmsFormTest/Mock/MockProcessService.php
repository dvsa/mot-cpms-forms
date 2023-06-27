<?php
namespace CpmsFormTest\Mock;

use Laminas\Form\Form;

/**
 * Class MockProcessService
 *
 * @package CpmsFormTest\Mock
 */
class MockProcessService
{
    public function processFormData()
    {
        return new Form();
    }
}
