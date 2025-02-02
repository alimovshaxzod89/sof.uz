(function(){"use strict";function a(){}function b(a,b){for(var c=a.length;c--;)if(a[c].listener===b)return c;return-1}function c(a){return function(){return this[a].apply(this,arguments)}}var d=a.prototype,e=this,f=e.EventEmitter;d.getListeners=function(a){var b,c,d=this._getEvents();if("object"==typeof a){b={};for(c in d)d.hasOwnProperty(c)&&a.test(c)&&(b[c]=d[c])}else b=d[a]||(d[a]=[]);return b},d.flattenListeners=function(a){var b,c=[];for(b=0;b<a.length;b+=1)c.push(a[b].listener);return c},d.getListenersAsObject=function(a){var b,c=this.getListeners(a);return c instanceof Array&&(b={},b[a]=c),b||c},d.addListener=function(a,c){var d,e=this.getListenersAsObject(a),f="object"==typeof c;for(d in e)e.hasOwnProperty(d)&&-1===b(e[d],c)&&e[d].push(f?c:{listener:c,once:!1});return this},d.on=c("addListener"),d.addOnceListener=function(a,b){return this.addListener(a,{listener:b,once:!0})},d.once=c("addOnceListener"),d.defineEvent=function(a){return this.getListeners(a),this},d.defineEvents=function(a){for(var b=0;b<a.length;b+=1)this.defineEvent(a[b]);return this},d.removeListener=function(a,c){var d,e,f=this.getListenersAsObject(a);for(e in f)f.hasOwnProperty(e)&&(d=b(f[e],c),-1!==d&&f[e].splice(d,1));return this},d.off=c("removeListener"),d.addListeners=function(a,b){return this.manipulateListeners(!1,a,b)},d.removeListeners=function(a,b){return this.manipulateListeners(!0,a,b)},d.manipulateListeners=function(a,b,c){var d,e,f=a?this.removeListener:this.addListener,g=a?this.removeListeners:this.addListeners;if("object"!=typeof b||b instanceof RegExp)for(d=c.length;d--;)f.call(this,b,c[d]);else for(d in b)b.hasOwnProperty(d)&&(e=b[d])&&("function"==typeof e?f.call(this,d,e):g.call(this,d,e));return this},d.removeEvent=function(a){var b,c=typeof a,d=this._getEvents();if("string"===c)delete d[a];else if("object"===c)for(b in d)d.hasOwnProperty(b)&&a.test(b)&&delete d[b];else delete this._events;return this},d.removeAllListeners=c("removeEvent"),d.emitEvent=function(a,b){var c,d,e,f,g=this.getListenersAsObject(a);for(e in g)if(g.hasOwnProperty(e))for(d=g[e].length;d--;)c=g[e][d],c.once===!0&&this.removeListener(a,c.listener),f=c.listener.apply(this,b||[]),f===this._getOnceReturnValue()&&this.removeListener(a,c.listener);return this},d.trigger=c("emitEvent"),d.emit=function(a){var b=Array.prototype.slice.call(arguments,1);return this.emitEvent(a,b)},d.setOnceReturnValue=function(a){return this._onceReturnValue=a,this},d._getOnceReturnValue=function(){return!this.hasOwnProperty("_onceReturnValue")||this._onceReturnValue},d._getEvents=function(){return this._events||(this._events={})},a.noConflict=function(){return e.EventEmitter=f,a},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return a}):"object"==typeof module&&module.exports?module.exports=a:this.EventEmitter=a}).call(this),function(a){function b(b){var c=a.event;return c.target=c.target||c.srcElement||b,c}var c=document.documentElement,d=function(){};c.addEventListener?d=function(a,b,c){a.addEventListener(b,c,!1)}:c.attachEvent&&(d=function(a,c,d){a[c+d]=d.handleEvent?function(){var c=b(a);d.handleEvent.call(d,c)}:function(){var c=b(a);d.call(a,c)},a.attachEvent("on"+c,a[c+d])});var e=function(){};c.removeEventListener?e=function(a,b,c){a.removeEventListener(b,c,!1)}:c.detachEvent&&(e=function(a,b,c){a.detachEvent("on"+b,a[b+c]);try{delete a[b+c]}catch(d){a[b+c]=void 0}});var f={bind:d,unbind:e};"function"==typeof define&&define.amd?define("eventie/eventie",f):a.eventie=f}(this),function(a,b){"use strict";"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(c,d){return b(a,c,d)}):"object"==typeof module&&module.exports?module.exports=b(a,require("wolfy87-eventemitter"),require("eventie")):a.imagesLoaded=b(a,a.EventEmitter,a.eventie)}(window,function(a,b,c){function d(a,b){for(var c in b)a[c]=b[c];return a}function e(a){return"[object Array]"==l.call(a)}function f(a){var b=[];if(e(a))b=a;else if("number"==typeof a.length)for(var c=0;c<a.length;c++)b.push(a[c]);else b.push(a);return b}function g(a,b,c){if(!(this instanceof g))return new g(a,b,c);"string"==typeof a&&(a=document.querySelectorAll(a)),this.elements=f(a),this.options=d({},this.options),"function"==typeof b?c=b:d(this.options,b),c&&this.on("always",c),this.getImages(),j&&(this.jqDeferred=new j.Deferred);var e=this;setTimeout(function(){e.check()})}function h(a){this.img=a}function i(a,b){this.url=a,this.element=b,this.img=new Image}var j=a.jQuery,k=a.console,l=Object.prototype.toString;g.prototype=new b,g.prototype.options={},g.prototype.getImages=function(){this.images=[];for(var a=0;a<this.elements.length;a++){var b=this.elements[a];this.addElementImages(b)}},g.prototype.addElementImages=function(a){"IMG"==a.nodeName&&this.addImage(a),this.options.background===!0&&this.addElementBackgroundImages(a);var b=a.nodeType;if(b&&m[b]){for(var c=a.querySelectorAll("img"),d=0;d<c.length;d++){var e=c[d];this.addImage(e)}if("string"==typeof this.options.background){var f=a.querySelectorAll(this.options.background);for(d=0;d<f.length;d++){var g=f[d];this.addElementBackgroundImages(g)}}}};var m={1:!0,9:!0,11:!0};g.prototype.addElementBackgroundImages=function(a){for(var b=n(a),c=/url\(['"]*([^'"\)]+)['"]*\)/gi,d=c.exec(b.backgroundImage);null!==d;){var e=d&&d[1];e&&this.addBackground(e,a),d=c.exec(b.backgroundImage)}};var n=a.getComputedStyle||function(a){return a.currentStyle};return g.prototype.addImage=function(a){var b=new h(a);this.images.push(b)},g.prototype.addBackground=function(a,b){var c=new i(a,b);this.images.push(c)},g.prototype.check=function(){function a(a,c,d){setTimeout(function(){b.progress(a,c,d)})}var b=this;if(this.progressedCount=0,this.hasAnyBroken=!1,!this.images.length)return void this.complete();for(var c=0;c<this.images.length;c++){var d=this.images[c];d.once("progress",a),d.check()}},g.prototype.progress=function(a,b,c){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!a.isLoaded,this.emit("progress",this,a,b),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,a),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&k&&k.log("progress: "+c,a,b)},g.prototype.complete=function(){var a=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emit(a,this),this.emit("always",this),this.jqDeferred){var b=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[b](this)}},h.prototype=new b,h.prototype.check=function(){var a=this.getIsImageComplete();return a?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,c.bind(this.proxyImage,"load",this),c.bind(this.proxyImage,"error",this),c.bind(this.img,"load",this),c.bind(this.img,"error",this),void(this.proxyImage.src=this.img.src))},h.prototype.getIsImageComplete=function(){return this.img.complete&&void 0!==this.img.naturalWidth},h.prototype.confirm=function(a,b){this.isLoaded=a,this.emit("progress",this,this.img,b)},h.prototype.handleEvent=function(a){var b="on"+a.type;this[b]&&this[b](a)},h.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},h.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},h.prototype.unbindEvents=function(){c.unbind(this.proxyImage,"load",this),c.unbind(this.proxyImage,"error",this),c.unbind(this.img,"load",this),c.unbind(this.img,"error",this)},i.prototype=new h,i.prototype.check=function(){c.bind(this.img,"load",this),c.bind(this.img,"error",this),this.img.src=this.url;var a=this.getIsImageComplete();a&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},i.prototype.unbindEvents=function(){c.unbind(this.img,"load",this),c.unbind(this.img,"error",this)},i.prototype.confirm=function(a,b){this.isLoaded=a,this.emit("progress",this,this.element,b)},g.makeJQueryPlugin=function(b){b=b||a.jQuery,b&&(j=b,j.fn.imagesLoaded=function(a,b){var c=new g(this,a,b);return c.jqDeferred.promise(j(this))})},g.makeJQueryPlugin(),g});
/*!
 * Masonry PACKAGED v3.3.2
 * Cascading grid layout library
 * http://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

!function(a){function b(){}function c(a){function c(b){b.prototype.option||(b.prototype.option=function(b){a.isPlainObject(b)&&(this.options=a.extend(!0,this.options,b))})}function e(b,c){a.fn[b]=function(e){if("string"==typeof e){for(var g=d.call(arguments,1),h=0,i=this.length;i>h;h++){var j=this[h],k=a.data(j,b);if(k)if(a.isFunction(k[e])&&"_"!==e.charAt(0)){var l=k[e].apply(k,g);if(void 0!==l)return l}else f("no such method '"+e+"' for "+b+" instance");else f("cannot call methods on "+b+" prior to initialization; attempted to call '"+e+"'")}return this}return this.each(function(){var d=a.data(this,b);d?(d.option(e),d._init()):(d=new c(this,e),a.data(this,b,d))})}}if(a){var f="undefined"==typeof console?b:function(a){console.error(a)};return a.bridget=function(a,b){c(b),e(a,b)},a.bridget}}var d=Array.prototype.slice;"function"==typeof define&&define.amd?define("jquery-bridget/jquery.bridget",["jquery"],c):c("object"==typeof exports?require("jquery"):a.jQuery)}(window),function(a){function b(b){var c=a.event;return c.target=c.target||c.srcElement||b,c}var c=document.documentElement,d=function(){};c.addEventListener?d=function(a,b,c){a.addEventListener(b,c,!1)}:c.attachEvent&&(d=function(a,c,d){a[c+d]=d.handleEvent?function(){var c=b(a);d.handleEvent.call(d,c)}:function(){var c=b(a);d.call(a,c)},a.attachEvent("on"+c,a[c+d])});var e=function(){};c.removeEventListener?e=function(a,b,c){a.removeEventListener(b,c,!1)}:c.detachEvent&&(e=function(a,b,c){a.detachEvent("on"+b,a[b+c]);try{delete a[b+c]}catch(d){a[b+c]=void 0}});var f={bind:d,unbind:e};"function"==typeof define&&define.amd?define("eventie/eventie",f):"object"==typeof exports?module.exports=f:a.eventie=f}(window),function(){function a(){}function b(a,b){for(var c=a.length;c--;)if(a[c].listener===b)return c;return-1}function c(a){return function(){return this[a].apply(this,arguments)}}var d=a.prototype,e=this,f=e.EventEmitter;d.getListeners=function(a){var b,c,d=this._getEvents();if(a instanceof RegExp){b={};for(c in d)d.hasOwnProperty(c)&&a.test(c)&&(b[c]=d[c])}else b=d[a]||(d[a]=[]);return b},d.flattenListeners=function(a){var b,c=[];for(b=0;b<a.length;b+=1)c.push(a[b].listener);return c},d.getListenersAsObject=function(a){var b,c=this.getListeners(a);return c instanceof Array&&(b={},b[a]=c),b||c},d.addListener=function(a,c){var d,e=this.getListenersAsObject(a),f="object"==typeof c;for(d in e)e.hasOwnProperty(d)&&-1===b(e[d],c)&&e[d].push(f?c:{listener:c,once:!1});return this},d.on=c("addListener"),d.addOnceListener=function(a,b){return this.addListener(a,{listener:b,once:!0})},d.once=c("addOnceListener"),d.defineEvent=function(a){return this.getListeners(a),this},d.defineEvents=function(a){for(var b=0;b<a.length;b+=1)this.defineEvent(a[b]);return this},d.removeListener=function(a,c){var d,e,f=this.getListenersAsObject(a);for(e in f)f.hasOwnProperty(e)&&(d=b(f[e],c),-1!==d&&f[e].splice(d,1));return this},d.off=c("removeListener"),d.addListeners=function(a,b){return this.manipulateListeners(!1,a,b)},d.removeListeners=function(a,b){return this.manipulateListeners(!0,a,b)},d.manipulateListeners=function(a,b,c){var d,e,f=a?this.removeListener:this.addListener,g=a?this.removeListeners:this.addListeners;if("object"!=typeof b||b instanceof RegExp)for(d=c.length;d--;)f.call(this,b,c[d]);else for(d in b)b.hasOwnProperty(d)&&(e=b[d])&&("function"==typeof e?f.call(this,d,e):g.call(this,d,e));return this},d.removeEvent=function(a){var b,c=typeof a,d=this._getEvents();if("string"===c)delete d[a];else if(a instanceof RegExp)for(b in d)d.hasOwnProperty(b)&&a.test(b)&&delete d[b];else delete this._events;return this},d.removeAllListeners=c("removeEvent"),d.emitEvent=function(a,b){var c,d,e,f,g=this.getListenersAsObject(a);for(e in g)if(g.hasOwnProperty(e))for(d=g[e].length;d--;)c=g[e][d],c.once===!0&&this.removeListener(a,c.listener),f=c.listener.apply(this,b||[]),f===this._getOnceReturnValue()&&this.removeListener(a,c.listener);return this},d.trigger=c("emitEvent"),d.emit=function(a){var b=Array.prototype.slice.call(arguments,1);return this.emitEvent(a,b)},d.setOnceReturnValue=function(a){return this._onceReturnValue=a,this},d._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},d._getEvents=function(){return this._events||(this._events={})},a.noConflict=function(){return e.EventEmitter=f,a},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return a}):"object"==typeof module&&module.exports?module.exports=a:e.EventEmitter=a}.call(this),function(a){function b(a){if(a){if("string"==typeof d[a])return a;a=a.charAt(0).toUpperCase()+a.slice(1);for(var b,e=0,f=c.length;f>e;e++)if(b=c[e]+a,"string"==typeof d[b])return b}}var c="Webkit Moz ms Ms O".split(" "),d=document.documentElement.style;"function"==typeof define&&define.amd?define("get-style-property/get-style-property",[],function(){return b}):"object"==typeof exports?module.exports=b:a.getStyleProperty=b}(window),function(a){function b(a){var b=parseFloat(a),c=-1===a.indexOf("%")&&!isNaN(b);return c&&b}function c(){}function d(){for(var a={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},b=0,c=g.length;c>b;b++){var d=g[b];a[d]=0}return a}function e(c){function e(){if(!m){m=!0;var d=a.getComputedStyle;if(j=function(){var a=d?function(a){return d(a,null)}:function(a){return a.currentStyle};return function(b){var c=a(b);return c||f("Style returned "+c+". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"),c}}(),k=c("boxSizing")){var e=document.createElement("div");e.style.width="200px",e.style.padding="1px 2px 3px 4px",e.style.borderStyle="solid",e.style.borderWidth="1px 2px 3px 4px",e.style[k]="border-box";var g=document.body||document.documentElement;g.appendChild(e);var h=j(e);l=200===b(h.width),g.removeChild(e)}}}function h(a){if(e(),"string"==typeof a&&(a=document.querySelector(a)),a&&"object"==typeof a&&a.nodeType){var c=j(a);if("none"===c.display)return d();var f={};f.width=a.offsetWidth,f.height=a.offsetHeight;for(var h=f.isBorderBox=!(!k||!c[k]||"border-box"!==c[k]),m=0,n=g.length;n>m;m++){var o=g[m],p=c[o];p=i(a,p);var q=parseFloat(p);f[o]=isNaN(q)?0:q}var r=f.paddingLeft+f.paddingRight,s=f.paddingTop+f.paddingBottom,t=f.marginLeft+f.marginRight,u=f.marginTop+f.marginBottom,v=f.borderLeftWidth+f.borderRightWidth,w=f.borderTopWidth+f.borderBottomWidth,x=h&&l,y=b(c.width);y!==!1&&(f.width=y+(x?0:r+v));var z=b(c.height);return z!==!1&&(f.height=z+(x?0:s+w)),f.innerWidth=f.width-(r+v),f.innerHeight=f.height-(s+w),f.outerWidth=f.width+t,f.outerHeight=f.height+u,f}}function i(b,c){if(a.getComputedStyle||-1===c.indexOf("%"))return c;var d=b.style,e=d.left,f=b.runtimeStyle,g=f&&f.left;return g&&(f.left=b.currentStyle.left),d.left=c,c=d.pixelLeft,d.left=e,g&&(f.left=g),c}var j,k,l,m=!1;return h}var f="undefined"==typeof console?c:function(a){console.error(a)},g=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"];"function"==typeof define&&define.amd?define("get-size/get-size",["get-style-property/get-style-property"],e):"object"==typeof exports?module.exports=e(require("desandro-get-style-property")):a.getSize=e(a.getStyleProperty)}(window),function(a){function b(a){"function"==typeof a&&(b.isReady?a():g.push(a))}function c(a){var c="readystatechange"===a.type&&"complete"!==f.readyState;b.isReady||c||d()}function d(){b.isReady=!0;for(var a=0,c=g.length;c>a;a++){var d=g[a];d()}}function e(e){return"complete"===f.readyState?d():(e.bind(f,"DOMContentLoaded",c),e.bind(f,"readystatechange",c),e.bind(a,"load",c)),b}var f=a.document,g=[];b.isReady=!1,"function"==typeof define&&define.amd?define("doc-ready/doc-ready",["eventie/eventie"],e):"object"==typeof exports?module.exports=e(require("eventie")):a.docReady=e(a.eventie)}(window),function(a){function b(a,b){return a[g](b)}function c(a){if(!a.parentNode){var b=document.createDocumentFragment();b.appendChild(a)}}function d(a,b){c(a);for(var d=a.parentNode.querySelectorAll(b),e=0,f=d.length;f>e;e++)if(d[e]===a)return!0;return!1}function e(a,d){return c(a),b(a,d)}var f,g=function(){if(a.matches)return"matches";if(a.matchesSelector)return"matchesSelector";for(var b=["webkit","moz","ms","o"],c=0,d=b.length;d>c;c++){var e=b[c],f=e+"MatchesSelector";if(a[f])return f}}();if(g){var h=document.createElement("div"),i=b(h,"div");f=i?b:e}else f=d;"function"==typeof define&&define.amd?define("matches-selector/matches-selector",[],function(){return f}):"object"==typeof exports?module.exports=f:window.matchesSelector=f}(Element.prototype),function(a,b){"function"==typeof define&&define.amd?define("fizzy-ui-utils/utils",["doc-ready/doc-ready","matches-selector/matches-selector"],function(c,d){return b(a,c,d)}):"object"==typeof exports?module.exports=b(a,require("doc-ready"),require("desandro-matches-selector")):a.fizzyUIUtils=b(a,a.docReady,a.matchesSelector)}(window,function(a,b,c){var d={};d.extend=function(a,b){for(var c in b)a[c]=b[c];return a},d.modulo=function(a,b){return(a%b+b)%b};var e=Object.prototype.toString;d.isArray=function(a){return"[object Array]"==e.call(a)},d.makeArray=function(a){var b=[];if(d.isArray(a))b=a;else if(a&&"number"==typeof a.length)for(var c=0,e=a.length;e>c;c++)b.push(a[c]);else b.push(a);return b},d.indexOf=Array.prototype.indexOf?function(a,b){return a.indexOf(b)}:function(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]===b)return c;return-1},d.removeFrom=function(a,b){var c=d.indexOf(a,b);-1!=c&&a.splice(c,1)},d.isElement="function"==typeof HTMLElement||"object"==typeof HTMLElement?function(a){return a instanceof HTMLElement}:function(a){return a&&"object"==typeof a&&1==a.nodeType&&"string"==typeof a.nodeName},d.setText=function(){function a(a,c){b=b||(void 0!==document.documentElement.textContent?"textContent":"innerText"),a[b]=c}var b;return a}(),d.getParent=function(a,b){for(;a!=document.body;)if(a=a.parentNode,c(a,b))return a},d.getQueryElement=function(a){return"string"==typeof a?document.querySelector(a):a},d.handleEvent=function(a){var b="on"+a.type;this[b]&&this[b](a)},d.filterFindElements=function(a,b){a=d.makeArray(a);for(var e=[],f=0,g=a.length;g>f;f++){var h=a[f];if(d.isElement(h))if(b){c(h,b)&&e.push(h);for(var i=h.querySelectorAll(b),j=0,k=i.length;k>j;j++)e.push(i[j])}else e.push(h)}return e},d.debounceMethod=function(a,b,c){var d=a.prototype[b],e=b+"Timeout";a.prototype[b]=function(){var a=this[e];a&&clearTimeout(a);var b=arguments,f=this;this[e]=setTimeout(function(){d.apply(f,b),delete f[e]},c||100)}},d.toDashed=function(a){return a.replace(/(.)([A-Z])/g,function(a,b,c){return b+"-"+c}).toLowerCase()};var f=a.console;return d.htmlInit=function(c,e){b(function(){for(var b=d.toDashed(e),g=document.querySelectorAll(".js-"+b),h="data-"+b+"-options",i=0,j=g.length;j>i;i++){var k,l=g[i],m=l.getAttribute(h);try{k=m&&JSON.parse(m)}catch(n){f&&f.error("Error parsing "+h+" on "+l.nodeName.toLowerCase()+(l.id?"#"+l.id:"")+": "+n);continue}var o=new c(l,k),p=a.jQuery;p&&p.data(l,e,o)}})},d}),function(a,b){"function"==typeof define&&define.amd?define("outlayer/item",["eventEmitter/EventEmitter","get-size/get-size","get-style-property/get-style-property","fizzy-ui-utils/utils"],function(c,d,e,f){return b(a,c,d,e,f)}):"object"==typeof exports?module.exports=b(a,require("wolfy87-eventemitter"),require("get-size"),require("desandro-get-style-property"),require("fizzy-ui-utils")):(a.Outlayer={},a.Outlayer.Item=b(a,a.EventEmitter,a.getSize,a.getStyleProperty,a.fizzyUIUtils))}(window,function(a,b,c,d,e){function f(a){for(var b in a)return!1;return b=null,!0}function g(a,b){a&&(this.element=a,this.layout=b,this.position={x:0,y:0},this._create())}function h(a){return a.replace(/([A-Z])/g,function(a){return"-"+a.toLowerCase()})}var i=a.getComputedStyle,j=i?function(a){return i(a,null)}:function(a){return a.currentStyle},k=d("transition"),l=d("transform"),m=k&&l,n=!!d("perspective"),o={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"otransitionend",transition:"transitionend"}[k],p=["transform","transition","transitionDuration","transitionProperty"],q=function(){for(var a={},b=0,c=p.length;c>b;b++){var e=p[b],f=d(e);f&&f!==e&&(a[e]=f)}return a}();e.extend(g.prototype,b.prototype),g.prototype._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},g.prototype.handleEvent=function(a){var b="on"+a.type;this[b]&&this[b](a)},g.prototype.getSize=function(){this.size=c(this.element)},g.prototype.css=function(a){var b=this.element.style;for(var c in a){var d=q[c]||c;b[d]=a[c]}},g.prototype.getPosition=function(){var a=j(this.element),b=this.layout.options,c=b.isOriginLeft,d=b.isOriginTop,e=a[c?"left":"right"],f=a[d?"top":"bottom"],g=this.layout.size,h=-1!=e.indexOf("%")?parseFloat(e)/100*g.width:parseInt(e,10),i=-1!=f.indexOf("%")?parseFloat(f)/100*g.height:parseInt(f,10);h=isNaN(h)?0:h,i=isNaN(i)?0:i,h-=c?g.paddingLeft:g.paddingRight,i-=d?g.paddingTop:g.paddingBottom,this.position.x=h,this.position.y=i},g.prototype.layoutPosition=function(){var a=this.layout.size,b=this.layout.options,c={},d=b.isOriginLeft?"paddingLeft":"paddingRight",e=b.isOriginLeft?"left":"right",f=b.isOriginLeft?"right":"left",g=this.position.x+a[d];c[e]=this.getXValue(g),c[f]="";var h=b.isOriginTop?"paddingTop":"paddingBottom",i=b.isOriginTop?"top":"bottom",j=b.isOriginTop?"bottom":"top",k=this.position.y+a[h];c[i]=this.getYValue(k),c[j]="",this.css(c),this.emitEvent("layout",[this])},g.prototype.getXValue=function(a){var b=this.layout.options;return b.percentPosition&&!b.isHorizontal?a/this.layout.size.width*100+"%":a+"px"},g.prototype.getYValue=function(a){var b=this.layout.options;return b.percentPosition&&b.isHorizontal?a/this.layout.size.height*100+"%":a+"px"},g.prototype._transitionTo=function(a,b){this.getPosition();var c=this.position.x,d=this.position.y,e=parseInt(a,10),f=parseInt(b,10),g=e===this.position.x&&f===this.position.y;if(this.setPosition(a,b),g&&!this.isTransitioning)return void this.layoutPosition();var h=a-c,i=b-d,j={};j.transform=this.getTranslate(h,i),this.transition({to:j,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})},g.prototype.getTranslate=function(a,b){var c=this.layout.options;return a=c.isOriginLeft?a:-a,b=c.isOriginTop?b:-b,n?"translate3d("+a+"px, "+b+"px, 0)":"translate("+a+"px, "+b+"px)"},g.prototype.goTo=function(a,b){this.setPosition(a,b),this.layoutPosition()},g.prototype.moveTo=m?g.prototype._transitionTo:g.prototype.goTo,g.prototype.setPosition=function(a,b){this.position.x=parseInt(a,10),this.position.y=parseInt(b,10)},g.prototype._nonTransition=function(a){this.css(a.to),a.isCleaning&&this._removeStyles(a.to);for(var b in a.onTransitionEnd)a.onTransitionEnd[b].call(this)},g.prototype._transition=function(a){if(!parseFloat(this.layout.options.transitionDuration))return void this._nonTransition(a);var b=this._transn;for(var c in a.onTransitionEnd)b.onEnd[c]=a.onTransitionEnd[c];for(c in a.to)b.ingProperties[c]=!0,a.isCleaning&&(b.clean[c]=!0);if(a.from){this.css(a.from);var d=this.element.offsetHeight;d=null}this.enableTransition(a.to),this.css(a.to),this.isTransitioning=!0};var r="opacity,"+h(q.transform||"transform");g.prototype.enableTransition=function(){this.isTransitioning||(this.css({transitionProperty:r,transitionDuration:this.layout.options.transitionDuration}),this.element.addEventListener(o,this,!1))},g.prototype.transition=g.prototype[k?"_transition":"_nonTransition"],g.prototype.onwebkitTransitionEnd=function(a){this.ontransitionend(a)},g.prototype.onotransitionend=function(a){this.ontransitionend(a)};var s={"-webkit-transform":"transform","-moz-transform":"transform","-o-transform":"transform"};g.prototype.ontransitionend=function(a){if(a.target===this.element){var b=this._transn,c=s[a.propertyName]||a.propertyName;if(delete b.ingProperties[c],f(b.ingProperties)&&this.disableTransition(),c in b.clean&&(this.element.style[a.propertyName]="",delete b.clean[c]),c in b.onEnd){var d=b.onEnd[c];d.call(this),delete b.onEnd[c]}this.emitEvent("transitionEnd",[this])}},g.prototype.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(o,this,!1),this.isTransitioning=!1},g.prototype._removeStyles=function(a){var b={};for(var c in a)b[c]="";this.css(b)};var t={transitionProperty:"",transitionDuration:""};return g.prototype.removeTransitionStyles=function(){this.css(t)},g.prototype.removeElem=function(){this.element.parentNode.removeChild(this.element),this.css({display:""}),this.emitEvent("remove",[this])},g.prototype.remove=function(){if(!k||!parseFloat(this.layout.options.transitionDuration))return void this.removeElem();var a=this;this.once("transitionEnd",function(){a.removeElem()}),this.hide()},g.prototype.reveal=function(){delete this.isHidden,this.css({display:""});var a=this.layout.options,b={},c=this.getHideRevealTransitionEndProperty("visibleStyle");b[c]=this.onRevealTransitionEnd,this.transition({from:a.hiddenStyle,to:a.visibleStyle,isCleaning:!0,onTransitionEnd:b})},g.prototype.onRevealTransitionEnd=function(){this.isHidden||this.emitEvent("reveal")},g.prototype.getHideRevealTransitionEndProperty=function(a){var b=this.layout.options[a];if(b.opacity)return"opacity";for(var c in b)return c},g.prototype.hide=function(){this.isHidden=!0,this.css({display:""});var a=this.layout.options,b={},c=this.getHideRevealTransitionEndProperty("hiddenStyle");b[c]=this.onHideTransitionEnd,this.transition({from:a.visibleStyle,to:a.hiddenStyle,isCleaning:!0,onTransitionEnd:b})},g.prototype.onHideTransitionEnd=function(){this.isHidden&&(this.css({display:"none"}),this.emitEvent("hide"))},g.prototype.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},g}),function(a,b){"function"==typeof define&&define.amd?define("outlayer/outlayer",["eventie/eventie","eventEmitter/EventEmitter","get-size/get-size","fizzy-ui-utils/utils","./item"],function(c,d,e,f,g){return b(a,c,d,e,f,g)}):"object"==typeof exports?module.exports=b(a,require("eventie"),require("wolfy87-eventemitter"),require("get-size"),require("fizzy-ui-utils"),require("./item")):a.Outlayer=b(a,a.eventie,a.EventEmitter,a.getSize,a.fizzyUIUtils,a.Outlayer.Item)}(window,function(a,b,c,d,e,f){function g(a,b){var c=e.getQueryElement(a);if(!c)return void(h&&h.error("Bad element for "+this.constructor.namespace+": "+(c||a)));this.element=c,i&&(this.$element=i(this.element)),this.options=e.extend({},this.constructor.defaults),this.option(b);var d=++k;this.element.outlayerGUID=d,l[d]=this,this._create(),this.options.isInitLayout&&this.layout()}var h=a.console,i=a.jQuery,j=function(){},k=0,l={};return g.namespace="outlayer",g.Item=f,g.defaults={containerStyle:{position:"relative"},isInitLayout:!0,isOriginLeft:!0,isOriginTop:!0,isResizeBound:!0,isResizingContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}},e.extend(g.prototype,c.prototype),g.prototype.option=function(a){e.extend(this.options,a)},g.prototype._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),e.extend(this.element.style,this.options.containerStyle),this.options.isResizeBound&&this.bindResize()},g.prototype.reloadItems=function(){this.items=this._itemize(this.element.children)},g.prototype._itemize=function(a){for(var b=this._filterFindItemElements(a),c=this.constructor.Item,d=[],e=0,f=b.length;f>e;e++){var g=b[e],h=new c(g,this);d.push(h)}return d},g.prototype._filterFindItemElements=function(a){return e.filterFindElements(a,this.options.itemSelector)},g.prototype.getItemElements=function(){for(var a=[],b=0,c=this.items.length;c>b;b++)a.push(this.items[b].element);return a},g.prototype.layout=function(){this._resetLayout(),this._manageStamps();var a=void 0!==this.options.isLayoutInstant?this.options.isLayoutInstant:!this._isLayoutInited;this.layoutItems(this.items,a),this._isLayoutInited=!0},g.prototype._init=g.prototype.layout,g.prototype._resetLayout=function(){this.getSize()},g.prototype.getSize=function(){this.size=d(this.element)},g.prototype._getMeasurement=function(a,b){var c,f=this.options[a];f?("string"==typeof f?c=this.element.querySelector(f):e.isElement(f)&&(c=f),this[a]=c?d(c)[b]:f):this[a]=0},g.prototype.layoutItems=function(a,b){a=this._getItemsForLayout(a),this._layoutItems(a,b),this._postLayout()},g.prototype._getItemsForLayout=function(a){for(var b=[],c=0,d=a.length;d>c;c++){var e=a[c];e.isIgnored||b.push(e)}return b},g.prototype._layoutItems=function(a,b){if(this._emitCompleteOnItems("layout",a),a&&a.length){for(var c=[],d=0,e=a.length;e>d;d++){var f=a[d],g=this._getItemLayoutPosition(f);g.item=f,g.isInstant=b||f.isLayoutInstant,c.push(g)}this._processLayoutQueue(c)}},g.prototype._getItemLayoutPosition=function(){return{x:0,y:0}},g.prototype._processLayoutQueue=function(a){for(var b=0,c=a.length;c>b;b++){var d=a[b];this._positionItem(d.item,d.x,d.y,d.isInstant)}},g.prototype._positionItem=function(a,b,c,d){d?a.goTo(b,c):a.moveTo(b,c)},g.prototype._postLayout=function(){this.resizeContainer()},g.prototype.resizeContainer=function(){if(this.options.isResizingContainer){var a=this._getContainerSize();a&&(this._setContainerMeasure(a.width,!0),this._setContainerMeasure(a.height,!1))}},g.prototype._getContainerSize=j,g.prototype._setContainerMeasure=function(a,b){if(void 0!==a){var c=this.size;c.isBorderBox&&(a+=b?c.paddingLeft+c.paddingRight+c.borderLeftWidth+c.borderRightWidth:c.paddingBottom+c.paddingTop+c.borderTopWidth+c.borderBottomWidth),a=Math.max(a,0),this.element.style[b?"width":"height"]=a+"px"}},g.prototype._emitCompleteOnItems=function(a,b){function c(){e.dispatchEvent(a+"Complete",null,[b])}function d(){g++,g===f&&c()}var e=this,f=b.length;if(!b||!f)return void c();for(var g=0,h=0,i=b.length;i>h;h++){var j=b[h];j.once(a,d)}},g.prototype.dispatchEvent=function(a,b,c){var d=b?[b].concat(c):c;if(this.emitEvent(a,d),i)if(this.$element=this.$element||i(this.element),b){var e=i.Event(b);e.type=a,this.$element.trigger(e,c)}else this.$element.trigger(a,c)},g.prototype.ignore=function(a){var b=this.getItem(a);b&&(b.isIgnored=!0)},g.prototype.unignore=function(a){var b=this.getItem(a);b&&delete b.isIgnored},g.prototype.stamp=function(a){if(a=this._find(a)){this.stamps=this.stamps.concat(a);for(var b=0,c=a.length;c>b;b++){var d=a[b];this.ignore(d)}}},g.prototype.unstamp=function(a){if(a=this._find(a))for(var b=0,c=a.length;c>b;b++){var d=a[b];e.removeFrom(this.stamps,d),this.unignore(d)}},g.prototype._find=function(a){return a?("string"==typeof a&&(a=this.element.querySelectorAll(a)),a=e.makeArray(a)):void 0},g.prototype._manageStamps=function(){if(this.stamps&&this.stamps.length){this._getBoundingRect();for(var a=0,b=this.stamps.length;b>a;a++){var c=this.stamps[a];this._manageStamp(c)}}},g.prototype._getBoundingRect=function(){var a=this.element.getBoundingClientRect(),b=this.size;this._boundingRect={left:a.left+b.paddingLeft+b.borderLeftWidth,top:a.top+b.paddingTop+b.borderTopWidth,right:a.right-(b.paddingRight+b.borderRightWidth),bottom:a.bottom-(b.paddingBottom+b.borderBottomWidth)}},g.prototype._manageStamp=j,g.prototype._getElementOffset=function(a){var b=a.getBoundingClientRect(),c=this._boundingRect,e=d(a),f={left:b.left-c.left-e.marginLeft,top:b.top-c.top-e.marginTop,right:c.right-b.right-e.marginRight,bottom:c.bottom-b.bottom-e.marginBottom};return f},g.prototype.handleEvent=function(a){var b="on"+a.type;this[b]&&this[b](a)},g.prototype.bindResize=function(){this.isResizeBound||(b.bind(a,"resize",this),this.isResizeBound=!0)},g.prototype.unbindResize=function(){this.isResizeBound&&b.unbind(a,"resize",this),this.isResizeBound=!1},g.prototype.onresize=function(){function a(){b.resize(),delete b.resizeTimeout}this.resizeTimeout&&clearTimeout(this.resizeTimeout);var b=this;this.resizeTimeout=setTimeout(a,100)},g.prototype.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},g.prototype.needsResizeLayout=function(){var a=d(this.element),b=this.size&&a;return b&&a.innerWidth!==this.size.innerWidth},g.prototype.addItems=function(a){var b=this._itemize(a);return b.length&&(this.items=this.items.concat(b)),b},g.prototype.appended=function(a){var b=this.addItems(a);b.length&&(this.layoutItems(b,!0),this.reveal(b))},g.prototype.prepended=function(a){var b=this._itemize(a);if(b.length){var c=this.items.slice(0);this.items=b.concat(c),this._resetLayout(),this._manageStamps(),this.layoutItems(b,!0),this.reveal(b),this.layoutItems(c)}},g.prototype.reveal=function(a){this._emitCompleteOnItems("reveal",a);for(var b=a&&a.length,c=0;b&&b>c;c++){var d=a[c];d.reveal()}},g.prototype.hide=function(a){this._emitCompleteOnItems("hide",a);for(var b=a&&a.length,c=0;b&&b>c;c++){var d=a[c];d.hide()}},g.prototype.revealItemElements=function(a){var b=this.getItems(a);this.reveal(b)},g.prototype.hideItemElements=function(a){var b=this.getItems(a);this.hide(b)},g.prototype.getItem=function(a){for(var b=0,c=this.items.length;c>b;b++){var d=this.items[b];if(d.element===a)return d}},g.prototype.getItems=function(a){a=e.makeArray(a);for(var b=[],c=0,d=a.length;d>c;c++){var f=a[c],g=this.getItem(f);g&&b.push(g)}return b},g.prototype.remove=function(a){var b=this.getItems(a);if(this._emitCompleteOnItems("remove",b),b&&b.length)for(var c=0,d=b.length;d>c;c++){var f=b[c];f.remove(),e.removeFrom(this.items,f)}},g.prototype.destroy=function(){var a=this.element.style;a.height="",a.position="",a.width="";for(var b=0,c=this.items.length;c>b;b++){var d=this.items[b];d.destroy()}this.unbindResize();var e=this.element.outlayerGUID;delete l[e],delete this.element.outlayerGUID,i&&i.removeData(this.element,this.constructor.namespace)},g.data=function(a){a=e.getQueryElement(a);var b=a&&a.outlayerGUID;return b&&l[b]},g.create=function(a,b){function c(){g.apply(this,arguments)}return Object.create?c.prototype=Object.create(g.prototype):e.extend(c.prototype,g.prototype),c.prototype.constructor=c,c.defaults=e.extend({},g.defaults),e.extend(c.defaults,b),c.prototype.settings={},c.namespace=a,c.data=g.data,c.Item=function(){f.apply(this,arguments)},c.Item.prototype=new f,e.htmlInit(c,a),i&&i.bridget&&i.bridget(a,c),c},g.Item=f,g}),function(a,b){"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size","fizzy-ui-utils/utils"],b):"object"==typeof exports?module.exports=b(require("outlayer"),require("get-size"),require("fizzy-ui-utils")):a.Masonry=b(a.Outlayer,a.getSize,a.fizzyUIUtils)}(window,function(a,b,c){var d=a.create("masonry");return d.prototype._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns();var a=this.cols;for(this.colYs=[];a--;)this.colYs.push(0);this.maxY=0},d.prototype.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var a=this.items[0],c=a&&a.element;this.columnWidth=c&&b(c).outerWidth||this.containerWidth}var d=this.columnWidth+=this.gutter,e=this.containerWidth+this.gutter,f=e/d,g=d-e%d,h=g&&1>g?"round":"floor";f=Math[h](f),this.cols=Math.max(f,1)},d.prototype.getContainerWidth=function(){var a=this.options.isFitWidth?this.element.parentNode:this.element,c=b(a);this.containerWidth=c&&c.innerWidth},d.prototype._getItemLayoutPosition=function(a){a.getSize();var b=a.size.outerWidth%this.columnWidth,d=b&&1>b?"round":"ceil",e=Math[d](a.size.outerWidth/this.columnWidth);e=Math.min(e,this.cols);for(var f=this._getColGroup(e),g=Math.min.apply(Math,f),h=c.indexOf(f,g),i={x:this.columnWidth*h,y:g},j=g+a.size.outerHeight,k=this.cols+1-f.length,l=0;k>l;l++)this.colYs[h+l]=j;return i},d.prototype._getColGroup=function(a){if(2>a)return this.colYs;for(var b=[],c=this.cols+1-a,d=0;c>d;d++){var e=this.colYs.slice(d,d+a);b[d]=Math.max.apply(Math,e)}return b},d.prototype._manageStamp=function(a){var c=b(a),d=this._getElementOffset(a),e=this.options.isOriginLeft?d.left:d.right,f=e+c.outerWidth,g=Math.floor(e/this.columnWidth);g=Math.max(0,g);var h=Math.floor(f/this.columnWidth);h-=f%this.columnWidth?0:1,h=Math.min(this.cols-1,h);for(var i=(this.options.isOriginTop?d.top:d.bottom)+c.outerHeight,j=g;h>=j;j++)this.colYs[j]=Math.max(i,this.colYs[j])},d.prototype._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var a={height:this.maxY};return this.options.isFitWidth&&(a.width=this._getContainerFitWidth()),a},d.prototype._getContainerFitWidth=function(){for(var a=0,b=this.cols;--b&&0===this.colYs[b];)a++;return(this.cols-a)*this.columnWidth-this.gutter},d.prototype.needsResizeLayout=function(){var a=this.containerWidth;return this.getContainerWidth(),a!==this.containerWidth},d});
!function(i){i.fn.theiaStickySidebar=function(t){function e(t,e){var a=o(t,e);a||(console.log("TSS: Body width smaller than options.minWidth. Init is delayed."),i(document).on("scroll."+t.namespace,function(t,e){return function(a){var n=o(t,e);n&&i(this).unbind(a)}}(t,e)),i(window).on("resize."+t.namespace,function(t,e){return function(a){var n=o(t,e);n&&i(this).unbind(a)}}(t,e)))}function o(t,e){return t.initialized===!0||!(i("body").width()<t.minWidth)&&(a(t,e),!0)}function a(t,e){t.initialized=!0;var o=i("#theia-sticky-sidebar-stylesheet-"+t.namespace);0===o.length&&i("head").append(i('<style id="theia-sticky-sidebar-stylesheet-'+t.namespace+'">.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>')),e.each(function(){function e(){a.fixedScrollTop=0,a.sidebar.css({"min-height":"1px"}),a.stickySidebar.css({position:"static",width:"",transform:"none"})}function o(t){var e=t.height();return t.children().each(function(){e=Math.max(e,i(this).height())}),e}var a={};if(a.sidebar=i(this),a.options=t||{},a.container=i(a.options.containerSelector),0==a.container.length&&(a.container=a.sidebar.parent()),a.sidebar.parents().css("-webkit-transform","none"),a.sidebar.css({position:a.options.defaultPosition,overflow:"visible","-webkit-box-sizing":"border-box","-moz-box-sizing":"border-box","box-sizing":"border-box"}),a.stickySidebar=a.sidebar.find(".theiaStickySidebar"),0==a.stickySidebar.length){var s=/(?:text|application)\/(?:x-)?(?:javascript|ecmascript)/i;a.sidebar.find("script").filter(function(i,t){return 0===t.type.length||t.type.match(s)}).remove(),a.stickySidebar=i("<div>").addClass("theiaStickySidebar").append(a.sidebar.children()),a.sidebar.append(a.stickySidebar)}a.marginBottom=parseInt(a.sidebar.css("margin-bottom")),a.paddingTop=parseInt(a.sidebar.css("padding-top")),a.paddingBottom=parseInt(a.sidebar.css("padding-bottom"));var r=a.stickySidebar.offset().top,d=a.stickySidebar.outerHeight();a.stickySidebar.css("padding-top",1),a.stickySidebar.css("padding-bottom",1),r-=a.stickySidebar.offset().top,d=a.stickySidebar.outerHeight()-d-r,0==r?(a.stickySidebar.css("padding-top",0),a.stickySidebarPaddingTop=0):a.stickySidebarPaddingTop=1,0==d?(a.stickySidebar.css("padding-bottom",0),a.stickySidebarPaddingBottom=0):a.stickySidebarPaddingBottom=1,a.previousScrollTop=null,a.fixedScrollTop=0,e(),a.onScroll=function(a){if(a.stickySidebar.is(":visible")){if(i("body").width()<a.options.minWidth)return void e();if(a.options.disableOnResponsiveLayouts){var s=a.sidebar.outerWidth("none"==a.sidebar.css("float"));if(s+50>a.container.width())return void e()}var r=i(document).scrollTop(),d="static";if(r>=a.sidebar.offset().top+(a.paddingTop-a.options.additionalMarginTop)){var c,p=a.paddingTop+t.additionalMarginTop,b=a.paddingBottom+a.marginBottom+t.additionalMarginBottom,l=a.sidebar.offset().top,f=a.sidebar.offset().top+o(a.container),h=0+t.additionalMarginTop,g=a.stickySidebar.outerHeight()+p+b<i(window).height();c=g?h+a.stickySidebar.outerHeight():i(window).height()-a.marginBottom-a.paddingBottom-t.additionalMarginBottom;var u=l-r+a.paddingTop,S=f-r-a.paddingBottom-a.marginBottom,y=a.stickySidebar.offset().top-r,m=a.previousScrollTop-r;"fixed"==a.stickySidebar.css("position")&&"modern"==a.options.sidebarBehavior&&(y+=m),"stick-to-top"==a.options.sidebarBehavior&&(y=t.additionalMarginTop),"stick-to-bottom"==a.options.sidebarBehavior&&(y=c-a.stickySidebar.outerHeight()),y=m>0?Math.min(y,h):Math.max(y,c-a.stickySidebar.outerHeight()),y=Math.max(y,u),y=Math.min(y,S-a.stickySidebar.outerHeight());var k=a.container.height()==a.stickySidebar.outerHeight();d=(k||y!=h)&&(k||y!=c-a.stickySidebar.outerHeight())?r+y-a.sidebar.offset().top-a.paddingTop<=t.additionalMarginTop?"static":"absolute":"fixed"}if("fixed"==d){var v=i(document).scrollLeft();a.stickySidebar.css({position:"fixed",width:n(a.stickySidebar)+"px",transform:"translateY("+y+"px)",left:a.sidebar.offset().left+parseInt(a.sidebar.css("padding-left"))-v+"px",top:"0px"})}else if("absolute"==d){var x={};"absolute"!=a.stickySidebar.css("position")&&(x.position="absolute",x.transform="translateY("+(r+y-a.sidebar.offset().top-a.stickySidebarPaddingTop-a.stickySidebarPaddingBottom)+"px)",x.top="0px"),x.width=n(a.stickySidebar)+"px",x.left="",a.stickySidebar.css(x)}else"static"==d&&e();"static"!=d&&1==a.options.updateSidebarHeight&&a.sidebar.css({"min-height":a.stickySidebar.outerHeight()+a.stickySidebar.offset().top-a.sidebar.offset().top+a.paddingBottom}),a.previousScrollTop=r}},a.onScroll(a),i(document).on("scroll."+a.options.namespace,function(i){return function(){i.onScroll(i)}}(a)),i(window).on("resize."+a.options.namespace,function(i){return function(){i.stickySidebar.css({position:"static"}),i.onScroll(i)}}(a)),"undefined"!=typeof ResizeSensor&&new ResizeSensor(a.stickySidebar[0],function(i){return function(){i.onScroll(i)}}(a))})}function n(i){var t;try{t=i[0].getBoundingClientRect().width}catch(i){}return"undefined"==typeof t&&(t=i.width()),t}var s={containerSelector:"",additionalMarginTop:0,additionalMarginBottom:0,updateSidebarHeight:!0,minWidth:0,disableOnResponsiveLayouts:!0,sidebarBehavior:"modern",defaultPosition:"relative",namespace:"TSS"};return t=i.extend(s,t),t.additionalMarginTop=parseInt(t.additionalMarginTop)||0,t.additionalMarginBottom=parseInt(t.additionalMarginBottom)||0,e(t,this),this}}(jQuery);
//# sourceMappingURL=maps/theia-sticky-sidebar.min.js.map

!function (t, e) {
    "object" == typeof exports && "object" == typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define([], e) : "object" == typeof exports ? exports.ClipboardJS = e() : t.ClipboardJS = e()
}(this, function () {
    return function (n) {
        function o(t) {
            if (r[t]) return r[t].exports;
            var e = r[t] = {i: t, l: !1, exports: {}};
            return n[t].call(e.exports, e, e.exports, o), e.l = !0, e.exports
        }

        var r = {};
        return o.m = n, o.c = r, o.i = function (t) {
            return t
        }, o.d = function (t, e, n) {
            o.o(t, e) || Object.defineProperty(t, e, {configurable: !1, enumerable: !0, get: n})
        }, o.n = function (t) {
            var e = t && t.__esModule ? function () {
                return t.default
            } : function () {
                return t
            };
            return o.d(e, "a", e), e
        }, o.o = function (t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        }, o.p = "", o(o.s = 3)
    }([function (t, e, n) {
        var o, r, i;
        r = [t, n(7)], void 0 !== (i = "function" == typeof(o = function (t, e) {
            "use strict";
            var n, o = (n = e) && n.__esModule ? n : {default: n},
                r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                    return typeof t
                } : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
                }, i = function () {
                    function o(t, e) {
                        for (var n = 0; n < e.length; n++) {
                            var o = e[n];
                            o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                        }
                    }

                    return function (t, e, n) {
                        return e && o(t.prototype, e), n && o(t, n), t
                    }
                }(), a = function () {
                    function e(t) {
                        (function (t, e) {
                            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
                        })(this, e), this.resolveOptions(t), this.initSelection()
                    }

                    return i(e, [{
                        key: "resolveOptions", value: function () {
                            var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {};
                            this.action = t.action, this.container = t.container, this.emitter = t.emitter, this.target = t.target, this.text = t.text, this.trigger = t.trigger, this.selectedText = ""
                        }
                    }, {
                        key: "initSelection", value: function () {
                            this.text ? this.selectFake() : this.target && this.selectTarget()
                        }
                    }, {
                        key: "selectFake", value: function () {
                            var t = this, e = "rtl" == document.documentElement.getAttribute("dir");
                            this.removeFake(), this.fakeHandlerCallback = function () {
                                return t.removeFake()
                            }, this.fakeHandler = this.container.addEventListener("click", this.fakeHandlerCallback) || !0, this.fakeElem = document.createElement("textarea"), this.fakeElem.style.fontSize = "12pt", this.fakeElem.style.border = "0", this.fakeElem.style.padding = "0", this.fakeElem.style.margin = "0", this.fakeElem.style.position = "absolute", this.fakeElem.style[e ? "right" : "left"] = "-9999px";
                            var n = window.pageYOffset || document.documentElement.scrollTop;
                            this.fakeElem.style.top = n + "px", this.fakeElem.setAttribute("readonly", ""), this.fakeElem.value = this.text, this.container.appendChild(this.fakeElem), this.selectedText = (0, o.default)(this.fakeElem), this.copyText()
                        }
                    }, {
                        key: "removeFake", value: function () {
                            this.fakeHandler && (this.container.removeEventListener("click", this.fakeHandlerCallback), this.fakeHandler = null, this.fakeHandlerCallback = null), this.fakeElem && (this.container.removeChild(this.fakeElem), this.fakeElem = null)
                        }
                    }, {
                        key: "selectTarget", value: function () {
                            this.selectedText = (0, o.default)(this.target), this.copyText()
                        }
                    }, {
                        key: "copyText", value: function () {
                            var e = void 0;
                            try {
                                e = document.execCommand(this.action)
                            } catch (t) {
                                e = !1
                            }
                            this.handleResult(e)
                        }
                    }, {
                        key: "handleResult", value: function (t) {
                            this.emitter.emit(t ? "success" : "error", {
                                action: this.action,
                                text: this.selectedText,
                                trigger: this.trigger,
                                clearSelection: this.clearSelection.bind(this)
                            })
                        }
                    }, {
                        key: "clearSelection", value: function () {
                            this.trigger && this.trigger.focus(), window.getSelection().removeAllRanges()
                        }
                    }, {
                        key: "destroy", value: function () {
                            this.removeFake()
                        }
                    }, {
                        key: "action", set: function () {
                            var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : "copy";
                            if (this._action = t, "copy" !== this._action && "cut" !== this._action) throw new Error('Invalid "action" value, use either "copy" or "cut"')
                        }, get: function () {
                            return this._action
                        }
                    }, {
                        key: "target", set: function (t) {
                            if (void 0 !== t) {
                                if (!t || "object" !== (void 0 === t ? "undefined" : r(t)) || 1 !== t.nodeType) throw new Error('Invalid "target" value, use a valid Element');
                                if ("copy" === this.action && t.hasAttribute("disabled")) throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');
                                if ("cut" === this.action && (t.hasAttribute("readonly") || t.hasAttribute("disabled"))) throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');
                                this._target = t
                            }
                        }, get: function () {
                            return this._target
                        }
                    }]), e
                }();
            t.exports = a
        }) ? o.apply(e, r) : o) && (t.exports = i)
    }, function (t, e, n) {
        var d = n(6), h = n(5);
        t.exports = function (t, e, n) {
            if (!t && !e && !n) throw new Error("Missing required arguments");
            if (!d.string(e)) throw new TypeError("Second argument must be a String");
            if (!d.fn(n)) throw new TypeError("Third argument must be a Function");
            if (d.node(t)) return r = e, i = n, (o = t).addEventListener(r, i), {
                destroy: function () {
                    o.removeEventListener(r, i)
                }
            };
            var o, r, i, a, c, u, l, s, f;
            if (d.nodeList(t)) return a = t, c = e, u = n, Array.prototype.forEach.call(a, function (t) {
                t.addEventListener(c, u)
            }), {
                destroy: function () {
                    Array.prototype.forEach.call(a, function (t) {
                        t.removeEventListener(c, u)
                    })
                }
            };
            if (d.string(t)) return l = t, s = e, f = n, h(document.body, l, s, f);
            throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList")
        }
    }, function (t, e) {
        function n() {
        }

        n.prototype = {
            on: function (t, e, n) {
                var o = this.e || (this.e = {});
                return (o[t] || (o[t] = [])).push({fn: e, ctx: n}), this
            }, once: function (t, e, n) {
                function o() {
                    r.off(t, o), e.apply(n, arguments)
                }

                var r = this;
                return o._ = e, this.on(t, o, n)
            }, emit: function (t) {
                for (var e = [].slice.call(arguments, 1), n = ((this.e || (this.e = {}))[t] || []).slice(), o = 0, r = n.length; o < r; o++) n[o].fn.apply(n[o].ctx, e);
                return this
            }, off: function (t, e) {
                var n = this.e || (this.e = {}), o = n[t], r = [];
                if (o && e) for (var i = 0, a = o.length; i < a; i++) o[i].fn !== e && o[i].fn._ !== e && r.push(o[i]);
                return r.length ? n[t] = r : delete n[t], this
            }
        }, t.exports = n
    }, function (t, e, n) {
        var o, r, i;
        r = [t, n(0), n(2), n(1)], void 0 !== (i = "function" == typeof(o = function (t, e, n, o) {
            "use strict";

            function r(t) {
                return t && t.__esModule ? t : {default: t}
            }

            function i(t, e) {
                var n = "data-clipboard-" + t;
                if (e.hasAttribute(n)) return e.getAttribute(n)
            }

            var a = r(e), c = r(n), u = r(o),
                l = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                    return typeof t
                } : function (t) {
                    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
                }, s = function () {
                    function o(t, e) {
                        for (var n = 0; n < e.length; n++) {
                            var o = e[n];
                            o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                        }
                    }

                    return function (t, e, n) {
                        return e && o(t.prototype, e), n && o(t, n), t
                    }
                }(), f = function (t) {
                    function o(t, e) {
                        !function (t, e) {
                            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
                        }(this, o);
                        var n = function (t, e) {
                            if (!t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                            return !e || "object" != typeof e && "function" != typeof e ? t : e
                        }(this, (o.__proto__ || Object.getPrototypeOf(o)).call(this));
                        return n.resolveOptions(e), n.listenClick(t), n
                    }

                    return function (t, e) {
                        if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function, not " + typeof e);
                        t.prototype = Object.create(e && e.prototype, {
                            constructor: {
                                value: t,
                                enumerable: !1,
                                writable: !0,
                                configurable: !0
                            }
                        }), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : t.__proto__ = e)
                    }(o, t), s(o, [{
                        key: "resolveOptions", value: function () {
                            var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {};
                            this.action = "function" == typeof t.action ? t.action : this.defaultAction, this.target = "function" == typeof t.target ? t.target : this.defaultTarget, this.text = "function" == typeof t.text ? t.text : this.defaultText, this.container = "object" === l(t.container) ? t.container : document.body
                        }
                    }, {
                        key: "listenClick", value: function (t) {
                            var e = this;
                            this.listener = (0, u.default)(t, "click", function (t) {
                                return e.onClick(t)
                            })
                        }
                    }, {
                        key: "onClick", value: function (t) {
                            var e = t.delegateTarget || t.currentTarget;
                            this.clipboardAction && (this.clipboardAction = null), this.clipboardAction = new a.default({
                                action: this.action(e),
                                target: this.target(e),
                                text: this.text(e),
                                container: this.container,
                                trigger: e,
                                emitter: this
                            })
                        }
                    }, {
                        key: "defaultAction", value: function (t) {
                            return i("action", t)
                        }
                    }, {
                        key: "defaultTarget", value: function (t) {
                            var e = i("target", t);
                            if (e) return document.querySelector(e)
                        }
                    }, {
                        key: "defaultText", value: function (t) {
                            return i("text", t)
                        }
                    }, {
                        key: "destroy", value: function () {
                            this.listener.destroy(), this.clipboardAction && (this.clipboardAction.destroy(), this.clipboardAction = null)
                        }
                    }], [{
                        key: "isSupported", value: function () {
                            var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : ["copy", "cut"],
                                e = "string" == typeof t ? [t] : t, n = !!document.queryCommandSupported;
                            return e.forEach(function (t) {
                                n = n && !!document.queryCommandSupported(t)
                            }), n
                        }
                    }]), o
                }(c.default);
            t.exports = f
        }) ? o.apply(e, r) : o) && (t.exports = i)
    }, function (t, e) {
        if ("undefined" != typeof Element && !Element.prototype.matches) {
            var n = Element.prototype;
            n.matches = n.matchesSelector || n.mozMatchesSelector || n.msMatchesSelector || n.oMatchesSelector || n.webkitMatchesSelector
        }
        t.exports = function (t, e) {
            for (; t && 9 !== t.nodeType;) {
                if ("function" == typeof t.matches && t.matches(e)) return t;
                t = t.parentNode
            }
        }
    }, function (t, e, n) {
        function i(t, e, n, o, r) {
            var i = function (e, n, t, o) {
                return function (t) {
                    t.delegateTarget = a(t.target, n), t.delegateTarget && o.call(e, t)
                }
            }.apply(this, arguments);
            return t.addEventListener(n, i, r), {
                destroy: function () {
                    t.removeEventListener(n, i, r)
                }
            }
        }

        var a = n(4);
        t.exports = function (t, e, n, o, r) {
            return "function" == typeof t.addEventListener ? i.apply(null, arguments) : "function" == typeof n ? i.bind(null, document).apply(null, arguments) : ("string" == typeof t && (t = document.querySelectorAll(t)), Array.prototype.map.call(t, function (t) {
                return i(t, e, n, o, r)
            }))
        }
    }, function (t, n) {
        n.node = function (t) {
            return void 0 !== t && t instanceof HTMLElement && 1 === t.nodeType
        }, n.nodeList = function (t) {
            var e = Object.prototype.toString.call(t);
            return void 0 !== t && ("[object NodeList]" === e || "[object HTMLCollection]" === e) && "length" in t && (0 === t.length || n.node(t[0]))
        }, n.string = function (t) {
            return "string" == typeof t || t instanceof String
        }, n.fn = function (t) {
            return "[object Function]" === Object.prototype.toString.call(t)
        }
    }, function (t, e) {
        t.exports = function (t) {
            var e;
            if ("SELECT" === t.nodeName) t.focus(), e = t.value; else if ("INPUT" === t.nodeName || "TEXTAREA" === t.nodeName) {
                var n = t.hasAttribute("readonly");
                n || t.setAttribute("readonly", ""), t.select(), t.setSelectionRange(0, t.value.length), n || t.removeAttribute("readonly"), e = t.value
            } else {
                t.hasAttribute("contenteditable") && t.focus();
                var o = window.getSelection(), r = document.createRange();
                r.selectNodeContents(t), o.removeAllRanges(), o.addRange(r), e = o.toString()
            }
            return e
        }
    }])
});
!function (e, i) {
    "function" == typeof define && define.amd ? define("jquery-bridget/jquery-bridget", ["jquery"], function (t) {
        return i(e, t)
    }) : "object" == typeof module && module.exports ? module.exports = i(e, require("jquery")) : e.jQueryBridget = i(e, e.jQuery)
}(window, function (t, e) {
    "use strict";

    function i(h, o, c) {
        (c = c || e || t.jQuery) && (o.prototype.option || (o.prototype.option = function (t) {
            c.isPlainObject(t) && (this.options = c.extend(!0, this.options, t))
        }), c.fn[h] = function (t) {
            if ("string" != typeof t) return n = t, this.each(function (t, e) {
                var i = c.data(e, h);
                i ? (i.option(n), i._init()) : (i = new o(e, n), c.data(e, h, i))
            }), this;
            var e, r, s, l, a, n, i = u.call(arguments, 1);
            return s = i, a = "$()." + h + '("' + (r = t) + '")', (e = this).each(function (t, e) {
                var i = c.data(e, h);
                if (i) {
                    var n = i[r];
                    if (n && "_" != r.charAt(0)) {
                        var o = n.apply(i, s);
                        l = void 0 === l ? o : l
                    } else d(a + " is not a valid method")
                } else d(h + " not initialized. Cannot call methods, i.e. " + a)
            }), void 0 !== l ? l : e
        }, n(c))
    }

    function n(t) {
        !t || t && t.bridget || (t.bridget = i)
    }

    var u = Array.prototype.slice, o = t.console, d = void 0 === o ? function () {
    } : function (t) {
        o.error(t)
    };
    return n(e || t.jQuery), i
}), function (t, e) {
    "function" == typeof define && define.amd ? define("ev-emitter/ev-emitter", e) : "object" == typeof module && module.exports ? module.exports = e() : t.EvEmitter = e()
}("undefined" != typeof window ? window : this, function () {
    function t() {
    }

    var e = t.prototype;
    return e.on = function (t, e) {
        if (t && e) {
            var i = this._events = this._events || {}, n = i[t] = i[t] || [];
            return -1 == n.indexOf(e) && n.push(e), this
        }
    }, e.once = function (t, e) {
        if (t && e) {
            this.on(t, e);
            var i = this._onceEvents = this._onceEvents || {};
            return (i[t] = i[t] || {})[e] = !0, this
        }
    }, e.off = function (t, e) {
        var i = this._events && this._events[t];
        if (i && i.length) {
            var n = i.indexOf(e);
            return -1 != n && i.splice(n, 1), this
        }
    }, e.emitEvent = function (t, e) {
        var i = this._events && this._events[t];
        if (i && i.length) {
            i = i.slice(0), e = e || [];
            for (var n = this._onceEvents && this._onceEvents[t], o = 0; o < i.length; o++) {
                var r = i[o];
                n && n[r] && (this.off(t, r), delete n[r]), r.apply(this, e)
            }
            return this
        }
    }, e.allOff = function () {
        delete this._events, delete this._onceEvents
    }, t
}), function (t, e) {
    "use strict";
    "function" == typeof define && define.amd ? define("desandro-matches-selector/matches-selector", e) : "object" == typeof module && module.exports ? module.exports = e() : t.matchesSelector = e()
}(window, function () {
    "use strict";
    var i = function () {
        var t = window.Element.prototype;
        if (t.matches) return "matches";
        if (t.matchesSelector) return "matchesSelector";
        for (var e = ["webkit", "moz", "ms", "o"], i = 0; i < e.length; i++) {
            var n = e[i] + "MatchesSelector";
            if (t[n]) return n
        }
    }();
    return function (t, e) {
        return t[i](e)
    }
}), function (e, i) {
    "function" == typeof define && define.amd ? define("fizzy-ui-utils/utils", ["desandro-matches-selector/matches-selector"], function (t) {
        return i(e, t)
    }) : "object" == typeof module && module.exports ? module.exports = i(e, require("desandro-matches-selector")) : e.fizzyUIUtils = i(e, e.matchesSelector)
}(window, function (h, r) {
    var c = {
        extend: function (t, e) {
            for (var i in e) t[i] = e[i];
            return t
        }, modulo: function (t, e) {
            return (t % e + e) % e
        }, makeArray: function (t) {
            var e = [];
            if (Array.isArray(t)) e = t; else if (t && "object" == typeof t && "number" == typeof t.length) for (var i = 0; i < t.length; i++) e.push(t[i]); else e.push(t);
            return e
        }, removeFrom: function (t, e) {
            var i = t.indexOf(e);
            -1 != i && t.splice(i, 1)
        }, getParent: function (t, e) {
            for (; t.parentNode && t != document.body;) if (t = t.parentNode, r(t, e)) return t
        }, getQueryElement: function (t) {
            return "string" == typeof t ? document.querySelector(t) : t
        }, handleEvent: function (t) {
            var e = "on" + t.type;
            this[e] && this[e](t)
        }, filterFindElements: function (t, n) {
            t = c.makeArray(t);
            var o = [];
            return t.forEach(function (t) {
                if (t instanceof HTMLElement) {
                    if (!n) return void o.push(t);
                    r(t, n) && o.push(t);
                    for (var e = t.querySelectorAll(n), i = 0; i < e.length; i++) o.push(e[i])
                }
            }), o
        }, debounceMethod: function (t, e, n) {
            var o = t.prototype[e], r = e + "Timeout";
            t.prototype[e] = function () {
                var t = this[r];
                t && clearTimeout(t);
                var e = arguments, i = this;
                this[r] = setTimeout(function () {
                    o.apply(i, e), delete i[r]
                }, n || 100)
            }
        }, docReady: function (t) {
            var e = document.readyState;
            "complete" == e || "interactive" == e ? setTimeout(t) : document.addEventListener("DOMContentLoaded", t)
        }, toDashed: function (t) {
            return t.replace(/(.)([A-Z])/g, function (t, e, i) {
                return e + "-" + i
            }).toLowerCase()
        }
    }, u = h.console;
    return c.htmlInit = function (l, a) {
        c.docReady(function () {
            var t = c.toDashed(a), o = "data-" + t, e = document.querySelectorAll("[" + o + "]"),
                i = document.querySelectorAll(".js-" + t), n = c.makeArray(e).concat(c.makeArray(i)),
                r = o + "-options", s = h.jQuery;
            n.forEach(function (e) {
                var t, i = e.getAttribute(o) || e.getAttribute(r);
                try {
                    t = i && JSON.parse(i)
                } catch (t) {
                    return void(u && u.error("Error parsing " + o + " on " + e.className + ": " + t))
                }
                var n = new l(e, t);
                s && s.data(e, a, n)
            })
        })
    }, c
}), function (i, n) {
    "function" == typeof define && define.amd ? define("infinite-scroll/js/core", ["ev-emitter/ev-emitter", "fizzy-ui-utils/utils"], function (t, e) {
        return n(i, t, e)
    }) : "object" == typeof module && module.exports ? module.exports = n(i, require("ev-emitter"), require("fizzy-ui-utils")) : i.InfiniteScroll = n(i, i.EvEmitter, i.fizzyUIUtils)
}(window, function (e, t, o) {
    function r(t, e) {
        var i = o.getQueryElement(t);
        if (i) {
            if ((t = i).infiniteScrollGUID) {
                var n = l[t.infiniteScrollGUID];
                return n.option(e), n
            }
            this.element = t, this.options = o.extend({}, r.defaults), this.option(e), s && (this.$element = s(this.element)), this.create()
        } else console.error("Bad element for InfiniteScroll: " + (i || t))
    }

    var s = e.jQuery, l = {};
    r.defaults = {}, r.create = {}, r.destroy = {};
    var i = r.prototype;
    o.extend(i, t.prototype);
    var n = 0;
    i.create = function () {
        var t = this.guid = ++n;
        if (this.element.infiniteScrollGUID = t, (l[t] = this).pageIndex = 1, this.loadCount = 0, this.updateGetPath(), this.getPath) for (var e in this.updateGetAbsolutePath(), this.log("initialized", [this.element.className]), this.callOnInit(), r.create) r.create[e].call(this); else console.error("Disabling InfiniteScroll")
    }, i.option = function (t) {
        o.extend(this.options, t)
    }, i.callOnInit = function () {
        var t = this.options.onInit;
        t && t.call(this, this)
    }, i.dispatchEvent = function (t, e, i) {
        this.log(t, i);
        var n = e ? [e].concat(i) : i;
        if (this.emitEvent(t, n), s && this.$element) {
            var o = t += ".infiniteScroll";
            if (e) {
                var r = s.Event(e);
                r.type = t, o = r
            }
            this.$element.trigger(o, i)
        }
    };
    var a = {
        initialized: function (t) {
            return "on " + t
        }, request: function (t) {
            return "URL: " + t
        }, load: function (t, e) {
            return (t.title || "") + ". URL: " + e
        }, error: function (t, e) {
            return t + ". URL: " + e
        }, append: function (t, e, i) {
            return i.length + " items. URL: " + e
        }, last: function (t, e) {
            return "URL: " + e
        }, history: function (t, e) {
            return "URL: " + e
        }, pageIndex: function (t, e) {
            return "current page determined to be: " + t + " from " + e
        }
    };
    i.log = function (t, e) {
        if (this.options.debug) {
            var i = "[InfiniteScroll] " + t, n = a[t];
            n && (i += ". " + n.apply(this, e)), console.log(i)
        }
    }, i.updateMeasurements = function () {
        this.windowHeight = e.innerHeight;
        var t = this.element.getBoundingClientRect();
        this.top = t.top + e.pageYOffset
    }, i.updateScroller = function () {
        var t = this.options.elementScroll;
        if (t) {
            if (this.scroller = !0 === t ? this.element : o.getQueryElement(t), !this.scroller) throw"Unable to find elementScroll: " + t
        } else this.scroller = e
    }, i.updateGetPath = function () {
        var t = this.options.path;
        if (t) {
            var e = typeof t;
            if ("function" != e) return "string" == e && t.match("{{#}}") ? void this.updateGetPathTemplate(t) : void this.updateGetPathSelector(t);
            this.getPath = t
        } else console.error("InfiniteScroll path option required. Set as: " + t)
    }, i.updateGetPathTemplate = function (e) {
        this.getPath = function () {
            var t = this.pageIndex + 1;
            return e.replace("{{#}}", t)
        }.bind(this);
        var t = e.replace("{{#}}", "(\\d\\d?\\d?)"), i = new RegExp(t), n = location.href.match(i);
        n && (this.pageIndex = parseInt(n[1], 10), this.log("pageIndex", this.pageIndex, "template string"))
    };
    var h = [/^(.*?\/?page\/?)(\d\d?\d?)(.*?$)/, /^(.*?\/?\?page=)(\d\d?\d?)(.*?$)/, /(.*?)(\d\d?\d?)(?!.*\d)(.*?$)/];
    return i.updateGetPathSelector = function (t) {
        var e = document.querySelector(t);
        if (e) {
            for (var i, n, o = e.getAttribute("href"), r = 0; o && r < h.length; r++) {
                n = h[r];
                var s = o.match(n);
                if (s) {
                    i = s.slice(1);
                    break
                }
            }
            return i ? (this.isPathSelector = !0, this.getPath = function () {
                var t = this.pageIndex + 1;
                return i[0] + t + i[2]
            }.bind(this), this.pageIndex = parseInt(i[1], 10) - 1, void this.log("pageIndex", [this.pageIndex, "next link"])) : void console.error("InfiniteScroll unable to parse next link href: " + o)
        }
        console.error("Bad InfiniteScroll path option. Next link not found: " + t)
    }, i.updateGetAbsolutePath = function () {
        var t = this.getPath();
        if (t.match(/^http/) || t.match(/^\//)) this.getAbsolutePath = this.getPath; else {
            var e = location.pathname, i = e.substring(0, e.lastIndexOf("/"));
            this.getAbsolutePath = function () {
                return i + "/" + this.getPath()
            }
        }
    }, r.create.hideNav = function () {
        var t = o.getQueryElement(this.options.hideNav);
        t && (t.style.display = "none", this.nav = t)
    }, r.destroy.hideNav = function () {
        this.nav && (this.nav.style.display = "")
    }, i.destroy = function () {
        for (var t in this.allOff(), r.destroy) r.destroy[t].call(this);
        delete this.element.infiniteScrollGUID, delete l[this.guid]
    }, r.throttle = function (n, o) {
        var r, s;
        return o = o || 200, function () {
            var t = +new Date, e = arguments, i = function () {
                r = t, n.apply(this, e)
            }.bind(this);
            r && t < r + o ? (clearTimeout(s), s = setTimeout(i, o)) : i()
        }
    }, r.data = function (t) {
        var e = (t = o.getQueryElement(t)) && t.infiniteScrollGUID;
        return e && l[e]
    }, r.setJQuery = function (t) {
        s = t
    }, o.htmlInit(r, "infinite-scroll"), s && s.bridget && s.bridget("infiniteScroll", r), r
}), function (e, i) {
    "function" == typeof define && define.amd ? define("infinite-scroll/js/page-load", ["./core"], function (t) {
        return i(e, t)
    }) : "object" == typeof module && module.exports ? module.exports = i(e, require("./core")) : i(e, e.InfiniteScroll)
}(window, function (n, o) {
    function s(t) {
        for (var e = document.createDocumentFragment(), i = 0; t && i < t.length; i++) e.appendChild(t[i]);
        return e
    }

    function r(t, e) {
        for (var i = t.attributes, n = 0; n < i.length; n++) {
            var o = i[n];
            e.setAttribute(o.name, o.value)
        }
    }

    var t = o.prototype;
    return o.defaults.loadOnScroll = !0, o.defaults.checkLastPage = !0, o.defaults.responseType = "document", o.create.pageLoad = function () {
        this.canLoad = !0, this.on("scrollThreshold", this.onScrollThresholdLoad), this.on("load", this.checkLastPage), this.options.outlayer && this.on("append", this.onAppendOutlayer)
    }, t.onScrollThresholdLoad = function () {
        this.options.loadOnScroll && this.loadNextPage()
    }, t.loadNextPage = function () {
        if (!this.isLoading && this.canLoad) {
            var e = this.getAbsolutePath();
            this.isLoading = !0;
            var t = function (t) {
                this.onPageLoad(t, e)
            }.bind(this), i = function (t) {
                this.onPageError(t, e)
            }.bind(this);
            n = e, o = this.options.responseType, r = t, s = i, (l = new XMLHttpRequest).open("GET", n, !0), l.responseType = o || "", l.setRequestHeader("X-Requested-With", "XMLHttpRequest"), l.onload = function () {
                if (200 == l.status) r(l.response); else {
                    var t = new Error(l.statusText);
                    s(t)
                }
            }, l.onerror = function () {
                var t = new Error("Network error requesting " + n);
                s(t)
            }, l.send(), this.dispatchEvent("request", null, [e])
        }
        var n, o, r, s, l
    }, t.onPageLoad = function (t, e) {
        return this.options.append || (this.isLoading = !1), this.pageIndex++, this.loadCount++, this.dispatchEvent("load", null, [t, e]), this.appendNextPage(t, e), t
    }, t.appendNextPage = function (t, e) {
        var i = this.options.append;
        if ("document" == this.options.responseType && i) {
            var n = t.querySelectorAll(i), o = s(n), r = function () {
                this.appendItems(n, o), this.isLoading = !1, this.dispatchEvent("append", null, [t, e, n])
            }.bind(this);
            this.options.outlayer ? this.appendOutlayerItems(o, r) : r()
        }
    }, t.appendItems = function (t, e) {
        t && t.length && (function (t) {
            for (var e = t.querySelectorAll("script"), i = 0; i < e.length; i++) {
                var n = e[i], o = document.createElement("script");
                r(n, o), n.parentNode.replaceChild(o, n)
            }
        }(e = e || s(t)), this.element.appendChild(e))
    }, t.appendOutlayerItems = function (t, e) {
        var i = o.imagesLoaded || n.imagesLoaded;
        return i ? void i(t, e) : (console.error("[InfiniteScroll] imagesLoaded required for outlayer option"), void(this.isLoading = !1))
    }, t.onAppendOutlayer = function (t, e, i) {
        this.options.outlayer.appended(i)
    }, t.checkLastPage = function (t, e) {
        var i = this.options.checkLastPage;
        if (i) {
            var n, o = this.options.path;
            if ("function" == typeof o) if (!this.getPath()) return void this.lastPageReached(t, e);
            if ("string" == typeof i ? n = i : this.isPathSelector && (n = o), n && t.querySelector) t.querySelector(n) || this.lastPageReached(t, e)
        }
    }, t.lastPageReached = function (t, e) {
        this.canLoad = !1, this.dispatchEvent("last", null, [t, e])
    }, t.onPageError = function (t, e) {
        return this.isLoading = !1, this.canLoad = !1, this.dispatchEvent("error", null, [t, e]), t
    }, o.create.prefill = function () {
        if (this.options.prefill) {
            var t = this.options.append;
            if (!t) return void console.error("append option required for prefill. Set as :" + t);
            this.updateMeasurements(), this.updateScroller(), this.isPrefilling = !0, this.on("append", this.prefill), this.once("error", this.stopPrefill), this.once("last", this.stopPrefill), this.prefill()
        }
    }, t.prefill = function () {
        var t = this.getPrefillDistance();
        this.isPrefilling = 0 <= t, this.isPrefilling ? (this.log("prefill"), this.loadNextPage()) : this.stopPrefill()
    }, t.getPrefillDistance = function () {
        return this.options.elementScroll ? this.scroller.clientHeight - this.scroller.scrollHeight : this.windowHeight - this.element.clientHeight
    }, t.stopPrefill = function () {
        console.log("stopping prefill"), this.off("append", this.prefill)
    }, o
}), function (i, n) {
    "function" == typeof define && define.amd ? define("infinite-scroll/js/scroll-watch", ["./core", "fizzy-ui-utils/utils"], function (t, e) {
        return n(i, t, e)
    }) : "object" == typeof module && module.exports ? module.exports = n(i, require("./core"), require("fizzy-ui-utils")) : n(i, i.InfiniteScroll, i.fizzyUIUtils)
}(window, function (i, t, e) {
    var n = t.prototype;
    return t.defaults.scrollThreshold = 400, t.create.scrollWatch = function () {
        this.pageScrollHandler = this.onPageScroll.bind(this), this.resizeHandler = this.onResize.bind(this);
        var t = this.options.scrollThreshold;
        (t || 0 === t) && this.enableScrollWatch()
    }, t.destroy.scrollWatch = function () {
        this.disableScrollWatch()
    }, n.enableScrollWatch = function () {
        this.isScrollWatching || (this.isScrollWatching = !0, this.updateMeasurements(), this.updateScroller(), this.on("last", this.disableScrollWatch), this.bindScrollWatchEvents(!0))
    }, n.disableScrollWatch = function () {
        this.isScrollWatching && (this.bindScrollWatchEvents(!1), delete this.isScrollWatching)
    }, n.bindScrollWatchEvents = function (t) {
        var e = t ? "addEventListener" : "removeEventListener";
        this.scroller[e]("scroll", this.pageScrollHandler), i[e]("resize", this.resizeHandler)
    }, n.onPageScroll = t.throttle(function () {
        this.getBottomDistance() <= this.options.scrollThreshold && this.dispatchEvent("scrollThreshold")
    }), n.getBottomDistance = function () {
        return this.options.elementScroll ? this.getElementBottomDistance() : this.getWindowBottomDistance()
    }, n.getWindowBottomDistance = function () {
        return this.top + this.element.clientHeight - (i.pageYOffset + this.windowHeight)
    }, n.getElementBottomDistance = function () {
        return this.scroller.scrollHeight - (this.scroller.scrollTop + this.scroller.clientHeight)
    }, n.onResize = function () {
        this.updateMeasurements()
    }, e.debounceMethod(t, "onResize", 150), t
}), function (i, n) {
    "function" == typeof define && define.amd ? define("infinite-scroll/js/history", ["./core", "fizzy-ui-utils/utils"], function (t, e) {
        return n(i, t, e)
    }) : "object" == typeof module && module.exports ? module.exports = n(i, require("./core"), require("fizzy-ui-utils")) : n(i, i.InfiniteScroll, i.fizzyUIUtils)
}(window, function (n, t, e) {
    var i = t.prototype;
    t.defaults.history = "replace";
    var r = document.createElement("a");
    return t.create.history = function () {
        if (this.options.history) return r.href = this.getAbsolutePath(), (r.origin || r.protocol + "//" + r.host) == location.origin ? void(this.options.append ? this.createHistoryAppend() : this.createHistoryPageLoad()) : void console.error("[InfiniteScroll] cannot set history with different origin: " + r.origin + " on " + location.origin + " . History behavior disabled.")
    }, i.createHistoryAppend = function () {
        this.updateMeasurements(), this.updateScroller(), this.scrollPages = [{
            top: 0,
            path: location.href,
            title: document.title
        }], this.scrollPageIndex = 0, this.scrollHistoryHandler = this.onScrollHistory.bind(this), this.unloadHandler = this.onUnload.bind(this), this.scroller.addEventListener("scroll", this.scrollHistoryHandler), this.on("append", this.onAppendHistory), this.bindHistoryAppendEvents(!0)
    }, i.bindHistoryAppendEvents = function (t) {
        var e = t ? "addEventListener" : "removeEventListener";
        this.scroller[e]("scroll", this.scrollHistoryHandler), n[e]("unload", this.unloadHandler)
    }, i.createHistoryPageLoad = function () {
        this.on("load", this.onPageLoadHistory)
    }, t.destroy.history = i.destroyHistory = function () {
        this.options.history && this.options.append && this.bindHistoryAppendEvents(!1)
    }, i.onAppendHistory = function (t, e, i) {
        var n = i[0], o = this.getElementScrollY(n);
        r.href = e, this.scrollPages.push({top: o, path: r.href, title: t.title})
    }, i.getElementScrollY = function (t) {
        return this.options.elementScroll ? this.getElementElementScrollY(t) : this.getElementWindowScrollY(t)
    }, i.getElementWindowScrollY = function (t) {
        return t.getBoundingClientRect().top + n.pageYOffset
    }, i.getElementElementScrollY = function (t) {
        return t.offsetTop - this.top
    }, i.onScrollHistory = function () {
        for (var t, e, i = this.getScrollViewY(), n = 0; n < this.scrollPages.length; n++) {
            var o = this.scrollPages[n];
            if (o.top >= i) break;
            t = n, e = o
        }
        t != this.scrollPageIndex && (this.scrollPageIndex = t, this.setHistory(e.title, e.path))
    }, e.debounceMethod(t, "onScrollHistory", 150), i.getScrollViewY = function () {
        return this.options.elementScroll ? this.scroller.scrollTop + this.scroller.clientHeight / 2 : n.pageYOffset + this.windowHeight / 2
    }, i.setHistory = function (t, e) {
        var i = this.options.history;
        i && history[i + "State"] && (history[i + "State"](null, t, e), this.options.historyTitle && (document.title = t), this.dispatchEvent("history", null, [t, e]))
    }, i.onUnload = function () {
        var t = this.scrollPageIndex;
        if (0 !== t) {
            var e = this.scrollPages[t], i = n.pageYOffset - e.top + this.top;
            this.destroyHistory(), scrollTo(0, i)
        }
    }, i.onPageLoadHistory = function (t, e) {
        this.setHistory(t.title, e)
    }, t
}), function (i, n) {
    "function" == typeof define && define.amd ? define("infinite-scroll/js/button", ["./core", "fizzy-ui-utils/utils"], function (t, e) {
        return n(i, t, e)
    }) : "object" == typeof module && module.exports ? module.exports = n(i, require("./core"), require("fizzy-ui-utils")) : n(i, i.InfiniteScroll, i.fizzyUIUtils)
}(window, function (t, e, i) {
    function n(t, e) {
        this.element = t, this.infScroll = e, this.clickHandler = this.onClick.bind(this), this.element.addEventListener("click", this.clickHandler), e.on("request", this.disable.bind(this)), e.on("load", this.enable.bind(this)), e.on("error", this.hide.bind(this)), e.on("last", this.hide.bind(this))
    }

    return e.create.button = function () {
        var t = i.getQueryElement(this.options.button);
        t && (this.button = new n(t, this))
    }, e.destroy.button = function () {
        this.button && this.button.destroy()
    }, n.prototype.onClick = function (t) {
        t.preventDefault(), this.infScroll.loadNextPage()
    }, n.prototype.enable = function () {
        this.element.removeAttribute("disabled")
    }, n.prototype.disable = function () {
        this.element.disabled = "disabled"
    }, n.prototype.hide = function () {
        this.element.style.display = "none"
    }, n.prototype.destroy = function () {
        this.element.removeEventListener("click", this.clickHandler)
    }, e.Button = n, e
}), function (i, n) {
    "function" == typeof define && define.amd ? define("infinite-scroll/js/status", ["./core", "fizzy-ui-utils/utils"], function (t, e) {
        return n(i, t, e)
    }) : "object" == typeof module && module.exports ? module.exports = n(i, require("./core"), require("fizzy-ui-utils")) : n(i, i.InfiniteScroll, i.fizzyUIUtils)
}(window, function (t, e, i) {
    function n(t) {
        r(t, "none")
    }

    function o(t) {
        r(t, "block")
    }

    function r(t, e) {
        t && (t.style.display = e)
    }

    var s = e.prototype;
    return e.create.status = function () {
        var t = i.getQueryElement(this.options.status);
        t && (this.statusElement = t, this.statusEventElements = {
            request: t.querySelector(".infinite-scroll-request"),
            error: t.querySelector(".infinite-scroll-error"),
            last: t.querySelector(".infinite-scroll-last")
        }, this.on("request", this.showRequestStatus), this.on("error", this.showErrorStatus), this.on("last", this.showLastStatus), this.bindHideStatus("on"))
    }, s.bindHideStatus = function (t) {
        var e = this.options.append ? "append" : "load";
        this[t](e, this.hideAllStatus)
    }, s.showRequestStatus = function () {
        this.showStatus("request")
    }, s.showErrorStatus = function () {
        this.showStatus("error")
    }, s.showLastStatus = function () {
        this.showStatus("last"), this.bindHideStatus("off")
    }, s.showStatus = function (t) {
        o(this.statusElement), this.hideStatusEventElements(), o(this.statusEventElements[t])
    }, s.hideAllStatus = function () {
        n(this.statusElement), this.hideStatusEventElements()
    }, s.hideStatusEventElements = function () {
        for (var t in this.statusEventElements) {
            n(this.statusEventElements[t])
        }
    }, e
}), function (t, e) {
    "function" == typeof define && define.amd ? define(["infinite-scroll/js/core", "infinite-scroll/js/page-load", "infinite-scroll/js/scroll-watch", "infinite-scroll/js/history", "infinite-scroll/js/button", "infinite-scroll/js/status"], e) : "object" == typeof module && module.exports && (module.exports = e(require("./core"), require("./page-load"), require("./scroll-watch"), require("./history"), require("./button"), require("./status")))
}(window, function (t) {
    return t
}), function (e, i) {
    "use strict";
    "function" == typeof define && define.amd ? define("imagesloaded/imagesloaded", ["ev-emitter/ev-emitter"], function (t) {
        return i(e, t)
    }) : "object" == typeof module && module.exports ? module.exports = i(e, require("ev-emitter")) : e.imagesLoaded = i(e, e.EvEmitter)
}("undefined" != typeof window ? window : this, function (e, t) {
    function r(t, e) {
        for (var i in e) t[i] = e[i];
        return t
    }

    function s(t, e, i) {
        if (!(this instanceof s)) return new s(t, e, i);
        var n, o = t;
        return "string" == typeof t && (o = document.querySelectorAll(t)), o ? (this.elements = (n = o, Array.isArray(n) ? n : "object" == typeof n && "number" == typeof n.length ? h.call(n) : [n]), this.options = r({}, this.options), "function" == typeof e ? i = e : r(this.options, e), i && this.on("always", i), this.getImages(), l && (this.jqDeferred = new l.Deferred), void setTimeout(this.check.bind(this))) : void a.error("Bad element for imagesLoaded " + (o || t))
    }

    function i(t) {
        this.img = t
    }

    function n(t, e) {
        this.url = t, this.element = e, this.img = new Image
    }

    var l = e.jQuery, a = e.console, h = Array.prototype.slice;
    (s.prototype = Object.create(t.prototype)).options = {}, s.prototype.getImages = function () {
        this.images = [], this.elements.forEach(this.addElementImages, this)
    }, s.prototype.addElementImages = function (t) {
        "IMG" == t.nodeName && this.addImage(t), !0 === this.options.background && this.addElementBackgroundImages(t);
        var e = t.nodeType;
        if (e && c[e]) {
            for (var i = t.querySelectorAll("img"), n = 0; n < i.length; n++) {
                var o = i[n];
                this.addImage(o)
            }
            if ("string" == typeof this.options.background) {
                var r = t.querySelectorAll(this.options.background);
                for (n = 0; n < r.length; n++) {
                    var s = r[n];
                    this.addElementBackgroundImages(s)
                }
            }
        }
    };
    var c = {1: !0, 9: !0, 11: !0};
    return s.prototype.addElementBackgroundImages = function (t) {
        var e = getComputedStyle(t);
        if (e) for (var i = /url\((['"])?(.*?)\1\)/gi, n = i.exec(e.backgroundImage); null !== n;) {
            var o = n && n[2];
            o && this.addBackground(o, t), n = i.exec(e.backgroundImage)
        }
    }, s.prototype.addImage = function (t) {
        var e = new i(t);
        this.images.push(e)
    }, s.prototype.addBackground = function (t, e) {
        var i = new n(t, e);
        this.images.push(i)
    }, s.prototype.check = function () {
        function e(t, e, i) {
            setTimeout(function () {
                n.progress(t, e, i)
            })
        }

        var n = this;
        return this.progressedCount = 0, this.hasAnyBroken = !1, this.images.length ? void this.images.forEach(function (t) {
            t.once("progress", e), t.check()
        }) : void this.complete()
    }, s.prototype.progress = function (t, e, i) {
        this.progressedCount++, this.hasAnyBroken = this.hasAnyBroken || !t.isLoaded, this.emitEvent("progress", [this, t, e]), this.jqDeferred && this.jqDeferred.notify && this.jqDeferred.notify(this, t), this.progressedCount == this.images.length && this.complete(), this.options.debug && a && a.log("progress: " + i, t, e)
    }, s.prototype.complete = function () {
        var t = this.hasAnyBroken ? "fail" : "done";
        if (this.isComplete = !0, this.emitEvent(t, [this]), this.emitEvent("always", [this]), this.jqDeferred) {
            var e = this.hasAnyBroken ? "reject" : "resolve";
            this.jqDeferred[e](this)
        }
    }, (i.prototype = Object.create(t.prototype)).check = function () {
        return this.getIsImageComplete() ? void this.confirm(0 !== this.img.naturalWidth, "naturalWidth") : (this.proxyImage = new Image, this.proxyImage.addEventListener("load", this), this.proxyImage.addEventListener("error", this), this.img.addEventListener("load", this), this.img.addEventListener("error", this), void(this.proxyImage.src = this.img.src))
    }, i.prototype.getIsImageComplete = function () {
        return this.img.complete && this.img.naturalWidth
    }, i.prototype.confirm = function (t, e) {
        this.isLoaded = t, this.emitEvent("progress", [this, this.img, e])
    }, i.prototype.handleEvent = function (t) {
        var e = "on" + t.type;
        this[e] && this[e](t)
    }, i.prototype.onload = function () {
        this.confirm(!0, "onload"), this.unbindEvents()
    }, i.prototype.onerror = function () {
        this.confirm(!1, "onerror"), this.unbindEvents()
    }, i.prototype.unbindEvents = function () {
        this.proxyImage.removeEventListener("load", this), this.proxyImage.removeEventListener("error", this), this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, (n.prototype = Object.create(i.prototype)).check = function () {
        this.img.addEventListener("load", this), this.img.addEventListener("error", this), this.img.src = this.url, this.getIsImageComplete() && (this.confirm(0 !== this.img.naturalWidth, "naturalWidth"), this.unbindEvents())
    }, n.prototype.unbindEvents = function () {
        this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, n.prototype.confirm = function (t, e) {
        this.isLoaded = t, this.emitEvent("progress", [this, this.element, e])
    }, s.makeJQueryPlugin = function (t) {
        (t = t || e.jQuery) && ((l = t).fn.imagesLoaded = function (t, e) {
            return new s(this, t, e).jqDeferred.promise(l(this))
        })
    }, s.makeJQueryPlugin(), s
});
!function (o) {
    var n = {};

    function i(e) {
        if (n[e]) return n[e].exports;
        var t = n[e] = {i: e, l: !1, exports: {}};
        return o[e].call(t.exports, t, t.exports, i), t.l = !0, t.exports
    }

    i.m = o, i.c = n, i.d = function (e, t, o) {
        i.o(e, t) || Object.defineProperty(e, t, {configurable: !1, enumerable: !0, get: o})
    }, i.n = function (e) {
        var t = e && e.__esModule ? function () {
            return e.default
        } : function () {
            return e
        };
        return i.d(t, "a", t), t
    }, i.o = function (e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }, i.p = "", i(i.s = 11)
}([function (o, e, t) {
    "use strict";
    (function (e) {
        var t;
        t = "undefined" != typeof window ? window : void 0 !== e ? e : "undefined" != typeof self ? self : {}, o.exports = t
    }).call(e, t(2))
}, function (e, t, o) {
    "use strict";
    e.exports = function (e) {
        "complete" === document.readyState || "interactive" === document.readyState ? e.call() : document.attachEvent ? document.attachEvent("onreadystatechange", function () {
            "interactive" === document.readyState && e.call()
        }) : document.addEventListener && document.addEventListener("DOMContentLoaded", e)
    }
}, function (e, t, o) {
    "use strict";
    var n, i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
        return typeof e
    } : function (e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    };
    n = function () {
        return this
    }();
    try {
        n = n || Function("return this")() || (0, eval)("this")
    } catch (e) {
        "object" === ("undefined" == typeof window ? "undefined" : i(window)) && (n = window)
    }
    e.exports = n
}, , , , , , , , , function (e, t, o) {
    e.exports = o(12)
}, function (e, t, o) {
    "use strict";
    var n = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
        return typeof e
    } : function (e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    }, i = l(o(1)), a = o(0), r = l(o(13));

    function l(e) {
        return e && e.__esModule ? e : {default: e}
    }

    var s = a.window.jarallax;
    if (a.window.jarallax = r.default, a.window.jarallax.noConflict = function () {
        return a.window.jarallax = s, this
    }, void 0 !== a.jQuery) {
        var c = function () {
            var e = arguments || [];
            Array.prototype.unshift.call(e, this);
            var t = r.default.apply(a.window, e);
            return "object" !== (void 0 === t ? "undefined" : n(t)) ? t : this
        };
        c.constructor = r.default.constructor;
        var u = a.jQuery.fn.jarallax;
        a.jQuery.fn.jarallax = c, a.jQuery.fn.jarallax.noConflict = function () {
            return a.jQuery.fn.jarallax = u, this
        }
    }
    (0, i.default)(function () {
        (0, r.default)(document.querySelectorAll("[data-jarallax]"))
    })
}, function (e, w, $) {
    "use strict";
    (function (e) {
        Object.defineProperty(w, "__esModule", {value: !0});
        var t = function () {
            function n(e, t) {
                for (var o = 0; o < t.length; o++) {
                    var n = t[o];
                    n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(e, n.key, n)
                }
            }

            return function (e, t, o) {
                return t && n(e.prototype, t), o && n(e, o), e
            }
        }(), d = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
            return typeof e
        } : function (e) {
            return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }, o = a($(1)), n = a($(14)), i = $(0);

        function a(e) {
            return e && e.__esModule ? e : {default: e}
        }

        var r = function () {
            for (var e = "transform WebkitTransform MozTransform".split(" "), t = document.createElement("div"), o = 0; o < e.length; o++) if (t && void 0 !== t.style[e[o]]) return e[o];
            return !1
        }(), b = void 0, v = void 0, l = void 0, s = !1, c = !1;

        function u(e) {
            b = i.window.innerWidth || document.documentElement.clientWidth, v = i.window.innerHeight || document.documentElement.clientHeight, "object" !== (void 0 === e ? "undefined" : d(e)) || "load" !== e.type && "dom-loaded" !== e.type || (s = !0)
        }

        u(), i.window.addEventListener("resize", u), i.window.addEventListener("orientationchange", u), i.window.addEventListener("load", u), (0, o.default)(function () {
            u({type: "dom-loaded"})
        });
        var p = [], m = !1;

        function f() {
            if (p.length) {
                l = void 0 !== i.window.pageYOffset ? i.window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
                var t = s || !m || m.width !== b || m.height !== v, o = c || t || !m || m.y !== l;
                c = s = !1, (t || o) && (p.forEach(function (e) {
                    t && e.onResize(), o && e.onScroll()
                }), m = {width: b, height: v, y: l}), (0, n.default)(f)
            }
        }

        var y = !!e.ResizeObserver && new e.ResizeObserver(function (e) {
            e && e.length && (0, n.default)(function () {
                e.forEach(function (e) {
                    e.target && e.target.jarallax && (s || e.target.jarallax.onResize(), c = !0)
                })
            })
        }), g = 0, h = function () {
            function u(e, t) {
                !function (e, t) {
                    if (!(e instanceof u)) throw new TypeError("Cannot call a class as a function")
                }(this);
                var o = this;
                o.instanceID = g++, o.$item = e, o.defaults = {
                    type: "scroll",
                    speed: .5,
                    imgSrc: null,
                    imgElement: ".jarallax-img",
                    imgSize: "cover",
                    imgPosition: "50% 50%",
                    imgRepeat: "no-repeat",
                    keepImg: !1,
                    elementInViewport: null,
                    zIndex: -100,
                    disableParallax: !1,
                    disableVideo: !1,
                    automaticResize: !0,
                    videoSrc: null,
                    videoStartTime: 0,
                    videoEndTime: 0,
                    videoVolume: 0,
                    videoPlayOnlyVisible: !0,
                    onScroll: null,
                    onInit: null,
                    onDestroy: null,
                    onCoverImage: null
                };
                var n = o.$item.getAttribute("data-jarallax"), i = JSON.parse(n || "{}");
                n && console.warn("Detected usage of deprecated data-jarallax JSON options, you should use pure data-attribute options. See info here - https://github.com/nk-o/jarallax/issues/53");
                var a = o.$item.dataset || {}, r = {};
                if (Object.keys(a).forEach(function (e) {
                    var t = e.substr(0, 1).toLowerCase() + e.substr(1);
                    t && void 0 !== o.defaults[t] && (r[t] = a[e])
                }), o.options = o.extend({}, o.defaults, i, r, t), o.pureOptions = o.extend({}, o.options), Object.keys(o.options).forEach(function (e) {
                    "true" === o.options[e] ? o.options[e] = !0 : "false" === o.options[e] && (o.options[e] = !1)
                }), o.options.speed = Math.min(2, Math.max(-1, parseFloat(o.options.speed))), (o.options.noAndroid || o.options.noIos) && (console.warn("Detected usage of deprecated noAndroid or noIos options, you should use disableParallax option. See info here - https://github.com/nk-o/jarallax/#disable-on-mobile-devices"), o.options.disableParallax || (o.options.noIos && o.options.noAndroid ? o.options.disableParallax = /iPad|iPhone|iPod|Android/ : o.options.noIos ? o.options.disableParallax = /iPad|iPhone|iPod/ : o.options.noAndroid && (o.options.disableParallax = /Android/))), "string" == typeof o.options.disableParallax && (o.options.disableParallax = new RegExp(o.options.disableParallax)), o.options.disableParallax instanceof RegExp) {
                    var l = o.options.disableParallax;
                    o.options.disableParallax = function () {
                        return l.test(navigator.userAgent)
                    }
                }
                if ("function" != typeof o.options.disableParallax && (o.options.disableParallax = function () {
                    return !1
                }), "string" == typeof o.options.disableVideo && (o.options.disableVideo = new RegExp(o.options.disableVideo)), o.options.disableVideo instanceof RegExp) {
                    var s = o.options.disableVideo;
                    o.options.disableVideo = function () {
                        return s.test(navigator.userAgent)
                    }
                }
                "function" != typeof o.options.disableVideo && (o.options.disableVideo = function () {
                    return !1
                });
                var c = o.options.elementInViewport;
                c && "object" === (void 0 === c ? "undefined" : d(c)) && void 0 !== c.length && (c = function (e, t) {
                    if (Array.isArray(e)) return e;
                    if (Symbol.iterator in Object(e)) return function (e, t) {
                        var o = [], n = !0, i = !1, a = void 0;
                        try {
                            for (var r, l = e[Symbol.iterator](); !(n = (r = l.next()).done) && (o.push(r.value), !t || o.length !== t); n = !0) ;
                        } catch (e) {
                            i = !0, a = e
                        } finally {
                            try {
                                !n && l.return && l.return()
                            } finally {
                                if (i) throw a
                            }
                        }
                        return o
                    }(e, t);
                    throw new TypeError("Invalid attempt to destructure non-iterable instance")
                }(c, 1)[0]), c instanceof Element || (c = null), o.options.elementInViewport = c, o.image = {
                    src: o.options.imgSrc || null,
                    $container: null,
                    useImgTag: !1,
                    position: /iPad|iPhone|iPod|Android/.test(navigator.userAgent) ? "absolute" : "fixed"
                }, o.initImg() && o.canInitParallax() && o.init()
            }

            return t(u, [{
                key: "css", value: function (t, o) {
                    return "string" == typeof o ? i.window.getComputedStyle(t).getPropertyValue(o) : (o.transform && r && (o[r] = o.transform), Object.keys(o).forEach(function (e) {
                        t.style[e] = o[e]
                    }), t)
                }
            }, {
                key: "extend", value: function (o) {
                    var n = arguments;
                    return o = o || {}, Object.keys(arguments).forEach(function (t) {
                        n[t] && Object.keys(n[t]).forEach(function (e) {
                            o[e] = n[t][e]
                        })
                    }), o
                }
            }, {
                key: "getWindowData", value: function () {
                    return {width: b, height: v, y: l}
                }
            }, {
                key: "initImg", value: function () {
                    var e = this, t = e.options.imgElement;
                    return t && "string" == typeof t && (t = e.$item.querySelector(t)), t instanceof Element || (t = null), t && (e.options.keepImg ? e.image.$item = t.cloneNode(!0) : (e.image.$item = t, e.image.$itemParent = t.parentNode), e.image.useImgTag = !0), !(!e.image.$item && (null === e.image.src && (e.image.src = e.css(e.$item, "background-image").replace(/^url\(['"]?/g, "").replace(/['"]?\)$/g, "")), !e.image.src || "none" === e.image.src))
                }
            }, {
                key: "canInitParallax", value: function () {
                    return r && !this.options.disableParallax()
                }
            }, {
                key: "init", value: function () {
                    var e = this, t = {
                        position: "absolute",
                        top: 0,
                        left: 0,
                        width: "100%",
                        height: "100%",
                        overflow: "hidden",
                        pointerEvents: "none"
                    }, o = {};
                    if (!e.options.keepImg) {
                        var n = e.$item.getAttribute("style");
                        if (n && e.$item.setAttribute("data-jarallax-original-styles", n), e.image.useImgTag) {
                            var i = e.image.$item.getAttribute("style");
                            i && e.image.$item.setAttribute("data-jarallax-original-styles", i)
                        }
                    }
                    if ("static" === e.css(e.$item, "position") && e.css(e.$item, {position: "relative"}), "auto" === e.css(e.$item, "z-index") && e.css(e.$item, {zIndex: 0}), e.image.$container = document.createElement("div"), e.css(e.image.$container, t), e.css(e.image.$container, {"z-index": e.options.zIndex}), e.image.$container.setAttribute("id", "jarallax-container-" + e.instanceID), e.$item.appendChild(e.image.$container), e.image.useImgTag ? o = e.extend({
                        "object-fit": e.options.imgSize,
                        "object-position": e.options.imgPosition,
                        "font-family": "object-fit: " + e.options.imgSize + "; object-position: " + e.options.imgPosition + ";",
                        "max-width": "none"
                    }, t, o) : (e.image.$item = document.createElement("div"), e.image.src && (o = e.extend({
                        "background-position": e.options.imgPosition,
                        "background-size": e.options.imgSize,
                        "background-repeat": e.options.imgRepeat,
                        "background-image": 'url("' + e.image.src + '")'
                    }, t, o))), "opacity" !== e.options.type && "scale" !== e.options.type && "scale-opacity" !== e.options.type && 1 !== e.options.speed || (e.image.position = "absolute"), "fixed" === e.image.position) for (var a = 0, r = e.$item; null !== r && r !== document && 0 === a;) {
                        var l = e.css(r, "-webkit-transform") || e.css(r, "-moz-transform") || e.css(r, "transform");
                        l && "none" !== l && (a = 1, e.image.position = "absolute"), r = r.parentNode
                    }
                    o.position = e.image.position, e.css(e.image.$item, o), e.image.$container.appendChild(e.image.$item), e.coverImage(), e.clipContainer(), e.onScroll(!0), e.options.automaticResize && y && y.observe(e.$item), e.options.onInit && e.options.onInit.call(e), "none" !== e.css(e.$item, "background-image") && e.css(e.$item, {"background-image": "none"}), e.addToParallaxList()
                }
            }, {
                key: "addToParallaxList", value: function () {
                    p.push(this), 1 === p.length && f()
                }
            }, {
                key: "removeFromParallaxList", value: function () {
                    var o = this;
                    p.forEach(function (e, t) {
                        e.instanceID === o.instanceID && p.splice(t, 1)
                    })
                }
            }, {
                key: "destroy", value: function () {
                    var e = this;
                    e.removeFromParallaxList();
                    var t = e.$item.getAttribute("data-jarallax-original-styles");
                    if (e.$item.removeAttribute("data-jarallax-original-styles"), t ? e.$item.setAttribute("style", t) : e.$item.removeAttribute("style"), e.image.useImgTag) {
                        var o = e.image.$item.getAttribute("data-jarallax-original-styles");
                        e.image.$item.removeAttribute("data-jarallax-original-styles"), o ? e.image.$item.setAttribute("style", t) : e.image.$item.removeAttribute("style"), e.image.$itemParent && e.image.$itemParent.appendChild(e.image.$item)
                    }
                    e.$clipStyles && e.$clipStyles.parentNode.removeChild(e.$clipStyles), e.image.$container && e.image.$container.parentNode.removeChild(e.image.$container), e.options.onDestroy && e.options.onDestroy.call(e), delete e.$item.jarallax
                }
            }, {
                key: "clipContainer", value: function () {
                    if ("fixed" === this.image.position) {
                        var e = this, t = e.image.$container.getBoundingClientRect(), o = t.width, n = t.height;
                        e.$clipStyles || (e.$clipStyles = document.createElement("style"), e.$clipStyles.setAttribute("type", "text/css"), e.$clipStyles.setAttribute("id", "jarallax-clip-" + e.instanceID), (document.head || document.getElementsByTagName("head")[0]).appendChild(e.$clipStyles));
                        var i = "#jarallax-container-" + e.instanceID + " {\n           clip: rect(0 " + o + "px " + n + "px 0);\n           clip: rect(0, " + o + "px, " + n + "px, 0);\n        }";
                        e.$clipStyles.styleSheet ? e.$clipStyles.styleSheet.cssText = i : e.$clipStyles.innerHTML = i
                    }
                }
            }, {
                key: "coverImage", value: function () {
                    var e, t = this, o = t.image.$container.getBoundingClientRect(), n = o.height, i = t.options.speed,
                        a = "scroll" === t.options.type || "scroll-opacity" === t.options.type, r = 0, l = n;
                    return a && (r = i < 0 ? i * Math.max(n, v) : i * (n + v), 1 < i ? l = Math.abs(r - v) : i < 0 ? l = r / i + Math.abs(r) : l += Math.abs(v - n) * (1 - i), r /= 2), t.parallaxScrollDistance = r, e = a ? (v - l) / 2 : (n - l) / 2, t.css(t.image.$item, {
                        height: l + "px",
                        marginTop: e + "px",
                        left: "fixed" === t.image.position ? o.left + "px" : "0",
                        width: o.width + "px"
                    }), t.options.onCoverImage && t.options.onCoverImage.call(t), {
                        image: {height: l, marginTop: e},
                        container: o
                    }
                }
            }, {
                key: "isVisible", value: function () {
                    return this.isElementInViewport || !1
                }
            }, {
                key: "onScroll", value: function (e) {
                    var t = this, o = t.$item.getBoundingClientRect(), n = o.top, i = o.height, a = {}, r = o;
                    if (t.options.elementInViewport && (r = t.options.elementInViewport.getBoundingClientRect()), t.isElementInViewport = 0 <= r.bottom && 0 <= r.right && r.top <= v && r.left <= b, e || t.isElementInViewport) {
                        var l = Math.max(0, n), s = Math.max(0, i + n), c = Math.max(0, -n), u = Math.max(0, n + i - v),
                            d = Math.max(0, i - (n + i - v)), p = Math.max(0, -n + v - i),
                            m = 1 - 2 * (v - n) / (v + i), f = 1;
                        if (i < v ? f = 1 - (c || u) / i : s <= v ? f = s / v : d <= v && (f = d / v), "opacity" !== t.options.type && "scale-opacity" !== t.options.type && "scroll-opacity" !== t.options.type || (a.transform = "translate3d(0,0,0)", a.opacity = f), "scale" === t.options.type || "scale-opacity" === t.options.type) {
                            var y = 1;
                            t.options.speed < 0 ? y -= t.options.speed * f : y += t.options.speed * (1 - f), a.transform = "scale(" + y + ") translate3d(0,0,0)"
                        }
                        if ("scroll" === t.options.type || "scroll-opacity" === t.options.type) {
                            var g = t.parallaxScrollDistance * m;
                            "absolute" === t.image.position && (g -= n), a.transform = "translate3d(0," + g + "px,0)"
                        }
                        t.css(t.image.$item, a), t.options.onScroll && t.options.onScroll.call(t, {
                            section: o,
                            beforeTop: l,
                            beforeTopEnd: s,
                            afterTop: c,
                            beforeBottom: u,
                            beforeBottomEnd: d,
                            afterBottom: p,
                            visiblePercent: f,
                            fromViewportCenter: m
                        })
                    }
                }
            }, {
                key: "onResize", value: function () {
                    this.coverImage(), this.clipContainer()
                }
            }]), u
        }(), x = function (e) {
            ("object" === ("undefined" == typeof HTMLElement ? "undefined" : d(HTMLElement)) ? e instanceof HTMLElement : e && "object" === (void 0 === e ? "undefined" : d(e)) && null !== e && 1 === e.nodeType && "string" == typeof e.nodeName) && (e = [e]);
            for (var t = arguments[1], o = Array.prototype.slice.call(arguments, 2), n = e.length, i = 0, a = void 0; i < n; i++) if ("object" === (void 0 === t ? "undefined" : d(t)) || void 0 === t ? e[i].jarallax || (e[i].jarallax = new h(e[i], t)) : e[i].jarallax && (a = e[i].jarallax[t].apply(e[i].jarallax, o)), void 0 !== a) return a;
            return e
        };
        x.constructor = h, w.default = x
    }).call(w, $(2))
}, function (e, t, o) {
    "use strict";
    var n = o(0),
        i = n.requestAnimationFrame || n.webkitRequestAnimationFrame || n.mozRequestAnimationFrame || function (e) {
            var t = +new Date, o = Math.max(0, 16 - (t - a)), n = setTimeout(e, o);
            return a = t, n
        }, a = +new Date,
        r = n.cancelAnimationFrame || n.webkitCancelAnimationFrame || n.mozCancelAnimationFrame || clearTimeout;
    Function.prototype.bind && (i = i.bind(n), r = r.bind(n)), (e.exports = i).cancel = r
}]);
!function (a) {
    "use strict";
    a.fn.fitVids = function (t) {
        var i = {customSelector: null, ignore: null};
        if (!document.getElementById("fit-vids-style")) {
            var e = document.head || document.getElementsByTagName("head")[0], r = document.createElement("div");
            r.innerHTML = '<p>x</p><style id="fit-vids-style">.fluid-width-video-wrapper{width:100%;position:relative;padding-bottom: 56.25%;height:0;padding-top:0!important;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style>', e.appendChild(r.childNodes[1])
        }
        return t && a.extend(i, t), this.each(function () {
            var t = ['iframe[src*="player.vimeo.com"]', 'iframe[src*="youtube.com"]', 'iframe[src*="youtube-nocookie.com"]', 'iframe[src*="kickstarter.com"][src*="video.html"]', "object", "embed"];
            i.customSelector && t.push(i.customSelector);
            var r = ".fitvidsignore";
            i.ignore && (r = r + ", " + i.ignore);
            var e = a(this).find(t.join(","));
            (e = (e = e.not("object object")).not(r)).each(function () {
                var t = a(this);
                if (!(0 < t.parents(r).length || "embed" === this.tagName.toLowerCase() && t.parent("object").length || t.parent(".fluid-width-video-wrapper").length)) {
                    t.css("height") || t.css("width") || !isNaN(t.attr("height")) && !isNaN(t.attr("width")) || (t.attr("height", 9), t.attr("width", 16));
                    var e = ("object" === this.tagName.toLowerCase() || t.attr("height") && !isNaN(parseInt(t.attr("height"), 10)) ? parseInt(t.attr("height"), 10) : t.height()) / (isNaN(parseInt(t.attr("width"), 10)) ? t.width() : parseInt(t.attr("width"), 10));
                    if (!t.attr("name")) {
                        var i = "fitvid" + a.fn.fitVids._count;
                        t.attr("name", i), a.fn.fitVids._count++
                    }
                    t.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top", 100 * e + "%"), t.removeAttr("height").removeAttr("width")
                }
            })
        })
    }, a.fn.fitVids._count = 0
}(window.jQuery || window.Zepto);
!function (d, s, e) {
    function i(e, t) {
        this.element = e, this.settings = d.extend({}, n, t), this.settings.duplicate || t.hasOwnProperty("removeIds") || (this.settings.removeIds = !1), this._defaults = n, this._name = o, this.init()
    }

    var n = {
        label: "MENU",
        duplicate: !0,
        duration: 200,
        easingOpen: "swing",
        easingClose: "swing",
        closedSymbol: "&#9658;",
        openedSymbol: "&#9660;",
        prependTo: "body",
        appendTo: "",
        parentTag: "a",
        closeOnClick: !1,
        allowParentLinks: !1,
        nestedParentLinks: !0,
        showChildren: !1,
        removeIds: !0,
        removeClasses: !1,
        removeStyles: !1,
        brand: "",
        animations: "jquery",
        init: function () {
        },
        beforeOpen: function () {
        },
        beforeClose: function () {
        },
        afterOpen: function () {
        },
        afterClose: function () {
        }
    }, o = "slicknav", u = "slicknav", c = 40, p = 13, m = 27, h = 37, v = 39, f = 32, _ = 38;
    i.prototype.init = function () {
        var e, t, l = this, n = d(this.element), r = this.settings;
        if (r.duplicate ? l.mobileNav = n.clone() : l.mobileNav = n, r.removeIds && (l.mobileNav.removeAttr("id"), l.mobileNav.find("*").each(function (e, t) {
            d(t).removeAttr("id")
        })), r.removeClasses && (l.mobileNav.removeAttr("class"), l.mobileNav.find("*").each(function (e, t) {
            d(t).removeAttr("class")
        })), r.removeStyles && (l.mobileNav.removeAttr("style"), l.mobileNav.find("*").each(function (e, t) {
            d(t).removeAttr("style")
        })), e = u + "_icon", "" === r.label && (e += " " + u + "_no-text"), "a" == r.parentTag && (r.parentTag = 'a href="#"'), l.mobileNav.attr("class", u + "_nav"), t = d('<div class="' + u + '_menu"></div>'), "" !== r.brand) {
            var a = d('<div class="' + u + '_brand">' + r.brand + "</div>");
            d(t).append(a)
        }
        l.btn = d(["<" + r.parentTag + ' aria-haspopup="true" role="button" tabindex="0" class="' + u + "_btn " + u + '_collapsed">', '<span class="' + u + '_menutxt">' + r.label + "</span>", '<span class="' + e + '">', '<span class="' + u + '_icon-bar"></span>', '<span class="' + u + '_icon-bar"></span>', '<span class="' + u + '_icon-bar"></span>', "</span>", "</" + r.parentTag + ">"].join("")), d(t).append(l.btn), "" !== r.appendTo ? d(r.appendTo).append(t) : d(r.prependTo).prepend(t), t.append(l.mobileNav);
        var i = l.mobileNav.find("li");
        d(i).each(function () {
            var e = d(this), t = {};
            if (t.children = e.children("ul").attr("role", "menu"), e.data("menu", t), 0 < t.children.length) {
                var n = e.contents(), a = !1, i = [];
                d(n).each(function () {
                    return !d(this).is("ul") && (i.push(this), void(d(this).is("a") && (a = !0)))
                });
                var s = d("<" + r.parentTag + ' role="menuitem" aria-haspopup="true" tabindex="-1" class="' + u + '_item"/>');
                if (r.allowParentLinks && !r.nestedParentLinks && a) d(i).wrapAll('<span class="' + u + "_parent-link " + u + '_row"/>').parent(); else d(i).wrapAll(s).parent().addClass(u + "_row");
                r.showChildren ? e.addClass(u + "_open") : e.addClass(u + "_collapsed"), e.addClass(u + "_parent");
                var o = d('<span class="' + u + '_arrow">' + (r.showChildren ? r.openedSymbol : r.closedSymbol) + "</span>");
                r.allowParentLinks && !r.nestedParentLinks && a && (o = o.wrap(s).parent()), d(i).last().after(o)
            } else 0 === e.children().length && e.addClass(u + "_txtnode");
            e.children("a").attr("role", "menuitem").click(function (e) {
                r.closeOnClick && !d(e.target).parent().closest("li").hasClass(u + "_parent") && d(l.btn).click()
            }), r.closeOnClick && r.allowParentLinks && (e.children("a").children("a").click(function (e) {
                d(l.btn).click()
            }), e.find("." + u + "_parent-link a:not(." + u + "_item)").click(function (e) {
                d(l.btn).click()
            }))
        }), d(i).each(function () {
            var e = d(this).data("menu");
            r.showChildren || l._visibilityToggle(e.children, null, !1, null, !0)
        }), l._visibilityToggle(l.mobileNav, null, !1, "init", !0), l.mobileNav.attr("role", "menu"), d(s).mousedown(function () {
            l._outlines(!1)
        }), d(s).keyup(function () {
            l._outlines(!0)
        }), d(l.btn).click(function (e) {
            e.preventDefault(), l._menuToggle()
        }), l.mobileNav.on("click", "." + u + "_item", function (e) {
            e.preventDefault(), l._itemClick(d(this))
        }), d(l.btn).keydown(function (e) {
            var t = e || event;
            switch (t.keyCode) {
                case p:
                case f:
                case c:
                    e.preventDefault(), t.keyCode === c && d(l.btn).hasClass(u + "_open") || l._menuToggle(), d(l.btn).next().find('[role="menuitem"]').first().focus()
            }
        }), l.mobileNav.on("keydown", "." + u + "_item", function (e) {
            switch ((e || event).keyCode) {
                case p:
                    e.preventDefault(), l._itemClick(d(e.target));
                    break;
                case v:
                    e.preventDefault(), d(e.target).parent().hasClass(u + "_collapsed") && l._itemClick(d(e.target)), d(e.target).next().find('[role="menuitem"]').first().focus()
            }
        }), l.mobileNav.on("keydown", '[role="menuitem"]', function (e) {
            switch ((e || event).keyCode) {
                case c:
                    e.preventDefault();
                    var t = (a = (n = d(e.target).parent().parent().children().children('[role="menuitem"]:visible')).index(e.target)) + 1;
                    n.length <= t && (t = 0), n.eq(t).focus();
                    break;
                case _:
                    e.preventDefault();
                    var n,
                        a = (n = d(e.target).parent().parent().children().children('[role="menuitem"]:visible')).index(e.target);
                    n.eq(a - 1).focus();
                    break;
                case h:
                    if (e.preventDefault(), d(e.target).parent().parent().parent().hasClass(u + "_open")) {
                        var i = d(e.target).parent().parent().prev();
                        i.focus(), l._itemClick(i)
                    } else d(e.target).parent().parent().hasClass(u + "_nav") && (l._menuToggle(), d(l.btn).focus());
                    break;
                case m:
                    e.preventDefault(), l._menuToggle(), d(l.btn).focus()
            }
        }), r.allowParentLinks && r.nestedParentLinks && d("." + u + "_item a").click(function (e) {
            e.stopImmediatePropagation()
        })
    }, i.prototype._menuToggle = function (e) {
        var t = this, n = t.btn, a = t.mobileNav;
        n.hasClass(u + "_collapsed") ? (n.removeClass(u + "_collapsed"), n.addClass(u + "_open")) : (n.removeClass(u + "_open"), n.addClass(u + "_collapsed")), n.addClass(u + "_animating"), t._visibilityToggle(a, n.parent(), !0, n)
    }, i.prototype._itemClick = function (e) {
        var t = this.settings, n = e.data("menu");
        n || ((n = {}).arrow = e.children("." + u + "_arrow"), n.ul = e.next("ul"), n.parent = e.parent(), n.parent.hasClass(u + "_parent-link") && (n.parent = e.parent().parent(), n.ul = e.parent().next("ul")), e.data("menu", n)), n.parent.hasClass(u + "_collapsed") ? (n.arrow.html(t.openedSymbol), n.parent.removeClass(u + "_collapsed"), n.parent.addClass(u + "_open")) : (n.arrow.html(t.closedSymbol), n.parent.addClass(u + "_collapsed"), n.parent.removeClass(u + "_open")), n.parent.addClass(u + "_animating"), this._visibilityToggle(n.ul, n.parent, !0, e)
    }, i.prototype._visibilityToggle = function (n, e, t, a, i) {
        function s(e, t) {
            d(e).removeClass(u + "_animating"), d(t).removeClass(u + "_animating"), i || r.afterOpen(e)
        }

        function o(e, t) {
            n.attr("aria-hidden", "true"), c.attr("tabindex", "-1"), l._setVisAttr(n, !0), n.hide(), d(e).removeClass(u + "_animating"), d(t).removeClass(u + "_animating"), i ? "init" == e && r.init() : r.afterClose(e)
        }

        var l = this, r = l.settings, c = l._getActionItems(n), p = 0;
        t && (p = r.duration), n.hasClass(u + "_hidden") ? (n.removeClass(u + "_hidden"), i || r.beforeOpen(a), "jquery" === r.animations ? n.stop(!0, !0).slideDown(p, r.easingOpen, function () {
            s(a, e)
        }) : "velocity" === r.animations && n.velocity("finish").velocity("slideDown", {
            duration: p,
            easing: r.easingOpen,
            complete: function () {
                s(a, e)
            }
        }), n.attr("aria-hidden", "false"), c.attr("tabindex", "0"), l._setVisAttr(n, !1)) : (n.addClass(u + "_hidden"), i || r.beforeClose(a), "jquery" === r.animations ? n.stop(!0, !0).slideUp(p, this.settings.easingClose, function () {
            o(a, e)
        }) : "velocity" === r.animations && n.velocity("finish").velocity("slideUp", {
            duration: p,
            easing: r.easingClose,
            complete: function () {
                o(a, e)
            }
        }))
    }, i.prototype._setVisAttr = function (e, t) {
        var n = this, a = e.children("li").children("ul").not("." + u + "_hidden");
        t ? a.each(function () {
            var e = d(this);
            e.attr("aria-hidden", "true"), n._getActionItems(e).attr("tabindex", "-1"), n._setVisAttr(e, t)
        }) : a.each(function () {
            var e = d(this);
            e.attr("aria-hidden", "false"), n._getActionItems(e).attr("tabindex", "0"), n._setVisAttr(e, t)
        })
    }, i.prototype._getActionItems = function (e) {
        var t = e.data("menu");
        if (!t) {
            t = {};
            var n = e.children("li"), a = n.find("a");
            t.links = a.add(n.find("." + u + "_item")), e.data("menu", t)
        }
        return t.links
    }, i.prototype._outlines = function (e) {
        e ? d("." + u + "_item, ." + u + "_btn").css("outline", "") : d("." + u + "_item, ." + u + "_btn").css("outline", "none")
    }, i.prototype.toggle = function () {
        this._menuToggle()
    }, i.prototype.open = function () {
        this.btn.hasClass(u + "_collapsed") && this._menuToggle()
    }, i.prototype.close = function () {
        this.btn.hasClass(u + "_open") && this._menuToggle()
    }, d.fn[o] = function (t) {
        var n, a = arguments;
        return void 0 === t || "object" == typeof t ? this.each(function () {
            d.data(this, "plugin_" + o) || d.data(this, "plugin_" + o, new i(this, t))
        }) : "string" == typeof t && "_" !== t[0] && "init" !== t ? (this.each(function () {
            var e = d.data(this, "plugin_" + o);
            e instanceof i && "function" == typeof e[t] && (n = e[t].apply(e, Array.prototype.slice.call(a, 1)))
        }), void 0 !== n ? n : this) : void 0
    }
}(jQuery, document, window);
!function (e) {
    if ("function" == typeof define && define.amd) define(e); else if ("object" == typeof exports) module.exports = e(); else {
        var n = window.Cookies, o = window.Cookies = e();
        o.noConflict = function () {
            return window.Cookies = n, o
        }
    }
}(function () {
    function u() {
        for (var e = 0, n = {}; e < arguments.length; e++) {
            var o = arguments[e];
            for (var t in o) n[t] = o[t]
        }
        return n
    }

    return function e(f) {
        function l(e, n, o) {
            var t;
            if (1 < arguments.length) {
                if ("number" == typeof(o = u({path: "/"}, l.defaults, o)).expires) {
                    var i = new Date;
                    i.setMilliseconds(i.getMilliseconds() + 864e5 * o.expires), o.expires = i
                }
                try {
                    t = JSON.stringify(n), /^[\{\[]/.test(t) && (n = t)
                } catch (e) {
                }
                return n = f.write ? f.write(n, e) : encodeURIComponent(String(n)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent), e = (e = (e = encodeURIComponent(String(e))).replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)).replace(/[\(\)]/g, escape), document.cookie = [e, "=", n, o.expires && "; expires=" + o.expires.toUTCString(), o.path && "; path=" + o.path, o.domain && "; domain=" + o.domain, o.secure ? "; secure" : ""].join("")
            }
            e || (t = {});
            for (var r = document.cookie ? document.cookie.split("; ") : [], c = /(%[0-9A-Z]{2})+/g, s = 0; s < r.length; s++) {
                var a = r[s].split("="), p = a[0].replace(c, decodeURIComponent), d = a.slice(1).join("=");
                '"' === d.charAt(0) && (d = d.slice(1, -1));
                try {
                    if (d = f.read ? f.read(d, p) : f(d, p) || d.replace(c, decodeURIComponent), this.json) try {
                        d = JSON.parse(d)
                    } catch (e) {
                    }
                    if (e === p) {
                        t = d;
                        break
                    }
                    e || (t[p] = d)
                } catch (e) {
                }
            }
            return t
        }

        return l.get = l.set = l, l.getJSON = function () {
            return l.apply({json: !0}, [].slice.call(arguments))
        }, l.defaults = {}, l.remove = function (e, n) {
            l(e, "", u(n, {expires: -1}))
        }, l.withConverter = e, l
    }(function () {
    })
});
!function (e, t) {
    var i = function (n, d) {
        "use strict";
        if (d.getElementsByClassName) {
            var u, f, m = d.documentElement, s = n.Date, a = n.HTMLPictureElement, o = "addEventListener",
                z = "getAttribute", t = n[o], v = n.setTimeout, i = n.requestAnimationFrame || v,
                r = n.requestIdleCallback, g = /^picture$/i, l = ["load", "error", "lazyincluded", "_lazyloaded"],
                c = {}, y = Array.prototype.forEach, h = function (e, t) {
                    return c[t] || (c[t] = new RegExp("(\\s|^)" + t + "(\\s|$)")), c[t].test(e[z]("class") || "") && c[t]
                }, p = function (e, t) {
                    h(e, t) || e.setAttribute("class", (e[z]("class") || "").trim() + " " + t)
                }, C = function (e, t) {
                    var i;
                    (i = h(e, t)) && e.setAttribute("class", (e[z]("class") || "").replace(i, " "))
                }, b = function (t, i, e) {
                    var n = e ? o : "removeEventListener";
                    e && b(t, i), l.forEach(function (e) {
                        t[n](e, i)
                    })
                }, A = function (e, t, i, n, a) {
                    var s = d.createEvent("CustomEvent");
                    return i || (i = {}), i.instance = u, s.initCustomEvent(t, !n, !a, i), e.dispatchEvent(s), s
                }, E = function (e, t) {
                    var i;
                    !a && (i = n.picturefill || f.pf) ? i({reevaluate: !0, elements: [e]}) : t && t.src && (e.src = t.src)
                }, w = function (e, t) {
                    return (getComputedStyle(e, null) || {})[t]
                }, M = function (e, t, i) {
                    for (i = i || e.offsetWidth; i < f.minSize && t && !e._lazysizesWidth;) i = t.offsetWidth, t = t.parentNode;
                    return i
                }, N = (be = [], Ae = Ce = [], (we = function (e, t) {
                    he && !t ? e.apply(this, arguments) : (Ae.push(e), pe || (pe = !0, (d.hidden ? v : i)(Ee)))
                })._lsFlush = Ee = function () {
                    var e = Ae;
                    for (Ae = Ce.length ? be : Ce, pe = !(he = !0); e.length;) e.shift()();
                    he = !1
                }, we), e = function (i, e) {
                    return e ? function () {
                        N(i)
                    } : function () {
                        var e = this, t = arguments;
                        N(function () {
                            i.apply(e, t)
                        })
                    }
                }, _ = function (e) {
                    var t, i, n = function () {
                        t = null, e()
                    }, a = function () {
                        var e = s.now() - i;
                        e < 99 ? v(a, 99 - e) : (r || n)(n)
                    };
                    return function () {
                        i = s.now(), t || (t = v(a, 99))
                    }
                };
            !function () {
                var e, t = {
                    lazyClass: "lazyload",
                    loadedClass: "lazyloaded",
                    loadingClass: "lazyloading",
                    preloadClass: "lazypreload",
                    errorClass: "lazyerror",
                    autosizesClass: "lazyautosizes",
                    srcAttr: "data-src",
                    srcsetAttr: "data-srcset",
                    sizesAttr: "data-sizes",
                    minSize: 40,
                    customMedia: {},
                    init: !0,
                    expFactor: 1.5,
                    hFac: .8,
                    loadMode: 2,
                    loadHidden: !0,
                    ricTimeout: 300
                };
                for (e in f = n.lazySizesConfig || n.lazysizesConfig || {}, t) e in f || (f[e] = t[e]);
                n.lazySizesConfig = f, v(function () {
                    f.init && T()
                })
            }();
            var W = (te = /^img$/i, ie = /^iframe$/i, ne = "onscroll" in n && !/glebot/.test(navigator.userAgent), oe = -1, re = function (e) {
                se--, e && e.target && b(e.target, re), (!e || se < 0 || !e.target) && (se = 0)
            }, le = function (e, t) {
                var i, n = e, a = "hidden" == w(d.body, "visibility") || "hidden" != w(e, "visibility");
                for (I -= t, G += t, q -= t, j += t; a && (n = n.offsetParent) && n != d.body && n != m;) (a = 0 < (w(n, "opacity") || 1)) && "visible" != w(n, "overflow") && (i = n.getBoundingClientRect(), a = j > i.left && q < i.right && G > i.top - 1 && I < i.bottom + 1);
                return a
            }, U = ce = function () {
                var e, t, i, n, a, s, o, r, l, c = u.elements;
                if ((O = f.loadMode) && se < 8 && (e = c.length)) {
                    t = 0, oe++, null == K && ("expand" in f || (f.expand = 500 < m.clientHeight && 500 < m.clientWidth ? 500 : 370), J = f.expand, K = J * f.expFactor), ae < K && se < 1 && 2 < oe && 2 < O && !d.hidden ? (ae = K, oe = 0) : ae = 1 < O && 1 < oe && se < 6 ? J : 0;
                    for (; t < e; t++) if (c[t] && !c[t]._lazyRace) if (ne) if ((r = c[t][z]("data-expand")) && (s = 1 * r) || (s = ae), l !== s && ($ = innerWidth + s * Q, D = innerHeight + s, o = -1 * s, l = s), i = c[t].getBoundingClientRect(), (G = i.bottom) >= o && (I = i.top) <= D && (j = i.right) >= o * Q && (q = i.left) <= $ && (G || j || q || I) && (f.loadHidden || "hidden" != w(c[t], "visibility")) && (k && se < 3 && !r && (O < 3 || oe < 4) || le(c[t], s))) {
                        if (ge(c[t]), a = !0, 9 < se) break
                    } else !a && k && !n && se < 4 && oe < 4 && 2 < O && (R[0] || f.preloadAfterLoad) && (R[0] || !r && (G || j || q || I || "auto" != c[t][z](f.sizesAttr))) && (n = R[0] || c[t]); else ge(c[t]);
                    n && !a && ge(n)
                }
            }, X = se = ae = 0, Y = f.ricTimeout, Z = function () {
                V = !1, X = s.now(), U()
            }, ee = r && f.ricTimeout ? function () {
                r(Z, {timeout: Y}), Y !== f.ricTimeout && (Y = f.ricTimeout)
            } : e(function () {
                v(Z)
            }, !0), de = function (e) {
                var t;
                (e = !0 === e) && (Y = 33), V || (V = !0, (t = 125 - (s.now() - X)) < 0 && (t = 0), e || t < 9 && r ? ee() : v(ee, t))
            }, fe = e(ue = function (e) {
                p(e.target, f.loadedClass), C(e.target, f.loadingClass), b(e.target, me), A(e.target, "lazyloaded")
            }), me = function (e) {
                fe({target: e.target})
            }, ze = function (e) {
                var t, i = e[z](f.srcsetAttr);
                (t = f.customMedia[e[z]("data-media") || e[z]("media")]) && e.setAttribute("media", t), i && e.setAttribute("srcset", i)
            }, ve = e(function (e, t, i, n, a) {
                var s, o, r, l, c, d;
                (c = A(e, "lazybeforeunveil", t)).defaultPrevented || (n && (i ? p(e, f.autosizesClass) : e.setAttribute("sizes", n)), o = e[z](f.srcsetAttr), s = e[z](f.srcAttr), a && (r = e.parentNode, l = r && g.test(r.nodeName || "")), d = t.firesLoad || "src" in e && (o || s || l), c = {target: e}, d && (b(e, re, !0), clearTimeout(H), H = v(re, 2500), p(e, f.loadingClass), b(e, me, !0)), l && y.call(r.getElementsByTagName("source"), ze), o ? e.setAttribute("srcset", o) : s && !l && (ie.test(e.nodeName) ? function (t, i) {
                    try {
                        t.contentWindow.location.replace(i)
                    } catch (e) {
                        t.src = i
                    }
                }(e, s) : e.src = s), a && (o || l) && E(e, {src: s})), e._lazyRace && delete e._lazyRace, C(e, f.lazyClass), N(function () {
                    (!d || e.complete && 1 < e.naturalWidth) && (d ? re(c) : se--, ue(c))
                }, !0)
            }), ye = function () {
                if (!k) {
                    if (s.now() - P < 999) return void v(ye, 999);
                    var e = _(function () {
                        f.loadMode = 3, de()
                    });
                    k = !0, f.loadMode = 3, de(), t("scroll", function () {
                        3 == f.loadMode && (f.loadMode = 2), e()
                    }, !0)
                }
            }, {
                _: function () {
                    P = s.now(), u.elements = d.getElementsByClassName(f.lazyClass), R = d.getElementsByClassName(f.lazyClass + " " + f.preloadClass), Q = f.hFac, t("scroll", de, !0), t("resize", de, !0), n.MutationObserver ? new MutationObserver(de).observe(m, {
                        childList: !0,
                        subtree: !0,
                        attributes: !0
                    }) : (m[o]("DOMNodeInserted", de, !0), m[o]("DOMAttrModified", de, !0), setInterval(de, 999)), t("hashchange", de, !0), ["focus", "mouseover", "click", "load", "transitionend", "animationend", "webkitAnimationEnd"].forEach(function (e) {
                        d[o](e, de, !0)
                    }), /d$|^c/.test(d.readyState) ? ye() : (t("load", ye), d[o]("DOMContentLoaded", de), v(ye, 2e4)), u.elements.length ? (ce(), N._lsFlush()) : de()
                }, checkElems: de, unveil: ge = function (e) {
                    var t, i = te.test(e.nodeName), n = i && (e[z](f.sizesAttr) || e[z]("sizes")), a = "auto" == n;
                    (!a && k || !i || !e[z]("src") && !e.srcset || e.complete || h(e, f.errorClass) || !h(e, f.lazyClass)) && (t = A(e, "lazyunveilread").detail, a && x.updateElem(e, !0, e.offsetWidth), e._lazyRace = !0, se++, ve(e, t, a, n, i))
                }
            }), x = (F = e(function (e, t, i, n) {
                var a, s, o;
                if (e._lazysizesWidth = n, n += "px", e.setAttribute("sizes", n), g.test(t.nodeName || "")) for (a = t.getElementsByTagName("source"), s = 0, o = a.length; s < o; s++) a[s].setAttribute("sizes", n);
                i.detail.dataAttr || E(e, i.detail)
            }), S = function (e, t, i) {
                var n, a = e.parentNode;
                a && (i = M(e, a, i), (n = A(e, "lazybeforesizes", {
                    width: i,
                    dataAttr: !!t
                })).defaultPrevented || (i = n.detail.width) && i !== e._lazysizesWidth && F(e, a, n, i))
            }, {
                _: function () {
                    B = d.getElementsByClassName(f.autosizesClass), t("resize", L)
                }, checkElems: L = _(function () {
                    var e, t = B.length;
                    if (t) for (e = 0; e < t; e++) S(B[e])
                }), updateElem: S
            }), T = function () {
                T.i || (T.i = !0, x._(), W._())
            };
            return u = {cfg: f, autoSizer: x, loader: W, init: T, uP: E, aC: p, rC: C, hC: h, fire: A, gW: M, rAF: N}
        }
        var B, F, S, L;
        var R, k, H, O, P, $, D, I, q, j, G, J, K, Q, U, V, X, Y, Z, ee, te, ie, ne, ae, se, oe, re, le, ce, de, ue, fe,
            me, ze, ve, ge, ye;
        var he, pe, Ce, be, Ae, Ee, we
    }(e, e.document);
    e.lazySizes = i, "object" == typeof module && module.exports && (module.exports = i)
}(window);
!function (e, t) {
    var r = function () {
        t(e.lazySizes), e.removeEventListener("lazyunveilread", r, !0)
    };
    t = t.bind(null, e, e.document), "object" == typeof module && module.exports ? t(require("lazysizes")) : e.lazySizes ? r() : e.addEventListener("lazyunveilread", r, !0)
}(window, function (e, n, i) {
    "use strict";

    function o(e, t) {
        if (!s[e]) {
            var r = n.createElement(t ? "link" : "script"), a = n.getElementsByTagName("script")[0];
            t ? (r.rel = "stylesheet", r.href = e) : r.src = e, s[e] = !0, s[r.src || r.href] = !0, a.parentNode.insertBefore(r, a)
        }
    }

    var l, d, s = {};
    n.addEventListener && (l = function (e, t) {
        var r = n.createElement("img");
        r.onload = function () {
            r.onload = null, r.onerror = null, r = null, t()
        }, r.onerror = r.onload, r.src = e, r && r.complete && r.onload && r.onload()
    }, addEventListener("lazybeforeunveil", function (e) {
        var t, r, a;
        e.detail.instance == i && (e.defaultPrevented || ("none" == e.target.preload && (e.target.preload = "auto"), (t = e.target.getAttribute("data-link")) && o(t, !0), (t = e.target.getAttribute("data-script")) && o(t), (t = e.target.getAttribute("data-require")) && (i.cfg.requireJs ? i.cfg.requireJs([t]) : o(t)), (r = e.target.getAttribute("data-bg")) && (e.detail.firesLoad = !0, l(r, function () {
            e.target.style.backgroundImage = "url(" + (d.test(r) ? JSON.stringify(r) : r) + ")", e.detail.firesLoad = !1, i.fire(e.target, "_lazyloaded", {}, !0, !0)
        })), (a = e.target.getAttribute("data-poster")) && (e.detail.firesLoad = !0, l(a, function () {
            e.target.poster = a, e.detail.firesLoad = !1, i.fire(e.target, "_lazyloaded", {}, !0, !0)
        }))))
    }, !(d = /\(|\)|\s|'/)))
});
!function (h, i, n, o) {
    function l(t, e) {
        this.settings = null, this.options = h.extend({}, l.Defaults, e), this.$element = h(t), this._handlers = {}, this._plugins = {}, this._supress = {}, this._current = null, this._speed = null, this._coordinates = [], this._breakpoint = null, this._width = null, this._items = [], this._clones = [], this._mergers = [], this._widths = [], this._invalidated = {}, this._pipe = [], this._drag = {
            time: null,
            target: null,
            pointer: null,
            stage: {start: null, current: null},
            direction: null
        }, this._states = {
            current: {},
            tags: {initializing: ["busy"], animating: ["busy"], dragging: ["interacting"]}
        }, h.each(["onResize", "onThrottledResize"], h.proxy(function (t, e) {
            this._handlers[e] = h.proxy(this[e], this)
        }, this)), h.each(l.Plugins, h.proxy(function (t, e) {
            this._plugins[t.charAt(0).toLowerCase() + t.slice(1)] = new e(this)
        }, this)), h.each(l.Workers, h.proxy(function (t, e) {
            this._pipe.push({filter: e.filter, run: h.proxy(e.run, this)})
        }, this)), this.setup(), this.initialize()
    }

    l.Defaults = {
        items: 3,
        loop: !1,
        center: !1,
        rewind: !1,
        mouseDrag: !0,
        touchDrag: !0,
        pullDrag: !0,
        freeDrag: !1,
        margin: 0,
        stagePadding: 0,
        merge: !1,
        mergeFit: !0,
        autoWidth: !1,
        startPosition: 0,
        rtl: !1,
        smartSpeed: 250,
        fluidSpeed: !1,
        dragEndSpeed: !1,
        responsive: {},
        responsiveRefreshRate: 200,
        responsiveBaseElement: i,
        fallbackEasing: "swing",
        info: !1,
        nestedItemSelector: !1,
        itemElement: "div",
        stageElement: "div",
        refreshClass: "owl-refresh",
        loadedClass: "owl-loaded",
        loadingClass: "owl-loading",
        rtlClass: "owl-rtl",
        responsiveClass: "owl-responsive",
        dragClass: "owl-drag",
        itemClass: "owl-item",
        stageClass: "owl-stage",
        stageOuterClass: "owl-stage-outer",
        grabClass: "owl-grab"
    }, l.Width = {Default: "default", Inner: "inner", Outer: "outer"}, l.Type = {
        Event: "event",
        State: "state"
    }, l.Plugins = {}, l.Workers = [{
        filter: ["width", "settings"], run: function () {
            this._width = this.$element.width()
        }
    }, {
        filter: ["width", "items", "settings"], run: function (t) {
            t.current = this._items && this._items[this.relative(this._current)]
        }
    }, {
        filter: ["items", "settings"], run: function () {
            this.$stage.children(".cloned").remove()
        }
    }, {
        filter: ["width", "items", "settings"], run: function (t) {
            var e = this.settings.margin || "", i = !this.settings.autoWidth, s = this.settings.rtl,
                n = {width: "auto", "margin-left": s ? e : "", "margin-right": s ? "" : e};
            !i && this.$stage.children().css(n), t.css = n
        }
    }, {
        filter: ["width", "items", "settings"], run: function (t) {
            var e = (this.width() / this.settings.items).toFixed(3) - this.settings.margin, i = null,
                s = this._items.length, n = !this.settings.autoWidth, o = [];
            for (t.items = {
                merge: !1,
                width: e
            }; s--;) i = this._mergers[s], i = this.settings.mergeFit && Math.min(i, this.settings.items) || i, t.items.merge = 1 < i || t.items.merge, o[s] = n ? e * i : this._items[s].width();
            this._widths = o
        }
    }, {
        filter: ["items", "settings"], run: function () {
            var t = [], e = this._items, i = this.settings, s = Math.max(2 * i.items, 4),
                n = 2 * Math.ceil(e.length / 2), o = i.loop && e.length ? i.rewind ? s : Math.max(s, n) : 0, r = "",
                a = "";
            for (o /= 2; o--;) t.push(this.normalize(t.length / 2, !0)), r += e[t[t.length - 1]][0].outerHTML, t.push(this.normalize(e.length - 1 - (t.length - 1) / 2, !0)), a = e[t[t.length - 1]][0].outerHTML + a;
            this._clones = t, h(r).addClass("cloned").appendTo(this.$stage), h(a).addClass("cloned").prependTo(this.$stage)
        }
    }, {
        filter: ["width", "items", "settings"], run: function () {
            for (var t = this.settings.rtl ? 1 : -1, e = this._clones.length + this._items.length, i = -1, s = 0, n = 0, o = []; ++i < e;) s = o[i - 1] || 0, n = this._widths[this.relative(i)] + this.settings.margin, o.push(s + n * t);
            this._coordinates = o
        }
    }, {
        filter: ["width", "items", "settings"], run: function () {
            var t = this.settings.stagePadding, e = this._coordinates, i = {
                width: Math.ceil(Math.abs(e[e.length - 1])) + 2 * t,
                "padding-left": t || "",
                "padding-right": t || ""
            };
            this.$stage.css(i)
        }
    }, {
        filter: ["width", "items", "settings"], run: function (t) {
            var e = this._coordinates.length, i = !this.settings.autoWidth, s = this.$stage.children();
            if (i && t.items.merge) for (; e--;) t.css.width = this._widths[this.relative(e)], s.eq(e).css(t.css); else i && (t.css.width = t.items.width, s.css(t.css))
        }
    }, {
        filter: ["items"], run: function () {
            this._coordinates.length < 1 && this.$stage.removeAttr("style")
        }
    }, {
        filter: ["width", "items", "settings"], run: function (t) {
            t.current = t.current ? this.$stage.children().index(t.current) : 0, t.current = Math.max(this.minimum(), Math.min(this.maximum(), t.current)), this.reset(t.current)
        }
    }, {
        filter: ["position"], run: function () {
            this.animate(this.coordinates(this._current))
        }
    }, {
        filter: ["width", "position", "items", "settings"], run: function () {
            var t, e, i, s, n = this.settings.rtl ? 1 : -1, o = 2 * this.settings.stagePadding,
                r = this.coordinates(this.current()) + o, a = r + this.width() * n, h = [];
            for (i = 0, s = this._coordinates.length; i < s; i++) t = this._coordinates[i - 1] || 0, e = Math.abs(this._coordinates[i]) + o * n, (this.op(t, "<=", r) && this.op(t, ">", a) || this.op(e, "<", r) && this.op(e, ">", a)) && h.push(i);
            this.$stage.children(".active").removeClass("active"), this.$stage.children(":eq(" + h.join("), :eq(") + ")").addClass("active"), this.settings.center && (this.$stage.children(".center").removeClass("center"), this.$stage.children().eq(this.current()).addClass("center"))
        }
    }], l.prototype.initialize = function () {
        var t, e, i;
        (this.enter("initializing"), this.trigger("initialize"), this.$element.toggleClass(this.settings.rtlClass, this.settings.rtl), this.settings.autoWidth && !this.is("pre-loading")) && (t = this.$element.find("img"), e = this.settings.nestedItemSelector ? "." + this.settings.nestedItemSelector : o, i = this.$element.children(e).width(), t.length && i <= 0 && this.preloadAutoWidthImages(t));
        this.$element.addClass(this.options.loadingClass), this.$stage = h("<" + this.settings.stageElement + ' class="' + this.settings.stageClass + '"/>').wrap('<div class="' + this.settings.stageOuterClass + '"/>'), this.$element.append(this.$stage.parent()), this.replace(this.$element.children().not(this.$stage.parent())), this.$element.is(":visible") ? this.refresh() : this.invalidate("width"), this.$element.removeClass(this.options.loadingClass).addClass(this.options.loadedClass), this.registerEventHandlers(), this.leave("initializing"), this.trigger("initialized")
    }, l.prototype.setup = function () {
        var e = this.viewport(), t = this.options.responsive, i = -1, s = null;
        t ? (h.each(t, function (t) {
            t <= e && i < t && (i = Number(t))
        }), "function" == typeof(s = h.extend({}, this.options, t[i])).stagePadding && (s.stagePadding = s.stagePadding()), delete s.responsive, s.responsiveClass && this.$element.attr("class", this.$element.attr("class").replace(new RegExp("(" + this.options.responsiveClass + "-)\\S+\\s", "g"), "$1" + i))) : s = h.extend({}, this.options), this.trigger("change", {
            property: {
                name: "settings",
                value: s
            }
        }), this._breakpoint = i, this.settings = s, this.invalidate("settings"), this.trigger("changed", {
            property: {
                name: "settings",
                value: this.settings
            }
        })
    }, l.prototype.optionsLogic = function () {
        this.settings.autoWidth && (this.settings.stagePadding = !1, this.settings.merge = !1)
    }, l.prototype.prepare = function (t) {
        var e = this.trigger("prepare", {content: t});
        return e.data || (e.data = h("<" + this.settings.itemElement + "/>").addClass(this.options.itemClass).append(t)), this.trigger("prepared", {content: e.data}), e.data
    }, l.prototype.update = function () {
        for (var t = 0, e = this._pipe.length, i = h.proxy(function (t) {
            return this[t]
        }, this._invalidated), s = {}; t < e;) (this._invalidated.all || 0 < h.grep(this._pipe[t].filter, i).length) && this._pipe[t].run(s), t++;
        this._invalidated = {}, !this.is("valid") && this.enter("valid")
    }, l.prototype.width = function (t) {
        switch (t = t || l.Width.Default) {
            case l.Width.Inner:
            case l.Width.Outer:
                return this._width;
            default:
                return this._width - 2 * this.settings.stagePadding + this.settings.margin
        }
    }, l.prototype.refresh = function () {
        this.enter("refreshing"), this.trigger("refresh"), this.setup(), this.optionsLogic(), this.$element.addClass(this.options.refreshClass), this.update(), this.$element.removeClass(this.options.refreshClass), this.leave("refreshing"), this.trigger("refreshed")
    }, l.prototype.onThrottledResize = function () {
        i.clearTimeout(this.resizeTimer), this.resizeTimer = i.setTimeout(this._handlers.onResize, this.settings.responsiveRefreshRate)
    }, l.prototype.onResize = function () {
        return !!this._items.length && (this._width !== this.$element.width() && (!!this.$element.is(":visible") && (this.enter("resizing"), this.trigger("resize").isDefaultPrevented() ? (this.leave("resizing"), !1) : (this.invalidate("width"), this.refresh(), this.leave("resizing"), void this.trigger("resized")))))
    }, l.prototype.registerEventHandlers = function () {
        h.support.transition && this.$stage.on(h.support.transition.end + ".owl.core", h.proxy(this.onTransitionEnd, this)), !1 !== this.settings.responsive && this.on(i, "resize", this._handlers.onThrottledResize), this.settings.mouseDrag && (this.$element.addClass(this.options.dragClass), this.$stage.on("mousedown.owl.core", h.proxy(this.onDragStart, this)), this.$stage.on("dragstart.owl.core selectstart.owl.core", function () {
            return !1
        })), this.settings.touchDrag && (this.$stage.on("touchstart.owl.core", h.proxy(this.onDragStart, this)), this.$stage.on("touchcancel.owl.core", h.proxy(this.onDragEnd, this)))
    }, l.prototype.onDragStart = function (t) {
        var e = null;
        3 !== t.which && (e = h.support.transform ? {
            x: (e = this.$stage.css("transform").replace(/.*\(|\)| /g, "").split(","))[16 === e.length ? 12 : 4],
            y: e[16 === e.length ? 13 : 5]
        } : (e = this.$stage.position(), {
            x: this.settings.rtl ? e.left + this.$stage.width() - this.width() + this.settings.margin : e.left,
            y: e.top
        }), this.is("animating") && (h.support.transform ? this.animate(e.x) : this.$stage.stop(), this.invalidate("position")), this.$element.toggleClass(this.options.grabClass, "mousedown" === t.type), this.speed(0), this._drag.time = (new Date).getTime(), this._drag.target = h(t.target), this._drag.stage.start = e, this._drag.stage.current = e, this._drag.pointer = this.pointer(t), h(n).on("mouseup.owl.core touchend.owl.core", h.proxy(this.onDragEnd, this)), h(n).one("mousemove.owl.core touchmove.owl.core", h.proxy(function (t) {
            var e = this.difference(this._drag.pointer, this.pointer(t));
            h(n).on("mousemove.owl.core touchmove.owl.core", h.proxy(this.onDragMove, this)), Math.abs(e.x) < Math.abs(e.y) && this.is("valid") || (t.preventDefault(), this.enter("dragging"), this.trigger("drag"))
        }, this)))
    }, l.prototype.onDragMove = function (t) {
        var e = null, i = null, s = null, n = this.difference(this._drag.pointer, this.pointer(t)),
            o = this.difference(this._drag.stage.start, n);
        this.is("dragging") && (t.preventDefault(), this.settings.loop ? (e = this.coordinates(this.minimum()), i = this.coordinates(this.maximum() + 1) - e, o.x = ((o.x - e) % i + i) % i + e) : (e = this.settings.rtl ? this.coordinates(this.maximum()) : this.coordinates(this.minimum()), i = this.settings.rtl ? this.coordinates(this.minimum()) : this.coordinates(this.maximum()), s = this.settings.pullDrag ? -1 * n.x / 5 : 0, o.x = Math.max(Math.min(o.x, e + s), i + s)), this._drag.stage.current = o, this.animate(o.x))
    }, l.prototype.onDragEnd = function (t) {
        var e = this.difference(this._drag.pointer, this.pointer(t)), i = this._drag.stage.current,
            s = 0 < e.x ^ this.settings.rtl ? "left" : "right";
        h(n).off(".owl.core"), this.$element.removeClass(this.options.grabClass), (0 !== e.x && this.is("dragging") || !this.is("valid")) && (this.speed(this.settings.dragEndSpeed || this.settings.smartSpeed), this.current(this.closest(i.x, 0 !== e.x ? s : this._drag.direction)), this.invalidate("position"), this.update(), this._drag.direction = s, (3 < Math.abs(e.x) || 300 < (new Date).getTime() - this._drag.time) && this._drag.target.one("click.owl.core", function () {
            return !1
        })), this.is("dragging") && (this.leave("dragging"), this.trigger("dragged"))
    }, l.prototype.closest = function (i, s) {
        var n = -1, o = this.width(), r = this.coordinates();
        return this.settings.freeDrag || h.each(r, h.proxy(function (t, e) {
            return "left" === s && e - 30 < i && i < e + 30 ? n = t : "right" === s && e - o - 30 < i && i < e - o + 30 ? n = t + 1 : this.op(i, "<", e) && this.op(i, ">", r[t + 1] || e - o) && (n = "left" === s ? t + 1 : t), -1 === n
        }, this)), this.settings.loop || (this.op(i, ">", r[this.minimum()]) ? n = i = this.minimum() : this.op(i, "<", r[this.maximum()]) && (n = i = this.maximum())), n
    }, l.prototype.animate = function (t) {
        var e = 0 < this.speed();
        this.is("animating") && this.onTransitionEnd(), e && (this.enter("animating"), this.trigger("translate")), h.support.transform3d && h.support.transition ? this.$stage.css({
            transform: "translate3d(" + t + "px,0px,0px)",
            transition: this.speed() / 1e3 + "s"
        }) : e ? this.$stage.animate({left: t + "px"}, this.speed(), this.settings.fallbackEasing, h.proxy(this.onTransitionEnd, this)) : this.$stage.css({left: t + "px"})
    }, l.prototype.is = function (t) {
        return this._states.current[t] && 0 < this._states.current[t]
    }, l.prototype.current = function (t) {
        if (t === o) return this._current;
        if (0 === this._items.length) return o;
        if (t = this.normalize(t), this._current !== t) {
            var e = this.trigger("change", {property: {name: "position", value: t}});
            e.data !== o && (t = this.normalize(e.data)), this._current = t, this.invalidate("position"), this.trigger("changed", {
                property: {
                    name: "position",
                    value: this._current
                }
            })
        }
        return this._current
    }, l.prototype.invalidate = function (t) {
        return "string" === h.type(t) && (this._invalidated[t] = !0, this.is("valid") && this.leave("valid")), h.map(this._invalidated, function (t, e) {
            return e
        })
    }, l.prototype.reset = function (t) {
        (t = this.normalize(t)) !== o && (this._speed = 0, this._current = t, this.suppress(["translate", "translated"]), this.animate(this.coordinates(t)), this.release(["translate", "translated"]))
    }, l.prototype.normalize = function (t, e) {
        var i = this._items.length, s = e ? 0 : this._clones.length;
        return !this.isNumeric(t) || i < 1 ? t = o : (t < 0 || i + s <= t) && (t = ((t - s / 2) % i + i) % i + s / 2), t
    }, l.prototype.relative = function (t) {
        return t -= this._clones.length / 2, this.normalize(t, !0)
    }, l.prototype.maximum = function (t) {
        var e, i, s, n = this.settings, o = this._coordinates.length;
        if (n.loop) o = this._clones.length / 2 + this._items.length - 1; else if (n.autoWidth || n.merge) {
            for (e = this._items.length, i = this._items[--e].width(), s = this.$element.width(); e-- && !(s < (i += this._items[e].width() + this.settings.margin));) ;
            o = e + 1
        } else o = n.center ? this._items.length - 1 : this._items.length - n.items;
        return t && (o -= this._clones.length / 2), Math.max(o, 0)
    }, l.prototype.minimum = function (t) {
        return t ? 0 : this._clones.length / 2
    }, l.prototype.items = function (t) {
        return t === o ? this._items.slice() : (t = this.normalize(t, !0), this._items[t])
    }, l.prototype.mergers = function (t) {
        return t === o ? this._mergers.slice() : (t = this.normalize(t, !0), this._mergers[t])
    }, l.prototype.clones = function (i) {
        var e = this._clones.length / 2, s = e + this._items.length, n = function (t) {
            return t % 2 == 0 ? s + t / 2 : e - (t + 1) / 2
        };
        return i === o ? h.map(this._clones, function (t, e) {
            return n(e)
        }) : h.map(this._clones, function (t, e) {
            return t === i ? n(e) : null
        })
    }, l.prototype.speed = function (t) {
        return t !== o && (this._speed = t), this._speed
    }, l.prototype.coordinates = function (t) {
        var e, i = 1, s = t - 1;
        return t === o ? h.map(this._coordinates, h.proxy(function (t, e) {
            return this.coordinates(e)
        }, this)) : (this.settings.center ? (this.settings.rtl && (i = -1, s = t + 1), e = this._coordinates[t], e += (this.width() - e + (this._coordinates[s] || 0)) / 2 * i) : e = this._coordinates[s] || 0, e = Math.ceil(e))
    }, l.prototype.duration = function (t, e, i) {
        return 0 === i ? 0 : Math.min(Math.max(Math.abs(e - t), 1), 6) * Math.abs(i || this.settings.smartSpeed)
    }, l.prototype.to = function (t, e) {
        var i = this.current(), s = null, n = t - this.relative(i), o = (0 < n) - (n < 0), r = this._items.length,
            a = this.minimum(), h = this.maximum();
        this.settings.loop ? (!this.settings.rewind && Math.abs(n) > r / 2 && (n += -1 * o * r), (s = (((t = i + n) - a) % r + r) % r + a) !== t && s - n <= h && 0 < s - n && (i = s - n, t = s, this.reset(i))) : t = this.settings.rewind ? (t % (h += 1) + h) % h : Math.max(a, Math.min(h, t)), this.speed(this.duration(i, t, e)), this.current(t), this.$element.is(":visible") && this.update()
    }, l.prototype.next = function (t) {
        t = t || !1, this.to(this.relative(this.current()) + 1, t)
    }, l.prototype.prev = function (t) {
        t = t || !1, this.to(this.relative(this.current()) - 1, t)
    }, l.prototype.onTransitionEnd = function (t) {
        return (t === o || (t.stopPropagation(), (t.target || t.srcElement || t.originalTarget) === this.$stage.get(0))) && (this.leave("animating"), void this.trigger("translated"))
    }, l.prototype.viewport = function () {
        var t;
        if (this.options.responsiveBaseElement !== i) t = h(this.options.responsiveBaseElement).width(); else if (i.innerWidth) t = i.innerWidth; else {
            if (!n.documentElement || !n.documentElement.clientWidth) throw"Can not detect viewport width.";
            t = n.documentElement.clientWidth
        }
        return t
    }, l.prototype.replace = function (t) {
        this.$stage.empty(), this._items = [], t && (t = t instanceof jQuery ? t : h(t)), this.settings.nestedItemSelector && (t = t.find("." + this.settings.nestedItemSelector)), t.filter(function () {
            return 1 === this.nodeType
        }).each(h.proxy(function (t, e) {
            e = this.prepare(e), this.$stage.append(e), this._items.push(e), this._mergers.push(1 * e.find("[data-merge]").addBack("[data-merge]").attr("data-merge") || 1)
        }, this)), this.reset(this.isNumeric(this.settings.startPosition) ? this.settings.startPosition : 0), this.invalidate("items")
    }, l.prototype.add = function (t, e) {
        var i = this.relative(this._current);
        e = e === o ? this._items.length : this.normalize(e, !0), t = t instanceof jQuery ? t : h(t), this.trigger("add", {
            content: t,
            position: e
        }), t = this.prepare(t), 0 === this._items.length || e === this._items.length ? (0 === this._items.length && this.$stage.append(t), 0 !== this._items.length && this._items[e - 1].after(t), this._items.push(t), this._mergers.push(1 * t.find("[data-merge]").addBack("[data-merge]").attr("data-merge") || 1)) : (this._items[e].before(t), this._items.splice(e, 0, t), this._mergers.splice(e, 0, 1 * t.find("[data-merge]").addBack("[data-merge]").attr("data-merge") || 1)), this._items[i] && this.reset(this._items[i].index()), this.invalidate("items"), this.trigger("added", {
            content: t,
            position: e
        })
    }, l.prototype.remove = function (t) {
        (t = this.normalize(t, !0)) !== o && (this.trigger("remove", {
            content: this._items[t],
            position: t
        }), this._items[t].remove(), this._items.splice(t, 1), this._mergers.splice(t, 1), this.invalidate("items"), this.trigger("removed", {
            content: null,
            position: t
        }))
    }, l.prototype.preloadAutoWidthImages = function (t) {
        t.each(h.proxy(function (t, e) {
            this.enter("pre-loading"), e = h(e), h(new Image).one("load", h.proxy(function (t) {
                e.attr("src", t.target.src), e.css("opacity", 1), this.leave("pre-loading"), !this.is("pre-loading") && !this.is("initializing") && this.refresh()
            }, this)).attr("src", e.attr("src") || e.attr("data-src") || e.attr("data-src-retina"))
        }, this))
    }, l.prototype.destroy = function () {
        for (var t in this.$element.off(".owl.core"), this.$stage.off(".owl.core"), h(n).off(".owl.core"), !1 !== this.settings.responsive && (i.clearTimeout(this.resizeTimer), this.off(i, "resize", this._handlers.onThrottledResize)), this._plugins) this._plugins[t].destroy();
        this.$stage.children(".cloned").remove(), this.$stage.unwrap(), this.$stage.children().contents().unwrap(), this.$stage.children().unwrap(), this.$element.removeClass(this.options.refreshClass).removeClass(this.options.loadingClass).removeClass(this.options.loadedClass).removeClass(this.options.rtlClass).removeClass(this.options.dragClass).removeClass(this.options.grabClass).attr("class", this.$element.attr("class").replace(new RegExp(this.options.responsiveClass + "-\\S+\\s", "g"), "")).removeData("owl.carousel")
    }, l.prototype.op = function (t, e, i) {
        var s = this.settings.rtl;
        switch (e) {
            case"<":
                return s ? i < t : t < i;
            case">":
                return s ? t < i : i < t;
            case">=":
                return s ? t <= i : i <= t;
            case"<=":
                return s ? i <= t : t <= i
        }
    }, l.prototype.on = function (t, e, i, s) {
        t.addEventListener ? t.addEventListener(e, i, s) : t.attachEvent && t.attachEvent("on" + e, i)
    }, l.prototype.off = function (t, e, i, s) {
        t.removeEventListener ? t.removeEventListener(e, i, s) : t.detachEvent && t.detachEvent("on" + e, i)
    }, l.prototype.trigger = function (t, e, i, s, n) {
        var o = {item: {count: this._items.length, index: this.current()}},
            r = h.camelCase(h.grep(["on", t, i], function (t) {
                return t
            }).join("-").toLowerCase()),
            a = h.Event([t, "owl", i || "carousel"].join(".").toLowerCase(), h.extend({relatedTarget: this}, o, e));
        return this._supress[t] || (h.each(this._plugins, function (t, e) {
            e.onTrigger && e.onTrigger(a)
        }), this.register({
            type: l.Type.Event,
            name: t
        }), this.$element.trigger(a), this.settings && "function" == typeof this.settings[r] && this.settings[r].call(this, a)), a
    }, l.prototype.enter = function (t) {
        h.each([t].concat(this._states.tags[t] || []), h.proxy(function (t, e) {
            this._states.current[e] === o && (this._states.current[e] = 0), this._states.current[e]++
        }, this))
    }, l.prototype.leave = function (t) {
        h.each([t].concat(this._states.tags[t] || []), h.proxy(function (t, e) {
            this._states.current[e]--
        }, this))
    }, l.prototype.register = function (i) {
        if (i.type === l.Type.Event) {
            if (h.event.special[i.name] || (h.event.special[i.name] = {}), !h.event.special[i.name].owl) {
                var e = h.event.special[i.name]._default;
                h.event.special[i.name]._default = function (t) {
                    return !e || !e.apply || t.namespace && -1 !== t.namespace.indexOf("owl") ? t.namespace && -1 < t.namespace.indexOf("owl") : e.apply(this, arguments)
                }, h.event.special[i.name].owl = !0
            }
        } else i.type === l.Type.State && (this._states.tags[i.name] ? this._states.tags[i.name] = this._states.tags[i.name].concat(i.tags) : this._states.tags[i.name] = i.tags, this._states.tags[i.name] = h.grep(this._states.tags[i.name], h.proxy(function (t, e) {
            return h.inArray(t, this._states.tags[i.name]) === e
        }, this)))
    }, l.prototype.suppress = function (t) {
        h.each(t, h.proxy(function (t, e) {
            this._supress[e] = !0
        }, this))
    }, l.prototype.release = function (t) {
        h.each(t, h.proxy(function (t, e) {
            delete this._supress[e]
        }, this))
    }, l.prototype.pointer = function (t) {
        var e = {x: null, y: null};
        return (t = (t = t.originalEvent || t || i.event).touches && t.touches.length ? t.touches[0] : t.changedTouches && t.changedTouches.length ? t.changedTouches[0] : t).pageX ? (e.x = t.pageX, e.y = t.pageY) : (e.x = t.clientX, e.y = t.clientY), e
    }, l.prototype.isNumeric = function (t) {
        return !isNaN(parseFloat(t))
    }, l.prototype.difference = function (t, e) {
        return {x: t.x - e.x, y: t.y - e.y}
    }, h.fn.owlCarousel = function (e) {
        var s = Array.prototype.slice.call(arguments, 1);
        return this.each(function () {
            var t = h(this), i = t.data("owl.carousel");
            i || (i = new l(this, "object" == typeof e && e), t.data("owl.carousel", i), h.each(["next", "prev", "to", "destroy", "refresh", "replace", "add", "remove"], function (t, e) {
                i.register({
                    type: l.Type.Event,
                    name: e
                }), i.$element.on(e + ".owl.carousel.core", h.proxy(function (t) {
                    t.namespace && t.relatedTarget !== this && (this.suppress([e]), i[e].apply(this, [].slice.call(arguments, 1)), this.release([e]))
                }, i))
            })), "string" == typeof e && "_" !== e.charAt(0) && i[e].apply(i, s)
        })
    }, h.fn.owlCarousel.Constructor = l
}(window.Zepto || window.jQuery, window, document), function (e, i, t, s) {
    var n = function (t) {
        this._core = t, this._interval = null, this._visible = null, this._handlers = {
            "initialized.owl.carousel": e.proxy(function (t) {
                t.namespace && this._core.settings.autoRefresh && this.watch()
            }, this)
        }, this._core.options = e.extend({}, n.Defaults, this._core.options), this._core.$element.on(this._handlers)
    };
    n.Defaults = {autoRefresh: !0, autoRefreshInterval: 500}, n.prototype.watch = function () {
        this._interval || (this._visible = this._core.$element.is(":visible"), this._interval = i.setInterval(e.proxy(this.refresh, this), this._core.settings.autoRefreshInterval))
    }, n.prototype.refresh = function () {
        this._core.$element.is(":visible") !== this._visible && (this._visible = !this._visible, this._core.$element.toggleClass("owl-hidden", !this._visible), this._visible && this._core.invalidate("width") && this._core.refresh())
    }, n.prototype.destroy = function () {
        var t, e;
        for (t in i.clearInterval(this._interval), this._handlers) this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this)) "function" != typeof this[e] && (this[e] = null)
    }, e.fn.owlCarousel.Constructor.Plugins.AutoRefresh = n
}(window.Zepto || window.jQuery, window, document), function (a, o, t, e) {
    var i = function (t) {
        this._core = t, this._loaded = [], this._handlers = {
            "initialized.owl.carousel change.owl.carousel resized.owl.carousel": a.proxy(function (t) {
                if (t.namespace && this._core.settings && this._core.settings.lazyLoad && (t.property && "position" == t.property.name || "initialized" == t.type)) for (var e = this._core.settings, i = e.center && Math.ceil(e.items / 2) || e.items, s = e.center && -1 * i || 0, n = (t.property && void 0 !== t.property.value ? t.property.value : this._core.current()) + s, o = this._core.clones().length, r = a.proxy(function (t, e) {
                    this.load(e)
                }, this); s++ < i;) this.load(o / 2 + this._core.relative(n)), o && a.each(this._core.clones(this._core.relative(n)), r), n++
            }, this)
        }, this._core.options = a.extend({}, i.Defaults, this._core.options), this._core.$element.on(this._handlers)
    };
    i.Defaults = {lazyLoad: !1}, i.prototype.load = function (t) {
        var e = this._core.$stage.children().eq(t), i = e && e.find(".owl-lazy");
        !i || -1 < a.inArray(e.get(0), this._loaded) || (i.each(a.proxy(function (t, e) {
            var i, s = a(e), n = 1 < o.devicePixelRatio && s.attr("data-src-retina") || s.attr("data-src");
            this._core.trigger("load", {
                element: s,
                url: n
            }, "lazy"), s.is("img") ? s.one("load.owl.lazy", a.proxy(function () {
                s.css("opacity", 1), this._core.trigger("loaded", {element: s, url: n}, "lazy")
            }, this)).attr("src", n) : ((i = new Image).onload = a.proxy(function () {
                s.css({"background-image": "url(" + n + ")", opacity: "1"}), this._core.trigger("loaded", {
                    element: s,
                    url: n
                }, "lazy")
            }, this), i.src = n)
        }, this)), this._loaded.push(e.get(0)))
    }, i.prototype.destroy = function () {
        var t, e;
        for (t in this.handlers) this._core.$element.off(t, this.handlers[t]);
        for (e in Object.getOwnPropertyNames(this)) "function" != typeof this[e] && (this[e] = null)
    }, a.fn.owlCarousel.Constructor.Plugins.Lazy = i
}(window.Zepto || window.jQuery, window, document), function (o, t, e, i) {
    var s = function (t) {
        this._core = t, this._handlers = {
            "initialized.owl.carousel refreshed.owl.carousel": o.proxy(function (t) {
                t.namespace && this._core.settings.autoHeight && this.update()
            }, this), "changed.owl.carousel": o.proxy(function (t) {
                t.namespace && this._core.settings.autoHeight && "position" == t.property.name && this.update()
            }, this), "loaded.owl.lazy": o.proxy(function (t) {
                t.namespace && this._core.settings.autoHeight && t.element.closest("." + this._core.settings.itemClass).index() === this._core.current() && this.update()
            }, this)
        }, this._core.options = o.extend({}, s.Defaults, this._core.options), this._core.$element.on(this._handlers)
    };
    s.Defaults = {autoHeight: !1, autoHeightClass: "owl-height"}, s.prototype.update = function () {
        var t, e = this._core._current, i = e + this._core.settings.items,
            s = this._core.$stage.children().toArray().slice(e, i), n = [];
        o.each(s, function (t, e) {
            n.push(o(e).height())
        }), t = Math.max.apply(null, n), this._core.$stage.parent().height(t).addClass(this._core.settings.autoHeightClass)
    }, s.prototype.destroy = function () {
        var t, e;
        for (t in this._handlers) this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this)) "function" != typeof this[e] && (this[e] = null)
    }, o.fn.owlCarousel.Constructor.Plugins.AutoHeight = s
}(window.Zepto || window.jQuery, window, document), function (c, t, e, i) {
    var s = function (t) {
        this._core = t, this._videos = {}, this._playing = null, this._handlers = {
            "initialized.owl.carousel": c.proxy(function (t) {
                t.namespace && this._core.register({type: "state", name: "playing", tags: ["interacting"]})
            }, this), "resize.owl.carousel": c.proxy(function (t) {
                t.namespace && this._core.settings.video && this.isInFullScreen() && t.preventDefault()
            }, this), "refreshed.owl.carousel": c.proxy(function (t) {
                t.namespace && this._core.is("resizing") && this._core.$stage.find(".cloned .owl-video-frame").remove()
            }, this), "changed.owl.carousel": c.proxy(function (t) {
                t.namespace && "position" === t.property.name && this._playing && this.stop()
            }, this), "prepared.owl.carousel": c.proxy(function (t) {
                if (t.namespace) {
                    var e = c(t.content).find(".owl-video");
                    e.length && (e.css("display", "none"), this.fetch(e, c(t.content)))
                }
            }, this)
        }, this._core.options = c.extend({}, s.Defaults, this._core.options), this._core.$element.on(this._handlers), this._core.$element.on("click.owl.video", ".owl-video-play-icon", c.proxy(function (t) {
            this.play(t)
        }, this))
    };
    s.Defaults = {video: !1, videoHeight: !1, videoWidth: !1}, s.prototype.fetch = function (t, e) {
        var i = t.attr("data-vimeo-id") ? "vimeo" : t.attr("data-vzaar-id") ? "vzaar" : "youtube",
            s = t.attr("data-vimeo-id") || t.attr("data-youtube-id") || t.attr("data-vzaar-id"),
            n = t.attr("data-width") || this._core.settings.videoWidth,
            o = t.attr("data-height") || this._core.settings.videoHeight, r = t.attr("href");
        if (!r) throw new Error("Missing video URL.");
        if (-1 < (s = r.match(/(http:|https:|)\/\/(player.|www.|app.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com)|vzaar\.com)\/(video\/|videos\/|embed\/|channels\/.+\/|groups\/.+\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/))[3].indexOf("youtu")) i = "youtube"; else if (-1 < s[3].indexOf("vimeo")) i = "vimeo"; else {
            if (!(-1 < s[3].indexOf("vzaar"))) throw new Error("Video URL not supported.");
            i = "vzaar"
        }
        s = s[6], this._videos[r] = {
            type: i,
            id: s,
            width: n,
            height: o
        }, e.attr("data-video", r), this.thumbnail(t, this._videos[r])
    }, s.prototype.thumbnail = function (e, t) {
        var i, s, n = t.width && t.height ? 'style="width:' + t.width + "px;height:" + t.height + 'px;"' : "",
            o = e.find("img"), r = "src", a = "", h = this._core.settings, l = function (t) {
                '<div class="owl-video-play-icon"></div>', i = h.lazyLoad ? '<div class="owl-video-tn ' + a + '" ' + r + '="' + t + '"></div>' : '<div class="owl-video-tn" style="opacity:1;background-image:url(' + t + ')"></div>', e.after(i), e.after('<div class="owl-video-play-icon"></div>')
            };
        return e.wrap('<div class="owl-video-wrapper"' + n + "></div>"), this._core.settings.lazyLoad && (r = "data-src", a = "owl-lazy"), o.length ? (l(o.attr(r)), o.remove(), !1) : void("youtube" === t.type ? (s = "//img.youtube.com/vi/" + t.id + "/hqdefault.jpg", l(s)) : "vimeo" === t.type ? c.ajax({
            type: "GET",
            url: "//vimeo.com/api/v2/video/" + t.id + ".json",
            jsonp: "callback",
            dataType: "jsonp",
            success: function (t) {
                s = t[0].thumbnail_large, l(s)
            }
        }) : "vzaar" === t.type && c.ajax({
            type: "GET",
            url: "//vzaar.com/api/videos/" + t.id + ".json",
            jsonp: "callback",
            dataType: "jsonp",
            success: function (t) {
                s = t.framegrab_url, l(s)
            }
        }))
    }, s.prototype.stop = function () {
        this._core.trigger("stop", null, "video"), this._playing.find(".owl-video-frame").remove(), this._playing.removeClass("owl-video-playing"), this._playing = null, this._core.leave("playing"), this._core.trigger("stopped", null, "video")
    }, s.prototype.play = function (t) {
        var e, i = c(t.target).closest("." + this._core.settings.itemClass), s = this._videos[i.attr("data-video")],
            n = s.width || "100%", o = s.height || this._core.$stage.height();
        this._playing || (this._core.enter("playing"), this._core.trigger("play", null, "video"), i = this._core.items(this._core.relative(i.index())), this._core.reset(i.index()), "youtube" === s.type ? e = '<iframe width="' + n + '" height="' + o + '" src="//www.youtube.com/embed/' + s.id + "?autoplay=1&v=" + s.id + '" frameborder="0" allowfullscreen></iframe>' : "vimeo" === s.type ? e = '<iframe src="//player.vimeo.com/video/' + s.id + '?autoplay=1" width="' + n + '" height="' + o + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>' : "vzaar" === s.type && (e = '<iframe frameborder="0"height="' + o + '"width="' + n + '" allowfullscreen mozallowfullscreen webkitAllowFullScreen src="//view.vzaar.com/' + s.id + '/player?autoplay=true"></iframe>'), c('<div class="owl-video-frame">' + e + "</div>").insertAfter(i.find(".owl-video")), this._playing = i.addClass("owl-video-playing"))
    }, s.prototype.isInFullScreen = function () {
        var t = e.fullscreenElement || e.mozFullScreenElement || e.webkitFullscreenElement;
        return t && c(t).parent().hasClass("owl-video-frame")
    }, s.prototype.destroy = function () {
        var t, e;
        for (t in this._core.$element.off("click.owl.video"), this._handlers) this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this)) "function" != typeof this[e] && (this[e] = null)
    }, c.fn.owlCarousel.Constructor.Plugins.Video = s
}(window.Zepto || window.jQuery, window, document), function (r, t, e, i) {
    var s = function (t) {
        this.core = t, this.core.options = r.extend({}, s.Defaults, this.core.options), this.swapping = !0, this.previous = void 0, this.next = void 0, this.handlers = {
            "change.owl.carousel": r.proxy(function (t) {
                t.namespace && "position" == t.property.name && (this.previous = this.core.current(), this.next = t.property.value)
            }, this), "drag.owl.carousel dragged.owl.carousel translated.owl.carousel": r.proxy(function (t) {
                t.namespace && (this.swapping = "translated" == t.type)
            }, this), "translate.owl.carousel": r.proxy(function (t) {
                t.namespace && this.swapping && (this.core.options.animateOut || this.core.options.animateIn) && this.swap()
            }, this)
        }, this.core.$element.on(this.handlers)
    };
    s.Defaults = {animateOut: !1, animateIn: !1}, s.prototype.swap = function () {
        if (1 === this.core.settings.items && r.support.animation && r.support.transition) {
            this.core.speed(0);
            var t, e = r.proxy(this.clear, this), i = this.core.$stage.children().eq(this.previous),
                s = this.core.$stage.children().eq(this.next), n = this.core.settings.animateIn,
                o = this.core.settings.animateOut;
            this.core.current() !== this.previous && (o && (t = this.core.coordinates(this.previous) - this.core.coordinates(this.next), i.one(r.support.animation.end, e).css({left: t + "px"}).addClass("animated owl-animated-out").addClass(o)), n && s.one(r.support.animation.end, e).addClass("animated owl-animated-in").addClass(n))
        }
    }, s.prototype.clear = function (t) {
        r(t.target).css({left: ""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut), this.core.onTransitionEnd()
    }, s.prototype.destroy = function () {
        var t, e;
        for (t in this.handlers) this.core.$element.off(t, this.handlers[t]);
        for (e in Object.getOwnPropertyNames(this)) "function" != typeof this[e] && (this[e] = null)
    }, r.fn.owlCarousel.Constructor.Plugins.Animate = s
}(window.Zepto || window.jQuery, window, document), function (i, s, n, t) {
    var e = function (t) {
        this._core = t, this._timeout = null, this._paused = !1, this._handlers = {
            "changed.owl.carousel": i.proxy(function (t) {
                t.namespace && "settings" === t.property.name ? this._core.settings.autoplay ? this.play() : this.stop() : t.namespace && "position" === t.property.name && this._core.settings.autoplay && this._setAutoPlayInterval()
            }, this), "initialized.owl.carousel": i.proxy(function (t) {
                t.namespace && this._core.settings.autoplay && this.play()
            }, this), "play.owl.autoplay": i.proxy(function (t, e, i) {
                t.namespace && this.play(e, i)
            }, this), "stop.owl.autoplay": i.proxy(function (t) {
                t.namespace && this.stop()
            }, this), "mouseover.owl.autoplay": i.proxy(function () {
                this._core.settings.autoplayHoverPause && this._core.is("rotating") && this.pause()
            }, this), "mouseleave.owl.autoplay": i.proxy(function () {
                this._core.settings.autoplayHoverPause && this._core.is("rotating") && this.play()
            }, this), "touchstart.owl.core": i.proxy(function () {
                this._core.settings.autoplayHoverPause && this._core.is("rotating") && this.pause()
            }, this), "touchend.owl.core": i.proxy(function () {
                this._core.settings.autoplayHoverPause && this.play()
            }, this)
        }, this._core.$element.on(this._handlers), this._core.options = i.extend({}, e.Defaults, this._core.options)
    };
    e.Defaults = {
        autoplay: !1,
        autoplayTimeout: 5e3,
        autoplayHoverPause: !1,
        autoplaySpeed: !1
    }, e.prototype.play = function (t, e) {
        this._paused = !1, this._core.is("rotating") || (this._core.enter("rotating"), this._setAutoPlayInterval())
    }, e.prototype._getNextTimeout = function (t, e) {
        return this._timeout && s.clearTimeout(this._timeout), s.setTimeout(i.proxy(function () {
            this._paused || this._core.is("busy") || this._core.is("interacting") || n.hidden || this._core.next(e || this._core.settings.autoplaySpeed)
        }, this), t || this._core.settings.autoplayTimeout)
    }, e.prototype._setAutoPlayInterval = function () {
        this._timeout = this._getNextTimeout()
    }, e.prototype.stop = function () {
        this._core.is("rotating") && (s.clearTimeout(this._timeout), this._core.leave("rotating"))
    }, e.prototype.pause = function () {
        this._core.is("rotating") && (this._paused = !0)
    }, e.prototype.destroy = function () {
        var t, e;
        for (t in this.stop(), this._handlers) this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this)) "function" != typeof this[e] && (this[e] = null)
    }, i.fn.owlCarousel.Constructor.Plugins.autoplay = e
}(window.Zepto || window.jQuery, window, document), function (o, t, e, i) {
    "use strict";
    var s = function (t) {
        this._core = t, this._initialized = !1, this._pages = [], this._controls = {}, this._templates = [], this.$element = this._core.$element, this._overrides = {
            next: this._core.next,
            prev: this._core.prev,
            to: this._core.to
        }, this._handlers = {
            "prepared.owl.carousel": o.proxy(function (t) {
                t.namespace && this._core.settings.dotsData && this._templates.push('<div class="' + this._core.settings.dotClass + '">' + o(t.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot") + "</div>")
            }, this), "added.owl.carousel": o.proxy(function (t) {
                t.namespace && this._core.settings.dotsData && this._templates.splice(t.position, 0, this._templates.pop())
            }, this), "remove.owl.carousel": o.proxy(function (t) {
                t.namespace && this._core.settings.dotsData && this._templates.splice(t.position, 1)
            }, this), "changed.owl.carousel": o.proxy(function (t) {
                t.namespace && "position" == t.property.name && this.draw()
            }, this), "initialized.owl.carousel": o.proxy(function (t) {
                t.namespace && !this._initialized && (this._core.trigger("initialize", null, "navigation"), this.initialize(), this.update(), this.draw(), this._initialized = !0, this._core.trigger("initialized", null, "navigation"))
            }, this), "refreshed.owl.carousel": o.proxy(function (t) {
                t.namespace && this._initialized && (this._core.trigger("refresh", null, "navigation"), this.update(), this.draw(), this._core.trigger("refreshed", null, "navigation"))
            }, this)
        }, this._core.options = o.extend({}, s.Defaults, this._core.options), this.$element.on(this._handlers)
    };
    s.Defaults = {
        nav: !1,
        navText: ["prev", "next"],
        navSpeed: !1,
        navElement: "div",
        navContainer: !1,
        navContainerClass: "owl-nav",
        navClass: ["owl-prev", "owl-next"],
        slideBy: 1,
        dotClass: "owl-dot",
        dotsClass: "owl-dots",
        dots: !0,
        dotsEach: !1,
        dotsData: !1,
        dotsSpeed: !1,
        dotsContainer: !1
    }, s.prototype.initialize = function () {
        var t, i = this._core.settings;
        for (t in this._controls.$relative = (i.navContainer ? o(i.navContainer) : o("<div>").addClass(i.navContainerClass).appendTo(this.$element)).addClass("disabled"), this._controls.$previous = o("<" + i.navElement + ">").addClass(i.navClass[0]).html(i.navText[0]).prependTo(this._controls.$relative).on("click", o.proxy(function (t) {
            this.prev(i.navSpeed)
        }, this)), this._controls.$next = o("<" + i.navElement + ">").addClass(i.navClass[1]).html(i.navText[1]).appendTo(this._controls.$relative).on("click", o.proxy(function (t) {
            this.next(i.navSpeed)
        }, this)), i.dotsData || (this._templates = [o("<div>").addClass(i.dotClass).append(o("<span>")).prop("outerHTML")]), this._controls.$absolute = (i.dotsContainer ? o(i.dotsContainer) : o("<div>").addClass(i.dotsClass).appendTo(this.$element)).addClass("disabled"), this._controls.$absolute.on("click", "div", o.proxy(function (t) {
            var e = o(t.target).parent().is(this._controls.$absolute) ? o(t.target).index() : o(t.target).parent().index();
            t.preventDefault(), this.to(e, i.dotsSpeed)
        }, this)), this._overrides) this._core[t] = o.proxy(this[t], this)
    }, s.prototype.destroy = function () {
        var t, e, i, s;
        for (t in this._handlers) this.$element.off(t, this._handlers[t]);
        for (e in this._controls) this._controls[e].remove();
        for (s in this.overides) this._core[s] = this._overrides[s];
        for (i in Object.getOwnPropertyNames(this)) "function" != typeof this[i] && (this[i] = null)
    }, s.prototype.update = function () {
        var t, e, i = this._core.clones().length / 2, s = i + this._core.items().length, n = this._core.maximum(!0),
            o = this._core.settings, r = o.center || o.autoWidth || o.dotsData ? 1 : o.dotsEach || o.items;
        if ("page" !== o.slideBy && (o.slideBy = Math.min(o.slideBy, o.items)), o.dots || "page" == o.slideBy) for (this._pages = [], t = i, e = 0; t < s; t++) {
            if (r <= e || 0 === e) {
                if (this._pages.push({start: Math.min(n, t - i), end: t - i + r - 1}), Math.min(n, t - i) === n) break;
                e = 0, 0
            }
            e += this._core.mergers(this._core.relative(t))
        }
    }, s.prototype.draw = function () {
        var t, e = this._core.settings, i = this._core.items().length <= e.items,
            s = this._core.relative(this._core.current()), n = e.loop || e.rewind;
        this._controls.$relative.toggleClass("disabled", !e.nav || i), e.nav && (this._controls.$previous.toggleClass("disabled", !n && s <= this._core.minimum(!0)), this._controls.$next.toggleClass("disabled", !n && s >= this._core.maximum(!0))), this._controls.$absolute.toggleClass("disabled", !e.dots || i), e.dots && (t = this._pages.length - this._controls.$absolute.children().length, e.dotsData && 0 !== t ? this._controls.$absolute.html(this._templates.join("")) : 0 < t ? this._controls.$absolute.append(new Array(t + 1).join(this._templates[0])) : t < 0 && this._controls.$absolute.children().slice(t).remove(), this._controls.$absolute.find(".active").removeClass("active"), this._controls.$absolute.children().eq(o.inArray(this.current(), this._pages)).addClass("active"))
    }, s.prototype.onTrigger = function (t) {
        var e = this._core.settings;
        t.page = {
            index: o.inArray(this.current(), this._pages),
            count: this._pages.length,
            size: e && (e.center || e.autoWidth || e.dotsData ? 1 : e.dotsEach || e.items)
        }
    }, s.prototype.current = function () {
        var i = this._core.relative(this._core.current());
        return o.grep(this._pages, o.proxy(function (t, e) {
            return t.start <= i && t.end >= i
        }, this)).pop()
    }, s.prototype.getPosition = function (t) {
        var e, i, s = this._core.settings;
        return "page" == s.slideBy ? (e = o.inArray(this.current(), this._pages), i = this._pages.length, t ? ++e : --e, e = this._pages[(e % i + i) % i].start) : (e = this._core.relative(this._core.current()), i = this._core.items().length, t ? e += s.slideBy : e -= s.slideBy), e
    }, s.prototype.next = function (t) {
        o.proxy(this._overrides.to, this._core)(this.getPosition(!0), t)
    }, s.prototype.prev = function (t) {
        o.proxy(this._overrides.to, this._core)(this.getPosition(!1), t)
    }, s.prototype.to = function (t, e, i) {
        var s;
        !i && this._pages.length ? (s = this._pages.length, o.proxy(this._overrides.to, this._core)(this._pages[(t % s + s) % s].start, e)) : o.proxy(this._overrides.to, this._core)(t, e)
    }, o.fn.owlCarousel.Constructor.Plugins.Navigation = s
}(window.Zepto || window.jQuery, window, document), function (s, n, t, e) {
    "use strict";
    var i = function (t) {
        this._core = t, this._hashes = {}, this.$element = this._core.$element, this._handlers = {
            "initialized.owl.carousel": s.proxy(function (t) {
                t.namespace && "URLHash" === this._core.settings.startPosition && s(n).trigger("hashchange.owl.navigation")
            }, this), "prepared.owl.carousel": s.proxy(function (t) {
                if (t.namespace) {
                    var e = s(t.content).find("[data-hash]").addBack("[data-hash]").attr("data-hash");
                    if (!e) return;
                    this._hashes[e] = t.content
                }
            }, this), "changed.owl.carousel": s.proxy(function (t) {
                if (t.namespace && "position" === t.property.name) {
                    var i = this._core.items(this._core.relative(this._core.current())),
                        e = s.map(this._hashes, function (t, e) {
                            return t === i ? e : null
                        }).join();
                    if (!e || n.location.hash.slice(1) === e) return;
                    n.location.hash = e
                }
            }, this)
        }, this._core.options = s.extend({}, i.Defaults, this._core.options), this.$element.on(this._handlers), s(n).on("hashchange.owl.navigation", s.proxy(function (t) {
            var e = n.location.hash.substring(1), i = this._core.$stage.children(),
                s = this._hashes[e] && i.index(this._hashes[e]);
            void 0 !== s && s !== this._core.current() && this._core.to(this._core.relative(s), !1, !0)
        }, this))
    };
    i.Defaults = {URLhashListener: !1}, i.prototype.destroy = function () {
        var t, e;
        for (t in s(n).off("hashchange.owl.navigation"), this._handlers) this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this)) "function" != typeof this[e] && (this[e] = null)
    }, s.fn.owlCarousel.Constructor.Plugins.Hash = i
}(window.Zepto || window.jQuery, window, document), function (n, t, e, o) {
    function i(t, i) {
        var s = !1, e = t.charAt(0).toUpperCase() + t.slice(1);
        return n.each((t + " " + a.join(e + " ") + e).split(" "), function (t, e) {
            return r[e] !== o ? (s = !i || e, !1) : void 0
        }), s
    }

    function s(t) {
        return i(t, !0)
    }

    var r = n("<support>").get(0).style, a = "Webkit Moz O ms".split(" "), h = {
        transition: {
            end: {
                WebkitTransition: "webkitTransitionEnd",
                MozTransition: "transitionend",
                OTransition: "oTransitionEnd",
                transition: "transitionend"
            }
        },
        animation: {
            end: {
                WebkitAnimation: "webkitAnimationEnd",
                MozAnimation: "animationend",
                OAnimation: "oAnimationEnd",
                animation: "animationend"
            }
        }
    }, l = function () {
        return !!i("transform")
    }, c = function () {
        return !!i("perspective")
    }, p = function () {
        return !!i("animation")
    };
    (function () {
        return !!i("transition")
    })() && (n.support.transition = new String(s("transition")), n.support.transition.end = h.transition.end[n.support.transition]), p() && (n.support.animation = new String(s("animation")), n.support.animation.end = h.animation.end[n.support.animation]), l() && (n.support.transform = new String(s("transform")), n.support.transform3d = c())
}(window.Zepto || window.jQuery, window, document);
!function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof module && module.exports ? module.exports = function (t, i) {
        return void 0 === i && (i = "undefined" != typeof window ? require("jquery") : require("jquery")(t)), e(i), i
    } : e(jQuery)
}(function (g) {
    var r = function (t, i) {
        this.settings = i, this.checkSettings(), this.imgAnalyzerTimeout = null, this.entries = null, this.buildingRow = {
            entriesBuff: [],
            width: 0,
            height: 0,
            aspectRatio: 0
        }, this.lastFetchedEntry = null, this.lastAnalyzedIndex = -1, this.yield = {
            every: 2,
            flushed: 0
        }, this.border = 0 <= i.border ? i.border : i.margins, this.maxRowHeight = this.retrieveMaxRowHeight(), this.suffixRanges = this.retrieveSuffixRanges(), this.offY = this.border, this.rows = 0, this.spinner = {
            phase: 0,
            timeSlot: 150,
            $el: g('<div class="spinner"><span></span><span></span><span></span></div>'),
            intervalId: null
        }, this.scrollBarOn = !1, this.checkWidthIntervalId = null, this.galleryWidth = t.width(), this.$gallery = t
    };
    r.prototype.getSuffix = function (t, i) {
        var e, s;
        for (e = i < t ? t : i, s = 0; s < this.suffixRanges.length; s++) if (e <= this.suffixRanges[s]) return this.settings.sizeRangeSuffixes[this.suffixRanges[s]];
        return this.settings.sizeRangeSuffixes[this.suffixRanges[s - 1]]
    }, r.prototype.removeSuffix = function (t, i) {
        return t.substring(0, t.length - i.length)
    }, r.prototype.endsWith = function (t, i) {
        return -1 !== t.indexOf(i, t.length - i.length)
    }, r.prototype.getUsedSuffix = function (t) {
        for (var i in this.settings.sizeRangeSuffixes) if (this.settings.sizeRangeSuffixes.hasOwnProperty(i)) {
            if (0 === this.settings.sizeRangeSuffixes[i].length) continue;
            if (this.endsWith(t, this.settings.sizeRangeSuffixes[i])) return this.settings.sizeRangeSuffixes[i]
        }
        return ""
    }, r.prototype.newSrc = function (t, i, e, s) {
        var n;
        if (this.settings.thumbnailPath) n = this.settings.thumbnailPath(t, i, e, s); else {
            var r = t.match(this.settings.extension), o = null !== r ? r[0] : "";
            n = t.replace(this.settings.extension, ""), n = this.removeSuffix(n, this.getUsedSuffix(n)), n += this.getSuffix(i, e) + o
        }
        return n
    }, r.prototype.showImg = function (t, i) {
        this.settings.cssAnimation ? (t.addClass("entry-visible"), i && i()) : (t.stop().fadeTo(this.settings.imagesAnimationDuration, 1, i), t.find(this.settings.imgSelector).stop().fadeTo(this.settings.imagesAnimationDuration, 1, i))
    }, r.prototype.extractImgSrcFromImage = function (t) {
        var i = void 0 !== t.data("safe-src") ? t.data("safe-src") : t.attr("src");
        return t.data("jg.originalSrc", i), i
    }, r.prototype.imgFromEntry = function (t) {
        var i = t.find(this.settings.imgSelector);
        return 0 === i.length ? null : i
    }, r.prototype.captionFromEntry = function (t) {
        var i = t.find("> .caption");
        return 0 === i.length ? null : i
    }, r.prototype.displayEntry = function (t, i, e, s, n, r) {
        t.width(s), t.height(r), t.css("top", e), t.css("left", i);
        var o = this.imgFromEntry(t);
        if (null !== o) {
            o.css("width", s), o.css("height", n), o.css("margin-left", -s / 2), o.css("margin-top", -n / 2);
            var a = o.attr("src"), h = this.newSrc(a, s, n, o[0]);
            o.one("error", function () {
                o.attr("src", o.data("jg.originalSrc"))
            });
            var l = function () {
                a !== h && o.attr("src", h)
            };
            "skipped" === t.data("jg.loaded") ? this.onImageEvent(a, g.proxy(function () {
                this.showImg(t, l), t.data("jg.loaded", !0)
            }, this)) : this.showImg(t, l)
        } else this.showImg(t);
        this.displayEntryCaption(t)
    }, r.prototype.displayEntryCaption = function (t) {
        var i = this.imgFromEntry(t);
        if (null !== i && this.settings.captions) {
            var e = this.captionFromEntry(t);
            if (null === e) {
                var s = i.attr("alt");
                this.isValidCaption(s) || (s = t.attr("title")), this.isValidCaption(s) && (e = g('<div class="caption">' + s + "</div>"), t.append(e), t.data("jg.createdCaption", !0))
            }
            null !== e && (this.settings.cssAnimation || e.stop().fadeTo(0, this.settings.captionSettings.nonVisibleOpacity), this.addCaptionEventsHandlers(t))
        } else this.removeCaptionEventsHandlers(t)
    }, r.prototype.isValidCaption = function (t) {
        return void 0 !== t && 0 < t.length
    }, r.prototype.onEntryMouseEnterForCaption = function (t) {
        var i = this.captionFromEntry(g(t.currentTarget));
        this.settings.cssAnimation ? i.addClass("caption-visible").removeClass("caption-hidden") : i.stop().fadeTo(this.settings.captionSettings.animationDuration, this.settings.captionSettings.visibleOpacity)
    }, r.prototype.onEntryMouseLeaveForCaption = function (t) {
        var i = this.captionFromEntry(g(t.currentTarget));
        this.settings.cssAnimation ? i.removeClass("caption-visible").removeClass("caption-hidden") : i.stop().fadeTo(this.settings.captionSettings.animationDuration, this.settings.captionSettings.nonVisibleOpacity)
    }, r.prototype.addCaptionEventsHandlers = function (t) {
        var i = t.data("jg.captionMouseEvents");
        void 0 === i && (i = {
            mouseenter: g.proxy(this.onEntryMouseEnterForCaption, this),
            mouseleave: g.proxy(this.onEntryMouseLeaveForCaption, this)
        }, t.on("mouseenter", void 0, void 0, i.mouseenter), t.on("mouseleave", void 0, void 0, i.mouseleave), t.data("jg.captionMouseEvents", i))
    }, r.prototype.removeCaptionEventsHandlers = function (t) {
        var i = t.data("jg.captionMouseEvents");
        void 0 !== i && (t.off("mouseenter", void 0, i.mouseenter), t.off("mouseleave", void 0, i.mouseleave), t.removeData("jg.captionMouseEvents"))
    }, r.prototype.clearBuildingRow = function () {
        this.buildingRow.entriesBuff = [], this.buildingRow.aspectRatio = 0, this.buildingRow.width = 0
    }, r.prototype.prepareBuildingRow = function (t) {
        var i, e, s, n, r, o = !0, a = 0,
            h = this.galleryWidth - 2 * this.border - (this.buildingRow.entriesBuff.length - 1) * this.settings.margins,
            l = h / this.buildingRow.aspectRatio, g = this.settings.rowHeight,
            u = this.buildingRow.width / h > this.settings.justifyThreshold;
        if (t && "hide" === this.settings.lastRow && !u) {
            for (i = 0; i < this.buildingRow.entriesBuff.length; i++) e = this.buildingRow.entriesBuff[i], this.settings.cssAnimation ? e.removeClass("entry-visible") : (e.stop().fadeTo(0, .1), e.find("> img, > a > img").fadeTo(0, 0));
            return -1
        }
        for (t && !u && "justify" !== this.settings.lastRow && "hide" !== this.settings.lastRow && (o = !1, 0 < this.rows && (o = (g = (this.offY - this.border - this.settings.margins * this.rows) / this.rows) * this.buildingRow.aspectRatio / h > this.settings.justifyThreshold)), i = 0; i < this.buildingRow.entriesBuff.length; i++) s = (e = this.buildingRow.entriesBuff[i]).data("jg.width") / e.data("jg.height"), r = o ? (n = i === this.buildingRow.entriesBuff.length - 1 ? h : l * s, l) : (n = g * s, g), h -= Math.round(n), e.data("jg.jwidth", Math.round(n)), e.data("jg.jheight", Math.ceil(r)), (0 === i || r < a) && (a = r);
        return this.buildingRow.height = a, o
    }, r.prototype.flushRow = function (t) {
        var i, e, s, n = this.settings, r = this.border;
        if (e = this.prepareBuildingRow(t), t && "hide" === n.lastRow && -1 === e) this.clearBuildingRow(); else {
            if (this.maxRowHeight && this.maxRowHeight < this.buildingRow.height && (this.buildingRow.height = this.maxRowHeight), t && ("center" === n.lastRow || "right" === n.lastRow)) {
                var o = this.galleryWidth - 2 * this.border - (this.buildingRow.entriesBuff.length - 1) * n.margins;
                for (s = 0; s < this.buildingRow.entriesBuff.length; s++) o -= (i = this.buildingRow.entriesBuff[s]).data("jg.jwidth");
                "center" === n.lastRow ? r += o / 2 : "right" === n.lastRow && (r += o)
            }
            var a = this.buildingRow.entriesBuff.length - 1;
            for (s = 0; s <= a; s++) i = this.buildingRow.entriesBuff[this.settings.rtl ? a - s : s], this.displayEntry(i, r, this.offY, i.data("jg.jwidth"), i.data("jg.jheight"), this.buildingRow.height), r += i.data("jg.jwidth") + n.margins;
            this.galleryHeightToSet = this.offY + this.buildingRow.height + this.border, this.setGalleryTempHeight(this.galleryHeightToSet + this.getSpinnerHeight()), (!t || this.buildingRow.height <= n.rowHeight && e) && (this.offY += this.buildingRow.height + n.margins, this.rows += 1, this.clearBuildingRow(), this.settings.triggerEvent.call(this, "jg.rowflush"))
        }
    };
    var i = 0;

    function e() {
        return g("body").height() > g(window).height()
    }

    r.prototype.rememberGalleryHeight = function () {
        i = this.$gallery.height(), this.$gallery.height(i)
    }, r.prototype.setGalleryTempHeight = function (t) {
        i = Math.max(t, i), this.$gallery.height(i)
    }, r.prototype.setGalleryFinalHeight = function (t) {
        i = t, this.$gallery.height(t)
    }, r.prototype.checkWidth = function () {
        this.checkWidthIntervalId = setInterval(g.proxy(function () {
            if (this.$gallery.is(":visible")) {
                var t = parseFloat(this.$gallery.width());
                e() === this.scrollBarOn ? Math.abs(t - this.galleryWidth) > this.settings.refreshSensitivity && (this.galleryWidth = t, this.rewind(), this.rememberGalleryHeight(), this.startImgAnalyzer(!0)) : (this.scrollBarOn = e(), this.galleryWidth = t)
            }
        }, this), this.settings.refreshTime)
    }, r.prototype.isSpinnerActive = function () {
        return null !== this.spinner.intervalId
    }, r.prototype.getSpinnerHeight = function () {
        return this.spinner.$el.innerHeight()
    }, r.prototype.stopLoadingSpinnerAnimation = function () {
        clearInterval(this.spinner.intervalId), this.spinner.intervalId = null, this.setGalleryTempHeight(this.$gallery.height() - this.getSpinnerHeight()), this.spinner.$el.detach()
    }, r.prototype.startLoadingSpinnerAnimation = function () {
        var t = this.spinner, i = t.$el.find("span");
        clearInterval(t.intervalId), this.$gallery.append(t.$el), this.setGalleryTempHeight(this.offY + this.buildingRow.height + this.getSpinnerHeight()), t.intervalId = setInterval(function () {
            t.phase < i.length ? i.eq(t.phase).fadeTo(t.timeSlot, 1) : i.eq(t.phase - i.length).fadeTo(t.timeSlot, 0), t.phase = (t.phase + 1) % (2 * i.length)
        }, t.timeSlot)
    }, r.prototype.rewind = function () {
        this.lastFetchedEntry = null, this.lastAnalyzedIndex = -1, this.offY = this.border, this.rows = 0, this.clearBuildingRow()
    }, r.prototype.updateEntries = function (t) {
        var i;
        return 0 < (i = t && null != this.lastFetchedEntry ? g(this.lastFetchedEntry).nextAll(this.settings.selector).toArray() : (this.entries = [], this.$gallery.children(this.settings.selector).toArray())).length && (g.isFunction(this.settings.sort) ? i = this.sortArray(i) : this.settings.randomize && (i = this.shuffleArray(i)), this.lastFetchedEntry = i[i.length - 1], this.settings.filter ? i = this.filterArray(i) : this.resetFilters(i)), this.entries = this.entries.concat(i), !0
    }, r.prototype.insertToGallery = function (t) {
        var i = this;
        g.each(t, function () {
            g(this).appendTo(i.$gallery)
        })
    }, r.prototype.shuffleArray = function (t) {
        var i, e, s;
        for (i = t.length - 1; 0 < i; i--) e = Math.floor(Math.random() * (i + 1)), s = t[i], t[i] = t[e], t[e] = s;
        return this.insertToGallery(t), t
    }, r.prototype.sortArray = function (t) {
        return t.sort(this.settings.sort), this.insertToGallery(t), t
    }, r.prototype.resetFilters = function (t) {
        for (var i = 0; i < t.length; i++) g(t[i]).removeClass("jg-filtered")
    }, r.prototype.filterArray = function (t) {
        var e = this.settings;
        if ("string" === g.type(e.filter)) return t.filter(function (t) {
            var i = g(t);
            return i.is(e.filter) ? (i.removeClass("jg-filtered"), !0) : (i.addClass("jg-filtered").removeClass("jg-visible"), !1)
        });
        if (g.isFunction(e.filter)) {
            for (var i = t.filter(e.filter), s = 0; s < t.length; s++) -1 === i.indexOf(t[s]) ? g(t[s]).addClass("jg-filtered").removeClass("jg-visible") : g(t[s]).removeClass("jg-filtered");
            return i
        }
    }, r.prototype.destroy = function () {
        clearInterval(this.checkWidthIntervalId), g.each(this.entries, g.proxy(function (t, i) {
            var e = g(i);
            e.css("width", ""), e.css("height", ""), e.css("top", ""), e.css("left", ""), e.data("jg.loaded", void 0), e.removeClass("jg-entry");
            var s = this.imgFromEntry(e);
            s.css("width", ""), s.css("height", ""), s.css("margin-left", ""), s.css("margin-top", ""), s.attr("src", s.data("jg.originalSrc")), s.data("jg.originalSrc", void 0), this.removeCaptionEventsHandlers(e);
            var n = this.captionFromEntry(e);
            e.data("jg.createdCaption") ? (e.data("jg.createdCaption", void 0), null !== n && n.remove()) : null !== n && n.fadeTo(0, 1)
        }, this)), this.$gallery.css("height", ""), this.$gallery.removeClass("justified-gallery"), this.$gallery.data("jg.controller", void 0)
    }, r.prototype.analyzeImages = function (t) {
        for (var i = this.lastAnalyzedIndex + 1; i < this.entries.length; i++) {
            var e = g(this.entries[i]);
            if (!0 === e.data("jg.loaded") || "skipped" === e.data("jg.loaded")) {
                var s = this.galleryWidth - 2 * this.border - (this.buildingRow.entriesBuff.length - 1) * this.settings.margins,
                    n = e.data("jg.width") / e.data("jg.height");
                if (s / (this.buildingRow.aspectRatio + n) < this.settings.rowHeight && (this.flushRow(!1), ++this.yield.flushed >= this.yield.every)) return void this.startImgAnalyzer(t);
                this.buildingRow.entriesBuff.push(e), this.buildingRow.aspectRatio += n, this.buildingRow.width += n * this.settings.rowHeight, this.lastAnalyzedIndex = i
            } else if ("error" !== e.data("jg.loaded")) return
        }
        0 < this.buildingRow.entriesBuff.length && this.flushRow(!0), this.isSpinnerActive() && this.stopLoadingSpinnerAnimation(), this.stopImgAnalyzerStarter(), this.settings.triggerEvent.call(this, t ? "jg.resize" : "jg.complete"), this.setGalleryFinalHeight(this.galleryHeightToSet)
    }, r.prototype.stopImgAnalyzerStarter = function () {
        this.yield.flushed = 0, null !== this.imgAnalyzerTimeout && (clearTimeout(this.imgAnalyzerTimeout), this.imgAnalyzerTimeout = null)
    }, r.prototype.startImgAnalyzer = function (t) {
        var i = this;
        this.stopImgAnalyzerStarter(), this.imgAnalyzerTimeout = setTimeout(function () {
            i.analyzeImages(t)
        }, .001)
    }, r.prototype.onImageEvent = function (t, i, e) {
        if (i || e) {
            var s = new Image, n = g(s);
            i && n.one("load", function () {
                n.off("load error"), i(s)
            }), e && n.one("error", function () {
                n.off("load error"), e(s)
            }), s.src = t
        }
    }, r.prototype.init = function () {
        var a = !1, h = !1, l = this;
        g.each(this.entries, function (t, i) {
            var e = g(i), s = l.imgFromEntry(e);
            if (e.addClass("jg-entry"), !0 !== e.data("jg.loaded") && "skipped" !== e.data("jg.loaded")) if (null !== l.settings.rel && e.attr("rel", l.settings.rel), null !== l.settings.target && e.attr("target", l.settings.target), null !== s) {
                var n = l.extractImgSrcFromImage(s);
                if (s.attr("src", n), !1 === l.settings.waitThumbnailsLoad) {
                    var r = parseFloat(s.prop("width")), o = parseFloat(s.prop("height"));
                    if (!isNaN(r) && !isNaN(o)) return e.data("jg.width", r), e.data("jg.height", o), e.data("jg.loaded", "skipped"), h = !0, l.startImgAnalyzer(!1), !0
                }
                e.data("jg.loaded", !1), a = !0, l.isSpinnerActive() || l.startLoadingSpinnerAnimation(), l.onImageEvent(n, function (t) {
                    e.data("jg.width", t.width), e.data("jg.height", t.height), e.data("jg.loaded", !0), l.startImgAnalyzer(!1)
                }, function () {
                    e.data("jg.loaded", "error"), l.startImgAnalyzer(!1)
                })
            } else e.data("jg.loaded", !0), e.data("jg.width", e.width() | parseFloat(e.css("width")) | 1), e.data("jg.height", e.height() | parseFloat(e.css("height")) | 1)
        }), a || h || this.startImgAnalyzer(!1), this.checkWidth()
    }, r.prototype.checkOrConvertNumber = function (t, i) {
        if ("string" === g.type(t[i]) && (t[i] = parseFloat(t[i])), "number" !== g.type(t[i])) throw i + " must be a number";
        if (isNaN(t[i])) throw"invalid number for " + i
    }, r.prototype.checkSizeRangesSuffixes = function () {
        if ("object" !== g.type(this.settings.sizeRangeSuffixes)) throw"sizeRangeSuffixes must be defined and must be an object";
        var t = [];
        for (var i in this.settings.sizeRangeSuffixes) this.settings.sizeRangeSuffixes.hasOwnProperty(i) && t.push(i);
        for (var e = {0: ""}, s = 0; s < t.length; s++) if ("string" === g.type(t[s])) try {
            e[parseInt(t[s].replace(/^[a-z]+/, ""), 10)] = this.settings.sizeRangeSuffixes[t[s]]
        } catch (t) {
            throw"sizeRangeSuffixes keys must contains correct numbers (" + t + ")"
        } else e[t[s]] = this.settings.sizeRangeSuffixes[t[s]];
        this.settings.sizeRangeSuffixes = e
    }, r.prototype.retrieveMaxRowHeight = function () {
        var t = null, i = this.settings.rowHeight;
        if ("string" === g.type(this.settings.maxRowHeight)) t = this.settings.maxRowHeight.match(/^[0-9]+%$/) ? i * parseFloat(this.settings.maxRowHeight.match(/^([0-9]+)%$/)[1]) / 100 : parseFloat(this.settings.maxRowHeight); else {
            if ("number" !== g.type(this.settings.maxRowHeight)) {
                if (!1 === this.settings.maxRowHeight || null == this.settings.maxRowHeight) return null;
                throw"maxRowHeight must be a number or a percentage"
            }
            t = this.settings.maxRowHeight
        }
        if (isNaN(t)) throw"invalid number for maxRowHeight";
        return t < i && (t = i), t
    }, r.prototype.checkSettings = function () {
        this.checkSizeRangesSuffixes(), this.checkOrConvertNumber(this.settings, "rowHeight"), this.checkOrConvertNumber(this.settings, "margins"), this.checkOrConvertNumber(this.settings, "border");
        var t = ["justify", "nojustify", "left", "center", "right", "hide"];
        if (-1 === t.indexOf(this.settings.lastRow)) throw"lastRow must be one of: " + t.join(", ");
        if (this.checkOrConvertNumber(this.settings, "justifyThreshold"), this.settings.justifyThreshold < 0 || 1 < this.settings.justifyThreshold) throw"justifyThreshold must be in the interval [0,1]";
        if ("boolean" !== g.type(this.settings.cssAnimation)) throw"cssAnimation must be a boolean";
        if ("boolean" !== g.type(this.settings.captions)) throw"captions must be a boolean";
        if (this.checkOrConvertNumber(this.settings.captionSettings, "animationDuration"), this.checkOrConvertNumber(this.settings.captionSettings, "visibleOpacity"), this.settings.captionSettings.visibleOpacity < 0 || 1 < this.settings.captionSettings.visibleOpacity) throw"captionSettings.visibleOpacity must be in the interval [0, 1]";
        if (this.checkOrConvertNumber(this.settings.captionSettings, "nonVisibleOpacity"), this.settings.captionSettings.nonVisibleOpacity < 0 || 1 < this.settings.captionSettings.nonVisibleOpacity) throw"captionSettings.nonVisibleOpacity must be in the interval [0, 1]";
        if (this.checkOrConvertNumber(this.settings, "imagesAnimationDuration"), this.checkOrConvertNumber(this.settings, "refreshTime"), this.checkOrConvertNumber(this.settings, "refreshSensitivity"), "boolean" !== g.type(this.settings.randomize)) throw"randomize must be a boolean";
        if ("string" !== g.type(this.settings.selector)) throw"selector must be a string";
        if (!1 !== this.settings.sort && !g.isFunction(this.settings.sort)) throw"sort must be false or a comparison function";
        if (!1 !== this.settings.filter && !g.isFunction(this.settings.filter) && "string" !== g.type(this.settings.filter)) throw"filter must be false, a string or a filter function"
    }, r.prototype.retrieveSuffixRanges = function () {
        var t = [];
        for (var i in this.settings.sizeRangeSuffixes) this.settings.sizeRangeSuffixes.hasOwnProperty(i) && t.push(parseInt(i, 10));
        return t.sort(function (t, i) {
            return i < t ? 1 : t < i ? -1 : 0
        }), t
    }, r.prototype.updateSettings = function (t) {
        this.settings = g.extend({}, this.settings, t), this.checkSettings(), this.border = 0 <= this.settings.border ? this.settings.border : this.settings.margins, this.maxRowHeight = this.retrieveMaxRowHeight(), this.suffixRanges = this.retrieveSuffixRanges()
    }, r.prototype.defaults = {
        sizeRangeSuffixes: {},
        thumbnailPath: void 0,
        rowHeight: 120,
        maxRowHeight: !1,
        margins: 1,
        border: -1,
        lastRow: "nojustify",
        justifyThreshold: .9,
        waitThumbnailsLoad: !0,
        captions: !0,
        cssAnimation: !0,
        imagesAnimationDuration: 500,
        captionSettings: {animationDuration: 500, visibleOpacity: .7, nonVisibleOpacity: 0},
        rel: null,
        target: null,
        extension: /\.[^.\\/]+$/,
        refreshTime: 200,
        refreshSensitivity: 0,
        randomize: !1,
        rtl: !1,
        sort: !1,
        filter: !1,
        selector: "a, div:not(.spinner)",
        imgSelector: "> img, > a > img",
        triggerEvent: function (t) {
            this.$gallery.trigger(t)
        }
    }, g.fn.justifiedGallery = function (n) {
        return this.each(function (t, i) {
            var e = g(i);
            e.addClass("justified-gallery");
            var s = e.data("jg.controller");
            if (void 0 === s) {
                if (null != n && "object" !== g.type(n)) {
                    if ("destroy" === n) return;
                    throw"The argument must be an object"
                }
                s = new r(e, g.extend({}, r.prototype.defaults, n)), e.data("jg.controller", s)
            } else if ("norewind" === n) ; else {
                if ("destroy" === n) return void s.destroy();
                s.updateSettings(n), s.rewind()
            }
            s.updateEntries("norewind" === n) && s.init()
        })
    }
});
!function (e, t) {
    "function" == typeof define && define.amd ? define(t) : "object" == typeof exports ? module.exports = t() : e.PhotoSwipe = t()
}(this, function () {
    "use strict";
    return function (m, i, e, t) {
        var f = {
            features: null, bind: function (e, t, n, i) {
                var o = (i ? "remove" : "add") + "EventListener";
                t = t.split(" ");
                for (var a = 0; a < t.length; a++) t[a] && e[o](t[a], n, !1)
            }, isArray: function (e) {
                return e instanceof Array
            }, createEl: function (e, t) {
                var n = document.createElement(t || "div");
                return e && (n.className = e), n
            }, getScrollY: function () {
                var e = window.pageYOffset;
                return void 0 !== e ? e : document.documentElement.scrollTop
            }, unbind: function (e, t, n) {
                f.bind(e, t, n, !0)
            }, removeClass: function (e, t) {
                var n = new RegExp("(\\s|^)" + t + "(\\s|$)");
                e.className = e.className.replace(n, " ").replace(/^\s\s*/, "").replace(/\s\s*$/, "")
            }, addClass: function (e, t) {
                f.hasClass(e, t) || (e.className += (e.className ? " " : "") + t)
            }, hasClass: function (e, t) {
                return e.className && new RegExp("(^|\\s)" + t + "(\\s|$)").test(e.className)
            }, getChildByClass: function (e, t) {
                for (var n = e.firstChild; n;) {
                    if (f.hasClass(n, t)) return n;
                    n = n.nextSibling
                }
            }, arraySearch: function (e, t, n) {
                for (var i = e.length; i--;) if (e[i][n] === t) return i;
                return -1
            }, extend: function (e, t, n) {
                for (var i in t) if (t.hasOwnProperty(i)) {
                    if (n && e.hasOwnProperty(i)) continue;
                    e[i] = t[i]
                }
            }, easing: {
                sine: {
                    out: function (e) {
                        return Math.sin(e * (Math.PI / 2))
                    }, inOut: function (e) {
                        return -(Math.cos(Math.PI * e) - 1) / 2
                    }
                }, cubic: {
                    out: function (e) {
                        return --e * e * e + 1
                    }
                }
            }, detectFeatures: function () {
                if (f.features) return f.features;
                var e = f.createEl().style, t = "", n = {};
                if (n.oldIE = document.all && !document.addEventListener, n.touch = "ontouchstart" in window, window.requestAnimationFrame && (n.raf = window.requestAnimationFrame, n.caf = window.cancelAnimationFrame), n.pointerEvent = navigator.pointerEnabled || navigator.msPointerEnabled, !n.pointerEvent) {
                    var i = navigator.userAgent;
                    if (/iP(hone|od)/.test(navigator.platform)) {
                        var o = navigator.appVersion.match(/OS (\d+)_(\d+)_?(\d+)?/);
                        o && 0 < o.length && 1 <= (o = parseInt(o[1], 10)) && o < 8 && (n.isOldIOSPhone = !0)
                    }
                    var a = i.match(/Android\s([0-9\.]*)/), r = a ? a[1] : 0;
                    1 <= (r = parseFloat(r)) && (r < 4.4 && (n.isOldAndroid = !0), n.androidVersion = r), n.isMobileOpera = /opera mini|opera mobi/i.test(i)
                }
                for (var l, s, u = ["transform", "perspective", "animationName"], c = ["", "webkit", "Moz", "ms", "O"], d = 0; d < 4; d++) {
                    t = c[d];
                    for (var p = 0; p < 3; p++) l = u[p], s = t + (t ? l.charAt(0).toUpperCase() + l.slice(1) : l), !n[l] && s in e && (n[l] = s);
                    t && !n.raf && (t = t.toLowerCase(), n.raf = window[t + "RequestAnimationFrame"], n.raf && (n.caf = window[t + "CancelAnimationFrame"] || window[t + "CancelRequestAnimationFrame"]))
                }
                if (!n.raf) {
                    var m = 0;
                    n.raf = function (e) {
                        var t = (new Date).getTime(), n = Math.max(0, 16 - (t - m)), i = window.setTimeout(function () {
                            e(t + n)
                        }, n);
                        return m = t + n, i
                    }, n.caf = function (e) {
                        clearTimeout(e)
                    }
                }
                return n.svg = !!document.createElementNS && !!document.createElementNS("http://www.w3.org/2000/svg", "svg").createSVGRect, f.features = n
            }
        };
        f.detectFeatures(), f.features.oldIE && (f.bind = function (e, t, n, i) {
            t = t.split(" ");
            for (var o, a = (i ? "detach" : "attach") + "Event", r = function () {
                n.handleEvent.call(n)
            }, l = 0; l < t.length; l++) if (o = t[l]) if ("object" == typeof n && n.handleEvent) {
                if (i) {
                    if (!n["oldIE" + o]) return !1
                } else n["oldIE" + o] = r;
                e[a]("on" + o, n["oldIE" + o])
            } else e[a]("on" + o, n)
        });
        var h = this, y = {
            allowPanToNext: !0,
            spacing: .12,
            bgOpacity: 1,
            mouseUsed: !1,
            loop: !0,
            pinchToClose: !0,
            closeOnScroll: !0,
            closeOnVerticalDrag: !0,
            verticalDragRange: .75,
            hideAnimationDuration: 333,
            showAnimationDuration: 333,
            showHideOpacity: !1,
            focus: !0,
            escKey: !0,
            arrowKeys: !0,
            mainScrollEndFriction: .35,
            panEndFriction: .35,
            isClickableElement: function (e) {
                return "A" === e.tagName
            },
            getDoubleTapZoom: function (e, t) {
                return e ? 1 : t.initialZoomLevel < .7 ? 1 : 1.33
            },
            maxSpreadZoom: 1.33,
            modal: !0,
            scaleMode: "fit"
        };
        f.extend(y, t);
        var s, o, a, x, r, l, u, c, d, v, p, g, w, b, I, C, D, T, M, S, A, E, O, k, R, Z, P, F, L, z, _, N, U, H, Y, W,
            B, G, X, V, K, q, n, $, j, J, Q, ee, te, ne, ie, oe, ae, re, le, se, ue = {x: 0, y: 0}, ce = {x: 0, y: 0},
            de = {x: 0, y: 0}, pe = {}, me = 0, fe = {}, he = {x: 0, y: 0}, ye = 0, xe = !0, ve = [], ge = {}, we = !1,
            be = function (e, t) {
                f.extend(h, t.publicMethods), ve.push(e)
            }, Ie = function (e) {
                var t = Ht();
                return t - 1 < e ? e - t : e < 0 ? t + e : e
            }, Ce = {}, De = function (e, t) {
                return Ce[e] || (Ce[e] = []), Ce[e].push(t)
            }, Te = function (e) {
                var t = Ce[e];
                if (t) {
                    var n = Array.prototype.slice.call(arguments);
                    n.shift();
                    for (var i = 0; i < t.length; i++) t[i].apply(h, n)
                }
            }, Me = function () {
                return (new Date).getTime()
            }, Se = function (e) {
                re = e, h.bg.style.opacity = e * y.bgOpacity
            }, Ae = function (e, t, n, i, o) {
                (!we || o && o !== h.currItem) && (i /= o ? o.fitRatio : h.currItem.fitRatio), e[E] = g + t + "px, " + n + "px" + w + " scale(" + i + ")"
            }, Ee = function (e) {
                te && (e && (v > h.currItem.fitRatio ? we || ($t(h.currItem, !1, !0), we = !0) : we && ($t(h.currItem), we = !1)), Ae(te, de.x, de.y, v))
            }, Oe = function (e) {
                e.container && Ae(e.container.style, e.initialPosition.x, e.initialPosition.y, e.initialZoomLevel, e)
            }, ke = function (e, t) {
                t[E] = g + e + "px, 0px" + w
            }, Re = function (e, t) {
                if (!y.loop && t) {
                    var n = x + (he.x * me - e) / he.x, i = Math.round(e - ct.x);
                    (n < 0 && 0 < i || n >= Ht() - 1 && i < 0) && (e = ct.x + i * y.mainScrollEndFriction)
                }
                ct.x = e, ke(e, r)
            }, Ze = function (e, t) {
                var n = dt[e] - fe[e];
                return ce[e] + ue[e] + n - n * (t / p)
            }, Pe = function (e, t) {
                e.x = t.x, e.y = t.y, t.id && (e.id = t.id)
            }, Fe = function (e) {
                e.x = Math.round(e.x), e.y = Math.round(e.y)
            }, Le = null, ze = function () {
                Le && (f.unbind(document, "mousemove", ze), f.addClass(m, "pswp--has_mouse"), y.mouseUsed = !0, Te("mouseUsed")), Le = setTimeout(function () {
                    Le = null
                }, 100)
            }, _e = function (e, t) {
                var n = Xt(h.currItem, pe, e);
                return t && (ee = n), n
            }, Ne = function (e) {
                return e || (e = h.currItem), e.initialZoomLevel
            }, Ue = function (e) {
                return e || (e = h.currItem), 0 < e.w ? y.maxSpreadZoom : 1
            }, He = function (e, t, n, i) {
                return i === h.currItem.initialZoomLevel ? (n[e] = h.currItem.initialPosition[e], !0) : (n[e] = Ze(e, i), n[e] > t.min[e] ? (n[e] = t.min[e], !0) : n[e] < t.max[e] && (n[e] = t.max[e], !0))
            }, Ye = function (e) {
                var t = "";
                y.escKey && 27 === e.keyCode ? t = "close" : y.arrowKeys && (37 === e.keyCode ? t = "prev" : 39 === e.keyCode && (t = "next")), t && (e.ctrlKey || e.altKey || e.shiftKey || e.metaKey || (e.preventDefault ? e.preventDefault() : e.returnValue = !1, h[t]()))
            }, We = function (e) {
                e && (q || K || ne || B) && (e.preventDefault(), e.stopPropagation())
            }, Be = function () {
                h.setScrollOffset(0, f.getScrollY())
            }, Ge = {}, Xe = 0, Ve = function (e) {
                Ge[e] && (Ge[e].raf && Z(Ge[e].raf), Xe--, delete Ge[e])
            }, Ke = function (e) {
                Ge[e] && Ve(e), Ge[e] || (Xe++, Ge[e] = {})
            }, qe = function () {
                for (var e in Ge) Ge.hasOwnProperty(e) && Ve(e)
            }, $e = function (e, t, n, i, o, a, r) {
                var l, s = Me();
                Ke(e);
                var u = function () {
                    if (Ge[e]) {
                        if (l = Me() - s, i <= l) return Ve(e), a(n), void(r && r());
                        a((n - t) * o(l / i) + t), Ge[e].raf = R(u)
                    }
                };
                u()
            }, je = {
                shout: Te, listen: De, viewportSize: pe, options: y, isMainScrollAnimating: function () {
                    return ne
                }, getZoomLevel: function () {
                    return v
                }, getCurrentIndex: function () {
                    return x
                }, isDragging: function () {
                    return X
                }, isZooming: function () {
                    return J
                }, setScrollOffset: function (e, t) {
                    fe.x = e, z = fe.y = t, Te("updateScrollOffset", fe)
                }, applyZoomPan: function (e, t, n, i) {
                    de.x = t, de.y = n, v = e, Ee(i)
                }, init: function () {
                    if (!s && !o) {
                        var e;
                        h.framework = f, h.template = m, h.bg = f.getChildByClass(m, "pswp__bg"), P = m.className, s = !0, _ = f.detectFeatures(), R = _.raf, Z = _.caf, E = _.transform, L = _.oldIE, h.scrollWrap = f.getChildByClass(m, "pswp__scroll-wrap"), h.container = f.getChildByClass(h.scrollWrap, "pswp__container"), r = h.container.style, h.itemHolders = C = [{
                            el: h.container.children[0],
                            wrap: 0,
                            index: -1
                        }, {el: h.container.children[1], wrap: 0, index: -1}, {
                            el: h.container.children[2],
                            wrap: 0,
                            index: -1
                        }], C[0].el.style.display = C[2].el.style.display = "none", function () {
                            if (E) {
                                var e = _.perspective && !k;
                                return g = "translate" + (e ? "3d(" : "("), w = _.perspective ? ", 0px)" : ")"
                            }
                            E = "left", f.addClass(m, "pswp--ie"), ke = function (e, t) {
                                t.left = e + "px"
                            }, Oe = function (e) {
                                var t = 1 < e.fitRatio ? 1 : e.fitRatio, n = e.container.style, i = t * e.w, o = t * e.h;
                                n.width = i + "px", n.height = o + "px", n.left = e.initialPosition.x + "px", n.top = e.initialPosition.y + "px"
                            }, Ee = function () {
                                if (te) {
                                    var e = te, t = h.currItem, n = 1 < t.fitRatio ? 1 : t.fitRatio, i = n * t.w,
                                        o = n * t.h;
                                    e.width = i + "px", e.height = o + "px", e.left = de.x + "px", e.top = de.y + "px"
                                }
                            }
                        }(), d = {
                            resize: h.updateSize, orientationchange: function () {
                                clearTimeout(N), N = setTimeout(function () {
                                    pe.x !== h.scrollWrap.clientWidth && h.updateSize()
                                }, 500)
                            }, scroll: Be, keydown: Ye, click: We
                        };
                        var t = _.isOldIOSPhone || _.isOldAndroid || _.isMobileOpera;
                        for (_.animationName && _.transform && !t || (y.showAnimationDuration = y.hideAnimationDuration = 0), e = 0; e < ve.length; e++) h["init" + ve[e]]();
                        i && (h.ui = new i(h, f)).init(), Te("firstUpdate"), x = x || y.index || 0, (isNaN(x) || x < 0 || x >= Ht()) && (x = 0), h.currItem = Ut(x), (_.isOldIOSPhone || _.isOldAndroid) && (xe = !1), m.setAttribute("aria-hidden", "false"), y.modal && (xe ? m.style.position = "fixed" : (m.style.position = "absolute", m.style.top = f.getScrollY() + "px")), void 0 === z && (Te("initialLayout"), z = F = f.getScrollY());
                        var n = "pswp--open ";
                        for (y.mainClass && (n += y.mainClass + " "), y.showHideOpacity && (n += "pswp--animate_opacity "), n += k ? "pswp--touch" : "pswp--notouch", n += _.animationName ? " pswp--css_animation" : "", n += _.svg ? " pswp--svg" : "", f.addClass(m, n), h.updateSize(), l = -1, ye = null, e = 0; e < 3; e++) ke((e + l) * he.x, C[e].el.style);
                        L || f.bind(h.scrollWrap, c, h), De("initialZoomInEnd", function () {
                            h.setContent(C[0], x - 1), h.setContent(C[2], x + 1), C[0].el.style.display = C[2].el.style.display = "block", y.focus && m.focus(), f.bind(document, "keydown", h), _.transform && f.bind(h.scrollWrap, "click", h), y.mouseUsed || f.bind(document, "mousemove", ze), f.bind(window, "resize scroll orientationchange", h), Te("bindEvents")
                        }), h.setContent(C[1], x), h.updateCurrItem(), Te("afterInit"), xe || (b = setInterval(function () {
                            Xe || X || J || v !== h.currItem.initialZoomLevel || h.updateSize()
                        }, 1e3)), f.addClass(m, "pswp--visible")
                    }
                }, close: function () {
                    s && (o = !(s = !1), Te("close"), f.unbind(window, "resize scroll orientationchange", h), f.unbind(window, "scroll", d.scroll), f.unbind(document, "keydown", h), f.unbind(document, "mousemove", ze), _.transform && f.unbind(h.scrollWrap, "click", h), X && f.unbind(window, u, h), clearTimeout(N), Te("unbindEvents"), Yt(h.currItem, null, !0, h.destroy))
                }, destroy: function () {
                    Te("destroy"), Lt && clearTimeout(Lt), m.setAttribute("aria-hidden", "true"), m.className = P, b && clearInterval(b), f.unbind(h.scrollWrap, c, h), f.unbind(window, "scroll", h), ft(), qe(), Ce = null
                }, panTo: function (e, t, n) {
                    n || (e > ee.min.x ? e = ee.min.x : e < ee.max.x && (e = ee.max.x), t > ee.min.y ? t = ee.min.y : t < ee.max.y && (t = ee.max.y)), de.x = e, de.y = t, Ee()
                }, handleEvent: function (e) {
                    e = e || window.event, d[e.type] && d[e.type](e)
                }, goTo: function (e) {
                    var t = (e = Ie(e)) - x;
                    ye = t, x = e, h.currItem = Ut(x), me -= t, Re(he.x * me), qe(), ne = !1, h.updateCurrItem()
                }, next: function () {
                    h.goTo(x + 1)
                }, prev: function () {
                    h.goTo(x - 1)
                }, updateCurrZoomItem: function (e) {
                    if (e && Te("beforeChange", 0), C[1].el.children.length) {
                        var t = C[1].el.children[0];
                        te = f.hasClass(t, "pswp__zoom-wrap") ? t.style : null
                    } else te = null;
                    ee = h.currItem.bounds, p = v = h.currItem.initialZoomLevel, de.x = ee.center.x, de.y = ee.center.y, e && Te("afterChange")
                }, invalidateCurrItems: function () {
                    I = !0;
                    for (var e = 0; e < 3; e++) C[e].item && (C[e].item.needsUpdate = !0)
                }, updateCurrItem: function (e) {
                    if (0 !== ye) {
                        var t, n = Math.abs(ye);
                        if (!(e && n < 2)) {
                            h.currItem = Ut(x), we = !1, Te("beforeChange", ye), 3 <= n && (l += ye + (0 < ye ? -3 : 3), n = 3);
                            for (var i = 0; i < n; i++) 0 < ye ? (t = C.shift(), C[2] = t, ke((++l + 2) * he.x, t.el.style), h.setContent(t, x - n + i + 1 + 1)) : (t = C.pop(), C.unshift(t), ke(--l * he.x, t.el.style), h.setContent(t, x + n - i - 1 - 1));
                            if (te && 1 === Math.abs(ye)) {
                                var o = Ut(D);
                                o.initialZoomLevel !== v && (Xt(o, pe), $t(o), Oe(o))
                            }
                            ye = 0, h.updateCurrZoomItem(), D = x, Te("afterChange")
                        }
                    }
                }, updateSize: function (e) {
                    if (!xe && y.modal) {
                        var t = f.getScrollY();
                        if (z !== t && (m.style.top = t + "px", z = t), !e && ge.x === window.innerWidth && ge.y === window.innerHeight) return;
                        ge.x = window.innerWidth, ge.y = window.innerHeight, m.style.height = ge.y + "px"
                    }
                    if (pe.x = h.scrollWrap.clientWidth, pe.y = h.scrollWrap.clientHeight, Be(), he.x = pe.x + Math.round(pe.x * y.spacing), he.y = pe.y, Re(he.x * me), Te("beforeResize"), void 0 !== l) {
                        for (var n, i, o, a = 0; a < 3; a++) n = C[a], ke((a + l) * he.x, n.el.style), o = x + a - 1, y.loop && 2 < Ht() && (o = Ie(o)), (i = Ut(o)) && (I || i.needsUpdate || !i.bounds) ? (h.cleanSlide(i), h.setContent(n, o), 1 === a && (h.currItem = i, h.updateCurrZoomItem(!0)), i.needsUpdate = !1) : -1 === n.index && 0 <= o && h.setContent(n, o), i && i.container && (Xt(i, pe), $t(i), Oe(i));
                        I = !1
                    }
                    p = v = h.currItem.initialZoomLevel, (ee = h.currItem.bounds) && (de.x = ee.center.x, de.y = ee.center.y, Ee(!0)), Te("resize")
                }, zoomTo: function (t, e, n, i, o) {
                    e && (p = v, dt.x = Math.abs(e.x) - de.x, dt.y = Math.abs(e.y) - de.y, Pe(ce, de));
                    var a = _e(t, !1), r = {};
                    He("x", a, r, t), He("y", a, r, t);
                    var l = v, s = de.x, u = de.y;
                    Fe(r);
                    var c = function (e) {
                        de.y = 1 === e ? (v = t, de.x = r.x, r.y) : (v = (t - l) * e + l, de.x = (r.x - s) * e + s, (r.y - u) * e + u), o && o(e), Ee(1 === e)
                    };
                    n ? $e("customZoomTo", 0, 1, n, i || f.easing.sine.inOut, c) : c(1)
                }
            }, Je = {}, Qe = {}, et = {}, tt = {}, nt = {}, it = [], ot = {}, at = [], rt = {}, lt = 0, st = {x: 0, y: 0},
            ut = 0, ct = {x: 0, y: 0}, dt = {x: 0, y: 0}, pt = {x: 0, y: 0}, mt = function (e, t) {
                return rt.x = Math.abs(e.x - t.x), rt.y = Math.abs(e.y - t.y), Math.sqrt(rt.x * rt.x + rt.y * rt.y)
            }, ft = function () {
                n && (Z(n), n = null)
            }, ht = function () {
                X && (n = R(ht), Et())
            }, yt = function (e, t) {
                return !(!e || e === document) && !(e.getAttribute("class") && -1 < e.getAttribute("class").indexOf("pswp__scroll-wrap")) && (t(e) ? e : yt(e.parentNode, t))
            }, xt = {}, vt = function (e, t) {
                return xt.prevent = !yt(e.target, y.isClickableElement), Te("preventDragEvent", e, t, xt), xt.prevent
            }, gt = function (e, t) {
                return t.x = e.pageX, t.y = e.pageY, t.id = e.identifier, t
            }, wt = function (e, t, n) {
                n.x = .5 * (e.x + t.x), n.y = .5 * (e.y + t.y)
            }, bt = function () {
                var e = de.y - h.currItem.initialPosition.y;
                return 1 - Math.abs(e / (pe.y / 2))
            }, It = {}, Ct = {}, Dt = [], Tt = function (e) {
                for (; 0 < Dt.length;) Dt.pop();
                return O ? (se = 0, it.forEach(function (e) {
                    0 === se ? Dt[0] = e : 1 === se && (Dt[1] = e), se++
                })) : -1 < e.type.indexOf("touch") ? e.touches && 0 < e.touches.length && (Dt[0] = gt(e.touches[0], It), 1 < e.touches.length && (Dt[1] = gt(e.touches[1], Ct))) : (It.x = e.pageX, It.y = e.pageY, It.id = "", Dt[0] = It), Dt
            }, Mt = function (e, t) {
                var n, i, o, a, r = de[e] + t[e], l = 0 < t[e], s = ct.x + t.x, u = ct.x - ot.x;
                return n = r > ee.min[e] || r < ee.max[e] ? y.panEndFriction : 1, r = de[e] + t[e] * n, !y.allowPanToNext && v !== h.currItem.initialZoomLevel || (te ? "h" !== ie || "x" !== e || K || (l ? (r > ee.min[e] && (n = y.panEndFriction, ee.min[e], i = ee.min[e] - ce[e]), (i <= 0 || u < 0) && 1 < Ht() ? (a = s, u < 0 && s > ot.x && (a = ot.x)) : ee.min.x !== ee.max.x && (o = r)) : (r < ee.max[e] && (n = y.panEndFriction, ee.max[e], i = ce[e] - ee.max[e]), (i <= 0 || 0 < u) && 1 < Ht() ? (a = s, 0 < u && s < ot.x && (a = ot.x)) : ee.min.x !== ee.max.x && (o = r))) : a = s, "x" !== e) ? void(ne || $ || v > h.currItem.fitRatio && (de[e] += t[e] * n)) : (void 0 !== a && (Re(a, !0), $ = a !== ot.x), ee.min.x !== ee.max.x && (void 0 !== o ? de.x = o : $ || (de.x += t.x * n)), void 0 !== a)
            }, St = function (e) {
                if (!("mousedown" === e.type && 0 < e.button)) {
                    if (Nt) return void e.preventDefault();
                    if (!G || "mousedown" !== e.type) {
                        if (vt(e, !0) && e.preventDefault(), Te("pointerDown"), O) {
                            var t = f.arraySearch(it, e.pointerId, "id");
                            t < 0 && (t = it.length), it[t] = {x: e.pageX, y: e.pageY, id: e.pointerId}
                        }
                        var n = Tt(e), i = n.length;
                        j = null, qe(), X && 1 !== i || (X = oe = !0, f.bind(window, u, h), W = le = ae = B = $ = q = V = K = !1, ie = null, Te("firstTouchStart", n), Pe(ce, de), ue.x = ue.y = 0, Pe(tt, n[0]), Pe(nt, tt), ot.x = he.x * me, at = [{
                            x: tt.x,
                            y: tt.y
                        }], H = U = Me(), _e(v, !0), ft(), ht()), !J && 1 < i && !ne && !$ && (p = v, J = V = !(K = !1), ue.y = ue.x = 0, Pe(ce, de), Pe(Je, n[0]), Pe(Qe, n[1]), wt(Je, Qe, pt), dt.x = Math.abs(pt.x) - de.x, dt.y = Math.abs(pt.y) - de.y, Q = mt(Je, Qe))
                    }
                }
            }, At = function (e) {
                if (e.preventDefault(), O) {
                    var t = f.arraySearch(it, e.pointerId, "id");
                    if (-1 < t) {
                        var n = it[t];
                        n.x = e.pageX, n.y = e.pageY
                    }
                }
                if (X) {
                    var i = Tt(e);
                    if (ie || q || J) j = i; else if (ct.x !== he.x * me) ie = "h"; else {
                        var o = Math.abs(i[0].x - tt.x) - Math.abs(i[0].y - tt.y);
                        10 <= Math.abs(o) && (ie = 0 < o ? "h" : "v", j = i)
                    }
                }
            }, Et = function () {
                if (j) {
                    var e = j.length;
                    if (0 !== e) if (Pe(Je, j[0]), et.x = Je.x - tt.x, et.y = Je.y - tt.y, J && 1 < e) {
                        if (tt.x = Je.x, tt.y = Je.y, !et.x && !et.y && (s = j[1], u = Qe, s.x === u.x && s.y === u.y)) return;
                        Pe(Qe, j[1]), K || (K = !0, Te("zoomGestureStarted"));
                        var t = mt(Je, Qe), n = Pt(t);
                        n > h.currItem.initialZoomLevel + h.currItem.initialZoomLevel / 15 && (le = !0);
                        var i = 1, o = Ne(), a = Ue();
                        if (n < o) if (y.pinchToClose && !le && p <= h.currItem.initialZoomLevel) {
                            var r = 1 - (o - n) / (o / 1.2);
                            Se(r), Te("onPinchClose", r), ae = !0
                        } else 1 < (i = (o - n) / o) && (i = 1), n = o - i * (o / 3); else a < n && (1 < (i = (n - a) / (6 * o)) && (i = 1), n = a + i * o);
                        i < 0 && (i = 0), wt(Je, Qe, st), ue.x += st.x - pt.x, ue.y += st.y - pt.y, Pe(pt, st), de.x = Ze("x", n), de.y = Ze("y", n), W = v < n, v = n, Ee()
                    } else {
                        if (!ie) return;
                        if (oe && (oe = !1, 10 <= Math.abs(et.x) && (et.x -= j[0].x - nt.x), 10 <= Math.abs(et.y) && (et.y -= j[0].y - nt.y)), tt.x = Je.x, tt.y = Je.y, 0 === et.x && 0 === et.y) return;
                        if ("v" === ie && y.closeOnVerticalDrag && "fit" === y.scaleMode && v === h.currItem.initialZoomLevel) {
                            ue.y += et.y, de.y += et.y;
                            var l = bt();
                            return B = !0, Te("onVerticalDrag", l), Se(l), void Ee()
                        }
                        (function (e, t, n) {
                            if (50 < e - H) {
                                var i = 2 < at.length ? at.shift() : {};
                                i.x = t, i.y = n, at.push(i), H = e
                            }
                        })(Me(), Je.x, Je.y), q = !0, ee = h.currItem.bounds, Mt("x", et) || (Mt("y", et), Fe(de), Ee())
                    }
                }
                var s, u
            }, Ot = function (e) {
                if (_.isOldAndroid) {
                    if (G && "mouseup" === e.type) return;
                    -1 < e.type.indexOf("touch") && (clearTimeout(G), G = setTimeout(function () {
                        G = 0
                    }, 600))
                }
                var t;
                if (Te("pointerUp"), vt(e, !1) && e.preventDefault(), O) {
                    var n = f.arraySearch(it, e.pointerId, "id");
                    -1 < n && (t = it.splice(n, 1)[0], navigator.pointerEnabled ? t.type = e.pointerType || "mouse" : (t.type = {
                        4: "mouse",
                        2: "touch",
                        3: "pen"
                    }[e.pointerType], t.type || (t.type = e.pointerType || "mouse")))
                }
                var i, o = Tt(e), a = o.length;
                if ("mouseup" === e.type && (a = 0), 2 === a) return !(j = null);
                1 === a && Pe(nt, o[0]), 0 !== a || ie || ne || (t || ("mouseup" === e.type ? t = {
                    x: e.pageX,
                    y: e.pageY,
                    type: "mouse"
                } : e.changedTouches && e.changedTouches[0] && (t = {
                    x: e.changedTouches[0].pageX,
                    y: e.changedTouches[0].pageY,
                    type: "touch"
                })), Te("touchRelease", e, t));
                var r = -1;
                if (0 === a && (X = !1, f.unbind(window, u, h), ft(), J ? r = 0 : -1 !== ut && (r = Me() - ut)), ut = 1 === a ? Me() : -1, i = -1 !== r && r < 150 ? "zoom" : "swipe", J && a < 2 && (J = !1, 1 === a && (i = "zoomPointerUp"), Te("zoomGestureEnded")), j = null, q || K || ne || B) if (qe(), Y || (Y = kt()), Y.calculateSwipeSpeed("x"), B) if (bt() < y.verticalDragRange) h.close(); else {
                    var l = de.y, s = re;
                    $e("verticalDrag", 0, 1, 300, f.easing.cubic.out, function (e) {
                        de.y = (h.currItem.initialPosition.y - l) * e + l, Se((1 - s) * e + s), Ee()
                    }), Te("onVerticalDrag", 1)
                } else {
                    if (($ || ne) && 0 === a) {
                        if (Zt(i, Y)) return;
                        i = "zoomPointerUp"
                    }
                    if (!ne) return "swipe" !== i ? void Ft() : void(!$ && v > h.currItem.fitRatio && Rt(Y))
                }
            }, kt = function () {
                var t, n, i = {
                    lastFlickOffset: {},
                    lastFlickDist: {},
                    lastFlickSpeed: {},
                    slowDownRatio: {},
                    slowDownRatioReverse: {},
                    speedDecelerationRatio: {},
                    speedDecelerationRatioAbs: {},
                    distanceOffset: {},
                    backAnimDestination: {},
                    backAnimStarted: {},
                    calculateSwipeSpeed: function (e) {
                        n = 1 < at.length ? (t = Me() - H + 50, at[at.length - 2][e]) : (t = Me() - U, nt[e]), i.lastFlickOffset[e] = tt[e] - n, i.lastFlickDist[e] = Math.abs(i.lastFlickOffset[e]), 20 < i.lastFlickDist[e] ? i.lastFlickSpeed[e] = i.lastFlickOffset[e] / t : i.lastFlickSpeed[e] = 0, Math.abs(i.lastFlickSpeed[e]) < .1 && (i.lastFlickSpeed[e] = 0), i.slowDownRatio[e] = .95, i.slowDownRatioReverse[e] = 1 - i.slowDownRatio[e], i.speedDecelerationRatio[e] = 1
                    },
                    calculateOverBoundsAnimOffset: function (t, e) {
                        i.backAnimStarted[t] || (de[t] > ee.min[t] ? i.backAnimDestination[t] = ee.min[t] : de[t] < ee.max[t] && (i.backAnimDestination[t] = ee.max[t]), void 0 !== i.backAnimDestination[t] && (i.slowDownRatio[t] = .7, i.slowDownRatioReverse[t] = 1 - i.slowDownRatio[t], i.speedDecelerationRatioAbs[t] < .05 && (i.lastFlickSpeed[t] = 0, i.backAnimStarted[t] = !0, $e("bounceZoomPan" + t, de[t], i.backAnimDestination[t], e || 300, f.easing.sine.out, function (e) {
                            de[t] = e, Ee()
                        }))))
                    },
                    calculateAnimOffset: function (e) {
                        i.backAnimStarted[e] || (i.speedDecelerationRatio[e] = i.speedDecelerationRatio[e] * (i.slowDownRatio[e] + i.slowDownRatioReverse[e] - i.slowDownRatioReverse[e] * i.timeDiff / 10), i.speedDecelerationRatioAbs[e] = Math.abs(i.lastFlickSpeed[e] * i.speedDecelerationRatio[e]), i.distanceOffset[e] = i.lastFlickSpeed[e] * i.speedDecelerationRatio[e] * i.timeDiff, de[e] += i.distanceOffset[e])
                    },
                    panAnimLoop: function () {
                        if (Ge.zoomPan && (Ge.zoomPan.raf = R(i.panAnimLoop), i.now = Me(), i.timeDiff = i.now - i.lastNow, i.lastNow = i.now, i.calculateAnimOffset("x"), i.calculateAnimOffset("y"), Ee(), i.calculateOverBoundsAnimOffset("x"), i.calculateOverBoundsAnimOffset("y"), i.speedDecelerationRatioAbs.x < .05 && i.speedDecelerationRatioAbs.y < .05)) return de.x = Math.round(de.x), de.y = Math.round(de.y), Ee(), void Ve("zoomPan")
                    }
                };
                return i
            }, Rt = function (e) {
                return e.calculateSwipeSpeed("y"), ee = h.currItem.bounds, e.backAnimDestination = {}, e.backAnimStarted = {}, Math.abs(e.lastFlickSpeed.x) <= .05 && Math.abs(e.lastFlickSpeed.y) <= .05 ? (e.speedDecelerationRatioAbs.x = e.speedDecelerationRatioAbs.y = 0, e.calculateOverBoundsAnimOffset("x"), e.calculateOverBoundsAnimOffset("y"), !0) : (Ke("zoomPan"), e.lastNow = Me(), void e.panAnimLoop())
            }, Zt = function (e, t) {
                var n, i, o;
                if (ne || (lt = x), "swipe" === e) {
                    var a = tt.x - nt.x, r = t.lastFlickDist.x < 10;
                    30 < a && (r || 20 < t.lastFlickOffset.x) ? i = -1 : a < -30 && (r || t.lastFlickOffset.x < -20) && (i = 1)
                }
                i && ((x += i) < 0 ? (x = y.loop ? Ht() - 1 : 0, o = !0) : x >= Ht() && (x = y.loop ? 0 : Ht() - 1, o = !0), o && !y.loop || (ye += i, me -= i, n = !0));
                var l, s = he.x * me, u = Math.abs(s - ct.x);
                return l = n || s > ct.x == 0 < t.lastFlickSpeed.x ? (l = 0 < Math.abs(t.lastFlickSpeed.x) ? u / Math.abs(t.lastFlickSpeed.x) : 333, l = Math.min(l, 400), Math.max(l, 250)) : 333, lt === x && (n = !1), ne = !0, Te("mainScrollAnimStart"), $e("mainScroll", ct.x, s, l, f.easing.cubic.out, Re, function () {
                    qe(), ne = !1, lt = -1, (n || lt !== x) && h.updateCurrItem(), Te("mainScrollAnimComplete")
                }), n && h.updateCurrItem(!0), n
            }, Pt = function (e) {
                return 1 / Q * e * p
            }, Ft = function () {
                var e = v, t = Ne(), n = Ue();
                v < t ? e = t : n < v && (e = n);
                var i, o = re;
                return ae && !W && !le && v < t ? h.close() : (ae && (i = function (e) {
                    Se((1 - o) * e + o)
                }), h.zoomTo(e, 0, 200, f.easing.cubic.out, i)), !0
            };
        be("Gestures", {
            publicMethods: {
                initGestures: function () {
                    var e = function (e, t, n, i, o) {
                        T = e + t, M = e + n, S = e + i, A = o ? e + o : ""
                    };
                    (O = _.pointerEvent) && _.touch && (_.touch = !1), O ? navigator.pointerEnabled ? e("pointer", "down", "move", "up", "cancel") : e("MSPointer", "Down", "Move", "Up", "Cancel") : _.touch ? (e("touch", "start", "move", "end", "cancel"), k = !0) : e("mouse", "down", "move", "up"), u = M + " " + S + " " + A, c = T, O && !k && (k = 1 < navigator.maxTouchPoints || 1 < navigator.msMaxTouchPoints), h.likelyTouchDevice = k, d[T] = St, d[M] = At, d[S] = Ot, A && (d[A] = d[S]), _.touch && (c += " mousedown", u += " mousemove mouseup", d.mousedown = d[T], d.mousemove = d[M], d.mouseup = d[S]), k || (y.allowPanToNext = !1)
                }
            }
        });
        var Lt, zt, _t, Nt, Ut, Ht, Yt = function (r, e, l, t) {
            var s;
            Lt && clearTimeout(Lt), _t = Nt = !0, r.initialLayout ? (s = r.initialLayout, r.initialLayout = null) : s = y.getThumbBoundsFn && y.getThumbBoundsFn(x);
            var u, c, d = l ? y.hideAnimationDuration : y.showAnimationDuration, p = function () {
                Ve("initialZoom"), l ? (h.template.removeAttribute("style"), h.bg.removeAttribute("style")) : (Se(1), e && (e.style.display = "block"), f.addClass(m, "pswp--animated-in"), Te("initialZoom" + (l ? "OutEnd" : "InEnd"))), t && t(), Nt = !1
            };
            if (!d || !s || void 0 === s.x) return Te("initialZoom" + (l ? "Out" : "In")), v = r.initialZoomLevel, Pe(de, r.initialPosition), Ee(), m.style.opacity = l ? 0 : 1, Se(1), void(d ? setTimeout(function () {
                p()
            }, d) : p());
            u = a, c = !h.currItem.src || h.currItem.loadError || y.showHideOpacity, r.miniImg && (r.miniImg.style.webkitBackfaceVisibility = "hidden"), l || (v = s.w / r.w, de.x = s.x, de.y = s.y - F, h[c ? "template" : "bg"].style.opacity = .001, Ee()), Ke("initialZoom"), l && !u && f.removeClass(m, "pswp--animated-in"), c && (l ? f[(u ? "remove" : "add") + "Class"](m, "pswp--animate_opacity") : setTimeout(function () {
                f.addClass(m, "pswp--animate_opacity")
            }, 30)), Lt = setTimeout(function () {
                if (Te("initialZoom" + (l ? "Out" : "In")), l) {
                    var t = s.w / r.w, n = de.x, i = de.y, o = v, a = re, e = function (e) {
                        de.y = 1 === e ? (v = t, de.x = s.x, s.y - z) : (v = (t - o) * e + o, de.x = (s.x - n) * e + n, (s.y - z - i) * e + i), Ee(), c ? m.style.opacity = 1 - e : Se(a - e * a)
                    };
                    u ? $e("initialZoom", 0, 1, d, f.easing.cubic.out, e, p) : (e(1), Lt = setTimeout(p, d + 20))
                } else v = r.initialZoomLevel, Pe(de, r.initialPosition), Ee(), Se(1), c ? m.style.opacity = 1 : Se(1), Lt = setTimeout(p, d + 20)
            }, l ? 25 : 90)
        }, Wt = {}, Bt = [], Gt = {
            index: 0,
            errorMsg: '<div class="pswp__error-msg"><a href="%url%" target="_blank">The image</a> could not be loaded.</div>',
            forceProgressiveLoading: !1,
            preload: [1, 1],
            getNumItemsFn: function () {
                return zt.length
            }
        }, Xt = function (e, t, n) {
            if (!e.src || e.loadError) return e.w = e.h = 0, e.initialZoomLevel = e.fitRatio = 1, e.bounds = {
                center: {
                    x: 0,
                    y: 0
                }, max: {x: 0, y: 0}, min: {x: 0, y: 0}
            }, e.initialPosition = e.bounds.center, e.bounds;
            var i, o, a, r, l = !n;
            if (l && (e.vGap || (e.vGap = {
                top: 0,
                bottom: 0
            }), Te("parseVerticalMargin", e)), Wt.x = t.x, Wt.y = t.y - e.vGap.top - e.vGap.bottom, l) {
                var s = Wt.x / e.w, u = Wt.y / e.h;
                e.fitRatio = s < u ? s : u;
                var c = y.scaleMode;
                "orig" === c ? n = 1 : "fit" === c && (n = e.fitRatio), 1 < n && (n = 1), e.initialZoomLevel = n, e.bounds || (e.bounds = {
                    center: {
                        x: 0,
                        y: 0
                    }, max: {x: 0, y: 0}, min: {x: 0, y: 0}
                })
            }
            return n ? (o = (i = e).w * n, a = e.h * n, (r = i.bounds).center.x = Math.round((Wt.x - o) / 2), r.center.y = Math.round((Wt.y - a) / 2) + i.vGap.top, r.max.x = o > Wt.x ? Math.round(Wt.x - o) : r.center.x, r.max.y = a > Wt.y ? Math.round(Wt.y - a) + i.vGap.top : r.center.y, r.min.x = o > Wt.x ? 0 : r.center.x, r.min.y = a > Wt.y ? i.vGap.top : r.center.y, l && n === e.initialZoomLevel && (e.initialPosition = e.bounds.center), e.bounds) : void 0
        }, Vt = function (e, t, n, i, o, a) {
            t.loadError || i && (t.imageAppended = !0, $t(t, i, t === h.currItem && we), n.appendChild(i), a && setTimeout(function () {
                t && t.loaded && t.placeholder && (t.placeholder.style.display = "none", t.placeholder = null)
            }, 500))
        }, Kt = function (e) {
            e.loading = !0, e.loaded = !1;
            var t = e.img = f.createEl("pswp__img", "img"), n = function () {
                e.loading = !1, e.loaded = !0, e.loadComplete ? e.loadComplete(e) : e.img = null, t.onload = t.onerror = null, t = null
            };
            return t.onload = n, t.onerror = function () {
                e.loadError = !0, n()
            }, t.src = e.src, t
        }, qt = function (e, t) {
            if (e.src && e.loadError && e.container) return t && (e.container.innerHTML = ""), e.container.innerHTML = y.errorMsg.replace("%url%", e.src), !0
        }, $t = function (e, t, n) {
            if (e.src) {
                t || (t = e.container.lastChild);
                var i = n ? e.w : Math.round(e.w * e.fitRatio), o = n ? e.h : Math.round(e.h * e.fitRatio);
                e.placeholder && !e.loaded && (e.placeholder.style.width = i + "px", e.placeholder.style.height = o + "px"), t.style.width = i + "px", t.style.height = o + "px"
            }
        }, jt = function () {
            if (Bt.length) {
                for (var e, t = 0; t < Bt.length; t++) (e = Bt[t]).holder.index === e.index && Vt(e.index, e.item, e.baseDiv, e.img, 0, e.clearPlaceholder);
                Bt = []
            }
        };
        be("Controller", {
            publicMethods: {
                lazyLoadItem: function (e) {
                    e = Ie(e);
                    var t = Ut(e);
                    t && (!t.loaded && !t.loading || I) && (Te("gettingData", e, t), t.src && Kt(t))
                }, initController: function () {
                    f.extend(y, Gt, !0), h.items = zt = e, Ut = h.getItemAt, Ht = y.getNumItemsFn, y.loop, Ht() < 3 && (y.loop = !1), De("beforeChange", function (e) {
                        var t, n = y.preload, i = null === e || 0 <= e, o = Math.min(n[0], Ht()),
                            a = Math.min(n[1], Ht());
                        for (t = 1; t <= (i ? a : o); t++) h.lazyLoadItem(x + t);
                        for (t = 1; t <= (i ? o : a); t++) h.lazyLoadItem(x - t)
                    }), De("initialLayout", function () {
                        h.currItem.initialLayout = y.getThumbBoundsFn && y.getThumbBoundsFn(x)
                    }), De("mainScrollAnimComplete", jt), De("initialZoomInEnd", jt), De("destroy", function () {
                        for (var e, t = 0; t < zt.length; t++) (e = zt[t]).container && (e.container = null), e.placeholder && (e.placeholder = null), e.img && (e.img = null), e.preloader && (e.preloader = null), e.loadError && (e.loaded = e.loadError = !1);
                        Bt = null
                    })
                }, getItemAt: function (e) {
                    return 0 <= e && void 0 !== zt[e] && zt[e]
                }, allowProgressiveImg: function () {
                    return y.forceProgressiveLoading || !k || y.mouseUsed || 1200 < screen.width
                }, setContent: function (t, n) {
                    y.loop && (n = Ie(n));
                    var e = h.getItemAt(t.index);
                    e && (e.container = null);
                    var i, o = h.getItemAt(n);
                    if (o) {
                        Te("gettingData", n, o), t.index = n;
                        var a = (t.item = o).container = f.createEl("pswp__zoom-wrap");
                        if (!o.src && o.html && (o.html.tagName ? a.appendChild(o.html) : a.innerHTML = o.html), qt(o), Xt(o, pe), !o.src || o.loadError || o.loaded) o.src && !o.loadError && ((i = f.createEl("pswp__img", "img")).style.opacity = 1, i.src = o.src, $t(o, i), Vt(0, o, a, i)); else {
                            if (o.loadComplete = function (e) {
                                if (s) {
                                    if (t && t.index === n) {
                                        if (qt(e, !0)) return e.loadComplete = e.img = null, Xt(e, pe), Oe(e), void(t.index === x && h.updateCurrZoomItem());
                                        e.imageAppended ? !Nt && e.placeholder && (e.placeholder.style.display = "none", e.placeholder = null) : _.transform && (ne || Nt) ? Bt.push({
                                            item: e,
                                            baseDiv: a,
                                            img: e.img,
                                            index: n,
                                            holder: t,
                                            clearPlaceholder: !0
                                        }) : Vt(0, e, a, e.img, 0, !0)
                                    }
                                    e.loadComplete = null, e.img = null, Te("imageLoadComplete", n, e)
                                }
                            }, f.features.transform) {
                                var r = "pswp__img pswp__img--placeholder";
                                r += o.msrc ? "" : " pswp__img--placeholder--blank";
                                var l = f.createEl(r, o.msrc ? "img" : "");
                                o.msrc && (l.src = o.msrc), $t(o, l), a.appendChild(l), o.placeholder = l
                            }
                            o.loading || Kt(o), h.allowProgressiveImg() && (!_t && _.transform ? Bt.push({
                                item: o,
                                baseDiv: a,
                                img: o.img,
                                index: n,
                                holder: t
                            }) : Vt(0, o, a, o.img, 0, !0))
                        }
                        _t || n !== x ? Oe(o) : (te = a.style, Yt(o, i || o.img)), t.el.innerHTML = "", t.el.appendChild(a)
                    } else t.el.innerHTML = ""
                }, cleanSlide: function (e) {
                    e.img && (e.img.onload = e.img.onerror = null), e.loaded = e.loading = e.img = e.imageAppended = !1
                }
            }
        });
        var Jt, Qt, en = {}, tn = function (e, t, n) {
            var i = document.createEvent("CustomEvent"),
                o = {origEvent: e, target: e.target, releasePoint: t, pointerType: n || "touch"};
            i.initCustomEvent("pswpTap", !0, !0, o), e.target.dispatchEvent(i)
        };
        be("Tap", {
            publicMethods: {
                initTap: function () {
                    De("firstTouchStart", h.onTapStart), De("touchRelease", h.onTapRelease), De("destroy", function () {
                        en = {}, Jt = null
                    })
                }, onTapStart: function (e) {
                    1 < e.length && (clearTimeout(Jt), Jt = null)
                }, onTapRelease: function (e, t) {
                    if (t && !q && !V && !Xe) {
                        var n = t;
                        if (Jt && (clearTimeout(Jt), Jt = null, i = n, o = en, Math.abs(i.x - o.x) < 25 && Math.abs(i.y - o.y) < 25)) return void Te("doubleTap", n);
                        if ("mouse" === t.type) return void tn(e, t, "mouse");
                        if ("BUTTON" === e.target.tagName.toUpperCase() || f.hasClass(e.target, "pswp__single-tap")) return void tn(e, t);
                        Pe(en, n), Jt = setTimeout(function () {
                            tn(e, t), Jt = null
                        }, 300)
                    }
                    var i, o
                }
            }
        }), be("DesktopZoom", {
            publicMethods: {
                initDesktopZoom: function () {
                    L || (k ? De("mouseUsed", function () {
                        h.setupDesktopZoom()
                    }) : h.setupDesktopZoom(!0))
                }, setupDesktopZoom: function (e) {
                    Qt = {};
                    var t = "wheel mousewheel DOMMouseScroll";
                    De("bindEvents", function () {
                        f.bind(m, t, h.handleMouseWheel)
                    }), De("unbindEvents", function () {
                        Qt && f.unbind(m, t, h.handleMouseWheel)
                    }), h.mouseZoomedIn = !1;
                    var n, i = function () {
                        h.mouseZoomedIn && (f.removeClass(m, "pswp--zoomed-in"), h.mouseZoomedIn = !1), v < 1 ? f.addClass(m, "pswp--zoom-allowed") : f.removeClass(m, "pswp--zoom-allowed"), o()
                    }, o = function () {
                        n && (f.removeClass(m, "pswp--dragging"), n = !1)
                    };
                    De("resize", i), De("afterChange", i), De("pointerDown", function () {
                        h.mouseZoomedIn && (n = !0, f.addClass(m, "pswp--dragging"))
                    }), De("pointerUp", o), e || i()
                }, handleMouseWheel: function (e) {
                    if (v <= h.currItem.fitRatio) return y.modal && (!y.closeOnScroll || Xe || X ? e.preventDefault() : E && 2 < Math.abs(e.deltaY) && (a = !0, h.close())), !0;
                    if (e.stopPropagation(), Qt.x = 0, "deltaX" in e) 1 === e.deltaMode ? (Qt.x = 18 * e.deltaX, Qt.y = 18 * e.deltaY) : (Qt.x = e.deltaX, Qt.y = e.deltaY); else if ("wheelDelta" in e) e.wheelDeltaX && (Qt.x = -.16 * e.wheelDeltaX), e.wheelDeltaY ? Qt.y = -.16 * e.wheelDeltaY : Qt.y = -.16 * e.wheelDelta; else {
                        if (!("detail" in e)) return;
                        Qt.y = e.detail
                    }
                    _e(v, !0);
                    var t = de.x - Qt.x, n = de.y - Qt.y;
                    (y.modal || t <= ee.min.x && t >= ee.max.x && n <= ee.min.y && n >= ee.max.y) && e.preventDefault(), h.panTo(t, n)
                }, toggleDesktopZoom: function (e) {
                    e = e || {x: pe.x / 2 + fe.x, y: pe.y / 2 + fe.y};
                    var t = y.getDoubleTapZoom(!0, h.currItem), n = v === t;
                    h.mouseZoomedIn = !n, h.zoomTo(n ? h.currItem.initialZoomLevel : t, e, 333), f[(n ? "remove" : "add") + "Class"](m, "pswp--zoomed-in")
                }
            }
        });
        var nn, on, an, rn, ln, sn, un, cn, dn, pn, mn, fn, hn = {history: !0, galleryUID: 1}, yn = function () {
            return mn.hash.substring(1)
        }, xn = function () {
            nn && clearTimeout(nn), an && clearTimeout(an)
        }, vn = function () {
            var e = yn(), t = {};
            if (e.length < 5) return t;
            var n, i = e.split("&");
            for (n = 0; n < i.length; n++) if (i[n]) {
                var o = i[n].split("=");
                o.length < 2 || (t[o[0]] = o[1])
            }
            if (y.galleryPIDs) {
                var a = t.pid;
                for (n = t.pid = 0; n < zt.length; n++) if (zt[n].pid === a) {
                    t.pid = n;
                    break
                }
            } else t.pid = parseInt(t.pid, 10) - 1;
            return t.pid < 0 && (t.pid = 0), t
        }, gn = function () {
            if (an && clearTimeout(an), Xe || X) an = setTimeout(gn, 500); else {
                rn ? clearTimeout(on) : rn = !0;
                var e = x + 1, t = Ut(x);
                t.hasOwnProperty("pid") && (e = t.pid);
                var n = un + "&gid=" + y.galleryUID + "&pid=" + e;
                cn || -1 === mn.hash.indexOf(n) && (pn = !0);
                var i = mn.href.split("#")[0] + "#" + n;
                fn ? "#" + n !== window.location.hash && history[cn ? "replaceState" : "pushState"]("", document.title, i) : cn ? mn.replace(i) : mn.hash = n, cn = !0, on = setTimeout(function () {
                    rn = !1
                }, 60)
            }
        };
        be("History", {
            publicMethods: {
                initHistory: function () {
                    if (f.extend(y, hn, !0), y.history) {
                        mn = window.location, cn = dn = pn = !1, un = yn(), fn = "pushState" in history, -1 < un.indexOf("gid=") && (un = (un = un.split("&gid=")[0]).split("?gid=")[0]), De("afterChange", h.updateURL), De("unbindEvents", function () {
                            f.unbind(window, "hashchange", h.onHashChange)
                        });
                        var e = function () {
                            sn = !0, dn || (pn ? history.back() : un ? mn.hash = un : fn ? history.pushState("", document.title, mn.pathname + mn.search) : mn.hash = ""), xn()
                        };
                        De("unbindEvents", function () {
                            a && e()
                        }), De("destroy", function () {
                            sn || e()
                        }), De("firstUpdate", function () {
                            x = vn().pid
                        });
                        var t = un.indexOf("pid=");
                        -1 < t && "&" === (un = un.substring(0, t)).slice(-1) && (un = un.slice(0, -1)), setTimeout(function () {
                            s && f.bind(window, "hashchange", h.onHashChange)
                        }, 40)
                    }
                }, onHashChange: function () {
                    return yn() === un ? (dn = !0, void h.close()) : void(rn || (ln = !0, h.goTo(vn().pid), ln = !1))
                }, updateURL: function () {
                    xn(), ln || (cn ? nn = setTimeout(gn, 800) : gn())
                }
            }
        }), f.extend(h, je)
    }
});
!function (e, t) {
    "function" == typeof define && define.amd ? define(t) : "object" == typeof exports ? module.exports = t() : e.PhotoSwipeUI_Default = t()
}(this, function () {
    "use strict";
    return function (l, s) {
        var n, a, r, i, t, o, u, c, p, e, d, m, f, h, w, g, v, b, _ = this, C = !1, T = !0, I = !0, E = {
            barsSize: {top: 44, bottom: "auto"},
            closeElClasses: ["item", "caption", "zoom-wrap", "ui", "top-bar"],
            timeToIdle: 4e3,
            timeToIdleOutside: 1e3,
            loadingIndicatorDelay: 1e3,
            addCaptionHTMLFn: function (e, t) {
                return e.title ? (t.children[0].innerHTML = e.title, !0) : (t.children[0].innerHTML = "", !1)
            },
            closeEl: !0,
            captionEl: !0,
            fullscreenEl: !0,
            zoomEl: !0,
            shareEl: !0,
            counterEl: !0,
            arrowEl: !0,
            preloaderEl: !0,
            tapToClose: !1,
            tapToToggleControls: !0,
            clickToCloseNonZoomable: !0,
            shareButtons: [{
                id: "facebook",
                label: "Share on Facebook",
                url: "https://www.facebook.com/sharer/sharer.php?u={{url}}"
            }, {
                id: "twitter",
                label: "Tweet",
                url: "https://twitter.com/intent/tweet?text={{text}}&url={{url}}"
            }, {
                id: "pinterest",
                label: "Pin it",
                url: "http://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}"
            }, {id: "download", label: "Download image", url: "{{raw_image_url}}", download: !0}],
            getImageURLForShare: function () {
                return l.currItem.src || ""
            },
            getPageURLForShare: function () {
                return window.location.href
            },
            getTextForShare: function () {
                return l.currItem.title || ""
            },
            indexIndicatorSep: " / ",
            fitControlsWidth: 1200
        }, F = function (e) {
            if (g) return !0;
            e = e || window.event, w.timeToIdle && w.mouseUsed && !p && z();
            for (var t, n, o = (e.target || e.srcElement).getAttribute("class") || "", l = 0; l < P.length; l++) (t = P[l]).onTap && -1 < o.indexOf("pswp__" + t.name) && (t.onTap(), n = !0);
            if (n) {
                e.stopPropagation && e.stopPropagation(), g = !0;
                var r = s.features.isOldAndroid ? 600 : 30;
                setTimeout(function () {
                    g = !1
                }, r)
            }
        }, x = function (e, t, n) {
            s[(n ? "add" : "remove") + "Class"](e, "pswp__" + t)
        }, S = function () {
            var e = 1 === w.getNumItemsFn();
            e !== h && (x(a, "ui--one-slide", e), h = e)
        }, k = function () {
            x(u, "share-modal--hidden", I)
        }, K = function () {
            return (I = !I) ? (s.removeClass(u, "pswp__share-modal--fade-in"), setTimeout(function () {
                I && k()
            }, 300)) : (k(), setTimeout(function () {
                I || s.addClass(u, "pswp__share-modal--fade-in")
            }, 30)), I || O(), !1
        }, L = function (e) {
            var t = (e = e || window.event).target || e.srcElement;
            return l.shout("shareLinkClick", e, t), !(!t.href || !t.hasAttribute("download") && (window.open(t.href, "pswp_share", "scrollbars=yes,resizable=yes,toolbar=no,location=yes,width=550,height=420,top=100,left=" + (window.screen ? Math.round(screen.width / 2 - 275) : 100)), I || K(), 1))
        }, O = function () {
            for (var e, t, n, o, l = "", r = 0; r < w.shareButtons.length; r++) e = w.shareButtons[r], t = w.getImageURLForShare(e), n = w.getPageURLForShare(e), o = w.getTextForShare(e), l += '<a href="' + e.url.replace("{{url}}", encodeURIComponent(n)).replace("{{image_url}}", encodeURIComponent(t)).replace("{{raw_image_url}}", t).replace("{{text}}", encodeURIComponent(o)) + '" target="_blank" class="pswp__share--' + e.id + '"' + (e.download ? "download" : "") + ">" + e.label + "</a>", w.parseShareButtonOut && (l = w.parseShareButtonOut(e, l));
            u.children[0].innerHTML = l, u.children[0].onclick = L
        }, R = function (e) {
            for (var t = 0; t < w.closeElClasses.length; t++) if (s.hasClass(e, "pswp__" + w.closeElClasses[t])) return !0
        }, y = 0, z = function () {
            clearTimeout(b), y = 0, p && _.setIdle(!1)
        }, M = function (e) {
            var t = (e = e || window.event).relatedTarget || e.toElement;
            t && "HTML" !== t.nodeName || (clearTimeout(b), b = setTimeout(function () {
                _.setIdle(!0)
            }, w.timeToIdleOutside))
        }, D = function (e) {
            m !== e && (x(d, "preloader--active", !e), m = e)
        }, A = function (e) {
            var t = e.vGap;
            if (!l.likelyTouchDevice || w.mouseUsed || screen.width > w.fitControlsWidth) {
                var n = w.barsSize;
                if (w.captionEl && "auto" === n.bottom) if (i || ((i = s.createEl("pswp__caption pswp__caption--fake")).appendChild(s.createEl("pswp__caption__center")), a.insertBefore(i, r), s.addClass(a, "pswp__ui--fit")), w.addCaptionHTMLFn(e, i, !0)) {
                    var o = i.clientHeight;
                    t.bottom = parseInt(o, 10) || 44
                } else t.bottom = n.top; else t.bottom = "auto" === n.bottom ? 0 : n.bottom;
                t.top = n.top
            } else t.top = t.bottom = 0
        }, P = [{
            name: "caption", option: "captionEl", onInit: function (e) {
                r = e
            }
        }, {
            name: "share-modal", option: "shareEl", onInit: function (e) {
                u = e
            }, onTap: function () {
                K()
            }
        }, {
            name: "button--share", option: "shareEl", onInit: function (e) {
                o = e
            }, onTap: function () {
                K()
            }
        }, {name: "button--zoom", option: "zoomEl", onTap: l.toggleDesktopZoom}, {
            name: "counter",
            option: "counterEl",
            onInit: function (e) {
                t = e
            }
        }, {name: "button--close", option: "closeEl", onTap: l.close}, {
            name: "button--arrow--left",
            option: "arrowEl",
            onTap: l.prev
        }, {name: "button--arrow--right", option: "arrowEl", onTap: l.next}, {
            name: "button--fs",
            option: "fullscreenEl",
            onTap: function () {
                n.isFullscreen() ? n.exit() : n.enter()
            }
        }, {
            name: "preloader", option: "preloaderEl", onInit: function (e) {
                d = e
            }
        }];
        _.init = function () {
            var t;
            s.extend(l.options, E, !0), w = l.options, a = s.getChildByClass(l.scrollWrap, "pswp__ui"), (e = l.listen)("onVerticalDrag", function (e) {
                T && e < .95 ? _.hideControls() : !T && .95 <= e && _.showControls()
            }), e("onPinchClose", function (e) {
                T && e < .9 ? (_.hideControls(), t = !0) : t && !T && .9 < e && _.showControls()
            }), e("zoomGestureEnded", function () {
                (t = !1) && !T && _.showControls()
            }), e("beforeChange", _.update), e("doubleTap", function (e) {
                var t = l.currItem.initialZoomLevel;
                l.getZoomLevel() !== t ? l.zoomTo(t, e, 333) : l.zoomTo(w.getDoubleTapZoom(!1, l.currItem), e, 333)
            }), e("preventDragEvent", function (e, t, n) {
                var o = e.target || e.srcElement;
                o && o.getAttribute("class") && -1 < e.type.indexOf("mouse") && (0 < o.getAttribute("class").indexOf("__caption") || /(SMALL|STRONG|EM)/i.test(o.tagName)) && (n.prevent = !1)
            }), e("bindEvents", function () {
                s.bind(a, "pswpTap click", F), s.bind(l.scrollWrap, "pswpTap", _.onGlobalTap), l.likelyTouchDevice || s.bind(l.scrollWrap, "mouseover", _.onMouseOver)
            }), e("unbindEvents", function () {
                I || K(), v && clearInterval(v), s.unbind(document, "mouseout", M), s.unbind(document, "mousemove", z), s.unbind(a, "pswpTap click", F), s.unbind(l.scrollWrap, "pswpTap", _.onGlobalTap), s.unbind(l.scrollWrap, "mouseover", _.onMouseOver), n && (s.unbind(document, n.eventK, _.updateFullscreen), n.isFullscreen() && (w.hideAnimationDuration = 0, n.exit()), n = null)
            }), e("destroy", function () {
                w.captionEl && (i && a.removeChild(i), s.removeClass(r, "pswp__caption--empty")), u && (u.children[0].onclick = null), s.removeClass(a, "pswp__ui--over-close"), s.addClass(a, "pswp__ui--hidden"), _.setIdle(!1)
            }), w.showAnimationDuration || s.removeClass(a, "pswp__ui--hidden"), e("initialZoomIn", function () {
                w.showAnimationDuration && s.removeClass(a, "pswp__ui--hidden")
            }), e("initialZoomOut", function () {
                s.addClass(a, "pswp__ui--hidden")
            }), e("parseVerticalMargin", A), function () {
                var l, r, i, e = function (e) {
                    if (e) for (var t = e.length, n = 0; n < t; n++) {
                        l = e[n], r = l.className;
                        for (var o = 0; o < P.length; o++) i = P[o], -1 < r.indexOf("pswp__" + i.name) && (w[i.option] ? (s.removeClass(l, "pswp__element--disabled"), i.onInit && i.onInit(l)) : s.addClass(l, "pswp__element--disabled"))
                    }
                };
                e(a.children);
                var t = s.getChildByClass(a, "pswp__top-bar");
                t && e(t.children)
            }(), w.shareEl && o && u && (I = !0), S(), w.timeToIdle && e("mouseUsed", function () {
                s.bind(document, "mousemove", z), s.bind(document, "mouseout", M), v = setInterval(function () {
                    2 == ++y && _.setIdle(!0)
                }, w.timeToIdle / 2)
            }), w.fullscreenEl && !s.features.isOldAndroid && (n || (n = _.getFullscreenAPI()), n ? (s.bind(document, n.eventK, _.updateFullscreen), _.updateFullscreen(), s.addClass(l.template, "pswp--supports-fs")) : s.removeClass(l.template, "pswp--supports-fs")), w.preloaderEl && (D(!0), e("beforeChange", function () {
                clearTimeout(f), f = setTimeout(function () {
                    l.currItem && l.currItem.loading ? (!l.allowProgressiveImg() || l.currItem.img && !l.currItem.img.naturalWidth) && D(!1) : D(!0)
                }, w.loadingIndicatorDelay)
            }), e("imageLoadComplete", function (e, t) {
                l.currItem === t && D(!0)
            }))
        }, _.setIdle = function (e) {
            x(a, "ui--idle", p = e)
        }, _.update = function () {
            C = !(!T || !l.currItem || (_.updateIndexIndicator(), w.captionEl && (w.addCaptionHTMLFn(l.currItem, r), x(r, "caption--empty", !l.currItem.title)), 0)), I || K(), S()
        }, _.updateFullscreen = function (e) {
            e && setTimeout(function () {
                l.setScrollOffset(0, s.getScrollY())
            }, 50), s[(n.isFullscreen() ? "add" : "remove") + "Class"](l.template, "pswp--fs")
        }, _.updateIndexIndicator = function () {
            w.counterEl && (t.innerHTML = l.getCurrentIndex() + 1 + w.indexIndicatorSep + w.getNumItemsFn())
        }, _.onGlobalTap = function (e) {
            var t = (e = e || window.event).target || e.srcElement;
            if (!g) if (e.detail && "mouse" === e.detail.pointerType) {
                if (R(t)) return void l.close();
                s.hasClass(t, "pswp__img") && (1 === l.getZoomLevel() && l.getZoomLevel() <= l.currItem.fitRatio ? w.clickToCloseNonZoomable && l.close() : l.toggleDesktopZoom(e.detail.releasePoint))
            } else if (w.tapToToggleControls && (T ? _.hideControls() : _.showControls()), w.tapToClose && (s.hasClass(t, "pswp__img") || R(t))) return void l.close()
        }, _.onMouseOver = function (e) {
            var t = (e = e || window.event).target || e.srcElement;
            x(a, "ui--over-close", R(t))
        }, _.hideControls = function () {
            s.addClass(a, "pswp__ui--hidden"), T = !1
        }, _.showControls = function () {
            T = !0, C || _.update(), s.removeClass(a, "pswp__ui--hidden")
        }, _.supportsFullscreen = function () {
            var e = document;
            return !!(e.exitFullscreen || e.mozCancelFullScreen || e.webkitExitFullscreen || e.msExitFullscreen)
        }, _.getFullscreenAPI = function () {
            var e, t = document.documentElement, n = "fullscreenchange";
            return t.requestFullscreen ? e = {
                enterK: "requestFullscreen",
                exitK: "exitFullscreen",
                elementK: "fullscreenElement",
                eventK: n
            } : t.mozRequestFullScreen ? e = {
                enterK: "mozRequestFullScreen",
                exitK: "mozCancelFullScreen",
                elementK: "mozFullScreenElement",
                eventK: "moz" + n
            } : t.webkitRequestFullscreen ? e = {
                enterK: "webkitRequestFullscreen",
                exitK: "webkitExitFullscreen",
                elementK: "webkitFullscreenElement",
                eventK: "webkit" + n
            } : t.msRequestFullscreen && (e = {
                enterK: "msRequestFullscreen",
                exitK: "msExitFullscreen",
                elementK: "msFullscreenElement",
                eventK: "MSFullscreenChange"
            }), e && (e.enter = function () {
                return c = w.closeOnScroll, w.closeOnScroll = !1, "webkitRequestFullscreen" !== this.enterK ? l.template[this.enterK]() : void l.template[this.enterK](Element.ALLOW_KEYBOARD_INPUT)
            }, e.exit = function () {
                return w.closeOnScroll = c, document[this.exitK]()
            }, e.isFullscreen = function () {
                return document[this.elementK]
            }), e
        }
    }
});
!function (B) {
    B.fn.theiaStickySidebar = function (i) {
        var t, o, e, a, d, s;

        function n(i, t) {
            return !0 === i.initialized || !(B("body").width() < i.minWidth) && (o = t, (v = i).initialized = !0, B("head").append(B('<style>.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>')), o.each(function () {
                var i = {};
                if (i.sidebar = B(this), i.options = v || {}, i.container = B(i.options.containerSelector), 0 == i.container.length && (i.container = i.sidebar.parent()), i.sidebar.parents().css("-webkit-transform", "none"), i.sidebar.css({
                    position: "relative",
                    overflow: "visible",
                    "-webkit-box-sizing": "border-box",
                    "-moz-box-sizing": "border-box",
                    "box-sizing": "border-box"
                }), i.stickySidebar = i.sidebar.find(".theiaStickySidebar"), 0 == i.stickySidebar.length) {
                    var o = /(?:text|application)\/(?:x-)?(?:javascript|ecmascript)/i;
                    i.sidebar.find("script").filter(function (i, t) {
                        return 0 === t.type.length || t.type.match(o)
                    }).remove(), i.stickySidebar = B("<div>").addClass("theiaStickySidebar").append(i.sidebar.children()), i.sidebar.append(i.stickySidebar)
                }
                i.marginBottom = parseInt(i.sidebar.css("margin-bottom")), i.paddingTop = parseInt(i.sidebar.css("padding-top")), i.paddingBottom = parseInt(i.sidebar.css("padding-bottom"));
                var t, e, a = i.stickySidebar.offset().top, d = i.stickySidebar.outerHeight();

                function k() {
                    i.fixedScrollTop = 0, i.sidebar.css({"min-height": "1px"}), i.stickySidebar.css({
                        position: "static",
                        width: "",
                        transform: "none"
                    })
                }

                i.stickySidebar.css("padding-top", 1), i.stickySidebar.css("padding-bottom", 1), a -= i.stickySidebar.offset().top, d = i.stickySidebar.outerHeight() - d - a, i.stickySidebarPaddingTop = 0 == a ? (i.stickySidebar.css("padding-top", 0), 0) : 1, i.stickySidebarPaddingBottom = 0 == d ? (i.stickySidebar.css("padding-bottom", 0), 0) : 1, i.previousScrollTop = null, i.fixedScrollTop = 0, k(), i.onScroll = function (i) {
                    if (i.stickySidebar.is(":visible")) if (B("body").width() < i.options.minWidth) k(); else {
                        if (i.options.disableOnResponsiveLayouts) {
                            var t = i.sidebar.outerWidth("none" == i.sidebar.css("float"));
                            if (t + 50 > i.container.width()) return void k()
                        }
                        var o, e, a = B(document).scrollTop(), d = "static";
                        if (a >= i.sidebar.offset().top + (i.paddingTop - i.options.additionalMarginTop)) {
                            var s, n = i.paddingTop + v.additionalMarginTop,
                                r = i.paddingBottom + i.marginBottom + v.additionalMarginBottom,
                                c = i.sidebar.offset().top,
                                p = i.sidebar.offset().top + (o = i.container, e = o.height(), o.children().each(function () {
                                    e = Math.max(e, B(this).height())
                                }), e), b = 0 + v.additionalMarginTop,
                                l = i.stickySidebar.outerHeight() + n + r < B(window).height();
                            s = l ? b + i.stickySidebar.outerHeight() : B(window).height() - i.marginBottom - i.paddingBottom - v.additionalMarginBottom;
                            var h = c - a + i.paddingTop, g = p - a - i.paddingBottom - i.marginBottom,
                                f = i.stickySidebar.offset().top - a, S = i.previousScrollTop - a;
                            "fixed" == i.stickySidebar.css("position") && "modern" == i.options.sidebarBehavior && (f += S), "stick-to-top" == i.options.sidebarBehavior && (f = v.additionalMarginTop), "stick-to-bottom" == i.options.sidebarBehavior && (f = s - i.stickySidebar.outerHeight()), f = 0 < S ? Math.min(f, b) : Math.max(f, s - i.stickySidebar.outerHeight()), f = Math.max(f, h), f = Math.min(f, g - i.stickySidebar.outerHeight());
                            var m = i.container.height() == i.stickySidebar.outerHeight();
                            d = !m && f == b || !m && f == s - i.stickySidebar.outerHeight() ? "fixed" : a + f - i.sidebar.offset().top - i.paddingTop <= v.additionalMarginTop ? "static" : "absolute"
                        }
                        if ("fixed" == d) {
                            var y = B(document).scrollLeft();
                            i.stickySidebar.css({
                                position: "fixed",
                                width: x(i.stickySidebar) + "px",
                                transform: "translateY(" + f + "px)",
                                left: i.sidebar.offset().left + parseInt(i.sidebar.css("padding-left")) - y + "px",
                                top: "0px"
                            })
                        } else if ("absolute" == d) {
                            var u = {};
                            "absolute" != i.stickySidebar.css("position") && (u.position = "absolute", u.transform = "translateY(" + (a + f - i.sidebar.offset().top - i.stickySidebarPaddingTop - i.stickySidebarPaddingBottom) + "px)", u.top = "0px"), u.width = x(i.stickySidebar) + "px", u.left = "", i.stickySidebar.css(u)
                        } else "static" == d && k();
                        "static" != d && 1 == i.options.updateSidebarHeight && i.sidebar.css({"min-height": i.stickySidebar.outerHeight() + i.stickySidebar.offset().top - i.sidebar.offset().top + i.paddingBottom}), i.previousScrollTop = a
                    }
                }, i.onScroll(i), B(document).scroll((t = i, function () {
                    t.onScroll(t)
                })), B(window).resize((e = i, function () {
                    e.stickySidebar.css({position: "static"}), e.onScroll(e)
                }))
            }), !0);
            var v, o
        }

        function x(i) {
            var t;
            try {
                t = i[0].getBoundingClientRect().width
            } catch (i) {
            }
            return void 0 === t && (t = i.width()), t
        }

        (i = B.extend({
            containerSelector: "",
            additionalMarginTop: 0,
            additionalMarginBottom: 0,
            updateSidebarHeight: !0,
            minWidth: 0,
            disableOnResponsiveLayouts: !0,
            sidebarBehavior: "modern"
        }, i)).additionalMarginTop = parseInt(i.additionalMarginTop) || 0, i.additionalMarginBottom = parseInt(i.additionalMarginBottom) || 0, n(t = i, o = this) || (console.log("TSS: Body width smaller than options.minWidth. Init is delayed."), B(document).scroll((d = t, s = o, function (i) {
            var t = n(d, s);
            t && B(this).unbind(i)
        })), B(window).resize((e = t, a = o, function (i) {
            var t = n(e, a);
            t && B(this).unbind(i)
        })))
    }
}(jQuery);
var body = jQuery("body"), st = 0, lastSt = 0,
    navText = ['<i class="mdi mdi-chevron-left"></i>', '<i class="mdi mdi-chevron-right"></i>'];

function navbar() {
    "use strict";
    st = jQuery(window).scrollTop();
    var e = jQuery(".ads.before_header").outerHeight(), t = jQuery(".site-header").height(),
        a = jQuery(".navbar-sticky_transparent.with-hero"),
        i = jQuery(".navbar-sticky.ads-before-header, .navbar-sticky_transparent.ads-before-header"),
        o = jQuery(".navbar-sticky.navbar-slide, .navbar-sticky_transparent.navbar-slide");
    t + e < st ? a.addClass("navbar-sticky") : a.removeClass("navbar-sticky"), e < st ? i.addClass("stick-now") : i.removeClass("stick-now"), lastSt < st && t + e + 100 < st ? o.addClass("slide-now") : st + jQuery(window).height() < jQuery(document).height() && o.removeClass("slide-now"), lastSt = st
}

function retinaLogo() {
    "use strict";
    var e = jQuery(".logo.regular"), t = jQuery(".logo.contrary"),
        a = "(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)";
    "" != magsyParams.logo_regular && (1 < window.devicePixelRatio || window.matchMedia && window.matchMedia(a).matches) && e.each(function (e, t) {
        jQuery(t).prop("width", jQuery(t).width()), jQuery(t).prop("height", jQuery(t).height()), jQuery(t).attr("src", magsyParams.logo_regular)
    }), "" != magsyParams.logo_contrary && (1 < window.devicePixelRatio || window.matchMedia && window.matchMedia(a).matches) && (t.prop("width", t.width()), t.prop("height", t.height()), t.attr("src", magsyParams.logo_contrary))
}

function fitVids() {
    "use strict";
    body.fitVids()
}

function carousel() {
    "use strict";
    jQuery(".carousel.module").owlCarousel({
        //autoHeight: !0,
        dots: !1,
        margin: 30,
        nav: !0,
        navSpeed: 500,
        navText: navText,
        responsive: {0: {items: 1}, 768: {items: 3}, 992: {items: 4}}
    })
}

function slider() {
    "use strict";
    var i = {autoplay: !0, autoplaySpeed: 800, loop: !0}, e = jQuery(".slider.big.module"),
        o = {animateOut: "fadeOut", dots: !1, items: 1, nav: !0, navText: navText};
    e.each(function (e, t) {
        if (jQuery(t).hasClass("autoplay")) {
            var a = Object.assign(i, o);
            jQuery(t).owlCarousel(a)
        } else jQuery(t).owlCarousel(o)
    });
    var t = jQuery(".slider.center.module"),
        n = {center: !0, dotsSpeed: 800, loop: !0, margin: 20, responsive: {0: {items: 1}, 768: {items: 2}}};
    t.each(function (e, t) {
        if (jQuery(t).hasClass("autoplay")) {
            var a = Object.assign(i, n);
            jQuery(t).owlCarousel(a)
        } else jQuery(t).owlCarousel(n)
    });
    var a = jQuery(".slider.thumbnail.module"), s = {dotsData: !0, dotsSpeed: 800, items: 1};
    a.each(function (e, t) {
        if (jQuery(t).hasClass("autoplay")) {
            var a = Object.assign(i, s);
            jQuery(t).owlCarousel(a)
        } else jQuery(t).owlCarousel(s)
    })
}

function megaMenu() {
    "use strict";
    jQuery(".menu-posts").not(".owl-loaded").owlCarousel({items: 5, margin: 15})
}

function hero() {
    "use strict";
    body.hasClass("with-hero") && (jQuery(".hero-full .hero").height(jQuery(window).height() - jQuery(".header-gap").height() - jQuery("#wpadminbar").height()), jQuery(".hero-gallery .hero").length && jQuery(".hero-gallery .hero").imagesLoaded({background: ".slider-item"}, function () {
        jQuery(".hero-slider").owlCarousel({
            animateOut: "fadeOut",
            dots: !1,
            items: 1,
            mouseDrag: !1,
            nav: !0,
            navText: navText,
            onInitialized: function (e) {
                jQuery(".hero-slider").find(".owl-item:first-child").addClass("finished")
            },
            onTranslated: function (e) {
                jQuery(".hero-slider").find(".owl-item").removeClass("finished"), jQuery(".hero-slider").find(".owl-item:nth-child(" + (e.item.index + 1) + ")").addClass("finished")
            },
            touchDrag: !1
        })
    }))
}

function categoryBoxes() {
    "use strict";
    jQuery(".category-boxes").owlCarousel({
        dots: !1,
        margin: 30,
        nav: !0,
        navSpeed: 500,
        navText: navText,
        responsive: {0: {items: 1}, 768: {items: 2}, 992: {items: 3}, 1230: {items: 4}}
    })
}

function picks() {
    "use strict";
    jQuery(".picked-posts").not(".owl-loaded").owlCarousel({
        autoHeight: !0,
        autoplay: !0,
        autoplayHoverPause: !0,
        autoplaySpeed: 500,
        autoplayTimeout: 3e3,
        items: 1,
        loop: !0
    })
}

function offCanvas() {
    "use strict";
    var e = jQuery(".burger"), t = jQuery(".canvas-close");
    jQuery(".main-menu .nav-list").slicknav({label: "", prependTo: ".mobile-menu"}), e.on("click", function () {
        body.toggleClass("canvas-opened"), body.addClass("canvas-visible"), dimmer("open", "medium")
    }), t.on("click", function () {
        body.hasClass("canvas-opened") && (body.removeClass("canvas-opened"), dimmer("close", "medium"))
    }), jQuery(".dimmer").on("click", function () {
        body.hasClass("canvas-opened") && (body.removeClass("canvas-opened"), dimmer("close", "medium"))
    }), jQuery(document).keyup(function (e) {
        27 == e.keyCode && body.hasClass("canvas-opened") && (body.removeClass("canvas-opened"), dimmer("close", "medium"))
    })
}

function gallery() {
    "use strict";
    jQuery(".entry-gallery.slider").not(".owl-loaded").owlCarousel({
        animateOut: "fadeOut",
        dots: !1,
        items: 1,
        nav: !0,
        navSpeed: 500,
        navText: navText
    }), jQuery(".entry-gallery.justified-gallery").justifiedGallery({border: 0, margins: 6, rowHeight: 200})
}

function photoSwipe() {
    "use strict";
    var t, a, i = jQuery(".pswp")[0], o = [];
    jQuery(".entry-gallery.justified-gallery").on("click", ".gallery-item > a", function (e) {
        e.preventDefault(), o = [], a = jQuery(this), t = a.parent().index(), jQuery.each(a.parent().siblings().addBack(), function (e, t) {
            o.push({
                h: parseInt(jQuery(t).find("a").attr("data-height")),
                src: jQuery(t).find("a").attr("href"),
                title: jQuery(t).find(".caption").text(),
                w: parseInt(jQuery(t).find("a").attr("data-width"))
            })
        }), new PhotoSwipe(i, PhotoSwipeUI_Default, o, {
            closeOnScroll: !1,
            history: !1,
            index: t,
            showAnimationDuration: 0,
            showHideOpacity: !0,
            timeToIdle: 2e3
        }).init()
    })
}

function search() {
    "use strict";
    var t = jQuery(".main-search"), e = t.find(".search-field");
    jQuery(".search-open").on("click", function () {
        body.addClass("search-open"), e.focus()
    }), jQuery(document).keyup(function (e) {
        27 == e.keyCode && body.hasClass("search-open") && body.removeClass("search-open")
    }), jQuery(".search-close").on("click", function () {
        body.hasClass("search-open") && body.removeClass("search-open")
    }), jQuery(document).mouseup(function (e) {
        !t.is(e.target) && 0 === t.has(e.target).length && body.hasClass("search-open") && body.removeClass("search-open")
    })
}

function masonry() {
    "use strict";
    jQuery(".module.masonry > .row").masonry({
        columnWidth: ".grid-sizer",
        itemSelector: ".grid-item",
        percentPosition: !0
    })
}

function pagination() {
    "use strict";
    var e = jQuery(".posts-wrapper"), i = jQuery(".infinite-scroll-button"), t = {
        append: e.selector + " > *",
        debug: !1,
        hideNav: ".posts-navigation",
        history: !1,
        path: ".posts-navigation .nav-previous a",
        prefill: !0,
        status: ".infinite-scroll-status"
    };
    body.hasClass("pagination-infinite_button") && (t.button = i.selector, t.prefill = !1, t.scrollThreshold = !1, e.on("request.infiniteScroll", function (e, t) {
        i.text(magsyParams.infinite_loading)
    }), e.on("load.infiniteScroll", function (e, t, a) {
        i.text(magsyParams.infinite_load)
    })), (body.hasClass("pagination-infinite_button") || body.hasClass("pagination-infinite_scroll")) && body.hasClass("paged-next") && e.infiniteScroll(t)
}

function bookmark() {
    "use strict";
    jQuery(".site-content").on("click", ".bookmark", function (e) {
        e.preventDefault(), popup(jQuery(this).attr("data-url"))
    })
}

function share() {
    "use strict";
    var t, a = jQuery(".modal"), i = a.find(".modal-thumbnail").find("img"), o = a.find(".modal-title"),
        n = a.find(".facebook"), s = a.find(".twitter"), r = a.find(".pinterest"), l = a.find(".email"),
        d = a.find(".whatsapp"), u = a.find(".modal-permalink"), c = a.find("button"), m = c.find(".mdi");
    jQuery(".site-content").on("click", ".share", function (e) {
        e.preventDefault(), t = jQuery(this), i.removeClass("lazyloaded").addClass("lazyload").attr("data-src", t.attr("data-thumbnail")), o.text(t.attr("data-title")), n.attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(t.attr("data-url"))), s.attr("href", "https://twitter.com/intent/tweet?text=" + escape(t.attr("data-title")) + "&url=" + encodeURIComponent(t.attr("data-url"))), r.attr("href", "https://pinterest.com/pin/create/button/?url=" + encodeURIComponent(t.attr("data-url")) + "&media=" + encodeURIComponent(t.attr("data-image")) + "&description=" + escape(t.attr("data-title"))), l.attr("href", "mailto:?subject=" + escape(t.attr("data-title")) + "&body=" + encodeURIComponent(t.attr("data-url"))), d.attr("href", "whatsapp://send?text=" + encodeURIComponent(t.attr("data-url"))), u.val(t.attr("data-url")), c.attr("data-clipboard-text", t.attr("data-url")), m.removeClass("mdi-check").addClass("mdi-content-copy"), a.fadeIn("fast"), dimmer("open", "fast"), body.addClass("modal-opened")
    }), c.on("click", function (e) {
        e.preventDefault(), new ClipboardJS(c.selector), m.removeClass("mdi-content-copy").addClass("mdi-check")
    }), jQuery(".dimmer").on("click", function () {
        body.hasClass("modal-opened") && (a.fadeOut(0), dimmer("close", 0), body.removeClass("modal-opened"))
    }), jQuery(document).keyup(function (e) {
        27 == e.keyCode && body.hasClass("modal-opened") && (a.fadeOut(0), dimmer("close", 0), body.removeClass("modal-opened"))
    })
}

function dimmer(e, t) {
    "use strict";
    var a = jQuery(".dimmer");
    switch (e) {
        case"open":
            a.fadeIn(t);
            break;
        case"close":
            a.fadeOut(t)
    }
}

function popup(e, t, a, i) {
    "use strict";
    t = t || "", a = a || 500, i = i || 300;
    var o = null != window.screenLeft ? window.screenLeft : screen.left,
        n = null != window.screenTop ? window.screenTop : screen.top,
        s = (window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width) / 2 - a / 2 + o,
        r = (window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height) / 2 - i / 2 + n,
        l = window.open(e, t, "scrollbars=yes, width=" + a + ", height=" + i + ", top=" + r + ", left=" + s);
    window.focus && l.focus()
}

window.lazySizesConfig = window.lazySizesConfig || {}, window.lazySizesConfig.loadHidden = !1, jQuery(function () {
    "use strict";
    $('p iframe').each(function () {
        $(this).parent().addClass('iframe-wrapper');
    })
    retinaLogo(), /*fitVids(),*/ carousel(), slider(), megaMenu(), hero(), categoryBoxes(), picks(), offCanvas(), gallery(), photoSwipe(), search(), masonry(), bookmark(), share()
}), jQuery(window).scroll(function () {
    "use strict";
    (body.hasClass("navbar-sticky") || body.hasClass("navbar-sticky_transparent")) && window.requestAnimationFrame(navbar)
}), document.addEventListener("lazyloaded", function (e) {
    var t = {disableParallax: /iPad|iPhone|iPod|Android/, disableVideo: /iPad|iPhone|iPod|Android/, speed: .1};
    (jQuery(e.target).parents(".hero").length || jQuery(e.target).hasClass("hero")) && jQuery(e.target).jarallax(t), (jQuery(e.target).parent().hasClass("module") && jQuery(e.target).parent().hasClass("parallax") || jQuery(e.target).parent().hasClass("entry-navigation")) && jQuery(e.target).parent().jarallax(t)
});
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