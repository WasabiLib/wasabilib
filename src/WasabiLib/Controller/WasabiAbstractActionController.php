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

namespace WasabiLib\Controller;

use WasabiLib\Ajax\Redirect;
use WasabiLib\Ajax\Response;
use Zend\Mvc\Controller\AbstractActionController;

abstract class WasabiAbstractActionController extends AbstractActionController {

    protected $translator = false;
    protected $sessionManager = false;


    protected function translator() {
        if (!$this->translator)
            $this->translator = $this->getServiceLocator()->get('translator');
        return $this->translator;
    }

    /**
     * @return SessionManager
     */
    protected function sessionManager() {
        if (!$this->sessionManager) {
            $this->sessionManager = $this->getServiceLocator()->get('SessionManager');
        }
        return $this->sessionManager;
    }


    protected function ajaxRedirectToRoute($route) {
        $response = new Response();
        $redirect = new Redirect($route);
        $response->add($redirect);
        return $this->getResponse()->setContent($response);
    }


} 