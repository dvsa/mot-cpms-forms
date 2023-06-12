<?php

namespace CpmsFormTest\View\Helper;

use CpmsForm\Service\FormGeneratorService;
use CpmsForm\View\Helper\RenderCpmsForm;
use CpmsFormTest\Bootstrap;
use CpmsFormTest\Helper\PaymentTypeProviderTrait;
use Laminas\Form\View\Helper\FormRow;
use PHPUnit\Framework\TestCase;
use Laminas\Form\View\Helper\Form;
use Laminas\Form\View\Helper\FormCollection;
use Laminas\Form\View\Helper\FormElement;
use Laminas\Form\View\Helper\FormElementErrors;
use Laminas\Form\View\Helper\FormHidden;
use Laminas\Form\View\Helper\FormLabel;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * Class RenderCpmsFormTest
 *
 * @package CpmsFormTest\View\Helper
 * @author  Jakub Igla <jakub.igla@valtech.co.uk>
 */
class RenderCpmsFormTest extends TestCase
{
    use PaymentTypeProviderTrait;

    /** @var  \Laminas\ServiceManager\ServiceManager */
    protected $serviceManager;

    public function setUp(): void
    {
        $this->serviceManager = Bootstrap::getInstance()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);
    }

    /**
     * @param $payment
     *
     * @dataProvider paymentTypeProvider
     */
    public function testWithDefaultPartial($payment)
    {
        $config = $this->serviceManager->get('Config');
        $pluginManager = new HelperPluginManager($this->serviceManager);
        $resolver = new TemplatePathStack(
            [
                'script_paths' => $config['view_manager']['template_path_stack']
            ]
        );

        $renderer = new PhpRenderer();
        $renderer->setResolver($resolver);

        $viewHelper = new RenderCpmsForm();
        $viewHelper->setView($renderer);
        $partials = $config['cpms_forms']['partials'];
        $viewHelper->setPartials($partials);

        $pluginManager->setService('renderCpmsForm', $viewHelper);
        $pluginManager->setService('form', new Form());
        $pluginManager->setService('formHidden', new FormHidden());
        $pluginManager->setService('formElement', new FormElement());
        $pluginManager->setService('formLabel', new FormLabel());
        $pluginManager->setService('formrow', new FormRow());

        $pluginManager->setService('formElementErrors', new FormElementErrors());
        $formCollection = new FormCollection();
        $formCollection->setView($renderer);
        $pluginManager->setService('formCollection', $formCollection);

        $translate = new Translate();
        $translate->setTranslator(new Translator());

        $pluginManager->setService('translate', $translate);

        $renderer->setHelperPluginManager($pluginManager);

        $form = $this->serviceManager->get(FormGeneratorService::CLASS_PATH)->build($payment);

        $result = $renderer->renderCpmsForm($form);

        $this->assertTrue(is_string($result));
        $this->assertMatchesRegularExpression('/<form(.*)>/', $result);
        $this->assertMatchesRegularExpression('/<input.*name="redirect_uri".*>/', $result);
        $this->assertMatchesRegularExpression('/<input.*name="user_id".*>/', $result);
    }
}
