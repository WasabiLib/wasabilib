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
use WasabiLib\Controller\WasabiAbstractActionController;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

use Closure;
use Zend\View\View;

class StepController extends WasabiAbstractActionController implements ServiceLocatorInterface {
    /**
     * @var String text for the breadcrumb-entry
     */
    private $breadcrumbTitle;
    /**
     * @var Form
     */
    private $form;
    /**
     * @var string name of the step
     */
    private $name;
    /**
     * @var callable
     */
    private $previousStepClosure = false;
    /**
     * @var callable
     */
    private $enterClosure = false;
    /**
     * @var callable
     */
    private $enterFromDescendantClosure = false;
    /**
     * @var callable
     */
    private $leaveClosure = false;
    /**
     * @var callable
     */
    private $leaveToAncestorClosure = false;
    /**
     * @var callable
     */
    private $preProcessClosure = false;
    /**
     * @var callable
     */
    private $postProcessClosure = false;
    /**
     * @var callable
     */
    private $processClosure;
    /**
     * @var string
     */
    private $processErrorMessage = "";
    /**
     * @var mixed result of processing the step
     */
    private $result;
    /**
     * @var StorageContainer
     */
    private $storageContainer = false;
    /**
     * key=>value
     * @var array storedData to fill the form
     */
    private $storedFormData = array();
    /**
     * @var ViewModel
     */
    private $viewModel;
    private $viewModelVariablesAndContent = array();

    private $formAction = "";

    /**
     * @param $breadcrumbTitle
     * @param bool $name html compliant name (without spaces, etc)
     */
    public function __construct($breadcrumbTitle, $name = false) {
        if (!($name))
            throw new \Exception ("name must be set for Stepcontroller");
        if (!($breadcrumbTitle))
            throw new \Exception ("name must be set for Stepcontroller");
        $this->breadcrumbTitle = $breadcrumbTitle;
        $this->name = $name;
        $this->setRequest($this->getRequest());

    }

    /**
     * @return String
     */
    public function getBreadCrumbTitle() {
        return $this->breadcrumbTitle;
    }

    /**
     * @return Form
     */
    public function getForm() {
        return $this->form;
    }

    /**
     * @return bool|string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * @return StorageContainer
     */
    public function getStorageContainer() {
        return $this->storageContainer;
    }


    /**
     * set the value to be stored
     * if $key isn't set, standardkey is used
     * @param $value
     * @param bool $key
     */
    public function setStorageValue($value, $key = false) {
        $key = (!$key ? $this->name."InputResult" : $key);
        $this->getStorageContainer()->set($key, $value);
    }


    /**
     * key -> name of formAttribute
     * value -> value of formAttribute
     * if $this->storedData = empty, use WizardStorageContainer
     */
    public function getStorageValue($key = false) {
        $key = (!$key ? $this->name."InputResult" : $key);
        $storedData = $this->storedFormData;
        if (empty($storedData)) {
            $storageContainer = $this->getStorageContainer();
            if ($storageContainer->offsetExists($key))
                $storedData = $storageContainer->get($key);
            else return false;

        }
        return $storedData;
    }

    /**
     * @return ViewModel
     */
    public function getViewModel() {
        return $this->viewModel;
    }

    /**
     * @return ClosureArguments
     */
    public function standardClosureArguments() {
        $closureArguments = new ClosureArguments();
        $closureArguments->setRequest($this->getRequest());
        $closureArguments->setServiceLocator($this->getServiceLocator());
        return $closureArguments;
    }

    public function enterStep() {
        if ($this->enterClosure) {
            $closureParameter = $this->standardClosureArguments();
            $enterClosure = $this->enterClosure;
            return $enterClosure($closureParameter);
        }
    }

    public function leaveStep() {
        if ($this->leaveClosure) {
            $closureParameter = $this->standardClosureArguments();
            $leaveClosure = $this->leaveClosure;
            return $leaveClosure($closureParameter);
        }
    }

    public function enterFromDescendant() {
        if ($this->enterFromDescendantClosure) {
            $closureParameter = $this->standardClosureArguments();
            $enterFromDescendantClosure = $this->enterFromDescendantClosure;
            return $enterFromDescendantClosure($closureParameter);
        }
    }

    public function leaveToAncestorStep() {
        if ($this->leaveToAncestorClosure) {
            $closureParameter = $this->standardClosureArguments();
            $leaveToAncestorClosure = $this->leaveToAncestorClosure;
            return $leaveToAncestorClosure($closureParameter);
        }
    }

    /**
     * @return mixed
     */
    public function preProcess() {
        if ($this->preProcessClosure) {
            $closureParameter = $this->standardClosureArguments();
            $preProcessClosure = $this->preProcessClosure;
            return $preProcessClosure($closureParameter);
        }
    }

    /**
     * @return mixed
     */
    public function postProcess() {
        if ($this->postProcessClosure) {
            $closureParameter = $this->standardClosureArguments();
            $postProcessClosure = $this->postProcessClosure;
            return $postProcessClosure($closureParameter);
        }
    }

    public function process($closureParameter) {
        if(!$this->processClosure) {
            $this->setProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
                /* @var $storageContainer StorageContainer */
                return true;
            });
        }
        $process = $this->processClosure;
        return $process($closureParameter);
    }

    public function resetResult() {
        $this->result = false;
    }

    /**
     * @param $form
     */
    public function setForm($form) {
        $this->form = $form;
    }

    /**
     * key -> name of formAttribute
     * value -> value of formAttribute
     * @param $formData array(key=>value)
     */
    public function setFormData($formData) {
        $this->storedFormData = $formData;
    }

    /**
     * initialize all necessary variables for this Class
     * initialized Variables can be used in other closurecalls in class-context ($this->...)
     * e.g.: $this->form= new FORM();
     * @param callable $initClosure
     * @throws \Exception
     */
    public function setInitClosure(CLOSURE $initClosure = null) {
        if ($initClosure === null)
            throw new \Exception ("the initClosure must be set for Stepcontroller");
        $initClosure = $this->generateClosure($initClosure);
        $closureParameter = $this->standardClosureArguments();
        $initClosure($closureParameter);
    }

    /**
     * @param callable $enterClosure
     */
    public function setEnterClosure($enterClosure) {
        $this->enterClosure = $this->generateClosure($enterClosure);
    }

    /**
     * @param callable $enterFromDescendantClosure
     */
    public function setEnterFromDescendantClosure($enterFromDescendantClosure) {
        $this->enterFromDescendantClosure = $this->generateClosure($enterFromDescendantClosure);
    }

    /**
     * @param callable $leaveClosure
     */
    public function setLeaveClosure($leaveClosure) {
        $this->leaveClosure = $this->generateClosure($leaveClosure);
    }

    /**
     * @param callable $leaveToAncestorClosure
     */
    public function setLeaveToAncestorClosure($leaveToAncestorClosure) {
        $this->leaveToAncestorClosure = $this->generateClosure($leaveToAncestorClosure);
    }

    /**
     * @param callable $postProcessClosure
     */
    public function setPostProcessClosure($postProcessClosure) {
        $this->postProcessClosure = $this->generateClosure($postProcessClosure);
    }

    /**
     * @param callable $preProcessClosure
     */
    public function setPreProcessClosure($preProcessClosure) {
        $this->preProcessClosure = $this->generateClosure($preProcessClosure);
    }

    /**
     * @param callable $processClosure
     */
    public function setProcessClosure($processClosure) {
        $this->processClosure = $this->generateClosure($processClosure);
    }

    /**
     * set the error message for processing the step
     * @param string $processErrorMessage
     */
    public function setProcessErrorMessage($processErrorMessage) {
        $this->processErrorMessage = $processErrorMessage;
    }

    /**
     * @return string
     */
    public function getProcessErrorMessage() {
        return $this->processErrorMessage;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request) {
        $this->request = $request;
    }

    /**
     * @param string $formAction
     */
    public function setFormAction($formAction) {
        $this->formAction = $formAction;
    }

    /**
     * @return string
     */
    public function getFormAction() {
        return $this->formAction;
    }

    /**
     * @param StorageContainer $storageContainer
     */
    public function setStorageContainer($storageContainer) {
        if (!$this->storageContainer)
            $this->storageContainer = $storageContainer;
    }

    /**
     * the template of the viewModel must provide variables 'currentStep' and 'fromName'
     * @param $viewModel
     * @throws \Exceptions
     */
    public function setViewModel(ViewModel $viewModel) {
        $viewModel->setVariable("formName", $this->getName());
        if($this->getFormAction()!=""){
        $viewModel->setVariable("formAction", $this->getFormAction());
        }
        else {
            throw new \Exception("You must provide a formAction");
        }
        $wizardFormCurrentStepViewModel = new ViewModel();
        $wizardFormCurrentStepViewModel->setTemplate("wizard/wizardFormCurrentStep");
        $wizardFormCurrentStepViewModel->setVariable(Wizard::WIZARD_CURRENT_STEP_IDENTIFIER, $this->getName());
        $viewModel->addChild($wizardFormCurrentStepViewModel, "wizardFormCurrentStep");
        $this->viewModel = $viewModel;
    }


    /**
     * @param $variable string
     * @param $content Array
     */
    public function addViewModelVariablesAndContent($variable, $content) {
        $this->viewModelVariablesAndContent[$variable] = $content;
    }

    /**
     * @return array
     */
    public function getViewModelVariablesAndContent() {
        return $this->viewModelVariablesAndContent;
    }

    public function removeElementFromViewModelVariablesAndContent($keyVariable) {
        unset($this->viewModelVariablesAndContent[$keyVariable]);
    }


    /**
     * @param $closureFunction
     * @return Closure
     */
    private function generateClosure($closureFunction) {
        return Closure::bind($closureFunction, $this, get_class($this));
    }

    /**
     * Retrieve a registered instance
     *
     * @param  string $name
     * @throws \Exception\ServiceNotFoundException
     * @return object|array
     */
    public function get($name) {
        // TODO: Implement get() method.
    }

    /**
     * Check for a registered instance
     *
     * @param  string|array $name
     * @return bool
     */
    public function has($name) {
        // TODO: Implement has() method.
    }
}