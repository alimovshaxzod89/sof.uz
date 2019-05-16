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
            $self.on("click", function () {
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

    var cookieHelper = {
        // this gets a cookie and returns the cookies value, if no cookies it returns blank ""
        get: function (c_name) {
            if (document.cookie.length > 0) {
                var c_start = document.cookie.indexOf(c_name + "=");
                if (c_start !== -1) {
                    c_start = c_start + c_name.length + 1;
                    var c_end = document.cookie.indexOf(";", c_start);
                    if (c_end === -1) {
                        c_end = document.cookie.length;
                    }
                    return unescape(document.cookie.substring(c_start, c_end));
                }
            }
            return "";
        },

        // this sets a cookie with your given ("cookie name", "cookie value", "good for x days")
        set: function (c_name, value, expiredays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expiredays);
            document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : "; path=/; expires=" + exdate.toUTCString());
        },

        // this checks to see if a cookie exists, then returns true or false
        check: function (c_name) {
            c_name = cookieHelper.get(c_name);
            if (c_name != null && c_name !== "") {
                return true;
            } else {
                return false;
            }
        }
    };
    $(document).ready(function () {
        var header = document.getElementById("header");

        var headroom = new Headroom(header, {
            tolerance: {
                down: 10,
                up: 30
            },
            offset: 305
        });
        headroom.init();

        $('.scrollTo').click(function () {
            if ($(this).data('pos').length > 0) {
                $('html, body').stop().animate({
                    scrollTop: $($(this).data('pos')).offset().top + 5
                }, 300);

                return false;
            }

            return true;
        });

        if (typeof globalVars !== "undefined") {
            if (globalVars.hasOwnProperty("p")) {
                $.get(globalVars.a + "/stat/post/" + globalVars.p + "?t=" + getTimeStamp() + "&l=" + globalVars.l, function () {
                });
            }
        }
        $('.load-more').on('click', function () {
            $(this).addClass('is_loading');
        });

        $(document).on("ready pjax:success", function (data, status, xhr, options) {
            $('.load-more').on('click', function () {
                $(this).addClass('is_loading');
            });
        });

        $(".ssbar").theiaStickySidebar({
            additionalMarginTop: 64
        });
        if ($('.header-messages').length > 0) {
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            var isIOS = false;

            if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                isIOS = true;
            }

            $.each($('.header-messages'), function () {
                var ck = $(this).data('cookie');
                var pr = $(this).data('param');
                var cn = parseInt($(this).data('count'));
                var that = $(this);
                var cr = parseInt(cookieHelper.get(ck));
                if ((cookieHelper.get(ck) == "" || isNaN(cr) || cr < cn) && (pr == undefined || document.location.href.indexOf(pr) != -1)) {
                    if (isIOS && that.data('cookie') === '_ios') {
                        that.delay(1500).slideDown(function () {
                            $('body').css('padding-top', that.height() + 'px');
                        });
                        that.find('.close-btn').on('click', function () {
                            that.slideUp();
                            cookieHelper.set(ck, isNaN(cr) ? 1 : cr + 1, 30);
                            return true;
                        });
                        that.find('.action-btn').on('click', function () {
                            that.slideUp();
                            $('body').css('padding-top', 0);
                            cookieHelper.set(ck, cn, 30);
                            return true;
                        });
                    } else if (that.data('cookie') !== '_ios') {
                        that.delay(1500).slideDown(function () {
                            $('body').css('padding-top', that.height() + 'px');
                        });

                        that.find('.close-btn').on('click', function () {
                            $('body').css('padding-top', 0);
                            that.slideUp();
                            $('body').removeClass('has-messages');
                            cookieHelper.set(ck, isNaN(cr) ? 1 : cr + 1, 30);
                            return true;
                        });
                        that.find('.action-btn').on('click', function () {
                            that.slideUp();
                            $('body').removeClass('has-messages');
                            cookieHelper.set(ck, cn, 30);
                            return true;
                        });
                    }
                }

            });
        }

        $(document).keydown(function (event) {
            if ((event.metaKey || event.ctrlKey) && event.keyCode === 13) {
                showError(getSelectText());
            }
            if ((event.metaKey || event.ctrlKey) && event.shiftKey && event.keyCode === 69) {
                if (globalVars.p !== undefined) {
                    window.open('https://backend.minbar.uz/post/edit/' + globalVars.p, '_blank');
                    return false;
                }
            }
        });

        /*if (false && ('ontouchstart' in window) && ('ontouchend' in window)) {
            var touchTimer;
            var touchDuration = 2000;

            var elems = ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'blockquote', 'a'];

            window.addEventListener("touchstart", function (params) {
                touchTimer = setTimeout(function () {
                    if (elems.indexOf(params.path[0].tagName.toLowerCase()) > -1) {
                        showError(params.path[0].innerText);
                    }
                }, touchDuration);
            }, false);

            window.addEventListener("touchend", function (params) {
                if (touchTimer) clearTimeout(touchTimer);
            }, false);
        } else {
            console.log("Touch not supported");
        }*/


    });
})(jQuery);

function getTimeStamp() {
    return new Date().getTime();
}

function showError(range) {
    range = range.trim();
    if (range.length == 0) {
        alert(globalVars.m.typo)
    } else {
        $('#error_container .error_content').load(globalVars.typo + '?q=' + encodeURI(range), function (data) {
            var popup = $('#error_container');
            popup.addClass('is_active');
            $('html').addClass('oh');
            popup.find('.popup-close-btn').on('click', function (e) {
                e.preventDefault();
                popup.removeClass('is_active');
                $('html').removeClass('oh');
            });
        });
    }
}

function getSelectText() {
    var range = '';
    if (window.getSelection) {
        range = window.getSelection();
        return range.toString();
    } else {
        if (document.selection.createRange) {
            range = document.selection.createRange();
            return range.text;
        }
    }
}

function copyToClipboard(text) {
    if (window.clipboardData && window.clipboardData.setData) {
        return clipboardData.setData("Text", text);

    } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
        var textarea = document.createElement("textarea");
        textarea.textContent = text;
        textarea.style.position = "fixed";
        document.body.appendChild(textarea);
        textarea.select();
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