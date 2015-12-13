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