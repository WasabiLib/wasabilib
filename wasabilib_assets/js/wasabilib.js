/**
 * Created by sascha.qualitz on 26.05.15.
 */
//--------------HTML Objects------------------------------------
//-------------  Common Ajax Requests  --------------------------
/**
 * Constructor for the DataExtractor class and all of its subclasses. A DataExtractor extracts data of html elements
 * like f.e. div or form elements.
 * @constructor
 */
function DataExtractor() {
    var _self = this;
    _self.element = null;
};

/**
 * Setter for the target attribute.
 * @param target {object|string} A parameter which allows to identify the target of the extraction process.
 */
DataExtractor.prototype.setTarget = function (target) {
    var _self = this;
    _self.element = typeof target === "object" ? target.closest(".ajax_element") : $('#' + target.closest(".ajax_element"));
}

/**
 * Returns the content of the element linked to the attribute target.
 * @returns {*|string|array} All transmission capable data.
 */
DataExtractor.prototype.getTransmissionCapableContent = function () {
    var _self = this;
    return _self.element.attr('data-json') ? JSON.parse(_self.element.attr('data-json')) : null;
};

/**
 * Returns the target uri of the element.
 * @returns {string}
 */
DataExtractor.prototype.getUri = function () {
    var _self = this;

    return _self.element.attr('href') ? _self.element.attr('href') : _self.element.attr('data-href') ? _self.element.attr('data-href') : null;
};

/**
 * Returns a jQuery object which is identified by the attribute target.
 * @returns {DataExtractor.element}
 * @see DataExtractor.prototype.setTarget
 */
DataExtractor.prototype.getElement = function() {
    var _self = this;
    return _self.element;
};

/**
 * Constructor of the callback object.
 * @param key {string} The key used to identify a particular Callback object. Used by the html attribute data-cb.
 * @param dataExtractor {DataExtractor} An object to extract data from a html element in a transmission capable kind.
 * @constructor
 */
function Callback(key, dataExtractor){
    var _self = this;
    _self.key = key;
    _self.dataExtractor = dataExtractor;
    _self.condition = new Condition(function() {
        return true;
    });
}

/**
 * Returns the key used to identify a particular Callback object. Used by the html attribute data-cb.
 * @return {string}
 */
Callback.prototype.getKey = function(){
    var _self = this;
    return _self.key;
};

/**
 * Initializes data extractor to extract the data.
 * @param event
 */
Callback.prototype.execute = function(event){
    var _self = this;
    _self.dataExtractor.setTarget($(event.target));
};

/**
 * Sets the condition which defines whether a request is allowed to send or not.
 * @param condition {Condition}
 * @see Condition
 */
Callback.prototype.setCondition = function(condition){
    var _self = this;
    _self.condition = condition;
};

/**
 * Returns the condition which defines whether a request is allowed to send or not.
 * @return {Condition}
 */
Callback.prototype.getCondition = function(){
    var _self = this;
    return _self.condition;
};

/**
 * Created by sascha.qualitz on 28.05.15.
 */
/**
 * The constructor of the object which defines if  a request is allowed to send or not.
 * @param conditionHandle
 * @constructor
 */
function Condition(conditionHandle){
    var _self = this;

    _self.handle = conditionHandle;
    _self.timeOut = 0;
    _self.actualTimerId = null
}

/**
 * Evaluates whether a request is allowed to send or not and returns true or false.
 * @param event
 * @returns {bool}
 */
Condition.prototype.check = function(event){
    var _self = this;
    return _self.handle(event);
};

/**
 * Starts a timer with a time defined by the attribute timeOut.
 * @param fncHandler {function} The function which will be executed when the time has expired.
 * @see setTimeout
 */
Condition.prototype.checkTimeOut = function(fncHandler){
    var _self = this;

    if(_self.actualTimerId) {
        clearTimeout(_self.actualTimerId);
    }
    _self.actualTimerId = setTimeout(function() {
        fncHandler();
        _self.actualTimerId = null;
    }, _self.getTimeout());
};

/**
 * Sets the time for a timeout in milliseconds.
 * @param time {number}
 */
Condition.prototype.setTimeout = function(time){
    var _self = this;
    _self.timeOut = time;
};

/**
 * Returns the time for a timeout in milliseconds.
 * @returns {number}
 */
Condition.prototype.getTimeout = function(){
    var _self = this;
    return _self.timeOut;
};

/**
 * The constructor of the recipient which process the response of a request.
 * @constructor
 */
function Recipient() {
    var _self = this;

    _self.execRPCManager = null;
}

/**
 * Setter for the ExecuteRemoteProcedureCallManager.
 * @param executeRemoteProcedureCallManager {ExecuteRemoteProcedureCallManager}
 */
Recipient.prototype.setExecuteRemoteProcedureCallManager = function(executeRemoteProcedureCallManager) {
    var _self = this;

    _self.execRPCManager = executeRemoteProcedureCallManager;
}

/**
 * Update method called by the WasabiNotificationCenter to process the request.
 * @param response {object}
 */
Recipient.prototype.update = function(response){
    var _self = this;
    var message = response.message;

    _self.execRPCManager.execute(message.selector, message.actionType, message.params);
};

/**
 * Constructor of the Alert object which shows a JavaScript alert dialog filled with informations received from
 * the server.
 * @constructor
 */
function Alert(){
    Recipient.call(this);
    var _self = this;

    _self.ACTION_TYPE_JS_ALERT = "alert";
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
Alert.prototype = Object.create(Recipient.prototype);

/**
 * Update method called by the WasabiNotificationCenter.
 * @param response {object}
 * @see Constructor
 */
Alert.prototype.update = function(response){
    var _self = this;
    var message = response.message;

    _self.execRPCManager.execute(undefined, message.actionType, message.params);
};

/**
 * Constructor of ConsoleLogs. This recipient logs the received informations into the console of the used browser to
 * debug apps in development.
 * @constructor
 */
function ConsoleLog(){
    Recipient.call(this);
    var _self = this;

}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
ConsoleLog.prototype = Object.create(Recipient.prototype);

/**
 * Update method called by the WasabiNotificationCenter to process the request.
 * @param response {object}
 * @see Constructor
 */
ConsoleLog.prototype.update = function(response){
    console.log(response.message);
}

/**
 * Created by norman.albusberger on 03.07.14.
 */

/**
 * The constructor of InnerHtml. The recipient is able to add content to the DOM replace existing elements with new ones
 * or remove them from the DOM.
 * @constructor
 */
function InnerHtml(){
    Recipient.call(this);
    var _self = this;

    _self.ACTION_TYPE_REPLACE = "html";
    _self.ACTION_TYPE_APPEND = "appendTo";
    _self.ACTION_TYPE_REMOVE = "empty";
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
InnerHtml.prototype = Object.create(Recipient.prototype);

/**
 * The constructor of DomManipulator. This recipient is used to manipulate existing elements within the DOM.
 * F.e. one is able to alter the style attribute of an element or add/remove css classes.
 * @constructor
 */
function DomManipulator(){
    Recipient.call(this);
    var _self = this;
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
DomManipulator.prototype = Object.create(Recipient.prototype);

/**
 * Created by sascha.qualitz on 22.09.14.
 */

/**
 * Constructor of TriggerEventManager which can trigger JavaScript events specifed by the server response.
 * The possibleEvents attribute defines which events are allowed.
 * @constructor
 */
function TriggerEventManager(){
    Recipient.call(this);
    var _self = this;

    _self.possibleEvents = {
        ACTION_TYPE_TRIGGER_EVENT_NEXT_STEP: "nextStep"
        , ACTION_TYPE_TRIGGER_EVENT_PREVIOUS_STEP: "prevStep"
        , ACTION_TYPE_TRIGGER_EVENT_CLICK: "click"
        , ACTION_TYPE_TRIGGER_EVENT_FOCUS: "focus"
    };
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
TriggerEventManager.prototype = Object.create(Recipient.prototype);

/**
 * Update method called by the WasabiNotificationCenter to process the request.
 * @param response {object}
 * @see Constructor
 */
TriggerEventManager.prototype.update = function(response){
    var _self = this;
    var message = response.message;

    if(!_self.possibleEvents[message.params[0]]){
        console.log("The given event "+ message.params[0] + " is not a valid event name");
    } else {
        _self.execRPCManager.execute(message.selector, message.actionType, [_self.possibleEvents[message.params[0]]]);
    }
};

/**
 * Constructor of Gritter recipient. It is used to display the Growl-like Gritter messages filled with informations
 * from the server.
 * @constructor
 */
function Gritter(){
    Recipient.call(this);
    var _self = this;
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
Gritter.prototype = Object.create(Recipient.prototype);

/**
 * Update method called by the WasabiNotificationCenter.
 * @param response {object}
 * @see Constructor
 */
Gritter.prototype.update = function(response){
    var message = response.message;

    if(message.params.position) {
        // clean the wrapper position class
        $('#gritter-notice-wrapper').attr('class', '');
        jQuery.gritter.options.position = message.params.position;
    }

    jQuery['gritter']['add'].apply($,[message.params]);
};

/**
 * Created by sascha.qualitz on 22.09.14.
 */
/**
 * Constructor of DropzoneManager. This recipient is used to activate the Dropzone functionality when using
 * Ajax requests.
 * @constructor
 */
function DropzoneManager(){
    Recipient.call(this);
    var _self = this;

    _self.possibleEvents = {
        ACTION_TYPE_DROPZONE_DISCOVER: "discover"
    };
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
DropzoneManager.prototype = Object.create(Recipient.prototype);

/**
 * Update method called by the WasabiNotificationCenter.
 * @param response {object}
 * @see Constructor
 */
DropzoneManager.prototype.update = function(response){
    var _self = this;
    var message = response.message;

    if(response.recipientType === "ajax_new_element") {
        // Execute discover function if defined only.
        if(window["Dropzone"] != undefined) {
            try {
                _self.execRPCManager.execute("dropzone", "ACTION_TYPE_DROPZONE_DISCOVER", []);
            }
            catch(err) {
                // Dropzone already attached!";
            }

        }
    } else {
        if(!_self.possibleEvents[message.params[0]]){
            console.log("The given event "+ message.params[0] + " is not a valid event name");
        } else {
            _self.execRPCManager.execute(message.selector, message.actionType, [_self.possibleEvents[message.params[0]]]);
        }
    }
};

/**
 * Constructor of ModalWindow recipient. The recipient is able to show a bootstrap modal window and handle the case
 * of opening many modal windows on the website.
 * @constructor
 */
function ModalWindow(){
    Recipient.call(this);
    var _self = this;
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
ModalWindow.prototype = Object.create(Recipient.prototype);

/**
 * Update method called by the WasabiNotificationCenter.
 * @param response {object}
 * @see Constructor
 */
ModalWindow.prototype.update = function(response){

    var _self = this;
    var message = response.message;
    //Bei Modalwindows muss gewartet werden, bis die Animation beendet ist
    if($(".modal").size() > 1) {
        $(message.selector).attr("data-backdrop", "false");
    }
    setTimeout(function(){
        _self.execRPCManager.execute(message.selector, message.actionType, message.params);
    },200);
};

//----------Suggest-----------------
/**
 * Constructor of Suggest. This recipient process the response of suggest request and create the html elements to
 * display the suggest results to the user.
 * @constructor
 * @see WasabiSuggestFeatures
 */
function Suggest(){
    Recipient.call(this);
    var _self = this;
}

/**
 * Adds superclass methods.
 * @type {Recipient.prototype}
 */
Suggest.prototype = Object.create(Recipient.prototype);

/**
 * Update method called by the WasabiNotificationCenter. It adds the suggest results wrapped within the needed html
 * elements to the DOM.
 * @param response {object}
 * @see Constructor
 */
Suggest.prototype.update = function(response){
    var suggestInput = $(response.message.selector);
    var suggestResultId = suggestInput.attr('id')+'-result';
    var containerSize = response.message.params.size;
    var list = response.message.params.list;
    if(containerSize==null){
        containerSize = suggestInput.outerWidth();
    }
    $("#"+suggestResultId).remove();

    // Create the wrapper div to display the suggest results and inject them into that wrapper div.
    $("<div id='"+suggestResultId+"'" +
        " class='wasabi-suggest-result-container list-group' " +
        " style='display: block; width:"+containerSize+"px'>"+list+"</div>").insertAfter(suggestInput);

    // Lets the already written text within the suggest field get bold in the suggest results.
    var digestCount = suggestInput.val().length;
    var partVal = suggestInput.val().substr(0, digestCount);
    var val = "";
    $("#"+suggestResultId).find('a h4').each(function(index) {
        val = $(this).html();
        $(this).html("");
        val = val.replace("<span id='"+suggestResultId+"_bold' class='suggest_result_bold'>", "");
        val = val.replace("</span>", "");
        var startIndex = val.toLowerCase().indexOf(partVal.toLowerCase());
        var partStringOfVal = val.substr(startIndex, partVal.length);
        $(this).html(val.replace(partStringOfVal, "<span id='"+suggestResultId+"_bold' class='suggest_result_bold'>" + partStringOfVal + "</span>"));
    });
    //hidden field for forms
};

/**
 * Created by sascha.qualitz on 26.05.15.
 */

//--------------Form ------------------------------------------
/**
 * Constructor of Form to extract data from a normal form element.
 * @constructor
 */
function Form() {
    DataExtractor.call(this);
}

/**
 * Adds superclass methods.
 * @type {DataExtractor.prototype}
 */
Form.prototype = Object.create(DataExtractor.prototype);

/**
 * Setter for the target attribute.
 * @param target {object|string} A parameter which allows to identify the target of the extraction process.
 */
Form.prototype.setTarget = function (target) {
    var _self = this;
    _self.element = typeof target === "object" ? target : $('#' + target);
}

Form.prototype.getTransmissionCapableContent = function () {
    var _self = this;
    return _self.getElement().serializeArray();
};

Form.prototype.getUri = function () {
    var _self = this;
    return _self.getElement().attr('action');
};

//-------------- External Form ------------------------------------------
/**
 * Constructor of External_form. This recipient allows to extract data from a normal form element, even if the
 * submit button is outside of that form element.
 * @constructor
 */
function External_Form() {
    Form.call(this);
}

/**
 * Adds superclass methods.
 * @type {DataExtractor.prototype}
 */
External_Form.prototype = Object.create(Form.prototype);

/**
 * Setter for the target attribute.
 * @param target {object|string} A parameter which allows to identify the target of the extraction process.
 */
External_Form.prototype.setTarget = function (target) {
    var _self = this;
    _self.element = target.tagName === "FORM" ? target : $('#' + target.attr("form"));
    _self.jsonParams = $(target).attr("data-json") ? JSON.parse('[' + $(target).attr("data-json") + ']') : null;
};

/**
 * Returns the content of the element linked to the attribute target. Additionally it extracts the data-json
 * informations from the external submit button.
 * @returns {*|string|array} All transmission capable data.
 */
External_Form.prototype.getTransmissionCapableContent = function () {
    var _self = this;
    var extractedData = Form.prototype.getTransmissionCapableContent.call(this);
    if (_self.jsonParams) {
        $.merge(extractedData, _self.jsonParams);
    }
    return extractedData;
};

//-------------- Text Field ------------------------------------------
/**
 * Constructor of Text_Field. It extracts the data of the value attribute.
 * @constructor
 */
function Text_Field() {
    DataExtractor.call(this);
}

/**
 * Adds superclass methods.
 * @type {DataExtractor.prototype}
 */
Text_Field.prototype = Object.create(DataExtractor.prototype);

/**
 * Returns the content of the element value attribute.
 * @returns {*|string|array} All transmission capable data.
 */
Text_Field.prototype.getTransmissionCapableContent = function () {
    var _self = this;
    return {content:_self.getElement().val()};
};

//--------------------------------------------------------------

/**
 * Created by norman.albusberger on 03.07.14.
 */
//----------- Website Manager -----------------------------
/**
 * The constructor of the WebSiteManager which orchestrates the initialization of the Wasabi-Ajax-System.
 * @constructor
 */
function WebSiteManager() {
    var _self = this;

    _self.wasabiNotificationCenter = new WasabiNotificationCenter(_self);
    _self.executeRemoteProcedureCallManager = null;

    _self.registeredElements = {};

    _self.possibleEvents = null;
    _self.possibleCallbacks = {};

    _self.registerCommonEvents();
}

/**
 * Returns the WasabiNotificationCenter.
 * @returns {WasabiNotificationCenter|*|WebSiteManager.wasabiNotificationCenter}
 * @see WasabiNotificationCenter
 */
WebSiteManager.prototype.getWasabiNotificationCenter = function () {
    var _self = this;
    return _self.wasabiNotificationCenter;
};

/**
 * Setter for the WasabiNotificationCenter
 * @param wasabiNotificationCenter {WasabiNotificationCenter}
 */
WebSiteManager.prototype.setWasabiNotificationCenter = function (wasabiNotificationCenter) {
    var _self = this;
    _self.wasabiNotificationCenter = wasabiNotificationCenter
};

/**
 * Setter for the ExecuteRemoteProcedureCallManager.
 * @param executeRemoteProcedureCallManager
 * @see ExecuteRemoteProcedureCallManager
 */
WebSiteManager.prototype.setExecuteRemoteProcedureCallManager = function (executeRemoteProcedureCallManager) {
    var _self = this;
    _self.executeRemoteProcedureCallManager = executeRemoteProcedureCallManager;
};

/**
 * Returns the ExecuteRemoteProcedureCallManager.
 * @returns {null|*|WebSiteManager.executeRemoteProcedureCallManager}
 */
WebSiteManager.prototype.getExecuteRemoteProcedureCallManager = function () {
    var _self = this;
    return _self.executeRemoteProcedureCallManager;
};

/**
 * Registers a command object used to execute a callback which extracts the transmission capable data of an 'ajax_element' element.
 * @param command
 */
WebSiteManager.prototype.addCallback = function (callback) {
    var _self = this;

    _self.possibleCallbacks[callback.getKey()] = callback;
    _self.registerEventHandler();
};

/**
 * Register the event handler for all events used by the Wasabi-Ajax-System.
 */
WebSiteManager.prototype.registerCommonEvents = function () {
    var _self = this;

    $(document).on("wasabiNotification", null, function(event) {
        _self.getWasabiNotificationCenter().notify(event.message);
    });

    // Remove modal window from the DOM when it is closed.
    $(document).on("hidden.bs.modal", document, function(event) {
        setTimeout(function(){
            $("#"+event.target.id).remove();
        },301);
    });

    $(document)
        .on('show.bs.modal', '.modal', function(event) {
            var countOfModalWindows = $(".modal").size();
            if(countOfModalWindows > 1) {
                var zIndex = countOfModalWindows + parseInt($(this).css("zIndex"));
                $(this).css("zIndex", zIndex);
                $('.modal-backdrop.in:last').css('zIndex', zIndex - 1);
            }
        })
        .on('hide.bs.modal', '.modal', function(event) {
            var zIndexArray = new Array();
            $(".modal").each(function(index) {
                zIndexArray.push($(this).css("zIndex"));
            });
            zIndexArray.sort();
            $('.modal-backdrop.in:last').css('zIndex', zIndexArray[zIndexArray.length - 2] - 1);
        });
};

/**
 * Registers on all html elements with a css class called 'ajax_element' an event for sending requests.
 */
WebSiteManager.prototype.registerEventHandler = function () {
    var _self = this;

    $(".ajax_element").each(function (index) {
        var myEvent = $(this).attr('data-event');
        var elementId = $(this).attr('id');
        var element = $("#"+elementId); //recent Element
        var callback = _self.getCallback(element.attr('data-cb'));

        if (!_self.registeredElements[elementId] && (element.attr('data-cb') == undefined || callback != undefined)) {
            if (element.prop('tagName') === "FORM") {
                myEvent = myEvent ? myEvent : "submit";
                callback = callback ? callback : _self.getCallback('form');
            }
            else if (element.prop('tagName') === "A") {
                callback = callback ? callback : _self.getCallback('link');
                myEvent = myEvent ? myEvent : "click";
            } else if (element.hasClass("wasabi_suggest")) {
                callback = callback ? callback : _self.getCallback('suggest');
                myEvent = myEvent ? myEvent : "keyup";
            } else {
                callback = callback ? callback : _self.getCallback('button');
                myEvent = myEvent ? myEvent : "click";
            }

            if (myEvent && callback) {
                if (!_self.possibleEvents[myEvent]) {
                    //console.log("Event "+ myEvent + " is not a valid event");
                }
                else {
                    if (!$(this).attr('id')) {
                        //console.log("Element found without id. Look into your bullshit code");
                    }
                    else {
                        $(document).on(myEvent, '#' + elementId, function(event) {
                            event.preventDefault();
                            callback.execute(event);
                            var conf = {};
                            //Form-Events standardmäßig POST
                            if (callback.dataExtractor.getElement().prop('tagName') === 'FORM' && callback.dataExtractor.getElement().attr('method') != 'get') {
                                conf["type"] = "POST";
                            }
                            if(callback.getCondition().check(event)) {
                                var timeOut = callback.dataExtractor.getElement().attr("data-timeout");
                                timeOut != undefined ? callback.getCondition().setTimeout(timeOut) : null;
                                var disableTimeout = callback.dataExtractor.getElement().attr("data-disabletime");

                                if(disableTimeout) {
                                    if(callback.dataExtractor.getElement().prop('tagName') === 'A') {
                                        callback.dataExtractor.getElement().attr("data-href", callback.dataExtractor.getElement().attr("href")).removeAttr("href");
                                    }
                                    if(callback.dataExtractor.getElement().prop('tagName') === 'FORM') {
                                        callback.dataExtractor.getElement().find("[type=submit]").prop('disabled',true);
                                    } else {
                                        callback.dataExtractor.getElement().prop('disabled',true);
                                    }
                                    if(disableTimeout !== "true" && $.isNumeric(disableTimeout)) {
                                        var cond = new Condition();
                                        cond.setTimeout(disableTimeout);
                                        cond.checkTimeOut(function() {
                                            if(callback.dataExtractor.getElement().prop('tagName') === 'A') {
                                                callback.dataExtractor.getElement().attr("href", callback.dataExtractor.getElement().attr("data-href")).removeAttr("data-href");
                                            }
                                            if(callback.dataExtractor.getElement().prop('tagName') === 'FORM') {
                                                callback.dataExtractor.getElement().find("[type=submit]").prop('disabled',false);
                                            } else {
                                                callback.dataExtractor.getElement().prop('disabled',false);
                                            }
                                        });
                                    }
                                }
                                callback.getCondition().checkTimeOut(function() {
                                    _self.wasabiNotificationCenter.send(callback.dataExtractor.getUri(), callback.dataExtractor,conf);
                                });
                            }
                        });
                    }
                }
                _self.registeredElements[elementId] = true;
            }
        }
    });
};

/**
 * Returns the Callback object registered with functionName.
 * @param functionName
 * @returns {*}
 */
WebSiteManager.prototype.getCallback = function (functionName) {
    var _self = this;
    if (functionName && !_self.possibleCallbacks[functionName]) {
        //console.log("Callback with abbreviation " + functionName + " is not defined");
    }
    else {
        return functionName ? _self.possibleCallbacks[functionName] : null;
    }
};

/**
 * Setter for an object which holds all possible events as key boolean pair, F.e. {click: true}.
 * @param possibleEvents
 */
WebSiteManager.prototype.setPossibleEvents = function (possibleEvents) {
    var _self = this;
    _self.possibleEvents = possibleEvents;
};

/**
 * Adds a possible event to the existing ones.
 * @param possibleEvent
 */
WebSiteManager.prototype.addPossibleEvent = function (possibleEvent) {
    var _self = this;

    _self.possibleEvents[possibleEvent] = true;
};


/**
 * Allows to register observers which has to notified if a certain event occurs.
 * @constructor
 */
function WasabiNotificationCenter(websiteManager) {
    var _self = this;
    _self.websiteManager = websiteManager;

    _self.observers = new Array();
}

/**
 * Registers an observer which will be notified, if a particular event will appear.
 * @param event
 * @param observer
 */
WasabiNotificationCenter.prototype.register = function (observer) {
    var _self = this;

    observer.setExecuteRemoteProcedureCallManager(_self.websiteManager.getExecuteRemoteProcedureCallManager());

    _self.observers.push(observer);
};

/**
 * Removes an observer from the notification list.
 * @param event
 * @param observer
 */
WasabiNotificationCenter.prototype.remove = function (event, id) {
    var _self = this;

    delete _self.observers[_self.generateIndex(event, id)];
};

/**
 * Removes an observer from the notification list.
 * @param event
 * @param observer
 */
WasabiNotificationCenter.prototype.generateIndex = function (event, id) {
    var _self = this;

    return event + "+" + id;
};

/**
 * All observers of the occurrence of a particular event.
 * @param notification
 */
WasabiNotificationCenter.prototype.notify = function (notification) {
    var _self = this;

    jQuery.each(_self.observers, function (obs) {
        var obs = this;
        jQuery.each(notification, function (index, response) {
            if (window[response.recipientType] != undefined && obs instanceof window[response.recipientType]) {
                obs.update(response);
            }
        });
    });
};

/**
 * Sends a request to the url with the content as get/post parameter where the config can be used to set the
 * AJAX parameters.
 * @param url {string} The target url.
 * @param content {string} The get/post parameters.
 * @param config {object} An object with the configuration parameters for the AJAX request.
 */
WasabiNotificationCenter.prototype.send = function (url, content, config) {
    var _self = this;
    var type = 'GET';
    var coding = 'json';

    if (config) {
        config.type ? type = config.type : false;
    }

    var dataContent = content ? content.getTransmissionCapableContent() : {};
    var dataJson = content.getElement().attr('data-json') ? JSON.parse(content.getElement().attr('data-json')) : {};
    var data = dataContent;
    if(dataJson) {
        if($.isArray(dataContent)) {
            $.each(dataJson, function(key, val) {
                dataContent.push({
                    name: key,
                    value: val
                });
            });
        } else {
            $.extend(data, dataContent, dataJson);
        }
    }

    data = $.extend(data, {triggeredId: content.getElement().attr("id")});

    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType: coding,
        beforeSend: function (xhr) {
            content.getElement().attr("data-ajax-loader") ? $("#" + content.getElement().attr("data-ajax-loader")).show() : null;
        }
    })
        .done(function (msg) {
            msg.push({
                recipientType: "ajax_new_element",
                status: 200
            });
            _self.notify(msg);
            _self.websiteManager.registerEventHandler();

            content.getElement().attr("data-ajax-loader") ? $("#" + content.getElement().attr("data-ajax-loader")).hide() : null;

            if(content.getElement().attr("data-disabletime") != undefined && content.getElement().attr("data-disabletime") == "true") {
                if(content.getElement().prop('tagName') === 'A') {
                    content.getElement().attr("href", content.getElement().attr("data-href")).removeAttr("data-href");
                }
                if(content.getElement().prop('tagName') === 'FORM') {
                    content.getElement().find("[type=submit]").prop('disabled',false);
                } else {
                    content.getElement().prop('disabled',false);
                }
            }
        });
};

/**
 * The constructor of the ExecuteRemoteProcedureCallManager which is used to encapsulate the calling of the
 * JavaScript/jQuery methods.
 * @constructor
 */
function ExecuteRemoteProcedureCallManager() {
    var _self = this;

    _self.possibleMethods = undefined;
    _self.keywords = {
        location: $(location)
    };
}

/**
 * Executes function specified by methodName, on an element specified by selector with the parameters for
 * the method call params
 * @param selector {string} A css selector.
 * @param methodName {string} A method name.
 * @param params {Array} The parameters stored in an array.
 */
ExecuteRemoteProcedureCallManager.prototype.execute = function(selector, methodName, params) {
    var _self = this;

    if(!_self.possibleMethods[methodName]){
        console.log("The given method "+ methodName + " is not a valid method name");
    }
    else if(_self.keywords[selector]){
        _self.keywords[selector][_self.possibleMethods[methodName]].apply(_self.keywords[selector], params);
    }
    else if(selector == undefined) {
        window[_self.possibleMethods[methodName]].apply(window, params);
    }
    else {
        $(selector)[_self.possibleMethods[methodName]].apply($(selector), params);
    }

};

/**
 * Setter for an object which holds the names of all kinds of methods which are allowed to use.
 * @param possibleMethods {object} Object with key value pairs, f.e. {ACTION_TYPE_REPLACE: "html"}.
 */
ExecuteRemoteProcedureCallManager.prototype.setPossibleMethods = function(possibleMethods) {
    var _self = this;
    _self.possibleMethods = possibleMethods;
};

/**
 * Created by norman.albusberger on 08.07.14.
 */
//--------------Init Objects------------------------------------
$.fn.extend({getWebsiteManager : function() {
    return null;
}
});
$(document).ready(function(){
    /**
     * Initialize the WebsiteManager to orchestrate the WasabiLib system.
     * @type {WebSiteManager}
     */
    var websiteManager = new WebSiteManager();
    $.fn.getWebsiteManager = function() {
        return websiteManager;
    };

    /**
     * Set the available event types which can be used to trigger WasabiLib requests.
     */
    websiteManager.setPossibleEvents({
        select: true
        , search: true
        , dblclick: true
        , mouseover: true
        , mouseout: true
        , click: true
        , mousedown: true
        , submit: true
        , blur: true
        , keyup: true
        , keydown: true
        , keypress: true
    });

    /**
     * Execute methods in a controlled environment.
     */
    websiteManager.setExecuteRemoteProcedureCallManager(new ExecuteRemoteProcedureCallManager());

    /**
     * Show JavaScript alert window.
     */
    websiteManager.getWasabiNotificationCenter().register(new Alert());

    /**
     * Add, replace or remove html elements in the DOM.
     */
    websiteManager.getWasabiNotificationCenter().register(new InnerHtml());

    /**
     * Change values of html element attributes.
     */
    websiteManager.getWasabiNotificationCenter().register(new DomManipulator());

    /**
     * Display one or many bootstrap modal windows.
     */
    websiteManager.getWasabiNotificationCenter().register(new ModalWindow());

    /**
     * Write develop informations into the web browsers console.
     */
    websiteManager.getWasabiNotificationCenter().register(new ConsoleLog());

    /**
     * Show Growl-like messages.
     */
    websiteManager.getWasabiNotificationCenter().register(new Gritter());

    /**
     * Trigger events received from the server.
     */
    websiteManager.getWasabiNotificationCenter().register(new TriggerEventManager());

    /**
     * Display results based on input in a suggest field.
     */
    websiteManager.getWasabiNotificationCenter().register(new Suggest());
//    websiteManager.getWasabiNotificationCenter().register(new DropzoneManager());

    /**
     * Callback for a elements to extract the informations of the data-json attribute only.
     */
    websiteManager.addCallback(new Callback("link", new DataExtractor()));

    /**
     * Callback for button elements to extract the informations of the data-json attribute only.
     */
    websiteManager.addCallback(new Callback("button", new DataExtractor()));

    /**
     * Callback for form elements with an external submit button to extract the informations of the form.
     */
    websiteManager.addCallback(new Callback("ext_form_submit", new External_Form()));

    /**
     * Callback for form elements to extract the informations of the form.
     */
    websiteManager.addCallback(new Callback("form", new Form()));

    /**
     * Callback for text fields to extract the informations from the value attribute.
     */
    websiteManager.addCallback(new Callback("text", new Text_Field()));

    /**
     * Callback for suggest fields to extract the informations from the value attribute. Additionally request are only
     * be send if a certain number of diggets are in the field and 300ms delay time expired.
     * @type {Callback}
     */
    var suggestCallback = new Callback("suggest", new Text_Field());
    /**
     * Condition to check if a certain number of digits are in the text field.
     * @type {Condition}
     */
    var condition = new Condition(function(event) {
        var _self = suggestCallback;
        var digits = _self.dataExtractor.getElement().attr('data-digits');
        return digits <= _self.dataExtractor.getTransmissionCapableContent().content.length && (event.which > 40 || event.which < 37);
    });
    /**
     * 300ms delay time.
     */
    condition.setTimeout(300);
    suggestCallback.setCondition(condition);
    websiteManager.addCallback(suggestCallback);

    /**
     * Set the possible methods which can be used by recipient objects.
     */
    websiteManager.getExecuteRemoteProcedureCallManager().setPossibleMethods({
        ACTION_TYPE_SHOW: "show"
        , ACTION_TYPE_HIDE: "hide"
        , ACTION_TYPE_MODAL: "modal"
        , ACTION_TYPE_SLIDEDOWN: "slideDown"
        , ACTION_TYPE_SLIDEUP: "slideUp"
        , ACTION_TYPE_REPLACE: "html"
        , ACTION_TYPE_APPEND: "append"
        , ACTION_TYPE_REMOVE: "empty"
        , ACTION_TYPE_CSS: "css"
        , ACTION_TYPE_FADEOUT: "fadeOut"
        , ACTION_TYPE_FADEIN: "fadeIn"
        , ACTION_TYPE_ATTR: "attr"
        , ACTION_TYPE_ADD_CLASS: "addClass"
        , ACTION_TYPE_REMOVE_CLASS: "removeClass"
        , ACTION_TYPE_TOGGLE_CLASS: "toggleClass"
        , ACTION_TYPE_HREF: "href"
        , ACTION_TYPE_DROPZONE_DISCOVER: "discover"
        , ACTION_TYPE_TRIGGER: "trigger"
        , ACTION_TYPE_REMOVE_ELEMENT : "remove"
        , ACTION_TYPE_JS_ALERT : "alert"
    });

    /**
     * Initialize the suggest field features.
     * @type {WasabiSuggestFeatures}
     */
    var suggest = new WasabiSuggestFeatures();
});
