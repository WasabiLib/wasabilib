<?php
/**
 * WasabiLib http://www.wasabilib.org
 *
 * @link https://github.com/WasabilibOrg/wasabilib
 * @license The MIT License (MIT) Copyright (c) 2015 Nico Berndt, Norman Albusberger, Sascha Qualitz
 *
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
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
class WasabiModalBaseConfigurator {
    //CONSTANTS
    const CLOSE_BUTTON = "closeButton";
    const TITLE = "title";
    const CONTENT = "content";
    const SIZE = "size";
    const INLINE_CONFIG = "inlineConfig";
    const DATA_BACKDROP = "data-backdrop";
    const DATA_KEYBOARD = "data-keyboard";
    const STATIC_CONST = "static";
    const BUTTONS = "buttons";
    const TEMPLATE = "template";
    const ID = "id";
    const CLASSES = "classes";
    const DATA_DISMISS = "data-dismiss";
    const BUTTON_TEXT = "buttonText";
    const CLASS_BUTTON_PRIMARY = "btn-primary";
    const CLASS_BUTTON_DEFAULT = "btn-default";
    const CLASS_BUTTON_SUCCESS = "btn-success";
    const CLASS_BUTTON_DANGER = "btn-danger";
    const MODAL = "modal";
    const AJAX_ELEMENT = "ajax_element";
    const DATA_HREF = "data-href";
    const DATA_EVENT = "data-event";
    const MODAL_LG = "modal-lg";
    const MODAL_SM = "modal-sm";
    const VARIABLES = "variables";
    const CAPTURE_TO = "captureTo";

    protected $configValues = array();

    public function __construct(array $configArray = array()) {
        $this->configValues = $configArray;
    }

    /**
     * @param array $configArray
     */
    public function setConfig(array $configArray) {
        $this->configValues = $configArray;
    }

    public function getConfig() {
        return $this->configValues;
    }
}