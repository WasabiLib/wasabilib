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

/**
 * Class WasabiModalBaseConfigurator
 * @package WasabiLib\Modal
 *
 * @example
 * EXAMPLE 1:
 * $preRenderConfig = new Modal\WasabiModalBaseConfigurator(array(
        Modal\WasabiModalBaseConfigurator::TITLE => "Mein Modal-Fenster"
            , Modal\WasabiModalBaseConfigurator::CONTENT => "Mein toller Body"
            , Modal\WasabiModalBaseConfigurator::CLASSES => array("my_class", "fade")
            , Modal\WasabiModalBaseConfigurator::CLOSE_BUTTON => array("buttonClasses" => array("my-class"), "symbolClasses" => array("my-symbol-class"))
        //            , "closeButton" => false
        , Modal\WasabiModalBaseConfigurator::INLINE_CONFIG => array(
            Modal\WasabiModalBaseConfigurator::DATA_BACKDROP => Modal\WasabiModalBaseConfigurator::STATIC_CONST
            , Modal\WasabiModalBaseConfigurator::DATA_KEYBOARD => "true"
            )
        ,Modal\WasabiModalBaseConfigurator::BUTTONS => array(
            array(Modal\WasabiModalBaseConfigurator::ID => "my_button"
                , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_DEFAULT, "ajax_element")
                , Modal\WasabiModalBaseConfigurator::DATA_DISMISS => Modal\WasabiModalBaseConfigurator::MODAL
                , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "Schliessen")
            , array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
                , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "Drücken")
                , array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
                , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_PRIMARY)
                , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "Save changes")
            )
        ));

    EXAMPLE 2:
        $preRenderConfig = new Modal\WasabiModalBaseConfigurator(array(
            Modal\WasabiModalBaseConfigurator::CLASSES => array("my_class"),
            Modal\WasabiModalBaseConfigurator::BUTTONS => array(
            array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
            , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_PRIMARY)
            , Modal\WasabiModalBaseConfigurator::DATA_DISMISS => Modal\WasabiModalBaseConfigurator::MODAL
            , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "OK")
            )
        ));

    EXAMPLE 3:
        $viewModel = new ViewModel();
        $viewModel->setTemplate("testContent");
        $viewModel->setTemplate("heidelpay-registration/heidelpay-registration/modal.phtml");

        $preRenderConfig = new Modal\WasabiModalBaseConfigurator(array(
            Modal\WasabiModalBaseConfigurator::CLASSES => array("my_class", "fade"),
            "size" => "centerModal",
            Modal\WasabiModalBaseConfigurator::CONTENT => $viewModel
            , Modal\WasabiModalBaseConfigurator::BUTTONS => array(
            array(Modal\WasabiModalBaseConfigurator::TEMPLATE => "button"
            , Modal\WasabiModalBaseConfigurator::CLASSES => array(Modal\WasabiModalBaseConfigurator::CLASS_BUTTON_PRIMARY)
            , Modal\WasabiModalBaseConfigurator::DATA_DISMISS => Modal\WasabiModalBaseConfigurator::MODAL
            , Modal\WasabiModalBaseConfigurator::BUTTON_TEXT => "OK")
            )
        ));
 */
class WasabiModalConfigurator extends WasabiModalBaseConfigurator{
    protected $id = "";
    protected $template = "";
    protected $classes = array();
    protected $inlineConfig = array();
    protected $content = null;
    protected $variables = array();
    protected $captureTo = null;

    /**
     * @param string $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $class
     */
    public function addClass($class) {
        $this->classes[] = $class;
    }

    /**
     * @return array
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * @param null $content
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
     * @param null $captureTo
     */
    public function setCaptureTo($captureTo) {
        $this->captureTo = $captureTo;
    }

    /**
     * @return null
     */
    public function getCaptureTo() {
        return $this->captureTo;
    }

    /**
     * @param array $variables
     */
    public function addVariable($variable, $value) {
        $this->variables[$variable] = $value;
    }

    /**
     * @return array
     */
    public function getVariables() {
        return $this->variables;
    }

    public function getConfig() {
        if(!isset($this->configValues[self::INLINE_CONFIG])) {
            $this->configValues[self::INLINE_CONFIG] = array();
        }

        if($this->id) {
            $this->configValues[self::INLINE_CONFIG][self::ID] = $this->id;
        }

        if($this->template) {
            $this->configValues[self::TEMPLATE] = $this->template;
        }

        if($this->content) {
            $this->configValues[self::CONTENT] = $this->content;
        }

        if($this->inlineConfig) {
            foreach($this->inlineConfig as $key => $value) {
                $this->configValues[self::INLINE_CONFIG][$key] = $value;
            }
        }

        if($this->variables) {
            $this->configValues[self::VARIABLES] = $this->variables;
        }

        if($this->captureTo) {
                $this->configValues[self::CAPTURE_TO] = $this->captureTo;
        }

        if(!empty($this->classes)) {
            $this->configValues[self::CLASSES] = $this->classes;
        }

        return parent::getConfig();
    }
} 