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
namespace WasabiLib\Form;


use Zend\Form\ElementInterface;
use Zend\Form\Form;
use WasabiLib\Ajax\DomManipulator;

class FormExtended extends Form {

    private $errorMessageString = false;
    private $badSelectorString = false;
    protected  $translator = false;
    protected  $logger;


    public function __construct($name=null, $translator){
        $this->translator = $translator;
        parent::__construct($name);
    }

    /**
     * @return Transl
     */
    protected function translator(){
        return $this->translator;
    }

    public function getErrorMessagesAsString($separator = "<br>") {

        if (!$this->errorMessageString) {
            $this->createErrorMessageAndBadSelectorString($separator);
        }
        return $this->errorMessageString;
    }

    public function getPreConfiguredDomManipulator() {
            if(!$this->badSelectorString){
                $this->createBadSelectorString();
            }
            $css = new DomManipulator($this->badSelectorString, "background-color", "rgb(224, 242, 252)");
            return $css;

    }

    private function createErrorMessageAndBadSelectorString($separator){
        $messages = array();
        $badSelectors = array();

        foreach ($this->getMessages() as $validatorKey => $message) {
            $messages[] = reset($message);
            $badSelectors[] = "[name =" . $validatorKey . "]";
        }
        $this->errorMessageString = implode($separator, $messages);
        $this->badSelectorString = implode(",", $badSelectors);
    }

    private function createBadSelectorString(){
        $badSelectors = array();
        foreach ($this->getMessages() as $validatorKey => $message) {
            $badSelectors[] = "[name= ".$validatorKey." ]";
        }
        $this->badSelectorString = implode(",", $badSelectors);
    }

    /**
     * Set a single option for an element
     *
     * @param  string $key
     * @param  mixed $value
     * @return self
     */
    public function setOption($key, $value) {
        // TODO: Implement setOption() method.
    }
}