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


abstract class ResponseType implements ResponseTypeInterface {

    protected $eventName = "ajaxResponse";
    protected $eventId = "";
    protected $status = 200;
    protected $message;

    /**
     * @param mixed $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * the recipient.js is registered under this name
     * @param string $eventName
     */
    public function setEventName($eventName) {
        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function eventName() {
        return $this->eventName;
    }

    /**
     * the recipient.js is registered under this id
     * @param string $eventId
     */
    public function setEventId($eventId) {
        $this->eventId = $eventId;
    }

    /**
     * @return string
     */
    public function eventId() {
        return $this->eventId;
    }

    /**
     * @param int $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function status() {
        return $this->status;
    }

    public function toArray(){
        return array('eventName' => $this->eventName, 'eventId' => $this->eventId, 'status' => $this->status, 'message' => $this->message());
    }











} 