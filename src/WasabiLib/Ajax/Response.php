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

use Zend\Code\Exception\InvalidArgumentException;

class Response{

    private $responses = array();



    public function __construct(ResponseTypeInterface $responseType = null){
        if($responseType)
            $this->add($responseType);
    }
    /**
     * Adds an object which implements the interfaces ResponseTypeInterface or ResponseConfiguratorInterface or an
     * array of objects which implement the ResponseTypeInterface.
     * @param $object array | ResponseConfiguratorInterface | ResponseTypeInterface
     * @throws \Zend\Code\Exception\InvalidArgumentException
     */
    public function add($object){

        if(is_array($object)) {
            $this->responses = array_merge($this->responses, $object);
        } else {
            $implementedInterfaces = class_implements($object);

            if(array_key_exists("WasabiLib\Ajax\ResponseConfiguratorInterface", $implementedInterfaces)) {
                $object->configure();
//                $this->responses = array_merge($this->responses, $object->getResponseTypes());
                foreach($object->getResponseTypes() as $responseType){
                    $implementedInterfacesInResponseType = class_implements($responseType);

                    if(array_key_exists("WasabiLib\Ajax\ResponseConfiguratorInterface", $implementedInterfacesInResponseType)) {
                        $this->add($responseType);
                    }
                    else{
                        $this->responses[]=$responseType;
                    }
                }
            } else if(array_key_exists("WasabiLib\Ajax\ResponseTypeInterface", $implementedInterfaces)) {
                $this->responses[] = $object;
            } else {
                throw new InvalidArgumentException("Invalid parameter type! Given value must be an array or implements the interfaces ResponseConfiguratorInterface or ResponseTypeInterface");
            }
        }
    }

    public function getResponseTypes(){
        return $this->responses;
    }

    public function json(){
        $array = [];
        /* @var $response \WasabiLib\Ajax\ResponseType */
        foreach($this->responses as $response){
            $array[] = $response->toArray();
        }

        return json_encode($array);
    }

    public function __toString(){
        $json = "";
        try{
            $json = $this->json();
        } catch (\Exception $e) {
            print_r($e->getMessage()." ".$e->getTraceAsString());
        }
        return $json;
    }

    public function asInjectedJS() {
        $domManipulator = new DomManipulator("#wasabiInjectedScript:parent", null, null, DomManipulator::ACTION_TYPE_REMOVE_ELEMENT);
        $this->add($domManipulator);
        return "<script id='wasabiInjectedScript' type='text/javascript' >$(document).ready(function(){\$(document).trigger({type: 'wasabiNotification',message: ".$this->__toString()."});});</script>";
    }
}
