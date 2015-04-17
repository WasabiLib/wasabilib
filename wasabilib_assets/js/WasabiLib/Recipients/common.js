/**
 * Created by norman.albusberger on 08.07.14.
 */

/**
 * Created by norman.albusberger on 07.07.14.
 */

function Alert(executeRemoteProcedureCallManager){
    var _self = this;

    _self.execRPCManager = executeRemoteProcedureCallManager;
    _self.ACTION_TYPE_JS_ALERT = "alert";
}

Alert.prototype.update = function(responseType){
    var _self = this;
    var message = responseType.message;

    _self.execRPCManager.execute(undefined, message.actionType, message.params);
};

function ConsoleLog(){
    var _self = this;

}

ConsoleLog.prototype.update = function(responseType){
    console.log(responseType.message);
}

/**
 * Created by norman.albusberger on 03.07.14.
 */

function InnerHtml(executeRemoteProcedureCallManager){
    var _self = this;

    _self.execRPCManager = executeRemoteProcedureCallManager;
    _self.ACTION_TYPE_REPLACE = "html";
    _self.ACTION_TYPE_APPEND = "appendTo";
    _self.ACTION_TYPE_REMOVE = "empty";
}

InnerHtml.prototype.update = function(responseType){
    var _self = this;
    var message = responseType.message;

    _self.execRPCManager.execute(message.selector, message.actionType, message.params);
};

/**
 * Created by sascha.qualitz on 22.09.14.
 */

function TriggerEventManager(executeRemoteProcedureCallManager){

    var _self = this;
    _self.execRPCManager = executeRemoteProcedureCallManager;

    _self.possibleEvents = {
        ACTION_TYPE_TRIGGER_EVENT_NEXT_STEP: "nextStep"
        , ACTION_TYPE_TRIGGER_EVENT_PREVIOUS_STEP: "prevStep"
        , ACTION_TYPE_TRIGGER_EVENT_CLICK: "click"
        , ACTION_TYPE_TRIGGER_EVENT_FOCUS: "focus"
    };
}

TriggerEventManager.prototype.update = function(responseType){
    var _self = this;
    var message = responseType.message;

    if(!_self.possibleEvents[message.params[0]]){
        console.log("The given event "+ message.params[0] + " is not a valid event name");
    } else {
        _self.execRPCManager.execute(message.selector, message.actionType, [_self.possibleEvents[message.params[0]]]);
    }
};

/**
 * Created by sascha.qualitz on 22.09.14.
 */

function DropzoneManager(executeRemoteProcedureCallManager){

    var _self = this;
    _self.execRPCManager = executeRemoteProcedureCallManager;

    _self.possibleEvents = {
        ACTION_TYPE_DROPZONE_DISCOVER: "discover"
    };
}

DropzoneManager.prototype.update = function(responseType){
    var _self = this;
    var message = responseType.message;

    if(responseType.eventName === "ajax_new_element") {
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

function DomManipulator(executeRemoteProcedureCallManager){
    var _self = this;

    _self.execRPCManager = executeRemoteProcedureCallManager;
}

DomManipulator.prototype.update = function(responseType){

    var _self = this;
    var message = responseType.message;

    _self.execRPCManager.execute(message.selector, message.actionType, message.params);
};

function ModalWindow(executeRemoteProcedureCallManager){
    var _self = this;

    _self.execRPCManager = executeRemoteProcedureCallManager;
}

ModalWindow.prototype.update = function(responseType){

    var _self = this;
    var message = responseType.message;
    //Bei Modalwindows muss gewartet werden, bis die Animation beendet ist
    if($(".modal").size() > 1) {
        $(message.selector).attr("data-backdrop", "false");
    }
    setTimeout(function(){
        _self.execRPCManager.execute(message.selector, message.actionType, message.params);
    },200);
};
