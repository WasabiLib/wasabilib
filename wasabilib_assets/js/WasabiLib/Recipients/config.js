/**
 * Created by norman.albusberger on 08.07.14.
 */
//--------------Init Objects------------------------------------
/**
 * initialize all recipients you want to use
 */
$(document).ready(function(){
    var websiteManager = new WebSiteManager();
    var executeRemoteProcedureCallManager = new ExecuteRemoteProcedureCallManager();
    websiteManager.getWasabiNotificationCenter().register("alert", "Alert", new Alert(executeRemoteProcedureCallManager));
    websiteManager.getWasabiNotificationCenter().register("innerHtml", "InnerHtml", new InnerHtml(executeRemoteProcedureCallManager));
    websiteManager.getWasabiNotificationCenter().register("domManipulator", "DomManipulator", new DomManipulator(executeRemoteProcedureCallManager));
    websiteManager.getWasabiNotificationCenter().register("domManipulator", "ModalWindow", new ModalWindow(executeRemoteProcedureCallManager));
    websiteManager.getWasabiNotificationCenter().register("consoleLog","ConsoleLog", new ConsoleLog());
    websiteManager.getWasabiNotificationCenter().register("gritter","Gritter", new Gritter());
    websiteManager.getWasabiNotificationCenter().register("triggerEventManager","TriggerEventManager", new TriggerEventManager(executeRemoteProcedureCallManager));
//    websiteManager.getWasabiNotificationCenter().register("ajax_new_element","DropzoneManager", new DropzoneManager(executeRemoteProcedureCallManager));

    executeRemoteProcedureCallManager.setPossibleMethods({
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
});