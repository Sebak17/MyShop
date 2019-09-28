(function ($) {
    $.fn.maskPhone = function (options) {
        var params = $.extend({
            format: 'xxx xxx xxx',
            international: !1,
        }, options);

        $(this).bind('paste', function (e) {
            e.preventDefault();
            var inputValue = e.originalEvent.clipboardData.getData('Text');
            if (!$.isNumeric(inputValue)) {
                return !1
            } else {
                inputValue = String(inputValue.replace(/(\d{3})(\d{3})(\d{3})/, "$1 $2 $3"));
                $(this).val(inputValue);
                $(this).val('');
                inputValue = inputValue.substring(0, 11);
                $(this).val(inputValue)
            }
        });
        $(this).on('keyup', function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return !1
            }
            var curchr = this.value.length;
            var curval = $(this).val();
            if (curchr == 3 && e.which != 8 && e.which != 0) {
                $(this).val(curval + " ")
            } else if (curchr == 7 && e.which != 8 && e.which != 0) {
                $(this).val(curval + " ")
            }
            $(this).attr('maxlength', '11')
        })
    }
}(jQuery));