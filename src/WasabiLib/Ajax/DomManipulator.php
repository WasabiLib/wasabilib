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


class DomManipulator extends GenericMessage {

    const ACTION_TYPE_CSS = "ACTION_TYPE_CSS";
    const ACTION_TYPE_SLIDEDOWN = "ACTION_TYPE_SLIDEDOWN";
    const ACTION_TYPE_FADEOUT = "ACTION_TYPE_FADEOUT";
    const ACTION_TYPE_FADEIN = "ACTION_TYPE_FADEIN";
    const ACTION_TYPE_ATTR = "ACTION_TYPE_ATTR";
    const ACTION_TYPE_MODAL = "ACTION_TYPE_MODAL";
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

        parent::__construct($selector, $actionType, "domManipulator", "DomManipulator", $params);
    }
}

