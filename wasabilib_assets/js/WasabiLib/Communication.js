/**
 * Created by norman.albusberger on 03.07.14.
 */
//--------------HTML Objects------------------------------------
//--------------Form ------------------------------------------
function Form(id) {
    var _self = this;
    _self.self = typeof id === "object" ? id : $('#' + id);
}

Form.prototype.getSendableContent = function () {
    var _self = this;
    return _self.self.serializeArray();
};

//-------------- External Form ------------------------------------------
function External_Form(buttonOrLinkOrForm) {
    var _self = this;
    _self.jsonParams = $.parseJSON('[' + buttonOrLinkOrForm.attr("data-json") + ']');
    _self.self = buttonOrLinkOrForm.tagName === "FORM" ? buttonOrLinkOrForm : $('#' + buttonOrLinkOrForm.attr("form"));
}

External_Form.prototype.getSendableContent = function () {
    var _self = this;
    var newArray = _self.self.serializeArray();
    if (_self.jsonParams) {
        var mergedArray = $.merge(newArray, _self.jsonParams);
    }
    return mergedArray;
};

//-------------  Common Ajax Requests  --------------------------

function GenericAjaxElementHandler(id) {
    var _self = this;
    _self.self = typeof id === "object" ? id : $('#' + id);
};

GenericAjaxElementHandler.prototype.getSendableContent = function () {
    var _self = this;

    return _self.self.attr('data-json') ? JSON.parse(_self.self.attr('data-json')) : null;
};


//--------------------------------------------------------------

//----------- Website Manager -----------------------------

function WebSiteManager() {
    var _self = this;

    _self.wasabiNotificationCenter = new WasabiNotificationCenter(_self);

    _self.registeredElements = {};

    _self.possibleEvents = {mouseover: true, mouseout: true, click: true, mousedown: true, submit: true, blur: true, keyup: true, keydown: true, keypress: true};
    _self.possibleCallbacks = {
        form: function (event) {
            event.preventDefault();
            var form = new Form($(event.target));
            _self.wasabiNotificationCenter.send(form.self.attr('action'), form);
        },
        link: function (event) {
            event.preventDefault();
            var target = $(event.target).closest(".ajax_element");
            var link = new GenericAjaxElementHandler(target);
            _self.wasabiNotificationCenter.send(link.self.attr('href'), link);
        },
        button: function (event) {
            event.preventDefault();
            var button = new GenericAjaxElementHandler($(event.target));
            _self.wasabiNotificationCenter.send(button.self.attr('data-href'), button);
        },
        ext_form_submit: function (event) {
            event.preventDefault();

            var form = new External_Form($(event.target));
            _self.wasabiNotificationCenter.send(form.self.attr('action'), form);
        },
        //´Wat is dat?
        dummyClick: function (event) {
            event.preventDefault();
        }
    }

    _self.registerEventHandler();
    _self.registerCommonEvents();
}

WebSiteManager.prototype.getWasabiNotificationCenter = function () {
    var _self = this;
    return _self.wasabiNotificationCenter;
};

WebSiteManager.prototype.setWasabiNotificationCenter = function (wasabiNotificationCenter) {
    var _self = this;
    _self.wasabiNotificationCenter = wasabiNotificationCenter
};

WebSiteManager.prototype.registerCommonEvents = function () {
    var _self = this;
    $(document).on("focus", "form input, form textarea, form select", function () {
        $(this).css("background-color", "white");
    });

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

WebSiteManager.prototype.registerEventHandler = function () {
    var _self = this;

    $(".ajax_element").each(function (index) {
        var myEvent = $(this).attr('data-event');
        var elementId = $(this).attr('id');
        var callback = _self.getCallback($(this).attr('data-cb'));
        var element = $(this); //recent Element

        if (!_self.registeredElements[elementId]) {
            if (element.prop('tagName') === "FORM") {
                myEvent = myEvent ? myEvent : "submit";
                callback = callback ? callback : _self.getCallback('form');
            }
            else if (element.prop('tagName') === "A") {
                callback = callback ? callback : _self.getCallback('link');
                myEvent = myEvent ? myEvent : "click";
            } else {
                callback = callback ? callback : _self.getCallback('button');
                myEvent = myEvent ? myEvent : "click";
            }

            if (myEvent) {
                if (!_self.possibleEvents[myEvent]) {
                    //console.log("Event "+ myEvent + " is not a valid event");
                }
                else {
                    if (!$(this).attr('id')) {
                        //console.log("Element found without id. Look into your bullshit code");
                    }
                    else {
                        $(document).on(myEvent, '#' + elementId, callback);
                    }
                }
            }


            _self.registeredElements[elementId] = true;
        }
    });
};

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
 * Allows to register observers which has to notified if a certain event occurs.
 * @constructor
 */
function WasabiNotificationCenter(websiteManager) {
    var _self = this;
    _self.websiteManager = websiteManager;

    _self.observers = {};
}

/**
 * Registers an oberver which will be notified, if a particular event will appear.
 * @param event
 * @param observer
 */
WasabiNotificationCenter.prototype.register = function (event, id, observer) {
    var _self = this;

    _self.observers[_self.generateIndex(event, id)] = observer;
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
 * All observers of the occurence of a particular event.
 * @param notification
 */
WasabiNotificationCenter.prototype.notify = function (notification) {
    var _self = this;

    jQuery.each(_self.observers, function (index, value) {
        var observerId = index;
        jQuery.each(notification, function (index, responseType) {
            if (observerId == responseType.eventName + "+" + responseType.eventId) {
                value.update(responseType);
            }
        });

    })
};


WasabiNotificationCenter.prototype.send = function (url, content, config) {
    var _self = this;
    var type = 'GET';
    var coding = 'json';

    /**
     * Irgendwelche config Geschichten mit config-Object
     */
    if (config) {

    }

    else {
        //Form-Events standardmäßig POST
        if (content.self.prop('tagName') === 'FORM' && content.self.attr('method') != 'get') {
            type = "POST";
        }
    }
    var data = content ? content.getSendableContent() : null;
    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType: coding,
        beforeSend: function (xhr) {
            content.self.attr("data-ajax-loader") ? $("#" + content.self.attr("data-ajax-loader")).show() : null;
        }
    })
        .done(function (msg) {
            msg.push({
                eventName: "ajax_new_element",
                status: 200
            });
            _self.notify(msg);
            _self.websiteManager.registerEventHandler();

            content.self.attr("data-ajax-loader") ? $("#" + content.self.attr("data-ajax-loader")).hide() : null;
        });
};

function ExecuteRemoteProcedureCallManager() {
    var _self = this;

    _self.possibleMethods = undefined;
    _self.keywords = {
        location: $(location)
    };
}

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

ExecuteRemoteProcedureCallManager.prototype.setPossibleMethods = function(possibleMethods) {
    var _self = this;
    _self.possibleMethods = possibleMethods;
};
