/**
 * Created by norman.albusberger on 15.08.14.
 */


function Gritter(){

    var _self = this;
}

Gritter.prototype.update = function(responseType){
    var message = responseType.message;

    if(message.params.position) {
        // clean the wrapper position class
        $('#gritter-notice-wrapper').attr('class', '');
        jQuery.gritter.options.position = message.params.position;
    }

    jQuery['gritter']['add'].apply($,[message.params]);
};

