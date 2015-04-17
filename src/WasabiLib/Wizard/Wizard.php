<?php
/**
 * WasabiLib http://www.wasabilib.org
 *
 * @link https://github.com/WasabilibOrg/wasabilib
 * @license The MIT License (MIT) Copyright (c) 2015 Nico Berndt, Norman Albusberger, Sascha Qualitz
 *
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

namespace WasabiLib\Wizard;

use WasabiLib\Ajax\DomManipulator;
use WasabiLib\Ajax\InnerHtml;
use WasabiLib\Controller\WasabiAbstractActionController;
use Zend\EventManager\ResponseCollection;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use WasabiLib\Wizard\StepCollection;
use WasabiLib\Wizard\WizardInterface;
use WasabiLib\Ajax\ResponseConfigurator;
use Zend\View\Model\ViewModel;
//use WasabiLib\Wizard\
use WasabiLib\Wizard\ArrayIterator;

class Wizard extends WasabiAbstractActionController {
    const WIZARD_BUTTON_NEXT_TEXT = "next";
    const WIZARD_BUTTON_PREV_TEXT = "previous";
    const WIZARD_BUTTON_FINISH_TEXT = "save";
    const WIZARD_STEP_ACTION_PREV = "prev";
    const WIZARD_STEP_ACTION_NEXT = "next";
    const WIZARD_STEP_ACTION_IDENTIFIER = "stepAction";
    const WIZARD_CURRENT_STEP_IDENTIFIER = "currentStep";

    const WIZARD_CSS_KEY_BREADCRUMB_LI_CLASSES = "breadcrumbElementClasses";
    const WIZARD_CSS_KEY_BREADCRUMB_BADGE_CLASSES = "breadcrumbBadgeClasses";
    const WIZARD_CSS_KEY_BREADCRUMB_TITLE_CLASSES = "breadcrumbTitleClasses";
    const WIZARD_CSS_KEY_BREADCRUMB_CHEVRON_CLASSES = "breadcrumbChevronClasses";

    const WIZARD_CSS_KEY_BUTTON_NEXT_CLASSES = "nextButtonClasses";
    const WIZARD_CSS_KEY_BUTTON_PREV_CLASSES = "nextButtonClasses";

    const WIZARD_CSS_KEY_WRAPPER_CLASSES = "wrapperClasses";
    const WIZARD_CSS_KEY_ERROR_CLASSES = "errorClasses";
    const WIZARD_CSS_KEY_BREADCRUMB_CONTAINER_CLASSES = "breadCrumbContainerClasses";
    const WIZARD_CSS_KEY_BREADCRUMB_LIST_CLASSES = "breadcrumbListClasses";
    const WIZARD_CSS_KEY_CONTENT_CLASSES = "contentClasses";
    const WIZARD_CSS_KEY_BUTTON_WRAPPER_CLASSES = "buttonWrapperClasses";

    /**
     * @var ViewModel
     */
    protected $viewModel;
    /**
     * @var StorageContainer
     */
    protected $storageContainer = false;
    /**
     * @var ResponseConfigurator|ViewModel
     */
    protected $resultContent;
    /**
     * @var bool
     */
    protected $stepAction = false;
    /**
     * @var StepCollection
     */
    protected $stepCollection;
    /**
     * @var ArrayIterator
     */
    protected $stepCollectionIterator;

    /**
     * @var string
     */
    protected $buttonNextLabel = "";

    /**
     * @var string
     */
    protected $buttonPrevLabel = "";

    /**
     * @var string
     */
    protected $buttonFinishLabel = "";

    /**
     * @var bool
     */
    protected $prevButtonDisabled = false;

    /**
     * @var ViewModel
     */
    protected $prevButtonViewModel = null;

    /**
     * @var ViewModel
     */
    protected $nextButtonViewModel = null;

    /**
     * @var bool
     */
    protected $asAjax = true;

    /**
     * @var array
     */
    protected $cssClasses = array();

    /**
     * @var string
     */
    protected $breadcrumbTemplatePath = "wizard/breadcrumb";

    /**
     * @var string
     */
    protected $selector = "wizard";

    /**
     * @param string $selector
     * @param $request
     * @param $stepCollectionClosure
     * @param $serviceLocator
     * @param bool $asAjax
     */
    public function __construct($selector = "wizard", $request, $stepCollectionClosure, $serviceLocator, $asAjax = true) {
        $this->request = $request;
        $this->stepCollection = $stepCollectionClosure();
        $this->serviceLocator = $serviceLocator;
        $this->asAjax = $asAjax;
        $this->selector = $selector;

        $this->initConfiguration();
    }

    /**
     * @return StepCollection
     */
    public function getSteps() {
        return $this->stepCollection;
    }

    /**
     * @return StorageContainer
     */
    public function getStorageContainer() {
        if (!$this->storageContainer) {
            $this->storageContainer = $this->getServiceLocator()->get("StorageContainer");
        }
        return $this->storageContainer;
    }

    /**
     * @return ResponseConfigurator
     */
    public function getViewResult() {
        $currentStep = $this->stepCollectionIterator->current();
        $isFirstStep = $this->getSteps()->isFirst($currentStep);
        if (!($this->prevButtonDisabled && $isFirstStep)) {
            $this->asAjax ? $this->prevButtonViewModel->setVariable("btnClass_extend", $this->prevButtonViewModel->getVariable("btnClass_extend")." ajax_element") : false;
            $this->prevButtonViewModel->setVariable("label", $this->getServiceLocator()->get('translator')->translate($this->buttonPrevLabel ? $this->buttonPrevLabel : Wizard::WIZARD_BUTTON_PREV_TEXT));
            $this->prevButtonViewModel->setVariable("buttonDataForm", $this->stepCollectionIterator->current()->getViewModel()->getVariable("formName"));
            $this->viewModel->addChild($this->prevButtonViewModel, "wizardButtons");
        }

        $this->asAjax ? $this->nextButtonViewModel->setVariable("btnClass_extend", $this->prevButtonViewModel->getVariable("btnClass_extend")." ajax_element") : false;
        $this->nextButtonViewModel->setVariable("label", $this->getServiceLocator()->get('translator')->translate($this->buttonNextLabel ? $this->buttonNextLabel : Wizard::WIZARD_BUTTON_NEXT_TEXT));
        $this->nextButtonViewModel->setVariable("buttonDataForm", $this->stepCollectionIterator->current()->getViewModel()->getVariable("formName"));
        #if the next step ist the penultimate step then we change the buttontext
        #otherwise restore the Buttontext
        if ($this->getSteps()->isPenultimate($this->stepCollectionIterator->current())) {
            $this->nextButtonViewModel->setVariable("label", $this->getServiceLocator()->get('translator')->translate($this->buttonFinishLabel ? $this->buttonFinishLabel : Wizard::WIZARD_BUTTON_FINISH_TEXT));
        }

        $this->viewModel->addChild($this->nextButtonViewModel, "wizardButtons", true);
        $this->setCssClassesToViewModel($this->viewModel);
        # if it is the first start, we expect an the viewModel of the first step and nothing else
        $innerHtml = new InnerHtml($this->selector, null, InnerHtml::ACTION_TYPE_REPLACE, $this->getServiceLocator()->get("ViewRenderer"));
        $innerHtml->setViewModel($this->resultContent);

        $return = null;
        if ($this->asAjax) {
            $return = $innerHtml;
        } else {
            $message = $innerHtml->message();
            $return = $message["params"][0];
        }
        return $return;
    }

    /**
     * checks if the wizard is first time called
     * @return bool
     */
    public function isFirstCall() {
        if (!$this->stepAction && $this->stepCollection->isFirst($this->stepCollectionIterator->current()))
            return true;
        else return false;
    }

    /**
     * processing the step
     */
    public function process() {
        /* @var $currentStep StepController */
        /* @var $ancestorStep StepController */
        /* @var $descendantStep StepController */
        $currentStep = $this->stepCollectionIterator->current();
        if ($this->stepAction == self::WIZARD_STEP_ACTION_PREV && !$this->stepCollection->isFirst($this->stepCollectionIterator->current())) {
            $currentStep->leaveToAncestorStep();
            $this->stepCollectionIterator->previous();
            $ancestorStep = $this->stepCollectionIterator->current();
            $ancestorStep->enterFromDescendant();
            $this->resultContent = $this->prepareNextStepView($ancestorStep);
        } elseif ($this->stepAction == self::WIZARD_STEP_ACTION_PREV && $this->stepCollection->isFirst($this->stepCollectionIterator->current())) {
            $this->resultContent = new ResponseConfigurator();
        }
        if ($this->stepAction == self::WIZARD_STEP_ACTION_NEXT) {
            $currentStep->preProcess();
            if ($currentStep->process($currentStep->standardClosureArguments())) {
                $currentStep->postProcess();
                $currentStep->leaveStep();
                $this->stepCollectionIterator->next();

                $descendantStep = $this->stepCollectionIterator->current();
                $descendantStep->enterStep();
                $this->resultContent = $this->prepareNextStepView($descendantStep);
            } else {
                $this->resultContent = $this->prepareNextStepView($currentStep);
                $this->resultContent->setVariable("errorMessage", $currentStep->getProcessErrorMessage());
            }

        }
    }

    /**
     * generate one elementView for the breadcrumb path
     * @param StepController $step
     * @return ViewModel
     */
    private function createBreadcrumbItems(StepController $step) {
        $viewModel = new ViewModel();
        $viewModel->setTemplate($this->breadcrumbTemplatePath);
        $viewModel->setVariable("breadcrumbTitle", $step->getBreadCrumbTitle());
        $viewModel->setVariable("stepNumber", $step->getBreadCrumbTitle());
        return $viewModel;

    }

    private function initConfiguration() {
        $this->stepCollectionIterator = $this->getSteps()->getIterator();
        /**
         * set position of current Step, depending step-information is set in post array, otherwise set current step to first element
         */
        $this->params()->fromPost(self::WIZARD_CURRENT_STEP_IDENTIFIER) ? $this->stepCollectionIterator->seek($this->stepCollection->has($this->params()->fromPost(self::WIZARD_CURRENT_STEP_IDENTIFIER))) : $this->stepCollectionIterator->seek(0);
        /**
         * set choosen stepAction in Wizard, depending on information set in post
         */
        $this->stepAction = $this->params()->fromPost(self::WIZARD_STEP_ACTION_IDENTIFIER, false);

        $this->viewModel = new ViewModel();
        $this->viewModel->setTemplate("wizard/wizard");

        $this->storageContainer = $this->getStorageContainer();
        $iterator = $this->getSteps()->getIterator();
        while ($iterator->valid()) {
            $currentStep = $iterator->current();
            $currentStep->setRequest($this->getRequest());
            $currentStep->setStorageContainer($this->storageContainer);
            $currentStep->setServiceLocator($this->getServiceLocator());
            $iterator->next();
        }
        $this->initStepViewModel();
    }

    /**
     * init the ViewModel for the wizard
     * @throws \Exception
     */
    private function initStepViewModel() {
        #first start of Wizard
        #load template from first step
        $currentStep = $this->stepCollectionIterator->current();
        $isFirstStep = $this->getSteps()->isFirst($currentStep);
        if ($isFirstStep) {
            $firstStep = $this->getSteps()->getFirst();
            $this->viewModel->addChild($firstStep->getViewModel(), "wizardContent");
            $firstStepFormName = $firstStep->getViewModel()->getVariable("formName", false);
            if (empty($firstStepFormName))
                throw new \Exception("viewModel variable 'formName' must set for Step '".$firstStep->getName()."'");
        }
        if (!($this->prevButtonDisabled && $isFirstStep)) {
            $this->prevButtonViewModel = new ViewModel();
            $this->prevButtonViewModel->setTemplate("wizard/wizardButton");
            $this->prevButtonViewModel->setVariable("stepActionDirection", "prev");
            $this->prevButtonViewModel->setVariable("id", "prevButton");
        }

        $this->nextButtonViewModel = new ViewModel();
        $this->nextButtonViewModel->setTemplate("wizard/wizardButton");
        $this->nextButtonViewModel->setVariable("stepActionDirection", "next");
        $this->nextButtonViewModel->setVariable("id", "nextButton");

        /**
         * creates breadcrumbPath
         */
        $stepIterator = $this->getSteps()->getIterator();
        while ($stepIterator->valid()) {
            $view = $this->createBreadcrumbItems($stepIterator->current());
            $this->getSteps()->isFirst($stepIterator->current()) ? $view->setVariable("extendCssClass", "active first") : false;
            $this->getSteps()->isLast($stepIterator->current()) ? $view->setVariable("extendCssClass", "last") : false;
            $view->setVariable("breadcrumbStepName", $stepIterator->current()->getName());
            $this->viewModel->addChild($view, "breadcrumbNavigation", true);
            $stepIterator->next();
        }
        $this->resultContent = $this->viewModel;
        $this->process();
    }

    /**
     * @param $nextStep StepController
     * @return ResponseConfigurator
     */
    private function  prepareNextStepView($nextStep) {
        $responseConfigurator = new ResponseConfigurator();
        $viewModelNextStep = $nextStep->getViewModel();
        #fill view with values if available
        if ($nextStep->getStorageValue()) {
            $viewModelNextStep->setVariables($nextStep->getStorageValue());

        }
        $viewModelNextStep->setVariables($nextStep->getViewModelVariablesAndContent());
        $this->viewModel->addChild($viewModelNextStep, "wizardContent");
        $this->prevButtonViewModel->setVariable("buttonDataForm", $nextStep->getViewModel()->getVariable("formName"));
        $this->nextButtonViewModel->setVariable("buttonDataForm", $nextStep->getViewModel()->getVariable("formName"));

        #change the buttonbehaviour of the Wizardbuttons depending on wether the step is the last one or not
        $this->toggleBreadcrumbItemActiveClass($nextStep);
        $this->toggleStandardWizardButtons(!$this->getSteps()->isLast($nextStep));
        return $this->viewModel;
    }

    /**
     * @param StepController $nextStep
     */
    private function toggleBreadcrumbItemActiveClass(StepController $nextStep) {
        foreach ($this->viewModel->getChildrenByCaptureTo("breadcrumbNavigation") as $stepBreadcrumb) {
            if ($stepBreadcrumb->getVariable("breadcrumbStepName") == $nextStep->getViewModel()->getVariable('formName')) {
                $stepBreadcrumb->setVariable("extendCssClass", "active");
            } else {
                $stepBreadcrumb->setVariable("extendCssClass", "");
            }
        }
    }

    /**
     * toggle the visibility of the wizardbutton depending on wether the step is the last one or not
     * @param bool $show
     * @return ResponseConfigurator
     */
    private function toggleStandardWizardButtons($show = true) {
        $this->prevButtonViewModel->setVariable("btnClass_extend", (!$show ? "hide" : ""), true);
        $this->nextButtonViewModel->setVariable("btnClass_extend", (!$show ? "hide" : ""), true);
    }

    /**
     * @param string $buttonFinishLabel
     */
    public function setButtonFinishLabel($buttonFinishLabel) {
        $this->buttonFinishLabel = $buttonFinishLabel;
    }

    /**
     * @return string
     */
    public function getButtonFinishLabel() {
        return $this->buttonFinishLabel;
    }

    /**
     * @param string $buttonNextLabel
     */
    public function setButtonNextLabel($buttonNextLabel) {
        $this->buttonNextLabel = $buttonNextLabel;
    }

    /**
     * @return string
     */
    public function getButtonNextLabel() {
        return $this->buttonNextLabel;
    }

    /**
     * @param string $buttonPrevLabel
     */
    public function setButtonPrevLabel($buttonPrevLabel) {
        $this->buttonPrevLabel = $buttonPrevLabel;
    }

    /**
     * @return string
     */
    public function getButtonPrevLabel() {
        return $this->buttonPrevLabel;
    }

    /**
     * Enables the previous button for the first step.
     */
    public function enablePrevButton() {
        $this->prevButtonDisabled = false;
    }

    /**
     * Disables the previous button is disabled for the first step.
     */
    public function disablePrevButton() {
        $this->prevButtonDisabled = true;
    }

    /**
     * @return boolean
     */
    public function getPrevButtonDisabled() {
        return $this->prevButtonDisabled;
    }

    /**
     * @param string $cssClassKey
     * @param string $cssClassValue
     */
    public function addCssClass($cssClassKey, $cssClassValue) {
        if (isset($this->cssClasses[$cssClassKey])) {
            $this->cssClasses[$cssClassKey] = $this->cssClasses[$cssClassKey]." ".$cssClassValue;
        } else {
            $this->cssClasses[$cssClassKey] = $cssClassValue;
        }
    }

    /**
     * @return array
     */
    public function getCssClasses() {
        return $this->cssClasses;
    }

    /**
     * @param string $cssClassKey
     */
    public function removeCssClasses($cssClassKey) {
        if (isset($this->cssClasses[$cssClassKey]) === true) {
            unset($this->cssClasses[$cssClassKey]);
        }
    }

    /**
     * @param ViewModel $viewModel
     */
    protected function setCssClassesToViewModel($viewModel) {
        $viewModel->setVariables($this->cssClasses);
        if ($viewModel->hasChildren()) {
            $children = $viewModel->getChildren();
            foreach ($children as $child) {
                $this->setCssClassesToViewModel($child);
            }
        }
    }

    /**
     * @param string $breadcrumbTemplatePath
     */
    public function setBreadcrumbTemplatePath($breadcrumbTemplatePath) {
        $this->breadcrumbTemplatePath = $breadcrumbTemplatePath;
    }

    /**
     * @return string
     */
    public function getBreadcrumbTemplatePath() {
        return $this->breadcrumbTemplatePath;
    }

    /**
     * @param string $selector
     */
    public function setSelector($selector) {
        $this->selector = $selector;
    }

    /**
     * @return string
     */
    public function getSelector() {
        return $this->selector;
    }

}
