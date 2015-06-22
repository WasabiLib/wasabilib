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

namespace WasabiLib\Modal;
use WasabiLib\Ajax\GenericMessage;
use WasabiLib\Ajax\ResponseConfigurator;
use Zend\Http\Response;
use Zend\View\Exception;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Resolver\TemplateMapResolver;
use WasabiLib\Ajax\InnerHtml;
use Zend\View\Strategy\PhpRendererStrategy;
use Zend\View\View;

/**
 * Class WasabiModal
 * @package WasabiLib\Modal
 * @example
 *
 *  $response = new Response();
 *  $modal = new Modal\WasabiModal("#wasabi_modal", $this->getrenderer());
 *  $modal->setTitle("Dies ist ein Modal-Fenster");
 *  $modal->setContent("... und sein zugehöriger Content.");
 *
 *  $response->add($modal);
 *
 *  return $this->ajaxResponse($response);
 */
class WasabiModalView extends ResponseConfigurator {
    /**
     * @var string
     */
    protected $header = null;

    /**
     * @var string
     */
    protected $title = null;

    /**
     * @var string
     */
    protected $content = null;

    /**
     * @var string
     */
    protected $footer = null;

    /**
     * @var array
     */
    protected $footerButtons = array();

    /**
     * @var array
     */
    protected $headerButtons = array();

    /**
     * @var array
     */
    protected $responseTypes = array();

    /**
     * @var \WasabiLib\Ajax\InnerHtml
     */
    protected $innerHtml = null;

    /**
     * @var WasabiModalBaseConfigurator
     */
    protected $preRenderConfig = null;

    /**
     * @var WasabiModalBaseConfigurator
     */
    protected $config = null;

    /**
     * @var string
     */
    protected $buttonTemplate = "button";

    /**
     * @var string
     */
    protected $template = "modal";

    /**
     * @var string
     */
    protected $templateBasePath = "";

    /**
     * @var string
     */
    protected $templateFullPath = "";

    /**
     * @var string
     */
    protected $templateExtension = "phtml";

    /**
     * @var \Zend\View\Resolver\TemplateMapResolver
     */
    protected $resolver = null;

    /**
     * @var \Zend\View\Renderer\PhpRenderer
     */
    protected $renderer = null;

    /**
     * @var \Zend\View\Model\ViewModel
     */
    protected $viewModel = null;

    /**
     * @var \Zend\View\View
     */
    protected $view = null;

    /**
     * @var string
     */
    protected $selector = null;

    public function __construct($selector = null, PhpRenderer $renderer, WasabiModalBaseConfigurator $config = null){
        $this->selector = $selector;
        $this->preRenderConfig = $config;
        $this->renderer = $renderer;
        $this->viewModel = new ViewModel();
        $this->view = new View();
        $this->templateBasePath = __DIR__. "../../../../view/wasabi-lib/modal/";
        $this->templateFullPath = $this->templateBasePath.$this->template.".".$this->templateExtension;
        $this->innerHtml = new InnerHtml($this->selector, null);

        /**
         * @var $this->resolver \Zend\View\Resolver\TemplateMapResolver
         */
        $this->resolver = new TemplateMapResolver();

        /**
         * @var $this->renderer \Zend\View\Renderer\PhpRenderer
         */
        $this->renderer->resolver()->attach($this->resolver);

        $this->view->setResponse(new Response());
        $this->view->getEventManager()->attach(new PhpRendererStrategy($this->renderer));
        $this->init();
    }

    protected function init() {

    }

    /**
     * @param null $actionType
     */
    public function setActionType($actionType) {
        $this->innerHtml->setActionType($actionType);
    }

    /**
     * @param mixed | string | \Zend\View\Model\ViewModel $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return null
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Extends the actual pre render config or replaces it, if the optional $replace flag is set tot true.
     * @param \WasabiLib\Modal\WasabiModalBaseConfigurator $preRenderConfig
     * @param bool $replace An optional parameter to replace the actual pre render config.
     */
    public function setPreRenderConfig($preRenderConfig, $replace = false) {
        if(!$replace && $this->preRenderConfig) {
            $this->preRenderConfig->setConfig(array_merge($this->preRenderConfig->getConfig(), $preRenderConfig->getConfig()));
        } else {
            $this->preRenderConfig = $preRenderConfig;
        }
    }

    /**
     * @return \WasabiLib\Modal\WasabiModalBaseConfigurator
     */
    public function getPreRenderConfig() {
        return $this->preRenderConfig;
    }

    /**
     * Extends the actual config or replaces it, if the optional $replace flag is set tot true.
     * @param \WasabiLib\modal\WasabiModalBaseConfigurator $config
     * @param bool $replace An optional parameter to replace the actual config.
     */
    public function setConfig($config, $replace = false) {
        if(!$replace && $this->config) {
            $this->config->setConfig(array_merge($this->config->getConfig(), $config->getConfig()));
        } else {
            $this->config = $config;
        }
    }

    /**
     * @return \WasabiLib\modal\WasabiModalBaseConfigurator
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param null $footer
     */
    public function setFooter($footer) {
        $this->footer = $footer;
    }

    /**
     * @return null
     */
    public function getFooter() {
        return $this->footer;
    }

    /**
     * @param array $footerButtons
     */
    public function setFooterButtons($footerButtons) {
        $this->footerButtons = $footerButtons;
    }

    /**
     * @return array
     */
    public function getFooterButtons() {
        return $this->footerButtons;
    }

    /**
     * @param null $header
     */
    public function setHeader($header) {
        $this->header = $header;
    }

    /**
     * @return null
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @param array $headerButtons
     */
    public function setHeaderButtons($headerButtons) {
        $this->headerButtons = $headerButtons;
    }

    /**
     * @return array
     */
    public function getHeaderButtons() {
        return $this->headerButtons;
    }

    /**
     * @param null $selector
     */
    public function setSelector($selector) {
        $this->innerHtml->setElementId($selector);
    }

    /**
     * @return null
     */
    public function getSelector() {
        return $this->innerHtml->getElementId();
    }

    /**
     * @param null $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return null
     */
    public function getTitle() {
        return $this->title;
    }

    /************************
     * HELPER METHODS
     ***********************/

    protected function getTemplatePath($template) {
        return $this->templateBasePath.$template.".".$this->templateExtension;
    }

    public function configure() {
        $this->preRender();
        foreach($this->preConfig() as $config) {
            $this->addResponseType($config);
        }
        $this->view->render($this->viewModel);
        $this->innerHtml->setContent($this->view->getResponse()->getContent());
        $this->addResponseType($this->innerHtml);
    }

    public function getResponseTypes(){
        return $this->responseTypes;
    }

    protected function preConfig() {
        $config = $this->config ? $this->config->getConfig() : array();
        // Prepare behavior config of the modal view and size
        $inlineConfig = "";
        if(isset($config["inlineConfig"])) {
            foreach($config["inlineConfig"] as $configKey => $configValue) {
                $inlineConfig .= $configKey."=".$configValue." ";
            }
            $this->viewModel->setVariable("inlineConfig", $inlineConfig);
        }
        // Prepare behavior config of the modal view and size
        $genericMessage = new GenericMessage($this->selector." .wasabiModal#".$this->preRenderConfig->getId(), "ACTION_TYPE_MODAL", "ModalWindow", array("show"));
        $preConfig[] = $genericMessage;

        return $preConfig;
    }

    /**
     * @todo Problem mit nicht belegten Template-Variablen beheben. Workaround ist die Variablen mit dem Leer-String zu belegen.
     */
    protected function preRender() {
        $buttonsStaticAttributes = array("classes" => "", "inlineConfig" => "", "template" => "", "buttonText" => "", "captureTo" => "", "content" => "");

        $config = $this->preRenderConfig ? $this->preRenderConfig->getConfig() : array();

        // Prepare Template configuration of modal window.
        isset($config["template"]) ? $this->template = $config["template"] : false;
        $this->resolver->add(array($this->template => $this->getTemplatePath($this->template)));
        $this->viewModel->setTemplate($this->template);
        // Prepare Template configuration of modal window.

        // Prepare title configuration of modal window.
        isset($config["title"]) ? $this->title = $config["title"] : false;
        $this->viewModel->setVariable("title", $this->title);
        $this->viewModel->setVariable("id", $config["id"]);
        // Prepare title configuration of modal window.

        // Prepare class values for modal template
        $this->viewModel->setVariable("classes", isset($config["classes"]) ? implode(" ", $config["classes"]) : "fade");
        // Prepare class values for modal template

        // Prepare the content configuration
        $content = isset($config["content"]) ? $config["content"] : $this->content;

        if(is_object($content) && get_class($content) === "Zend\\View\\Model\\ViewModel") {
            $this->resolver->add(array($content->getTemplate() => $this->getTemplatePath($content->getTemplate())));
            $this->viewModel->addChild($content, "content");
        } else {
            $this->viewModel->setVariable("content", $content);
        }
        // Prepare the content configuration

        // Prepare behavior config of the modal view and size
        $inlineConfig = "";
        if(isset($config["inlineConfig"])) {
            foreach($config["inlineConfig"] as $configKey => $configValue) {
                $inlineConfig .= $configKey."=".$configValue." ";
            }
        }
        $this->viewModel->setVariable("inlineConfig", $inlineConfig);
        // Prepare behavior config of the modal view and size
        $this->viewModel->setVariable("size", isset($config["size"]) ? $config["size"] : "");


        // Prepare buttons configuration
        $buttonModels = array();
        isset($config["buttons"]) ? $this->footerButtons = $config["buttons"] : false;
        foreach($this->footerButtons as $button) {
            $optionalAttributes = "";
            $buttonKeysDiff = array_diff_key($button, $buttonsStaticAttributes);

            foreach($buttonKeysDiff as $buttonKey => $value) {
                $optionalAttributes .= $buttonKey."=".$value." ";
            }

            $button["classes"] = isset($button["classes"]) ? implode(" ", $button["classes"]) : "";
            $buttonInlineConfig = "";
            if(isset($button["inlineConfig"])) {
                foreach($button["inlineConfig"] as $inlineConfigKey => $inlineConfigValue) {
                    $buttonInlineConfig .= $inlineConfigKey."=".$inlineConfigValue." ";
                }
            }
            $button["inlineConfig"] = $buttonInlineConfig;

            $button["optionalAttributes"] = $optionalAttributes;

            if(isset($button["data-dismiss"]) && $button["data-dismiss"] == true) {
                $button["data-dismiss"] = 'modal';
            }
            $keys = array_keys($button);
            $templateValues = array();

            foreach($keys as $key) {
                $templateValues = array_merge($templateValues, array($key => $button[$key]));
            }

            $buttonTemplate = isset($button["template"]) ? $button["template"] : "button";
            $buttonModels[$buttonTemplate][] = $templateValues;
        }

        $templVar = array();
        foreach($buttonModels as $template => $models) {
            foreach($models as $model) {
                if(isset($templVar[$template])) {
                    $templVar[$template] = array_merge($templVar[$template], array($model["captureTo"] => ""));
                } else {
                    $templVar[$template] = array($model["captureTo"] => "");
                }
            }
        }

        foreach($buttonModels as $template => $models) {
            $this->resolver->add(array($template => $this->getTemplatePath($template)));
                foreach($models as $model) {
                    $captureTo = $model["captureTo"];
                    unset($model["captureTo"]);
                    unset($model["template"]);
                    $viewModel = new ViewModel($model);
                    $viewModel->setTemplate($template);
                    if(is_object($model["content"]) && get_class($model["content"]) === "Zend\\View\\Model\\ViewModel") {
                        $this->resolver->add(array($model["content"]->getTemplate() => $this->getTemplatePath($model["content"]->getTemplate())));
                        $viewModel->addChild($model["content"], "content");
                        unset($model["content"]);
                    }
                    $this->viewModel->addChild($viewModel, $captureTo, true);
                }
        }
        // Prepare buttons configuration

        // Prepare the close button configuration
        $closeButton = $this->viewModel->getChildrenByCaptureTo("closeButton");
        if(empty($closeButton) && !isset($config["closeButton"])) {
            $viewModel = new ViewModel(array("buttonClasses" => "", "symbolClasses" => ""));
            $viewModel->setTemplate("closeButton");
            $this->resolver->add(array($viewModel->getTemplate() => $this->getTemplatePath($viewModel->getTemplate())));
            $this->viewModel->addChild($viewModel, "closeButton", true);
        }
        // Prepare the close button configuration
    }
}
