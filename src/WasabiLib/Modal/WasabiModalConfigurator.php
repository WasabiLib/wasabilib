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
class WasabiModalConfigurator extends WasabiModalBaseConfigurator{
    protected $id = "";
    protected $template = "";
    protected $classes = array();
    protected $inlineConfig = array();
    protected $content = null;
    protected $variables = array();
    protected $captureTo = null;

    /**
     * @param string $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $class
     */
    public function addClass($class) {
        $this->classes[] = $class;
    }

    /**
     * @return array
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * @param null $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return null
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param null $captureTo
     */
    public function setCaptureTo($captureTo) {
        $this->captureTo = $captureTo;
    }

    /**
     * @return null
     */
    public function getCaptureTo() {
        return $this->captureTo;
    }

    /**
     * @param array $variables
     */
    public function addVariable($variable, $value) {
        $this->variables[$variable] = $value;
    }

    /**
     * @return array
     */
    public function getVariables() {
        return $this->variables;
    }

    public function getConfig() {
        if(!isset($this->configValues[self::INLINE_CONFIG])) {
            $this->configValues[self::INLINE_CONFIG] = array();
        }

        if($this->id) {
            $this->configValues[self::INLINE_CONFIG][self::ID] = $this->id;
        }

        if($this->template) {
            $this->configValues[self::TEMPLATE] = $this->template;
        }

        if($this->content) {
            $this->configValues[self::CONTENT] = $this->content;
        }

        if($this->inlineConfig) {
            foreach($this->inlineConfig as $key => $value) {
                $this->configValues[self::INLINE_CONFIG][$key] = $value;
            }
        }

        if($this->variables) {
            $this->configValues[self::VARIABLES] = $this->variables;
        }

        if($this->captureTo) {
                $this->configValues[self::CAPTURE_TO] = $this->captureTo;
        }

        if(!empty($this->classes)) {
            $this->configValues[self::CLASSES] = $this->classes;
        }

        return parent::getConfig();
    }
} 