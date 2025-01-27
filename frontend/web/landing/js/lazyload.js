﻿!function (e, t, i, o) {
    var n = e(t);
    e.fn.lazyload = function (o) {
        function r() {
            var t = 0;
            l.each(function () {
                var i = e(this);
                if (!a.skip_invisible || i.is(":visible")) if (e.abovethetop(this, a) || e.leftofbegin(this, a)) ; else if (e.belowthefold(this, a) || e.rightoffold(this, a)) {
                    if (++t > a.failure_limit) {return !1;}
                } else i.trigger("appear"), t = 0
            })
        }

        var f, l = this, a = {
            threshold: 0,
            failure_limit: 0,
            event: "scroll",
            effect: "show",
            container: t,
            data_attribute: "original",
            skip_invisible: !0,
            appear: null,
            load: null,
            placeholder: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"
        };
        return o && (void 0 !== o.failurelimit && (o.failure_limit = o.failurelimit, delete o.failurelimit), void 0 !== o.effectspeed && (o.effect_speed = o.effectspeed, delete o.effectspeed), e.extend(a, o)), f = void 0 === a.container || a.container === t ? n : e(a.container), 0 === a.event.indexOf("scroll") && f.bind(a.event, function () {
            return r()
        }), this.each(function () {
            var t = this, i = e(t);
            t.loaded = !1, void 0 !== i.attr("src") && !1 !== i.attr("src") || i.is("img") && i.attr("src", a.placeholder), i.one("appear", function () {
                if (!this.loaded) {
                    if (a.appear) {
                        var o = l.length;
                        a.appear.call(t, o, a)
                    }
                    e("<img />").bind("load", function () {
                        var o = i.attr("data-" + a.data_attribute);
                        i.hide(), i.is("img") ? i.attr("src", o) : i.css("background-image", "url('" + o + "')"), i[a.effect](a.effect_speed), t.loaded = !0;
                        var n = e.grep(l, function (e) {
                            return !e.loaded
                        });
                        if (l = e(n), a.load) {
                            var r = l.length;
                            a.load.call(t, r, a)
                        }
                    }).attr("src", i.attr("data-" + a.data_attribute))
                }
            }), 0 !== a.event.indexOf("scroll") && i.bind(a.event, function () {
                t.loaded || i.trigger("appear")
            })
        }), n.bind("resize", function () {
            r()
        }), /(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion) && n.bind("pageshow", function (t) {
            t.originalEvent && t.originalEvent.persisted && l.each(function () {
                e(this).trigger("appear")
            })
        }), e(i).ready(function () {
            r()
        }), this
    }, e.belowthefold = function (i, o) {
        return (void 0 === o.container || o.container === t ? (t.innerHeight ? t.innerHeight : n.height()) + n.scrollTop() : e(o.container).offset().top + e(o.container).height()) <= e(i).offset().top - o.threshold
    }, e.rightoffold = function (i, o) {
        return (void 0 === o.container || o.container === t ? n.width() + n.scrollLeft() : e(o.container).offset().left + e(o.container).width()) <= e(i).offset().left - o.threshold
    }, e.abovethetop = function (i, o) {
        return (void 0 === o.container || o.container === t ? n.scrollTop() : e(o.container).offset().top) >= e(i).offset().top + o.threshold + e(i).height()
    }, e.leftofbegin = function (i, o) {
        return (void 0 === o.container || o.container === t ? n.scrollLeft() : e(o.container).offset().left) >= e(i).offset().left + o.threshold + e(i).width()
    }, e.inviewport = function (t, i) {
        return !(e.rightoffold(t, i) || e.leftofbegin(t, i) || e.belowthefold(t, i) || e.abovethetop(t, i))
    }, e.extend(e.expr[":"], {
        "below-the-fold": function (t) {
            return e.belowthefold(t, {threshold: 0})
        }, "above-the-top": function (t) {
            return !e.belowthefold(t, {threshold: 0})
        }, "right-of-screen": function (t) {
            return e.rightoffold(t, {threshold: 0})
        }, "left-of-screen": function (t) {
            return !e.rightoffold(t, {threshold: 0})
        }, "in-viewport": function (t) {
            return e.inviewport(t, {threshold: 0})
        }, "above-the-fold": function (t) {
            return !e.belowthefold(t, {threshold: 0})
        }, "right-of-fold": function (t) {
            return e.rightoffold(t, {threshold: 0})
        }, "left-of-fold": function (t) {
            return !e.rightoffold(t, {threshold: 0})
        }
    })
}(jQuery, window, document);