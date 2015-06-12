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
namespace WasabiLib\Form;


use Zend\Form\ElementInterface;
use Zend\Form\Form;
use WasabiLib\Ajax\DomManipulator;

class FormExtended extends Form {

    private $errorMessageString = false;
    private $badSelectorString = false;
    protected  $translator = false;
    protected  $logger;


    public function __construct($name=null, $translator){
        $this->translator = $translator;
        parent::__construct($name);
    }

    /**
     * @return Transl
     */
    protected function translator(){
        return $this->translator;
    }

    public function getErrorMessagesAsString($separator = "<br>") {

        if (!$this->errorMessageString) {
            $this->createErrorMessageAndBadSelectorString($separator);
        }
        return $this->errorMessageString;
    }

    public function getPreConfiguredDomManipulator() {
            if(!$this->badSelectorString){
                $this->createBadSelectorString();
            }
            $css = new DomManipulator($this->badSelectorString, "background-color", "rgb(224, 242, 252)");
            return $css;

    }

    private function createErrorMessageAndBadSelectorString($separator){
        $messages = array();
        $badSelectors = array();

        foreach ($this->getMessages() as $validatorKey => $message) {
            $messages[] = reset($message);
            $badSelectors[] = "[name =" . $validatorKey . "]";
        }
        $this->errorMessageString = implode($separator, $messages);
        $this->badSelectorString = implode(",", $badSelectors);
    }

    private function createBadSelectorString(){
        $badSelectors = array();
        foreach ($this->getMessages() as $validatorKey => $message) {
            $badSelectors[] = "[name= ".$validatorKey." ]";
        }
        $this->badSelectorString = implode(",", $badSelectors);
    }

    /**
     * Set a single option for an element
     *
     * @param  string $key
     * @param  mixed $value
     * @return self
     */
    public function setOption($key, $value) {
        // TODO: Implement setOption() method.
    }
}