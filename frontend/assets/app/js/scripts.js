(function ($) {
    $.fn.initBanner = function (options) {
        var $self = $(this);

        $.get(globalVars.a + "/ads/get",
            {place: options.place, device: getDevice(), t: new Date().getTime(), l: options.language, w: $self.width()},
            function (res) {
                if (res.success) {
                    $self.append(res.content);
                    handleClick(res.id);
                } else {
                    $self.removeClass("banner");
                }
            });

        function handleClick(id) {
            $self.on("click", function (e) {
                $.get(globalVars.a + "/stat/click/" + id, {t: new Date().getTime()}, function () {
                });
            });
        }

        function getDevice() {
            if ($(window).width() < 769) {
                return "mobile";
            }
            return "desktop";
        }

        return this;
    };

    $(document).ready(function () {
        if (typeof globalVars !== "undefined") {
            if (globalVars.hasOwnProperty("p")) {
                $.get(globalVars.a + "/stat/post/" + globalVars.p + "?t=" + getTimeStamp() + "&l=" + globalVars.l, function () {
                });
            }
        }

        $('.load-button.load-more').on('click', function () {
            $(this).addClass('btn__more');
        });

        $(document).on('ready pjax:success', function (data, status, xhr, options) {
            $('.load-button.load-more').on('click', function () {
                $(this).addClass('btn__more');
            });
        });

        $("#sticky-sidebar").theiaStickySidebar({
            additionalMarginTop: 90,
            additionalMarginBottom: 20
        });
    });
}(jQuery));