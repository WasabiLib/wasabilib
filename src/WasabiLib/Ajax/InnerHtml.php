<?php
/**
 * @link https://github.com/WasabilibOrg/wasabilib
 * Copyright 2015 www.wasabilib.org
 * @license Apache License, Version 2.0
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace WasabiLib\Ajax;

use Zend\View\Model\ViewModel;
use Zend\View\Exception;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Strategy\PhpRendererStrategy;
use Zend\View\View;

class InnerHtml extends GenericMessage {
    const ACTION_TYPE_REPLACE = "ACTION_TYPE_REPLACE";
    const ACTION_TYPE_APPEND = "ACTION_TYPE_APPEND";
    const ACTION_TYPE_REMOVE = "ACTION_TYPE_REMOVE"; //element remove

    /**
     * @var \Zend\View\Model\ViewModel
     */
    protected $viewModel = null;

    /**
     * @var null|object
     */
    protected $renderer = null;

    /**
     * @param string $selector
     * @param string $content
     * @param string $actionType
     * @param object $renderer
     */
    public function __construct($selector = null, $content=null,$actionType = self::ACTION_TYPE_REPLACE, $renderer = null){
        parent::__construct($selector, $actionType, "InnerHtml", [$content]);

        $this->renderer = $renderer;
    }

    public function setContent($content){
        $this->params = [$content];
    }

    public function setActionType($const){
        $this->actionType = $const;
    }

    /**
     * Set service locator
     *
     * @param \Zend\View\Renderer\RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer) {
        $this->renderer = $renderer;
    }

    /**
     * Set the template to be used by this model
     *
     * @param  string $template
     */
    public function setTemplate($templatePath) {
        $this->initViewModel();
        $this->viewModel->setTemplate($templatePath);
    }

    /**
     * Sets a view model
     * @param \Zend\View\Model\ViewModel $viewModel
     */
    public function setViewModel(ViewModel $viewModel) {
        $this->viewModel = $viewModel;
    }

    /**
     * Returns a view model
     * @return \Zend\View\Model\ViewModel
     */
    public function getViewModel() {
        $this->initViewModel();
        return $this->viewModel;
    }

    /**
     * Set flag indicating whether or not this is considered a terminal or standalone model
     *
     * @param  bool $terminate
     */
    public function setTerminal($terminate) {
        $this->initViewModel();
        $this->viewModel->setTerminal($terminate);
    }

    /**
     * Set view variable
     *
     * @param  string $name
     * @param  mixed $value
     */
    public function setTemplateVariable($name, $value) {
        $this->initViewModel();
        $this->viewModel->setVariable($name, $value);

        return $this;
    }

    /**
     * Set view variables en masse
     *
     * Can be an array or a Traversable + ArrayAccess object.
     *
     * @param  array|ArrayAccess|Traversable $variables
     * @param  bool $overwrite Whether or not to overwrite the internal container with $variables
     * @throws Exception\InvalidArgumentException
     * @return ViewModel
     */
    public function setVariables($variables, $overwrite = false) {
        $this->initViewModel();
        try{
            $this->viewModel->setVariables($variables, $overwrite);
        } catch (Exception\InvalidArgumentException $e) {
            throw new Exception\InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function message() {
        if($this->renderer && $this->viewModel) {
            $viewRender = $this->renderer;
            $view = new View();
            $view->setResponse(new \Zend\Http\Response());
            $view->getEventManager()->attach(new PhpRendererStrategy($viewRender));
            $view->render($this->viewModel);
            $html = $view->getResponse()->getContent();
            $this->params = [$html];
        }


        return parent::message();
    }

    /************************************************************
    * Helper Functions
    *************************************************************/

    protected function initViewModel() {
        if($this->viewModel == null) {
            $this->viewModel = new ViewModel();
            $this->viewModel->setTerminal(true);
        }
    }
}