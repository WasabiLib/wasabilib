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

use WasabiLib\Wizard\ArrayIterator;
use Countable;
use IteratorAggregate;

class StepCollection implements IteratorAggregate, Countable {

    /**
     * @var array
     */
    protected $steps = array();


    /**
     * @param  StepController $step
     * @return self
     */
    public function add(StepController $step) {
        if ($this->has($step)) {
            return $this;
        }

        $this->steps[] = $step;
        return $this;
    }

    /**
     * @param  string|StepController $identifier
     * @return self
     */
    public function remove($identifier) {
        $has = $this->has($identifier);
        if ($has === false) {
            return false;
        }

        array_splice($this->steps, $has, 1);
        return true;
    }

    /**
     * @param  string|StepController $identifier
     * @return bool|int
     */
    public function has($identifier) {
        if ($identifier instanceof StepController) {
            $identifier = $identifier->getName();
        }

        $steps = $this->steps;
        $result = array_filter($steps, function ($step) use ($identifier) {
            return $step->getName() === $identifier;
        });
        return !empty($result) ? key($result) : false;
    }

    /**
     * vorgänger
     * @param $identifier
     * @return bool|mixed
     */
    public function getAncestor($identifier) {
        $key = $this->has($identifier);
        if ($key > 0) {
            $iterator = $this->getIterator();
            $iterator->seek($key - 1);
            return $iterator->current();
        }
        return false;

    }

    /**
     * nachfolger
     * @param $identifier
     * @return bool|mixed
     */
    public function getDescendant($identifier) {
        $iterator = $this->getIterator();
        $key = $this->has($identifier);
        if ($key < $iterator->count()) {
            $iterator->seek($key + 1);
            return $iterator->current();
        }
        return false;

    }

    /**
     * @return StepController
     */
    public function getFirst() {
        if (!$this->steps) {
            return null;
        }

        $values = array_values($this->steps);

        return array_shift($values);
    }

    /**
     * @param  string|StepController $identifier
     * @return bool
     */
    public function isFirst($identifier) {
        if ($identifier instanceof StepController) {
            $identifier = $identifier->getName();
        }
        return reset($this->steps)->getName() === $identifier;
    }

    /**
     * @return StepController
     */
    public function getLast() {
        if (!$this->steps) {
            return null;
        }

        $values = array_values($this->steps);

        return array_pop($values);
    }

    /**
     * @param  string|StepController $identifier
     * @return bool
     */
    public function isLast($identifier) {
        if ($identifier instanceof StepController) {
            $identifier = $identifier->getName();
        }
        return end($this->steps)->getName() === $identifier;
    }


    /**
     * return true if IdentifierStep is the penultimate Step
     * @param $identifier
     * @return bool
     */
    public function isPenultimate($identifier) {
        $this->getLast();
        $iterator = $this->getIterator();
        $iterator->seek($iterator->count() - 2);
        $penultimate = $iterator->current();
        if ($identifier instanceof StepController) {
            $identifierName = $identifier->getName();
            if ($identifierName == $penultimate->getName())
                return true;
            else return false;
        }
        return false;
    }

    /**
     * @see IteratorAggregate
     * @return ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->steps);
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->steps);
    }

}