(function ($) {
  $.fn.initBanner = function (options) {
      var $self = $(this);

      $.get(globalVars.a + "/ads/get",
          {place: options.place, device: getDevice(), os:getDeviceOS(), t: new Date().getTime(), l: options.language, w: $self.width()},
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
    if (typeof globalVars !== "undefined") {
      if (globalVars.hasOwnProperty("p")) {
        $.get(globalVars.a + "/stat/post/" + globalVars.p + "?t=" + getTimeStamp() + "&l=" + globalVars.l, function () {
        });
      }
    }

  });
}(jQuery));

function getTimeStamp() {
  return new Date().getTime();
}

function getDeviceOS() {
    var userAgent = window.navigator.userAgent || navigator.vendor || window.opera,
        platform = window.navigator.platform,
        macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
        windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
        iosPlatforms = ['iPhone', 'iPad', 'iPod'],
        os = null;

    if (macosPlatforms.indexOf(platform) !== -1) {
        os = 'mac';
    } else if (iosPlatforms.indexOf(platform) !== -1) {
        os = 'ios';
    } else if (windowsPlatforms.indexOf(platform) !== -1) {
        os = 'windows';
    } else if (/Android/.test(userAgent)) {
        os = 'android';
    } else if (!os && /Linux/.test(platform)) {
        os = 'linux';
    }

    return os;
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

const hamburger = document.querySelector('.hamburger');
const navLink = document.querySelector('.nav__link');

hamburger.addEventListener('click', () => {
  navLink.classList.toggle('hide');
});

function darkMode() {
  var element = document.body;
  element.classList.toggle("dark-mode");
}

function la(src) {
  window.location=src;
}

