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
