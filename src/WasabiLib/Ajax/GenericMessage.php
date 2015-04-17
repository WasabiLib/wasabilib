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


class GenericMessage extends  ResponseType{

    /**
     * @var string
     */
    protected $selector;

    /**
     * @var string
     */
    protected $actionType;

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @param string $selector
     * @param string $actionType
     * @param string $eventType
     * @param array $params
     */
    public function __construct($selector = null, $actionType = null, $eventType = '', $eventId = '', $params = array()){
        $this->selector = $selector;
        $this->params = $params;
        $this->actionType = $actionType;
        $this->setEventName($eventType);
        $this->setEventId($eventId);
    }

    /**
     * @param string $selector
     */
    public function setSelector($selector){
        $this->selector = $selector;
    }

    /**
     * @return string
     */
    public function getSelector(){
        return $this->selector;
    }

    /**
     * @return array
     */
    public function message() {
       $a = array('selector' => $this->selector, 'params' => $this->params, 'actionType' => $this->actionType);
       return $a;

    }

    /**
     * @param string $const
     */
    public function setActionType($const) {
        $this->actionType = $const;
    }

    /**
     * @param array $paramsArray
     */
    public function setParams($paramsArray){
        $this->params = $paramsArray;
    }

    /**
     * @return array
     */
    public function getParams(){
        return $this->params;
    }
}
