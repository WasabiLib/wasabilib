<?php
/**
 * @link https://github.com/WasabilibOrg/wasabilib
 * Copyright 2015 www.wasabilib.org
 * @license Apache License, Version 2.0
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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