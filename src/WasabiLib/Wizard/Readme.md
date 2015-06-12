Wasabi Wizard 
==================================

####add/change this to/on each step-form

    <form id="<?=$this->formName?>" name="<?=$this->formName?>" method="post" action="/application/Index/wizardindex" ... >
        ...
        <?=$this->wizardFormCurrentStep?>
        ...
    </form>

####add this to your start phtml

    <?php
    $this->headLink()->appendStylesheet($this->basePath() . '/wasabilib_assets/css/wasabi.wizard.css');
    ?>
    <div id="wasabi_modal"></div>
    <div id="wizard_modal"></div>



###Standard behaviour

    you need every time 1 additional step, e.g. if you have 2 steps to collect information you need an additional step as last step (this steps display a summarize or thanks etc.)
    this behaviour is due to the change of the button in the penultimate step. the wizard change the label of the 'next'-button in the standard to >>finish<<
    (you can change the label with the setButtonFinishLabel())


###Breadcrumb

        if you change the breadcrumbtemplate, be sure that you provide a variable named >>breadcrumbTitle<< to show the Steptitel
        if you want to show stepnumber in badge, be sure to provide a variable named >>breadcrumbStepNumber<<

###Step
Following closures are provided

        setInitClosure -> initialize all necessary variables for the step

        setEnterClosure -> closure which is called when entering a step
        setPreProcessClosure -> closure which is executed before the actual process method is executed
        setProcessClosure -> actual process method
        setPostProcessClosure -> closure which is executed after the actual process method is executed
        setLeaveClosure -> closure which is called when leaving a step

        setLeaveToAncestorClosure -> closure which is executed before leaving a step to a previous Step
        setEnterFromDescendantClosure -> closure which is executed when entering a Step from an Descendant

---

        $step->addViewModelVariablesAndContent(VARIABLE,CONTENT); -> adds the content to a variable in the view, so you can provide information without processing the step
        $step->removeElementFromViewModelVariablesAndContent(VARIABLE); -> remove a VARIABLE and the content from variables for the view
        $step->setViewModel(VIEW_MODEL); sets the VIEW-MODEL to the viewmodel of the Step, and adds all necessary variables
        $step->setStorageContainer(STORAGE_CONTAINER); set se storageContainer for the step
        $step->setFormAction(FORM_ACTION); set the formAction Variable in the template
        $step->setRequest(REQUEST); set the Request to the step
        $step->setProcessErrorMessage(PROCESS_ERROR_MESSAGE); provide PROCESS_ERROR_MESSAGE to the Step, which is processed by $wizard->process()
        $step->setStorageValue(VALUE,KEY);  uses to store Value to KEY, if no key is provided standardkey is used
        $step->getStorageValue(KEY); return the Value to the specific KEY, if no KEY is provided, the standardkey is used


each closure must provide \WasabiLib\Wizard\ClosureArguments $closureArguments as Arguments e.g.

    $step->setProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
        ...
    }
            $closureArguments are provided by the stepcontroller (Request, ServiceLocator)


---

###Wizard
initialize wizard

    $selector="#Wizard .modal-body"; -> selector of the element where the wizard should be inserted, if you use it sa modal, the selector is the id of the modal
    request = $this->getRequest() -> the Request from your entry-point
    $stepCollectionClosure = function () {
                                        $stepCollection = new StepCollection();
                                        $stepCollection->add($this->stepOne());
                                        $stepCollection->add($this->stepTwo());
                                        $stepCollection->add($this->stepThree());

                                        return $stepCollection;
                                    }; -> for further Information see below
    $serviceLocator = $this->getServiceLocator()
    $asAjax = true|false; would you use the wizard in ajax-context or not
    $wizard = new \WasabiLib\Wizard\Wizard($selector = "wizard", $request, $stepCollectionClosure, $serviceLocator, $asAjax = true);

    $wizard->disablePrevButton(); -> disable the prev-button on first Step
    $wizard->setBreadcrumbTemplatePath(PATH_TO_BREADCRUMB_TEMPLATE); -> set the path to your own breadcrumbtemplate
    $wizard->addCssClass(CSS_CLASS_KEY,CSS_CLASS_VALUE); -> you can set your own css class to each element used by the wizard, CSS_CLASS_KEY´s are provided by WIZARD-CONSTANT
    $wizard->setButtonPrevLabel(LABEL); set your own LABEL to the prev-button
    $wizard->setButtonNextLabel(LABEL); set your own LABEL to the next-button
    $wizard->setButtonFinishLabel(LABEL); set your own LABEL to the finish-button
    $wizard->isFirstCall(); check if the wizard is called for the first time
    $wizard->getViewResult(); get the View Result depending on how you use the wizard (ajax-content or not)
    $wizard->getStorageContainer(); get the storageContainer for the wizard where you can store Values, is also used internally by the wizard
    $wizard->getSteps(); return the stepCollection


usage in ajax-context


        $response = new Response();
        $asAjax=true;
        $wizard = new \WasabiLib\Wizard\Wizard("#Wizard .modal-body", $this->getRequest(), $this->stepCollectionClosure(), $this->getServiceLocator(), $asAjax);

        if ($wizard->isFirstCall()) {
            $wizard->getStorageContainer()->clearStorage();
            $modal = new WasabiModal("Wizard", "Wizard Example", $wizard->getViewResult()->getViewModel());
            $modalView = new WasabiModalView("#wizard_modal", $this->getServiceLocator()->get("ViewRenderer"), $modal);
            $response->add($modalView);
        } else {
            $response->add($wizard->getViewResult());
        }

        return $this->getResponse()->setContent($response);


usage in non-ajax-content

    $asAjax=false;
    $wizard = new \WasabiLib\Wizard\Wizard("#Wizard .modal-body", $this->getRequest(), $this->stepCollectionClosure(), $this->getServiceLocator(), $asAjax);
    return array("content" => $wizard->getViewResult());

    (provide a variable "content" in your phtml)

    <div id="wizard"><?=$this->content?></div>


##EXAMPLE


    /**
    * this is only necessary for this example
    */
    private $formAction = array("ajax" => "/application/Index/processAjaxWizard",
        "html" => "/application/index/processStandardWizard");
    private $whichFormAction = false;

    public function wizardIndexAction() {
        return array();
    }

    public function initModalWizardAction() {
        $this->whichFormAction = "ajax";
        $response = new Response();
        $wizard = $this->buildWizard(true);
        if ($wizard->isFirstCall()) {
            $wizard->getStorageContainer()->clearStorage();
            $modal = new WasabiModal("Wizard", "Wizard Example", $wizard->getViewResult()->getViewModel());
            $modalView = new WasabiModalView("#wizard_modal", $this->getServiceLocator()->get("ViewRenderer"), $modal);
            $response->add($modalView);
        }

        return $this->getResponse()->setContent($response);
    }

    public function processAjaxWizardAction() {
        $this->whichFormAction = "ajax";
        $response = new Response();
        $wizard = $this->buildWizard(true);
        $response->add($wizard->getViewResult());
        return $this->getResponse()->setContent($response);
    }

    public function processStandardWizardAction() {
        $this->whichFormAction = "html";
        $wizard = $this->buildWizard(false);
        return array("content" => $wizard->getViewResult());
    }

    private function buildWizard($asAjax = false) {
        $wizard = new \WasabiLib\Wizard\Wizard("#Wizard .modal-body", $this->getRequest(), $this->stepCollectionClosure(), $this->getServiceLocator(), $asAjax);
        $wizard->disablePrevButton();

        return $wizard;
    }

    private function stepCollectionClosure() {
        $stepCollectionClosure = function () {
            $stepCollection = new StepCollection();
            $stepCollection->add($this->stepOne());
            $stepCollection->add($this->stepTwo());
            $stepCollection->add($this->stepThree());

            return $stepCollection;
        };
        return $stepCollectionClosure;
    }

    private function stepOne() {
        $stepOne = new \WasabiLib\Wizard\StepController("Name", "Name");
        $stepOne->setInitClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $this->form = new StepOneForm("stepOneForm", $closureArguments->getServiceLocator());
        });
        $stepOne->setProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $request = $closureArguments->getRequest();
            $serviceLocator = $closureArguments->getServiceLocator();
            if ($request->isPost()) {
                $formValidator = new StepOneFormValidator();
                $this->form->setInputFilter($formValidator->getInputFilter());
                $this->form->setData($request->getPost());
                if ($this->form->isValid()) {
                    $this->setStorageValue($this->form->getData());
                    return true;
                } else {
                    $this->setStorageValue($this->form->getData());
                    /* @var $form  StepOneForm */
                    $flashMessenger = $this->flashMessenger();
                    $fac = new FlashMessengerFactory();
                    $messages = $fac->createService($this);
                    $flashMessenger->addErrorMessage($this->form->getErrorMessagesAsString(" "));
                    $messageString = $messages->setMessageOpenFormat('<div%s>
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                             &phone;
                         </button>
                         <ul><li>')
                        ->setMessageSeparatorString('</li><li>')
                        ->setMessageCloseString('</li></ul></div>')
                        ->render("error", array('alert', 'alert-dismissible', 'alert-danger'));

                    $responseConfigurator = new ResponseConfigurator();
                    $responseConfigurator->addResponseType($this->form->getPreConfiguredDomManipulator());
                    $response = new \WasabiLib\Ajax\Response();
                    $response->add($responseConfigurator);
                    if ($this->getFormAction() == "/application/index/processStandardWizard") {
                        $this->setProcessErrorMessage($messageString.$response->asInjectedJS());
                    } else {

                        $info = new Info("alert_info_one", "WARNING", $messageString."");
                        $modal = new WasabiModalView("#wasabi_modal", $serviceLocator->get("ViewRenderer"), $info);
                        $responseConfigurator->addResponseType($modal);
                        $response->add($responseConfigurator);
                        $this->setProcessErrorMessage($response->asInjectedJS());
                    }
                    return false;
                }

            }
            return false;
        });
        $viewModel = new ViewModel();
        $viewModel->setTemplate("application/index/stepone.phtml");
        $stepOne->setFormAction($this->formAction[$this->whichFormAction]);
        $stepOne->setViewModel($viewModel);

        return $stepOne;
    }

    private function stepTwo() {
        $stepTwo = new \WasabiLib\Wizard\StepController("Address", "Address");
        $stepTwo->setInitClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $serviceLocator = $closureArguments->getServiceLocator();
            $this->form = new StepTwoForm("stepTwoForm", $serviceLocator);
        });
        $stepTwo->setProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $request = $closureArguments->getRequest();
            $serviceLocator = $closureArguments->getServiceLocator();
            if ($request->isPost()) {
                $formValidator = new StepTwoFormValidator();
                $this->form->setInputFilter($formValidator->getInputFilter());
                $this->form->setData($request->getPost());

                if ($this->form->isValid()) {
                    return true;
                } else {
                    $flashMessenger = $this->flashMessenger();
                    $fac = new FlashMessengerFactory();
                    $messages = $fac->createService($this);
                    $flashMessenger->addErrorMessage($this->form->getErrorMessagesAsString(" "));
                    $messageString = $messages->setMessageOpenFormat('<div%s>
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                             &phone;
                         </button>
                         <ul><li>')
                        ->setMessageSeparatorString('</li><li>')
                        ->setMessageCloseString('</li></ul></div>')
                        ->render("error", array('alert', 'alert-dismissible', 'alert-warning'));
                    $responseConfigurator = new ResponseConfigurator();
                    $responseConfigurator->addResponseType($this->form->getPreConfiguredDomManipulator());
                    $response = new \WasabiLib\Ajax\Response();
                    $response->add($responseConfigurator);


                    if ($this->getFormAction() == "/application/index/processStandardWizard") {
                        $this->setProcessErrorMessage($messageString.$response->asInjectedJS());
                    } else {

                        $info = new Info("alert_info_one", "WARNING", $messageString."");
                        $modal = new WasabiModalView("#wasabi_modal", $serviceLocator->get("ViewRenderer"), $info);
                        $responseConfigurator->addResponseType($modal);
                        $response->add($responseConfigurator);
                        $this->setProcessErrorMessage($response->asInjectedJS());
                    }
                    return false;
                }
            }

        });

        $stepTwo->setPostProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $this->setStorageValue($this->form->getData());
            return true;
        });
        $stepTwo->setLeaveToAncestorClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $request = $closureArguments->getRequest();
            $this->setStorageValue($request->getPost());

            return true;
        });
        $viewModel = new ViewModel();
        $viewModel->setTemplate("application/index/steptwo.phtml");
        $stepTwo->setFormAction($this->formAction[$this->whichFormAction]);
        $stepTwo->setViewModel($viewModel);

        return $stepTwo;

    }

    private function stepThree() {
        $stepThree = new \WasabiLib\Wizard\StepController("Summary", "Summary");
        $stepThree->setProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            /* @var $storageContainer StorageContainer */
            return true;
        });
        $stepThree->setEnterClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            /* @var $storageContainer StorageContainer */
            $storageContainer = $this->getStorageContainer();
            $storageIterator = $storageContainer->getIterator();
            $content = "<table  class='table  table-bordered table-hover table-striped'>";
            foreach ($storageIterator as $arrayElement) {
                foreach ($arrayElement as $key => $element) {
                    $content .= "<tr><td>".$key."</td><td>".$element."</td>";
                }

            }
            $content .= "</table>";
            $this->addViewModelVariablesAndContent("content", $content);

        });
        $viewModel = new ViewModel();
        $viewModel->setTemplate("application/index/stepthree.phtml");
        $stepThree->setFormAction($this->formAction[$this->whichFormAction]);
        $stepThree->setViewModel($viewModel);

        return $stepThree;
    }
