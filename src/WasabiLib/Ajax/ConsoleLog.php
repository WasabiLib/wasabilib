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
 * With this class one can send messages wih the php function var_export to the browsers console.
 * Class ConsoleLog
 * @example
 * $consoleLog = new ConsoleLog("what to log");
 *
 * $consoleLog = new ConsoleLog(get_class($this));
 *
 * $response = new Response();
 * $response->add($consoleLog);
 *
 * return $this->getResponse()->setContent($response);
 * @package WasabiLib\Ajax
 */
class ConsoleLog extends ResponseType{

    private $varExport;

    /**
     * Constructor
     * @param $whatToLog
     */
    public function __construct($whatToLog){
        $this->varExport = var_export($whatToLog, true) ;
        $this->setRecipientType("ConsoleLog");
    }

    /**
     * Returns the content of the message which is returned to the client browser.
     * @return string
     */
    public function message() {
        return "*************  WasabiLib Ajax Console Log   ******************
        ".$this->varExport."\n************************************************************";
    }
}