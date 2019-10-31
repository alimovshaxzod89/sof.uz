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
        $(document).keydown(function (event) {
            if ((event.metaKey || event.ctrlKey) && event.shiftKey && event.keyCode === 69) {
                if (globalVars.p !== undefined) {
                    window.open('https://backend.sof.uz/post/edit/' + globalVars.p, '_blank');
                    return false;
                }
            }
        });
        $("input.select_text").on('focus', function () {
            $(this).select();
            copyToClipboard($(this).val())
        })
    });
}(jQuery));

function getTimeStamp() {
    return new Date().getTime();
}


function copyToClipboard(text) {

    if (window.clipboardData && window.clipboardData.setData) {
        return clipboardData.setData("Text", text);

    } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
        try {
            return document.execCommand("copy");
        } catch (ex) {
            console.warn("Copy to clipboard failed.", ex);
            return false;
        } finally {
            document.body.removeChild(textarea);
        }
    }
}