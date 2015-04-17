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

namespace WasabiLib\View;
use Wasabi\Modal\Exception;

/**
 * Class Button
 * @package WasabiLib\view
 *
 * @example
 * EXAMPLE 1:
 */
class Button extends WasabiViewModel {
    const ACTION = "action";
    const PARAM = "param";
    const DATA_HREF = "data-href";
    const DATA_EVENT = "data-event";
    const DATA_JSON = "data-json";
    const TEXT = "text";
    const EVENT_TYPE = "eventType";
    const AJAX_ELEMENT_CLASS = "ajax_element";

    const CLICK_EVENT = "click";

    /**
     * Construct
     *
     * @param string $text The button text.
     */
    public function __construct($text = "") {
        parent::__construct();
        $this->setText($text);
        $this->setTemplate("view/button");
    }

    /**
     * Sets the route to a controller action.
     * @param string $action
     */
    public function setAction($action) {
        $this->setVariable(self::ACTION, $this->assembleAssignmentString(self::DATA_HREF, $action));
        $this->setEventType(self::CLICK_EVENT);
        $this->addClass(self::AJAX_ELEMENT_CLASS);
        if(!$this->getId()) {
            $this->setId("button_".floor(rand(0, 1000)));
        }
    }

    /**
     * Returns the route to a controller action.
     * @return string
     */
    public function getAction() {
        return $this->extractValueFromAssignmentString($this->getVariable(self::ACTION));
    }

    /**
     * Sets the button text.
     * @param string $text
     */
    public function setText($text) {
        $this->setVariable(self::TEXT, $text);
    }

    /**
     * Returns the button text.
     * @return string
     */
    public function getText() {
        return $this->getVariable(self::TEXT);
    }

    /**
     * Sets the event type of the button. Default is "click".
     * @param string $eventType
     */
    public function setEventType($eventType) {
        $this->setVariable(self::EVENT_TYPE, $this->assembleAssignmentString(self::DATA_EVENT, $eventType));
    }

    /**
     * Returns the event type of the button. Default is "click".
     * @return string
     */
    public function getEventType() {
        return $this->extractValueFromAssignmentString($this->getVariable(self::EVENT_TYPE));
    }

    /**
     * Sets an optional parameter which is send to the server if the by the event type specified event occurred.
     * @param string $paramJsonString
     */
    public function setParam($paramJsonString) {
        $this->setVariable(self::PARAM, $this->assembleAssignmentString(self::DATA_JSON, $paramJsonString));
    }

    /**
     * Returns the optional parameter which is send to the server if the by the event type specified event occurred.
     * @return string
     */
    public function getParam() {
        return $this->extractValueFromAssignmentString($this->getVariable(self::PARAM));
    }
}