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
use Wasabi\Modal\Exception;
use Zend\View\Model\ViewModel;


class Button extends WasabiModalElementConfigurator {
    const FORM_ACTION = "action";

    protected $action = "";
    protected $eventType = "click";
    protected $ajaxElement = false;
    protected $template = "button";
    protected $type = "";
    protected $isDismissButton = false;
    protected $attributes = array();
    protected $ajax = true;

    public function __construct($buttonText = "") {
        $this->content = $buttonText;
        $this->captureTo = "buttons";

        parent::__construct(
            array(
                WasabiModalBaseConfigurator::BUTTON_TEXT => $this->content,
            )
        );
    }

    /**
     * @param string $action
     * @param bool $ajax
     */
    public function setAction($action, $ajax = true) {
        $this->ajax = $ajax;
        $this->action = $action;
        if(!$this->ajax) {
            $this->template = "buttonAsLink";
        }

    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @param string | \Zend\View\Model\ViewModel $buttonText
     */
    public function setButtonText($buttonText) {
        if($this->ajax) {
            $this->content = $buttonText;
        } else {
            $this->content->setVariable("content", $buttonText);
        }
    }

    /**
     * @return string
     */
    public function getButtonText() {
        if($this->ajax) {
            return $this->content;
        } else {
            return $this->content->getVariable("content");
        }
    }

    /**
     * @return array
     */
    public function getConfig() {
        if($this->action && $this->ajax) {
            if(!$this->id) {
                $this->setId("button_".floor(rand(0, 1000)));
            }
            $this->inlineConfig[self::DATA_HREF] = $this->action;
            $this->inlineConfig[self::DATA_EVENT] = $this->eventType;
            $this->classes[] = self::AJAX_ELEMENT;
        } else {
            $this->inlineConfig[self::FORM_ACTION] = $this->action;
        }

        if($this->type) {
            $this->classes[] = $this->type;
        }

        if($this->isDismissButton) {
            $this->configValues[self::DATA_DISMISS] = self::MODAL;
        }

        if($this->attributes) {
            foreach($this->attributes as $key => $value) {
                $this->inlineConfig[$key] = $value;
            }
        }

        return parent::getConfig();
    }

    /**
     * @param string $eventType
     */
    public function setEventType($eventType) {
        $this->eventType = $eventType;
    }

    /**
     * @return string
     */
    public function getEventType() {
        return $this->eventType;
    }

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
     * @param string $template
     */
    public function setTemplate($template) {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     */
    public function isDismissButton() {
        $this->isDismissButton = true;
    }

    public function isNoDismissButton() {
        $this->isDismissButton = false;
    }

    /**
     * @return boolean
     */
    public function getIsDismissButton() {
        return $this->isDismissButton;
    }
}