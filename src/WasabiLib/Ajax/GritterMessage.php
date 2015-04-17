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

/**
 * Class Gritter
 * @package WasabiLib\Ajax
 *  title: 'Jane Doe',
 * text: 'Online',
 * image: 'assets/img/user3.png',
 * time: 2000,
 * after_close: function() {
 * $.gritter.add({
 * title: 'Jordan Smith',
 * text: 'Offline',
 * image: 'assets/img/user5.png',
 * time: 2000
 */
class GritterMessage implements ResponseTypeInterface {
    const POSITION_BOTTOM_LEFT = 'bottom-left';
    const POSITION_BOTTOM_RIGHT = 'bottom-right';
    const POSITION_TOP_LEFT = 'top-left';
    const POSITION_TOP_RIGHT = 'top-right';

    private $text;
    private $imageUrlString = false;
    private $time;
    private $afterCloseCallBack;
    private $title;
    private $sticky = false;
    private $position = false;
    private $cssClass = false;
    private $fontAwesomeIconName = false;
    private $fadeInTime = false;
    private $fadeOutTime = false;

    private $genericMessage = null;
    private $type = false;

    const TYPE_INFO = "typeInfo";
    const TYPE_ALERT = "typeAlert";
    const TYPE_SUCCESS = "typeSuccess";
    const TYPE_ERROR = "typeError";


    public function __construct($text=null, $title=null,$time = 3300) {
        $this->genericMessage = new GenericMessage(null,null,"gritter", "Gritter");
        $this->text = $text;
        $this->title = $title;
        $this->time = $time;
    }

    public function message() {
        return $this->genericMessage->message();
    }

    public function setSticky($bool){
        $this->sticky = $bool ? true :false;
    }

    public function setCssClass($cssClass){
        $this->cssClass = $cssClass;
    }

    public function setType($typeConst,$withIcon = true){
        $this->type = $typeConst;
        switch($this->type){
            case self::TYPE_ERROR:
                $this->setCssClass("gritterError");
                $this->setIcon("times");
                break;
            case self::TYPE_INFO:
                $this->setCssClass("gritterInfo");
                $this->setIcon("info");
                break;
            case self::TYPE_SUCCESS:
                $this->setCssClass("gritterSuccess");
                $this->setIcon("check");
                break;
            case self::TYPE_ALERT:
                $this->setCssClass("gritterAlert");
                $this->setIcon("exclamation-triangle");
                break;
        }
    }

    public function setIcon($fontAwesomeIconName){
        $this->fontAwesomeIconName = $fontAwesomeIconName;
    }

    private function prepare(){
        if($this->imageUrlString==true){
            $this->fontAwesomeIconName = false;
        }
    }

    /**
     * @param boolean $fadeInTime
     */
    public function setFadeInTime($fadeInTime) {
        $this->fadeInTime = $fadeInTime;
    }

    /**
     * @param boolean $fadeOutTime
     */
    public function setFadeOutTime($fadeOutTime) {
        $this->fadeOutTime = $fadeOutTime;
    }

    /**
     * @param string $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    private function params() {

        $array = array(
            'text' => $this->text,
            'title' => $this->title,
            'time' => $this->time,
            'fade_in_speed' => $this->fadeInTime,
            'fade_out_speed' => $this->fadeOutTime,
            'image' => $this->imageUrlString,
            'after_close' => $this->afterCloseCallBack,
            'sticky' => $this->sticky,
            'class_name' => $this->cssClass,
            'position' => $this->position,
            'icon' => $this->fontAwesomeIconName
        );

        return $array;
    }

    /**
     * @param mixed $afterCloseCallBack
     */
    public function setAfterCloseCallBack($afterCloseCallBack) {
        $this->afterCloseCallBack = $afterCloseCallBack;
    }

    /**
     * @param null $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @param int $time
     */
    public function setTime($time) {
        $this->time = $time;
    }

    /**
     * @param null $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }


    public function setImage($urlString){
        $this->imageUrlString = $urlString;
    }



    public function toArray() {
       $this->prepare();
       $this->genericMessage->setParams($this->params());
       return $this->genericMessage->toArray();
    }
}