!function (i, o, t, e) {
    "use strict";

    function n(o, t) {
        function e() {
            n.options.originalVideoW = n.options.$video[0].videoWidth, n.options.originalVideoH = n.options.$video[0].videoHeight, n.initialised || n.init()
        }

        var n = this;
        this.element = o, this.options = i.extend({}, r, t), this._defaults = r, this._name = s, this.options.$video = i(o), this.detectBrowser(), this.shimRequestAnimationFrame(), this.options.has3d = this.detect3d(), this.options.$videoWrap.css({
            position: "relative",
            overflow: "hidden",
            "z-index": "10"
        }), this.options.$video.css({
            position: "absolute",
            "z-index": "1"
        }), this.options.$video.on("canplay canplaythrough", e), this.options.$video[0].readyState > 3 && e()
    }

    var s = "backgroundVideo", r = {
        $videoWrap: i("#video-wrap"),
        $outerWrap: i(o),
        $window: i(o),
        minimumVideoWidth: 400,
        preventContextMenu: !1,
        parallax: !0,
        parallaxOptions: {effect: 1.5},
        pauseVideoOnViewLoss: !1
    };
    n.prototype = {
        init: function () {
            var i = this;
            this.initialised = !0, this.options.pauseVideoOnViewLoss && this.playPauseVideo(), this.options.preventContextMenu && this.options.$video.on("contextmenu", function () {
                return !1
            }), i.update()
        }, update: function () {
            var i = this, t = !1, e = function () {
                i.positionObject(), t = !1
            }, n = function () {
                t || (o.requestAnimationFrame(e), t = !0)
            };
            this.options.parallax && this.options.$window.on("scroll.backgroundVideo", n), this.options.$window.on("resize.backgroundVideo", n), n()
        }, detect3d: function () {
            var i, e, n = t.createElement("p"), s = {
                WebkitTransform: "-webkit-transform",
                OTransform: "-o-transform",
                MSTransform: "-ms-transform",
                MozTransform: "-moz-transform",
                transform: "transform"
            };
            t.body.insertBefore(n, t.body.lastChild);
            for (i in s) void 0 !== n.style[i] && (n.style[i] = "matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)", e = o.getComputedStyle(n).getPropertyValue(s[i]));
            return n.parentNode.removeChild(n), void 0 !== e && "none" !== e
        }, detectBrowser: function () {
            var i = navigator.userAgent.toLowerCase();
            i.indexOf("chrome") > -1 || i.indexOf("safari") > -1 ? (this.options.browser = "webkit", this.options.browserPrexix = "-webkit-") : i.indexOf("firefox") > -1 ? (this.options.browser = "firefox", this.options.browserPrexix = "-moz-") : -1 !== i.indexOf("MSIE") || i.indexOf("Trident/") > 0 ? (this.options.browser = "ie", this.options.browserPrexix = "-ms-") : i.indexOf("Opera") > -1 && (this.options.browser = "opera", this.options.browserPrexix = "-o-")
        }, scaleObject: function () {
            var i, o, t;
            return this.options.$videoWrap.width(this.options.$outerWrap.width()), this.options.$videoWrap.height(this.options.$outerWrap.height()), i = this.options.$window.width() / this.options.originalVideoW, o = this.options.$window.height() / this.options.originalVideoH, (t = i > o ? i : o) * this.options.originalVideoW < this.options.minimumVideoWidth && (t = this.options.minimumVideoWidth / this.options.originalVideoW), this.options.$video.width(t * this.options.originalVideoW), this.options.$video.height(t * this.options.originalVideoH), {
                xPos: -parseInt(this.options.$video.width() - this.options.$window.width()) / 2,
                yPos: parseInt(this.options.$video.height() - this.options.$window.height()) / 2
            }
        }, positionObject: function () {
            var i = this, t = o.pageYOffset, e = this.scaleObject(this.options.$video, i.options.$videoWrap),
                n = e.xPos, s = e.yPos;
            s = this.options.parallax ? t >= 0 ? this.calculateYPos(s, t) : this.calculateYPos(s, 0) : -s, i.options.has3d ? (this.options.$video.css(i.options.browserPrexix + "transform", "translate3d(-" + n + "px, " + s + "px, 0)"), this.options.$video.css("transform", "translate3d(" + n + "px, " + s + "px, 0)")) : (this.options.$video.css(i.options.browserPrexix + "transform", "translate(-" + n + "px, " + s + "px)"), this.options.$video.css("transform", "translate(" + n + "px, " + s + "px)"))
        }, calculateYPos: function (i, o) {
            var t, e;
            return t = parseInt(this.options.$videoWrap.offset().top), e = t - o, i = -(e / this.options.parallaxOptions.effect + i)
        }, disableParallax: function () {
            this.options.$window.unbind(".backgroundVideoParallax")
        }, playPauseVideo: function () {
            var i = this;
            this.options.$window.on("scroll.backgroundVideoPlayPause", function () {
                i.options.$window.scrollTop() < i.options.$videoWrap.height() ? i.options.$video.get(0).play() : i.options.$video.get(0).pause()
            })
        }, shimRequestAnimationFrame: function () {
            for (var i = 0, t = ["ms", "moz", "webkit", "o"], e = 0; e < t.length && !o.requestAnimationFrame; ++e) o.requestAnimationFrame = o[t[e] + "RequestAnimationFrame"], o.cancelAnimationFrame = o[t[e] + "CancelAnimationFrame"] || o[t[e] + "CancelRequestAnimationFrame"];
            o.requestAnimationFrame || (o.requestAnimationFrame = function (t, e) {
                var n = (new Date).getTime(), s = Math.max(0, 16 - (n - i)), r = o.setTimeout(function () {
                    t(n + s)
                }, s);
                return i = n + s, r
            }), o.cancelAnimationFrame || (o.cancelAnimationFrame = function (i) {
                clearTimeout(i)
            })
        }
    }, i.fn[s] = function (o) {
        return this.each(function () {
            i.data(this, "plugin_" + s) || i.data(this, "plugin_" + s, new n(this, o))
        })
    }
}(jQuery, window, document);