(function($) {
$.fn.donetyping = function(callback){
    var _this = $(this);
    var x_timer;
    _this.keyup(function (){
        clearTimeout(x_timer);
        x_timer = setTimeout(clear_timer, 1000);
    });

    function clear_timer(){
        clearTimeout(x_timer);
        callback.call(_this);
    }
}
})(jQuery);
