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