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
namespace WasabiLib\Suggest;


use WasabiLib\Ajax\GenericMessage;
use WasabiLib\Ajax\ResponseConfigurator;

class Result extends ResponseConfigurator{

    private $suggestInputId;
    private $suggestCells = array();
    private $size;
    private $action = null;

    public function __construct($suggestInputId){
        $this->suggestInputId = $suggestInputId;
    }

    public function configure(){
        $list = $this->createList();
        $messageParams = array('list' => $list,'size' => $this->size);
        $message = new GenericMessage("#".$this->suggestInputId,null,"Suggest",$messageParams);
        $this->addResponseType($message);
    }

    private function createList(){
        /**
         * override actions of all cells when action is not null
         */
        if($this->action)
            $this->setAction($this->action);
        $string = "<div class='list-group'>";
        foreach($this->suggestCells as $cell){
            $string .= $cell->createListElement();
        }
        $string.="</div>";

        return $string;
    }

    public function addResultCell(ResultCell $cell){
        $this->suggestCells[] = $cell;
    }

    public function setSize($pixel){
        $this->size = $pixel;
    }

    /**
     * sets the action to all list items
     * @param $action
     */
    public function setAction($action,$isAjax = true){
        $this->action = $action;
       foreach($this->suggestCells as $cell){
               $cell->setAjaxElement($isAjax);
           $cell->setAction($action);
       }
    }





}