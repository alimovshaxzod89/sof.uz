(function ($) {
    "use strict";

    $('.load-button.load-more').on('click', function () {
        $(this).addClass('btn__more');
    });

    $(document).on('ready pjax:success', function (data, status, xhr, options) {
        $('.load-button.load-more').on('click', function () {
            $(this).addClass('btn__more');
        });
    });

}(jQuery));