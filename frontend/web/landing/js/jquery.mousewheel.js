!function (e) {
    function t(t) {
        var n = t || window.event, i = [].slice.call(arguments, 1), l = 0, s = 0, o = 0;
        return t = e.event.fix(n), t.type = "mousewheel", n.wheelDelta && (l = n.wheelDelta / 120), n.detail && (l = -n.detail / 3), o = l, void 0 !== n.axis && n.axis === n.HORIZONTAL_AXIS && (o = 0, s = -1 * l), void 0 !== n.wheelDeltaY && (o = n.wheelDeltaY / 120), void 0 !== n.wheelDeltaX && (s = -1 * n.wheelDeltaX / 120), i.unshift(t, l, s, o), (e.event.dispatch || e.event.handle).apply(this, i)
    }

    var n = ["DOMMouseScroll", "mousewheel"];
    if (e.event.fixHooks) for (var i = n.length; i;) e.event.fixHooks[n[--i]] = e.event.mouseHooks;
    e.event.special.mousewheel = {
        setup: function () {
            if (this.addEventListener) for (var e = n.length; e;) this.addEventListener(n[--e], t, !1); else this.onmousewheel = t
        }, teardown: function () {
            if (this.removeEventListener) for (var e = n.length; e;) this.removeEventListener(n[--e], t, !1); else this.onmousewheel = null
        }
    }, e.fn.extend({
        mousewheel: function (e) {
            return e ? this.bind("mousewheel", e) : this.trigger("mousewheel")
        }, unmousewheel: function (e) {
            return this.unbind("mousewheel", e)
        }
    })
}(jQuery);