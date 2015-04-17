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
namespace WasabiLib\Ajax;


class TriggerEventManager extends GenericMessage {

    const ACTION_TYPE_TRIGGER ="ACTION_TYPE_TRIGGER";
    const ACTION_TYPE_TRIGGER_EVENT_CLICK ="ACTION_TYPE_TRIGGER_EVENT_CLICK";
    const ACTION_TYPE_TRIGGER_EVENT_FOCUS ="ACTION_TYPE_TRIGGER_EVENT_FOCUS";

    public function __construct($selector = null, $actionEvent = self::ACTION_TYPE_TRIGGER_EVENT_CLICK){
        $params = array();
        $params[] = $actionEvent;
        parent::__construct($selector, self::ACTION_TYPE_TRIGGER, "triggerEventManager", "TriggerEventManager", $params);
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

