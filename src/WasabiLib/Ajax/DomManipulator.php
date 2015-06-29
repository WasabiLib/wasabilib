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


class DomManipulator extends GenericMessage {

    const ACTION_TYPE_CSS = "ACTION_TYPE_CSS";
    const ACTION_TYPE_SLIDEDOWN = "ACTION_TYPE_SLIDEDOWN";
    const ACTION_TYPE_FADEOUT = "ACTION_TYPE_FADEOUT";
    const ACTION_TYPE_FADEIN = "ACTION_TYPE_FADEIN";
    const ACTION_TYPE_ATTR = "ACTION_TYPE_ATTR";
    const ACTION_TYPE_ADD_CLASS = "ACTION_TYPE_ADD_CLASS";
    const ACTION_TYPE_REMOVE_CLASS = "ACTION_TYPE_REMOVE_CLASS";
    const ACTION_TYPE_TOGGLE_CLASS = "ACTION_TYPE_TOGGLE_CLASS";
    const ACTION_TYPE_DROPZONE_DISCOVER = "ACTION_TYPE_DROPZONE_DISCOVER";
    const ACTION_TYPE_SHOW = "ACTION_TYPE_SHOW";
    const ACTION_TYPE_REMOVE_ELEMENT = "ACTION_TYPE_REMOVE_ELEMENT";
    const ACTION_TYPE_HIDE = "ACTION_TYPE_HIDE";

    /**
     * @param string $selector
     * @param string $propertyName
     * @param string $value
     * @param string $actionType
     */
    public function __construct($selector = null, $propertyName = null, $value = null, $actionType = self::ACTION_TYPE_CSS) {
        $params = array();
        if ($propertyName)
            $params[] = $propertyName;
        if ($value)
            $params[] = $value;

        parent::__construct($selector, $actionType, "DomManipulator", $params);
    }
}

