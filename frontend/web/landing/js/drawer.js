!function (e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof exports ? module.exports = e(require("jquery")) : e(jQuery)
}(function (e) {
    "use strict";
    var t = void 0 !== document.ontouchstart, n = {
        init: function (t) {
            return t = e.extend({
                iscroll: {mouseWheel: !0, preventDefault: !1},
                showOverlay: !0
            }, t), n.settings = {
                state: !1,
                class: {
                    nav: "drawer-nav",
                    toggle: "drawer-toggle",
                    overlay: "drawer-overlay",
                    open: "drawer-open",
                    close: "drawer-close",
                    dropdown: "drawer-dropdown"
                },
                events: {opened: "drawer.opened", closed: "drawer.closed"},
                dropdownEvents: {opened: "shown.bs.dropdown", closed: "hidden.bs.dropdown"}
            }, this.each(function () {
                var r = this, s = e(this);
                if (!s.data("drawer")) {
                    t = e.extend({}, t), s.data("drawer", {options: t});
                    var o = new IScroll("." + n.settings.class.nav, t.iscroll);
                    t.showOverlay && n.addOverlay.call(r), e("." + n.settings.class.toggle).on("click.drawer", function () {
                        return n.toggle.call(r), o.refresh()
                    }), e(window).resize(function () {
                        return n.close.call(r), o.refresh()
                    }), e("." + n.settings.class.dropdown).on(n.settings.dropdownEvents.opened + " " + n.settings.dropdownEvents.closed, function () {
                        return o.refresh()
                    })
                }
            })
        }, addOverlay: function () {
            var t = e(this), r = e("<div>").addClass(n.settings.class.overlay + " " + n.settings.class.toggle);
            return t.append(r)
        }, toggle: function () {
            var e = this;
            return n.settings.state ? n.close.call(e) : n.open.call(e)
        }, open: function (r) {
            var s = e(this);
            return s.data("drawer").options, t && s.on("touchmove.drawer", function (e) {
                e.preventDefault()
            }), s.removeClass(n.settings.class.close).addClass(n.settings.class.open).css({overflow: "hidden"}).drawerCallback(function () {
                n.settings.state = !0, s.trigger(n.settings.events.opened)
            })
        }, close: function (r) {
            var s = e(this);
            return s.data("drawer").options, t && s.off("touchmove.drawer"), s.removeClass(n.settings.class.open).addClass(n.settings.class.close).css({overflow: "auto"}).drawerCallback(function () {
                n.settings.state = !1, s.trigger(n.settings.events.closed)
            })
        }, destroy: function () {
            return this.each(function () {
                var t = e(this);
                e(window).off(".drawer"), t.removeData("drawer")
            })
        }
    };
    e.fn.drawerCallback = function (t) {
        var n = "transitionend webkitTransitionEnd";
        return this.each(function () {
            var r = e(this);
            r.on(n, function () {
                return r.off(n), t.call(this)
            })
        })
    }, e.fn.drawer = function (t) {
        return n[t] ? n[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? void e.error("Method " + t + " does not exist on jQuery.drawer") : n.init.apply(this, arguments)
    }
});