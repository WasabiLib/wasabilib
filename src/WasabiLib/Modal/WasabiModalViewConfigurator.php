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

class WasabiModalViewConfigurator extends WasabiModalConfigurator{
    const LARGE_SIZE = "modal-lg";
    const SMALL_SIZE = "modal-sm";

    protected $title = "";
    protected $backdrop = true;
    protected $keyboardDrop = true;
    protected $size = "";
    protected $animationType = "fade";
    protected $buttons = array();
    protected $closeButton = true;

    public function __construct($id, $title = "", $content = "") {
        parent::__construct();
        $this->title = $title;
        $this->content = $content;
        $this->id = $id;

    }
    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $backdrop
     */
    public function setBackdrop($backdrop) {
        $this->backdrop = $backdrop;
    }

    /**
     * @return string
     */
    public function getBackdrop() {
        return $this->backdrop;
    }

    /**
     * @param boolean $keyboardDrop
     */
    public function setKeyboardDrop($keyboardDrop) {
        $this->keyboardDrop = $keyboardDrop;
    }

    /**
     * @return boolean
     */
    public function getKeyboardDrop() {
        return $this->keyboardDrop;
    }

    /**
     * @param string $size
     */
    public function setSize($size) {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @param string $animationType
     */
    public function setAnimationType($animationType) {
        $this->animationType = $animationType;
    }

    /**
     * @return string
     */
    public function getAnimationType() {
        return $this->animationType;
    }

    /**
     * @param Button $buttonConfig
     */
    public function addButton(Button $buttonConfig) {
        $this->buttons[] = $buttonConfig->getConfig();
    }

    /**
     * @return array
     */
    public function getButtons() {
        return $this->buttons;
    }

    /**
     * @param Button $closeButton
     */
    public function setCloseButton($closeButton) {
        if($closeButton === false) {
            $this->closeButton = false;
        } else if($closeButton === true) {
            $this->closeButton = true;
        }  else {
            $this->addButton($closeButton);
        }
    }

    /**
     * @return null
     */
    public function getCloseButton() {
        return $this->closeButton;
    }

    public function getConfig() {
        if($this->id) {
            $this->configValues[self::ID] = $this->id;
        }

        if($this->title) {
            $this->configValues[self::TITLE] = $this->title;
        }

        if($this->backdrop !== true) {
            $array = array(self::DATA_BACKDROP => $this->backdrop === false ? "false" : $this->backdrop);
            if(isset($this->configValues[self::INLINE_CONFIG]) && is_array($this->configValues[self::INLINE_CONFIG])) {
                array_merge($this->configValues[self::INLINE_CONFIG], $array);
            } else {
                $this->configValues[self::INLINE_CONFIG] = $array;
            }
        }

        if($this->keyboardDrop === true) {
            $array = array(self::DATA_KEYBOARD => "true");
            if(isset($this->configValues[self::INLINE_CONFIG]) && is_array($this->configValues[self::INLINE_CONFIG])) {
                array_merge($this->configValues[self::INLINE_CONFIG], $array);
            } else {
                $this->configValues[self::INLINE_CONFIG] = $array;
            }
        }

        if($this->size) {
            $this->configValues[self::SIZE] = $this->size;
        }

        if($this->animationType) {
            !($this->animationType == "false" || $this->animationType === false) ? $this->classes[] = ($this->animationType === "fade" ? "" : "fade ").$this->animationType : false;
        }

        if(!empty($this->buttons)) {
            $this->configValues[self::BUTTONS] = $this->buttons;
        }

        if($this->closeButton === false) {
            $this->configValues[self::CLOSE_BUTTON] = false;
        } elseif(is_object($this->closeButton) && get_class($this->closeButton) === "WasabiLib\Modal\Button") {
            $this->configValues[self::CLOSE_BUTTON] = $this->closeButton->getConfig();
        }

        return parent::getConfig();
    }
} 