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
};

/**
 * Returns the content of the element linked to the attribute target. Additionally it extracts the data-json
 * informations from the external submit button.
 * @returns {*|string|array} All transmission capable data.
 */
External_Form.prototype.getTransmissionCapableContent = function () {
    var _self = this;
    var extractedData = Form.prototype.getTransmissionCapableContent.call(this);
    var extFormSubBtn = $("[form="+_self.getElement().attr("id")+"]");
    var jsonParams = extFormSubBtn.attr("data-json") ? JSON.parse('[' + extFormSubBtn.attr("data-json") + ']') : null;
    if (jsonParams) {
        $.merge(extractedData, jsonParams);
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