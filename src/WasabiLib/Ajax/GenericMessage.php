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

/**
 * The base class of all WasabiLib response classes.
 * Class GenericMessage
 * @example
 * $message = new GenericMessage("#target_id", "ACTION_TYPE_REPLACE", "InnerHtml", array("<p style='margin: 0 0 0 0'>I am injected during an AJAX request.</p>"));
 *
 * $response = new Response();
 * $response->add($message);
 *
 * return $this->getResponse()->setContent($response);
 * @package WasabiLib\Ajax
 */
class GenericMessage extends ResponseType{

    /**
     * A css selector to target one or more html elements in the clients browsers DOM.
     * @var string
     */
    protected $selector;

    /**
     * A string which holds the information which methods on the JavaScript side has to be called.
     * @var string
     */
    protected $actionType;

    /**
     * Array with parameters which are send to the browser to used as parameters for the JavaScript method call.
     * @var array
     */
    protected $params = array();

    /**
     * @param string $selector
     * @param string $actionType
     * @param string $recipientType
     * @param array $params
     */
    public function __construct($selector = null, $actionType = null, $recipientType = '', $params = array()){
        $this->selector = $selector;
        $this->params = $params;
        $this->actionType = $actionType;
        $this->setRecipientType($recipientType);
    }

    /**
     * @param string $selector
     */
    public function setSelector($selector){
        $this->selector = $selector;
    }

    /**
     * @return string
     */
    public function getSelector(){
        return $this->selector;
    }

    /**
     * @return array
     */
    public function message() {
       $a = array('selector' => $this->selector, 'params' => $this->params, 'actionType' => $this->actionType);
       return $a;

    }

    /**
     * @param string $const
     */
    public function setActionType($const) {
        $this->actionType = $const;
    }

    /**
     * @param array $paramsArray
     */
    public function setParams($paramsArray){
        $this->params = $paramsArray;
    }

    /**
     * @return array
     */
    public function getParams(){
        return $this->params;
    }
}
