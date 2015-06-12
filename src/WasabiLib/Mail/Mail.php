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
namespace WasabiLib\Mail;

use WasabiLib\main;
use Zend\Http\Response;
use Zend\Mail\Message;
use Zend\Mail\Header\ContentType;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Strategy\PhpRendererStrategy;
use Zend\View\View;

class Mail {

    /**
     * @var Message
     */
    protected $message;

    const MESSAGETYPEPLAINTEXT = "text";
    const MESSAGETYPEHTML = "html";

    protected $messageType = "text";
    protected $body = null;
    protected $renderer = null;
    protected $transporter = null;

    /**
     * @param string $from
     * @param string $name
     * @param string | \Zend\View\Model\ViewModel $body
     * @param \Zend\View\Renderer\RendererInterface $renderer
     */
    public function __construct($from = "", $name = "", $body = null, RendererInterface $renderer = null) {
        $this->message = new Message();
        $this->message->setEncoding("UTF-8");
        if($from) $this->message->setFrom($from, $name);
        $this->body = $body;
        $this->renderer = $renderer;

    }

    /**
     * @param string | \Zend\View\Model\ViewModel $body
     */
    public function setBody($body) {
        $this->body = $body;
    }

    /**
     * @param string $from
     * @param string $name
     */
    public function setFrom($from,$name = null){
        $this->message->setFrom($from,$name);
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject) {
        $this->message->setSubject($subject);
    }

    /**
     * @param string $emailAddress
     */
    public function setTo($emailAddress) {
        $this->message->setTo($emailAddress);
    }

    /**
     * @param string $emailAddress
     */
    public function addRecipient($emailAddress) {
        $this->message->addTo($emailAddress);
    }

    /**
     * @param string $emailAddress
     */
    public function addBccRecipient($emailAddress) {
        $this->message->addBcc($emailAddress);
    }

    /**
     * @param string $emailAddress
     */
    public function addCcRecipient($emailAddress) {
        $this->message->addCc($emailAddress);
    }

    /**
     * @param string $abstractConst
     */
    protected function setMessageType($abstractConst) {
        $this->messageType = $abstractConst;
    }

    /**
     * @param mixed $transporter
     */
    public function setTransporter($transporter) {
        $this->transporter = $transporter;
    }

    /**
     * @return mixed
     */
    public function getTransporter() {
        return $this->transporter;
    }

    /**
     * @param null|\Zend\View\Renderer\RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer) {
        $this->renderer = $renderer;
    }

    /**
     * @return null|\Zend\View\Renderer\RendererInterface
     */
    public function getRenderer() {
        return $this->renderer;
    }

    /**
     * Sends the email.
     */
    public function send() {
        $this->prepare();

        if ($this->messageType == self::MESSAGETYPEHTML) {
            $type = new ContentType();
            $type->setType('text/html');
            $this->message->getHeaders()->addHeader($type);
        }
        else{
            $textType = new ContentType();
            $textType->setType("text/plain");
            $this->message->getHeaders()->addHeader($textType);
        }

        $this->transporter->send($this->message);
    }

    /**
     * Prepares the email for the sending.
     */
    protected function prepare(){
        if(is_a($this->body, "Zend\\View\\Model\\ViewModel")) {
            $view = new View();
            $view->setResponse(new Response());

            $view->getEventManager()->attach(new PhpRendererStrategy($this->renderer));

            $view->render($this->body);

            $this->message->setBody($view->getResponse()->getContent());
            $this->messageType = "html";
        } else {
            $this->message->setBody($this->body);
            $this->messageType = "text";
        }
    }
}