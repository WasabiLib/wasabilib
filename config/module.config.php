<?php
/**
 * WasabiLib http://www.wasabilib.org
 *
 * @link https://github.com/WasabilibOrg/wasabilib
 * @license The MIT License (MIT) Copyright (c) 2015 Nico Berndt, Norman Albusberger, Sascha Qualitz
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
   THE SOFTWARE.
 */

return array(
    'view_manager' => array(
        'template_map' => array(
            'view/button'           => __DIR__ . '/../view/wasabi-lib/view/button.phtml',
            'wizard/wizard'           => __DIR__ . '/../view/wasabi-lib/wizard/wizard.phtml',
            'wizard/wizardButton'           => __DIR__ . '/../view/wasabi-lib/wizard/wizardButton.phtml',
            'wizard/breadcrumb'           => __DIR__ . '/../view/wasabi-lib/wizard/breadcrumb.phtml',
            'wizard/wizardFormCurrentStep'           => __DIR__ . '/../view/wasabi-lib/wizard/wizardFormCurrentStep.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);