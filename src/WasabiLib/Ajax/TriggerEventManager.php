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


class TriggerEventManager extends GenericMessage {

    const ACTION_TYPE_TRIGGER ="ACTION_TYPE_TRIGGER";
    const ACTION_TYPE_TRIGGER_EVENT_CLICK ="ACTION_TYPE_TRIGGER_EVENT_CLICK";
    const ACTION_TYPE_TRIGGER_EVENT_FOCUS ="ACTION_TYPE_TRIGGER_EVENT_FOCUS";

    public function __construct($selector = null, $actionEvent = self::ACTION_TYPE_TRIGGER_EVENT_CLICK){
        $params = array();
        $params[] = $actionEvent;
        parent::__construct($selector, self::ACTION_TYPE_TRIGGER, "TriggerEventManager", $params);
    }

    public function setElementId($selector){
        $this->selector = $selector;
    }


    public function setActionType($const){
        $this->actionType = $const;
    }

    public function setActionEvent($const){
        $this->setParams(array($const));
    }
}

