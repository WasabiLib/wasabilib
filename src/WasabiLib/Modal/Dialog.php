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
use Zend\View\Model\ViewModel;
use WasabiLib\Modal\WasabiModalViewConfigurator;

/**
 * Class WasabiModalBaseConfigurator
 * @package WasabiLib\Modal
 *
 *
 */
class Dialog extends WasabiModalViewConfigurator {

    const TYPE_SUCCESS = "dialogSuccess";
    const SUCCESS_ICON = "fa-check";

    /**
     * @var Button
     */
    protected $confirmButton = null;

    /**
     * @var Button
     */
    protected $dismissButton = null;
    protected $text = "";
    protected $contentTemplate = "";
    protected $type = "";
    protected $icon = "";

    public function __construct($id, $title = "", $text = "", $type = "") {
        parent::__construct($id, $title, new ViewModel());
        $this->text = $text;
        if($type) {
            $this->setType($type);
        }

        $this->classes = array("wasabi_modal_dialog");

        $this->backdrop = "static";
        $this->keyboardDrop = false;

        $this->closeButton = false;

        $this->confirmButton = new Button("accept");
        $this->confirmButton->isDismissButton();
        $this->confirmButton->setType(Button::CLASS_BUTTON_SUCCESS);
        $this->dismissButton = new Button("decline");
        $this->dismissButton->isDismissButton();
        $this->dismissButton->setType(Button::CLASS_BUTTON_DANGER);

        $this->contentTemplate = "dialogContent";
    }

    /**
     * @param \WasabiLib\Modal\Button $confirmButton
     */
    public function setConfirmButton($confirmButton) {
        $this->confirmButton = $confirmButton;
    }

    /**
     * @return \WasabiLib\Modal\Button
     */
    public function getConfirmButton() {
        return $this->confirmButton;
    }

    /**
     * @param \WasabiLib\Modal\Button $dismissButton
     */
    public function setDismissButton($dismissButton) {
        $this->dismissButton = $dismissButton;
    }

    /**
     * @return \WasabiLib\Modal\Button
     */
    public function getDismissButton() {
        return $this->dismissButton;
    }

    /**
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        switch($type) {
            case self::TYPE_SUCCESS:
                $this->setIcon(self::SUCCESS_ICON);
            break;
        }
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param string $contentTemplate
     */
    public function setContentTemplate($contentTemplate) {
        $this->contentTemplate = $contentTemplate;
    }

    /**
     * @return string
     */
    public function getContentTemplate() {
        return $this->contentTemplate;
    }

    /**
     * @param null $viewModel
     */
    public function setViewModel($viewModel) {
        $this->content = $viewModel;
    }

    /**
     * @return null
     */
    public function getViewModel() {
        return $this->content;
    }

    public function getConfig() {
        $this->content->setTemplate($this->contentTemplate);
        $this->content->setVariable("text", $this->text ? $this->text : "");
        $this->content->setVariable("icon", $this->icon ? $this->icon : "");

        if($this->type) {
            $this->classes[] = $this->type;
        }

        $this->confirmButton ? $this->addButton($this->confirmButton) : false;
        $this->dismissButton ? $this->addButton($this->dismissButton) : false;

        return parent::getConfig();
    }
}