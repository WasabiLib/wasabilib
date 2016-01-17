Wasabi Suggest Field
====================

The Wasabi Suggest Field is based on the Wasabi Ajax approach. 
So there is no need to write any line of JavaScript code to use it.

Files to include
----------------
If you are using the Wasabi Skeleton Application the Wasabi Suggest Field is ready to use.
If not you need to put the following line into the header section of your layout.phtml:
    <script type="text/javascript" src="/vendor/wasabi/js/wasabi.suggest.js"></script>

The wasabi.suggest.js manages all key events and visual effects.

The CSS file is also needed  
    <link href="/wasabilib_assets/css/wasabi.suggest.css" media="screen" rel="stylesheet" type="text/css">

Usage
=====
You can initialize the suggest field with the following line of html code:

    <input class="ajax_element wasabi_suggest_features form-control input-md" id="yourElementId" type="text" data-event="keyup" data-cb="suggest" data-href="/path/to/controller" data-digits="3" data-json="{}">

The necessary css classes for the ajax management are *ajax_element* and *wasabi_suggest_features*. 
The other css classes are bootstrap standard and can be customized in some cases. 

HTML5 attributes: data-href, data-timeout, data-disabletime
---------------------------------------------------

Data-href: Defines the path on the server where the suggestions come from.

Data-timeout: Integer value defines the delay in ms the request is send to the server. The default value is 0.

Data-disabletime: Defines how long the element is disabled. Default is as long the request is running.


Server-side
===========

Read the example below

    
    use WasabiLib\Ajax\Response;
    use WasabiLib\Suggest\Result;
    use WasabiLib\Suggest\ResultCell;
      
    public function GetMyHintsAction() {
        //grab the content of the input field and process it the way you need
        $content = $this->params()->fromQuery("content");
        //create a WasabiLib\Suggest\Result object with the id of the input field id
        $suggestResult = new Result("inputId");
        $suggestArray = array("hint1", "hint2","hint3",....,"hintN");

        foreach ($suggestArray as $s) {
            //create as much WasabiLib\Ajax\ResultCell objects as you need
            $resultCell = new ResultCell($s);
            //Each array key value pair creates an hidden field to send data within forms
            $resultCell->setData(array("value" => $s));            
            //add the cell to the ResultCell
            $suggestResult->addResultCell($resultCell);
        }
        //returning the response
        $response = new Response();
        $response->add($suggestResult);
        return $this->getResponse()->setContent($response);
    }
 
Customizing the view of ResultCells
====================================
  You can use the following methods to customize the view of the ResultCell:
         
        ResultCell::setTextLabel()
        ResultCell::setBadge() //sets a badge on the right
        ResultCell::setImage() //parameter is the image source 45x45 pixel
        ResultCell::setIcon() //creates an i element with a given font awesome or glyphicon icon (library must be included)
        //if icon is used the image will be overwritten 
        ResultCell::setSubTitle() //sets a subtitle beneath a text label
 
You can also use the following method to set an action to a cell:

        ResultCell::setAction(/path/to/controller/action, $isAjax = true)

Default the action will be requested as an ajax request. By setting the second parameter to false you can force the cell to be a standard link.


Setting hidden values
=====================
Often you need to transfer data which is different to the value shown in the suggest field e.g. some database ids.
You can set data by using the following method:

    ResultCell::setData(array('someId' => 'value', 'someMore' => 'anotherValue'));

When focus onto a suggest cell hidden fields will be created for every key-value pair given.
        
    <input type="hidden" class="yourElementId-hidden" name="someId" value="value">
    <input type="hidden" class="yourElementId-hidden" name="someMore" value="anotherValue">

For more details see the form example on www.wasabilib.org
