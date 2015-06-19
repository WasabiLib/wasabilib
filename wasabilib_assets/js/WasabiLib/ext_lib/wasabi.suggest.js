/**
 * Constructor of the class which provides all features for the suggest field like f.e. browse through the results of a
 * suggest request with the arrow keys.
 * @constructor
 */
function WasabiSuggestFeatures(){
    var _self = this;

    _self.registerCommonEvents();
}

/**
 * registers all event handlers for the suggest field features.
 */
WasabiSuggestFeatures.prototype.registerCommonEvents = function () {
    var recentElement = null;
    $.fn.setCursorPosition = function(pos) {
        this.each(function(index, elem) {
            if (elem.setSelectionRange) {
                elem.setSelectionRange(pos, pos);
            } else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        });
        return this;
    };

    /**
     * removes all suggest result containers except the one that belongs to the suggest field
     */
    $(document).on('click', function(e){
        //clicked element

        var target = $(e.target).closest('.wasabi_suggest');
        var suggestId = target.hasClass('wasabi_suggest')==true ? target.attr('id') : null;
        var suggestResultFields = $('.wasabi-suggest-result-container');
        if(suggestId==null) {
            suggestResultFields.remove();
        } else {
            suggestResultFields.each(function(index){
                if($(this).attr('id')!=suggestId + '-result'){
                    $(this).remove();
                }
            });
        }
    });

    /**
     * prevent arrow key navigation of the website
     */
    $(document).on("keydown", function (event) {
        if([38, 40].indexOf(event.keyCode) > -1) {
            var target = $(event.target);
            var closestResult = target.closest(".wasabi-suggest-result-container");
            var closestSuggest = target.closest(".wasabi_suggest");

            if(closestResult != undefined || closestSuggest != undefined) {
                event.preventDefault();
            }
        }
    });

    /**
     * arrow key navigation inside result-container
     */
    $(document).on("keydown",".wasabi-suggest-result-container", function (e) {
        var suggestResultId = $(this).attr('id');
        var listItems = $("#"+suggestResultId).find('a');
        /**
         * arrow key down
         */
        if(e.which === 40){
            recentElement = recentElement == null ? listItems.first() : recentElement.next().attr("id") != undefined ? recentElement.next() : recentElement;
            recentElement.focus();
        }
        /**
         * arrow key up
         */
        else if(e.which === 38){
            /**
             * if focus is on first element next key press sets the focus to the suggest field again
             */
            if(recentElement!=null && recentElement.attr('id')==listItems.first().attr('id')){
                $('#'+suggestResultId.replace('-result','')).focus();
            }
            else{
                recentElement = recentElement == null ? listItems.last() : recentElement.prev().attr("id") != undefined ? recentElement.prev() : recentElement;
                recentElement.focus();
            }
        }
    });

    /**
     * arrow key navigation on suggest field
     */
    $(".wasabi_suggest").on("keydown",function(e){
        var suggestResultId = $(this).attr('id')+'-result';
        var listItems = $("#"+suggestResultId).find('a');
        if(e.which === 40){
            recentElement = listItems.first();
            recentElement.focus();
        }
        /**
         * if focus is on the suggest field and arrow up is pressed focus jumps to the last suggestion
         */
        else if(e.which === 38){
            recentElement = listItems.last();
            recentElement.focus();
        }
    });
    /**
     * sets the text-label value inside the a element to the suggest field
     */
    $(document).on("focus",".wasabi-suggest-result-container a", function (e) {
        var _self = $(this);
        var suggesResultId = _self.closest(".wasabi-suggest-result-container").attr("id");
        var suggestId = suggesResultId.replace("-result", "");
        var suggest = $("#" + suggestId);
        /**
         * removing the hidden fields
         */
        $("."+suggestId+"-hidden").remove();

        suggest.val($(e.target).find('h4').html().replace(/<span.*id=.*_bold.*[^</span>]>/, "").replace("</span>", ""));
        /**
         * replaces the data-json attr of the suggest field with the data-json of the clicked a element
         */
        suggest.attr('data-json',_self.attr('data-json'));

        /**
         * creating the hidden fields for form submits
         */
        var dataJsonObj = JSON.parse(_self.attr('data-json'));
        $.each(dataJsonObj,function(key,value){
            $("<input type='hidden' class = '"+suggestId+"-hidden' name='"+key+"' value='"+value+"'>").insertAfter(suggest);
        });
    });

    /**
     * on pressing the enter key remove the result-container
     */
    $(document).on("keyup",".wasabi-suggest-result-container a", function (e) {
        if(e.which == 13) {
            var _self = $(this);
            var suggestResult = _self.closest(".wasabi-suggest-result-container");
            suggestResult.remove();
            /**
             * removing the hidden fields
             */

        }
    });
    /**
     * removes the result-container on click onto an a element inside the result-container
     */
    $(document).on("click",".wasabi-suggest-result-container a", function (e) {
        var _self = $(this);
        _self.focus();
        var suggestResult = _self.closest(".wasabi-suggest-result-container");
        suggestResult.remove();

    });
    /**
     * resets the recentElement by focusing the suggest field
     */
    $(document).on("focus",".wasabi_suggest", function(e){
        var _self = $(this);
        recentElement = null;
        setTimeout(function(){
            _self.setCursorPosition(_self.val().length);
        }, 1);
    });
    /**
     * removes the result container and the hidden fields if value is null
     */
    $(document).on("keyup",".wasabi_suggest",function(e){
        if($(e.target).val().length==0){
            var suggestResultId = $(e.target).attr('id')+'-result';
            $("#"+suggestResultId).remove();
            var suggestId = $(e.target).attr('id');
            $("."+suggestId+"-hidden").remove();
        }
    });

};
