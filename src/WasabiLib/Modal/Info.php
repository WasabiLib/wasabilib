<?php
/**
 * Created by PhpStorm.
 * User: sascha.qualitz
 * Date: 10.11.14
 * Time: 12:17
 */

namespace WasabiLib\Modal;
use Zend\View\Model\ViewModel;
use WasabiLib\Modal\WasabiModalConfigurator;

/**
 * Class WasabiModalBaseConfigurator
 * @package WasabiLib\Modal
 *
 *
 */
class Info extends WasabiModalViewConfigurator {

    /**
     * @var Button
     */
    protected $confirmButton = null;

    public function __construct($id, $title = "", $content = "") {
        parent::__construct($id, $title, $content);
        $this->classes = array("wasabi_modal_info");

        $this->backdrop = "static";
        $this->keyboardDrop = false;

        $this->closeButton = false;

        $this->confirmButton = new Button("OK");
        $this->confirmButton->isDismissButton();
        $this->confirmButton->setType(Button::CLASS_BUTTON_PRIMARY);
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

    public function getConfig() {
        $this->confirmButton ? $this->addButton($this->confirmButton) : false;

        return parent::getConfig();
    }
}