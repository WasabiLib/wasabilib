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

namespace WasabiLib\Wizard;
class ClosureArguments {
    /**
     * This property contains all added elements.
     *
     * @var array
     */
    protected $_elements;

    /**
     * Constructor method
     * Expects an array as a parameter with default elements.
     *
     * @param   array $elements
     */
    public function __construct($elements = array()) {
        $this->_elements = (array)$elements;
    }

    /**
     * Returns a value of an element in the list.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * Sets a value of an element in the list.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value = null) {
        $this->set($key, $value);
    }

    /**
     * Returns a value of an element in the list.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        return isset($this->_elements[$key]) ? $this->_elements[$key] : null;
    }

    /**
     * Sets a value of an element in the list.
     *
     * @param string $key
     * @param mixed $value
     * @return Enlight_Collection_ArrayCollection
     */
    public function set($key, $value) {
        $this->_elements[$key] = $value;
        return $this;


    }

    /**
     * Captures the magic phone calls and executes them accordingly.
     * @param string $name
     * @param array $args
     * @return mixed
     */
    function __call($name, $args = null) {
        switch (substr($name, 0, 3)) {
            case 'get':
                $key = strtolower(substr($name, 3, 1)) . substr($name, 4);
                $key = strtolower(preg_replace('/([A-Z])/', '_$0', $key));
                return $this->get($key);
            case 'set':
                $key = strtolower(substr($name, 3, 1)) . substr($name, 4);
                $key = strtolower(preg_replace('/([A-Z])/', '_$0', $key));

                return $this->set($key, isset($args[0]) ? $args[0] : null);
            default:
                throw new Enlight_Exception(
                    'Method "' . get_class($this) . '::' . $name . '" not found failure',
                    Enlight_Exception::METHOD_NOT_FOUND
                );
        }
    }
}