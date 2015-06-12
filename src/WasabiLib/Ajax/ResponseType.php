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
namespace WasabiLib\Ajax;

/**
 * Abstract class for all WasabiLib response classes.
 * Class ResponseType
 * @package WasabiLib\Ajax
 */
abstract class ResponseType implements ResponseTypeInterface {

    /**
     * @var string
     */
    protected $recipientType = "ajaxResponse";

    /**
     * A status code which describes whether the processed request
     * @var int
     */
    protected $status = 200;

    /**
     * The message which is sent to the browsers WasabiLib JavaScript code to used within a recipient.
     * @var string
     */
    protected $message = "";

    /**
     * @param mixed $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * the recipient.js is registered under this name
     * @param string $recipientType
     */
    public function setRecipientType($recipientType) {
        $this->recipientType = $recipientType;
    }

    /**
     * @return string
     */
    public function recipientType() {
        return $this->recipientType;
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
        return array('recipientType' => $this->recipientType(), 'status' => $this->status, 'message' => $this->message());
    }











} 