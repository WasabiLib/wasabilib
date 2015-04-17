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
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\View\Strategy\PhpRendererStrategy;
use Zend\View\View;

/**
 * Class WasabiModal
 * @package WasabiLib\Modal
 * @example
 *
 *  $response = new Response();
    $modal = new Modal\WasabiModal("#wasabi_modal", $this->getServiceLocator());
    $modal->setTitle("Dies ist ein Modal-Fenster");
    $modal->setContent("... und sein zugehöriger Content.");

    $response->add($modal);

    return $this->ajaxResponse($response);
 */
class WasabiViewModel extends ViewModel {
    const ID = "id";
    const VIEW_MODEL_CLASS = "class";
    const ATTRIBUTES = "attributes";
    const CONTENT = "content";

    /**
     * @var string
     */
    protected $defaultTemplateExtension = "phtml";
    protected $defaultTemplateBasePath = "";
    protected $defaultTemplateFullPath = "";

    /**
     * Constructor
     *
     * @param  null|array|Traversable $variables
     * @param  array|Traversable $options
     */
    public function __construct($variables = null, $options = null) {
        parent::__construct($variables, $options);

        $this->defaultTemplateBasePath = __DIR__. "../../../../view/wasabi-lib/";
        $this->defaultTemplateFullPath = $this->defaultTemplateBasePath.$this->getTemplate().".".$this->defaultTemplateExtension;
    }

    /**
     * Sets HTML id of the view model.
     * @param string $id
     */
    public function setId($id) {
        $this->setVariable(self::ID, self::ID.'="'.$id.'"');
    }

    /**
     * Returns HTML id of the view model.
     * @return string
     */
    public function getId() {
        return $this->extractValueFromAssignmentString($this->getVariable(self::ID));
    }

    /**
     * Adds a css class name.
     * @param mixed $class
     */
    public function addClass($class) {
        $this->setVariable(self::VIEW_MODEL_CLASS, $this->addValue($this->getVariable(self::VIEW_MODEL_CLASS), $class));
    }

    /**
     * Sets the css class and deletes all class names that has been set or added before.
     * @param mixed $class
     */
    public function setClass($class) {
        $this->setVariable(self::VIEW_MODEL_CLASS, $class);
    }

    /**
     * Returns the css class
     * @return mixed
     */
    public function getClass() {
        return $this->getVariable(self::VIEW_MODEL_CLASS);
    }

    /**
     * Adds an arbitrary attribute to the HTML element. For example addAttribute("data-dummy", "bar").
     * @param $name
     * @param $value
     */
    public function addAttribute($name, $value) {
        $this->setVariable(self::ATTRIBUTES, $this->addValue($this->getVariable(self::ATTRIBUTES), $this->assembleAssignmentString($name, $value)));
    }

    /**
     * Sets an array of attributes.
     * @param array $attributes An associative array [$name => $value].
     */
    public function setAttributes($attributes) {
        $this->setVariable(self::ATTRIBUTES, "");
        foreach($attributes as $name => $value) {
            $this->addAttribute($name, $value);
        }
    }

    /**
     * Returns all attributes as string.
     * @return string
     */
    public function getAttributes() {
        return $this->getVariable(self::ATTRIBUTES);
    }

    /**
     * Sets view model as content of the view model.
     * @param ViewModel $content
     */
    public function setContent(ViewModel $content) {
        $this->addChild($content, self::CONTENT);
    }

    /**
     * Returns the content of the view model.
     * @return ViewModel
     */
    public function getContent() {
        return $this->getChildrenByCaptureTo(self::CONTENT);
    }

    /**
     * Returns this view model as rendered html string.
     * @param PhpRenderer $renderer optional
     * @return mixed
     */
    public function html(PhpRenderer $renderer = null) {
        $view = new View();
        $view->setResponse(new Response());
        $resolver = new TemplateMapResolver();

        if($renderer === null) {
            $renderer = new PhpRenderer();
            $resolver->add($this->getTemplate(), $this->getDefaultTemplatePath($this->getTemplate()));
            $renderer->setResolver($resolver);
        } else {
            $renderer->resolver()->attach($resolver);
        }

        $view->getEventManager()->attach(new PhpRendererStrategy($renderer));

        $view->render($this);

        return $view->getResponse()->getContent();
    }

    /************************
     * HELPER METHODS
     ***********************/

    /**
     * Returns the full path to the given template. The template has to be in the view/wasabi-lib/view folder.
     * @param $template
     * @return string
     */
    protected function getDefaultTemplatePath($template) {
        return $this->defaultTemplateBasePath.$template.".".$this->defaultTemplateExtension;
    }

    /**
     * Returns the value after the equality sign.
     * @param $assignment
     * @return string | null
     */
    protected function extractValueFromAssignmentString($assignment) {
        if(!$assignment) {
            return null;
        }
        $assignmentArray = explode("=", $assignment);
        return isset($assignmentArray[1]) ? $assignmentArray[1] : null;
    }

    /**
     * Returns a assignment string like a=b.
     * @param $name Pre equality sign value.
     * @param $value Post equality sign value.
     * @param string $quote Kind of quotes to use for the post equality sign value.
     * @return string
     */
    protected function assembleAssignmentString($name, $value, $quote = '"') {
        if($quote === '"') {
            return $name.'="'.$value.'"';
        } else if($quote === "'") {
            return $name."='".$value."'";
        } else if($quote === false) {
            return $name."=".$value;
        }

    }

    /**
     * Returns the given $valueToAdd parameter if $value is null. Else it returns $value." ".$valueToAdd.
     * @param string $value
     * @param string $valueToAdd
     * @return string
     */
    protected function addValue($value, $valueToAdd) {
        if($value) {
            $returnValue = $value." ".$valueToAdd;
        } else {
            $returnValue = $valueToAdd;
        }

        return $returnValue;
    }
}