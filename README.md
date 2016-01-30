#WasabiLib ZF2 Ajax Module
[![Latest Stable Version](https://poser.pugx.org/wasabi/wasabilib/v/stable)](https://packagist.org/packages/wasabi/wasabilib) [![Total Downloads](https://poser.pugx.org/wasabi/wasabilib/downloads)](https://packagist.org/packages/wasabi/wasabilib) [![Latest Unstable Version](https://poser.pugx.org/wasabi/wasabilib/v/unstable)](https://packagist.org/packages/wasabi/wasabilib) [![License](https://poser.pugx.org/wasabi/wasabilib/license)](https://packagist.org/packages/wasabi/wasabilib)

WasabiLib is a ready-to-use ajax module. 
You do not need to write a single line JavaScript code to create ajax requests and responses. 
With the PHP ajax classes you have complete control of the DOM from server-side. 


It comes with ready-to-use components. Most important are:

**1. InnerHtml** - replace, append or remove HTML snippets from HTML elements.  http://www.wasabilib.org/application/pages/examples#inner_html

**2. DomManipulator** - manipulates existing elements. http://www.wasabilib.org/application/pages/examples#dom_manipulator

**3. TriggerEventManager** - trigger JavaScript events from within the PHP code  http://www.wasabilib.org/application/pages/examples#trigger_event_manager

And a lot of extensions based on these components

##Requirements
For a full integration the following assets are needed:
jQuery, Bootstrap, FontAwesome

##Installation
For a not yet existing project we recommend the WasabiLib Skeleton Application https://github.com/WasabiLib/wasabilib_zf2_skeleton_application

###For existing ZF2 projects 
1. Clone the repository into your vendor folder
2. Copy the css and img files under venodor/wasabilib/wasabi into the public css and img folder
3. Copy the wasabilib.min.js, wasabi.gritter.js, wasabi.suggest.js into public/js
4. Include the js-files in the layout.phtml as they are loaded after jquery
5. Include the CSS files as they are loaded after bootstrap.css


##Please Visit http://www.wasabilib.org for detailed information.
