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

    $(document).on("wasabi_new_ajax_element", null, function(event) {
        _self.registerEventHandler();
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
        var element = $(this); //recent Element
        var myEvent = element.attr('data-event');
        var elementId = element.attr('id');
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
                        //console.log("Element found without id.Look into your bullshit code");
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
 * Adds a possible event to the existing ones.
 * @param possibleEvent
 */
WebSiteManager.prototype.addPossibleEvent = function (possibleEvent) {
    var _self = this;

    _self.possibleEvents[possibleEvent] = true;
};
