function initAbtTest(e) {
    setTimeout(function () {
        initTry++, abtHelper.sendRequest("custom/geth/" + e + "?sid=" + abtHelper.sessionId(), function (t) {
            var n = t.currentTarget;
            4 == n.readyState && 200 == n.status ? document.getElementById("abtFrame").style.height = parseInt(n.responseText) + 100 + "px" : initTry < 4 && initAbtTest(e)
        })
    }, 1e3)
}

!function (e, t) {
    function n() {
        if (!a) {
            a = !0;
            for (var e = 0; e < i.length; e++) i[e].fn.call(window, i[e].ctx);
            i = []
        }
    }

    function o() {
        "complete" === document.readyState && n()
    }

    e = e || "docReadyFn";
    var i = [], a = !1, d = !1;
    (t = t || window)[e] = function (e, t) {
        if ("function" != typeof e) throw new TypeError("callback for docReady(fn) must be a function");
        a ? setTimeout(function () {
            e(t)
        }, 1) : (i.push({
            fn: e,
            ctx: t
        }), "complete" === document.readyState ? setTimeout(n, 1) : d || (document.addEventListener ? (document.addEventListener("DOMContentLoaded", n, !1), window.addEventListener("load", n, !1)) : (document.attachEvent("onreadystatechange", o), window.attachEvent("onload", n)), d = !0))
    }
}("docReadyFn", window);
var abtHelper = {
    get: function (e) {
        if (document.cookie.length > 0) {
            var t = document.cookie.indexOf(e + "=");
            if (-1 != t) {
                t = t + e.length + 1;
                var n = document.cookie.indexOf(";", t);
                return -1 == n && (n = document.cookie.length), unescape(document.cookie.substring(t, n))
            }
        }
        return ""
    }, set: function (e, t, n) {
        var o = new Date;
        o.setDate(o.getDate() + n), document.cookie = e + "=" + escape(t) + (null == n ? "" : "; expires=" + o.toUTCString())
    }, check: function (e) {
        return null != (e = jsCookies.get(e)) && "" != e
    }, sessionId: function () {
        var e = abtHelper.get("abtguid");
        return e.length > 10 ? e : (e = abtHelper.guid(), abtHelper.set("abtguid", e, 100), abtHelper.sessionId())
    }, guid: function () {
        function e() {
            return Math.floor(65536 * (1 + Math.random())).toString(16).substring(1)
        }

        return e() + e() + "-" + e() + "-" + e() + "-" + e() + "-" + e() + e() + e()
    }, sendRequest: function (e, t) {
        var n = new XMLHttpRequest;
        n.onreadystatechange = t, n.open("GET", window.location.protocol + "//www.abt.uz/" + e + "&t=" + (new Date).getTime(), !0), n.send(null)
    }
}, initTry = 0;
docReadyFn(function () {
    var e = document.getElementById("abt_test");
    if (null != e) {
        var t = parseInt(e.getAttribute("data-test"));
        if (t > 0) {
            var n = abtHelper.sessionId();
            e.innerHTML = "<iframe id='abtFrame' width='100%' frameborder='0' onload='initAbtTest(" + t + ")' src='" + window.location.protocol + "//www.abt.uz/custom/" + t + "?sid=" + n + "' style='border:2px solid #e3e3e3; min-height:320px'>"
        } else console.log("TEST_ID not defined")
    } else console.log('add following container: <div id="abt_test" data-test="TEST_ID"></div>')
});