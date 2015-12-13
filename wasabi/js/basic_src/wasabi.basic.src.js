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