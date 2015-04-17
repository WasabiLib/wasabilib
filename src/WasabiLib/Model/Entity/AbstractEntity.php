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

namespace WasabiLib\Model\Entity;

abstract class AbstractEntity {


    public function data() {
        $data = array();

        $reflection = new \Zend\Code\Reflection\ClassReflection($this);

        $propertyReflections = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);

        /* @var  $propertyReflection \Zend\Code\Reflection\PropertyReflection */
        foreach ($propertyReflections as $propertyReflection) {

            $methodReflection = new \Zend\Code\Reflection\MethodReflection($this, $propertyReflection->getName());
            $data[$propertyReflection->getName()] = $methodReflection->invoke($this);
        }
        return $data;
    }

    public function setData($data) {
        $reflection = new \Zend\Code\Reflection\ClassReflection($this);

        $propertyReflections = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);

        /* @var  $propertyReflection \Zend\Code\Reflection\PropertyReflection */
        foreach ($propertyReflections as $propertyReflection) {
            $methodReflection = new \Zend\Code\Reflection\MethodReflection($this, "set" . ucfirst($propertyReflection->getName()));
            $methodReflection->invoke($this, $data[$propertyReflection->getName()]);
        }
    }
}