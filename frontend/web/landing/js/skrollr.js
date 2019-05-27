!function (e, t, r) {
    "use strict";
    var n, o, a = e.skrollr = {
            get: function () {
                return ee
            }, init: function (e) {
                return ee || new G(e)
            }, VERSION: "0.6.17"
        }, i = Object.prototype.hasOwnProperty, l = e.Math, s = e.getComputedStyle, c = "touchstart", f = "touchmove",
        u = "touchcancel", p = "touchend", m = "skrollable", g = m + "-before", d = m + "-between", v = m + "-after",
        h = "skrollr", y = "no-" + h, T = h + "-desktop", b = h + "-mobile", S = "linear", w = .004, k = 200,
        E = "center", x = "bottom", A = "___skrollable_id", F = /^(?:input|textarea|button|select)$/i, C = /^\s+|\s+$/g,
        D = /^data(?:-(_\w+))?(?:-?(-?\d*\.?\d+p?))?(?:-?(start|end|top|center|bottom))?(?:-?(top|center|bottom))?$/,
        H = /\s*([\w\-\[\]]+)\s*:\s*(.+?)\s*(?:;|$)/gi, P = /^([a-z\-]+)\[(\w+)\]$/, V = /-([a-z])/g,
        z = function (e, t) {
            return t.toUpperCase()
        }, N = /[\-+]?[\d]*\.?[\d]+/g, O = /\{\?\}/g, q = /rgba?\(\s*-?\d+\s*,\s*-?\d+\s*,\s*-?\d+/g,
        I = /[a-z\-]+-gradient/g, L = "", M = "", $ = function () {
            var e = /^(?:O|Moz|webkit|ms)|(?:-(?:o|moz|webkit|ms)-)/;
            if (s) {
                var t = s(o, null);
                for (var r in t) if (L = r.match(e) || +r == r && t[r].match(e)) break;
                L ? "-" === (L = L[0]).slice(0, 1) ? (M = L, L = {
                    "-webkit-": "webkit",
                    "-moz-": "Moz",
                    "-ms-": "ms",
                    "-o-": "O"
                }[L]) : M = "-" + L.toLowerCase() + "-" : L = M = ""
            }
        }, B = function () {
            var t = e.requestAnimationFrame || e[L.toLowerCase() + "RequestAnimationFrame"], r = Ee();
            return !Oe && t || (t = function (t) {
                var n = Ee() - r, o = l.max(0, 1e3 / 60 - n);
                return e.setTimeout(function () {
                    r = Ee(), t()
                }, o)
            }), t
        }, _ = {
            begin: function () {
                return 0
            }, end: function () {
                return 1
            }, linear: function (e) {
                return e
            }, quadratic: function (e) {
                return e * e
            }, cubic: function (e) {
                return e * e * e
            }, swing: function (e) {
                return -l.cos(e * l.PI) / 2 + .5
            }, sqrt: function (e) {
                return l.sqrt(e)
            }, outCubic: function (e) {
                return l.pow(e - 1, 3) + 1
            }, bounce: function (e) {
                var t;
                if (e <= .5083) t = 3; else if (e <= .8489) t = 9; else if (e <= .96208) t = 27; else {
                    if (!(e <= .99981)) return 1;
                    t = 91
                }
                return 1 - l.abs(3 * l.cos(e * t * 1.028) / t)
            }
        };

    function G(r) {
        if (n = t.documentElement, o = t.body, $(), ee = this, ae = (r = r || {}).constants || {}, r.easing) for (var a in r.easing) _[a] = r.easing[a];
        pe = r.edgeStrategy || "set", ne = {
            beforerender: r.beforerender,
            render: r.render
        }, (oe = !1 !== r.forceHeight) && (Fe = r.scale || 1), ie = r.mobileDeceleration || w, se = !1 !== r.smoothScrolling, ce = r.smoothScrollingDuration || k, fe = {targetTop: ee.getScrollTop()}, (Oe = (r.mobileCheck || function () {
            return /Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent || navigator.vendor || e.opera)
        })()) ? ((re = t.getElementById("skrollr-body")) && Q(), K(), Se(n, [h, b], [y])) : Se(n, [h, T], [y]), ee.refresh(), de(e, "resize orientationchange", function () {
            var e = n.clientWidth, t = n.clientHeight;
            t === Ve && e === Pe || (Ve = t, Pe = e, ze = !0)
        });
        var i = B();
        return function e() {
            Y(), ge = i(e)
        }(), ee
    }

    G.prototype.refresh = function (e) {
        var n, o, a = !1;
        for (e === r ? (a = !0, te = [], Ne = 0, e = t.getElementsByTagName("*")) : e = [].concat(e), n = 0, o = e.length; n < o; n++) {
            var i = e[n], l = i, s = [], c = se, f = pe;
            if (i.attributes) {
                for (var u, p, g, d = 0, v = i.attributes.length; d < v; d++) {
                    var h = i.attributes[d];
                    if ("data-anchor-target" !== h.name) if ("data-smooth-scrolling" !== h.name) if ("data-edge-strategy" !== h.name) {
                        var y = h.name.match(D);
                        if (null !== y) {
                            var T = {props: h.value, element: i};
                            s.push(T);
                            var b = y[1];
                            b = b && ae[b.substr(1)] || 0;
                            var S = y[2];
                            /p$/.test(S) ? (T.isPercentage = !0, T.offset = ((0 | S.slice(0, -1)) + b) / 100) : T.offset = (0 | S) + b;
                            var w = y[3], k = y[4] || w;
                            w && "start" !== w && "end" !== w ? (T.mode = "relative", T.anchors = [w, k]) : (T.mode = "absolute", "end" === w ? T.isEnd = !0 : T.isPercentage || (T.frame = T.offset * Fe, delete T.offset))
                        }
                    } else f = h.value; else c = "off" !== h.value; else if (null === (l = t.querySelector(h.value))) throw'Unable to find anchor target "' + h.value + '"'
                }
                if (s.length) !a && A in i ? (g = i[A], u = te[g].styleAttr, p = te[g].classAttr) : (g = i[A] = Ne++, u = i.style.cssText, p = be(i)), te[g] = {
                    element: i,
                    styleAttr: u,
                    classAttr: p,
                    anchorTarget: l,
                    keyFrames: s,
                    smoothScrolling: c,
                    edgeStrategy: f
                }, Se(i, [m], [])
            }
        }
        for (ye(), n = 0, o = e.length; n < o; n++) {
            var E = te[e[n][A]];
            E !== r && (R(E), X(E))
        }
        return ee
    }, G.prototype.relativeToAbsolute = function (e, t, r) {
        var o = n.clientHeight, a = e.getBoundingClientRect(), i = a.top, l = a.bottom - a.top;
        return t === x ? i -= o : t === E && (i -= o / 2), r === x ? i += l : r === E && (i += l / 2), (i += ee.getScrollTop()) + .5 | 0
    }, G.prototype.animateTo = function (e, t) {
        t = t || {};
        var n = Ee(), o = ee.getScrollTop();
        return (le = {
            startTop: o,
            topDiff: e - o,
            targetTop: e,
            duration: t.duration || 1e3,
            startTime: n,
            endTime: n + (t.duration || 1e3),
            easing: _[t.easing || S],
            done: t.done
        }).topDiff || (le.done && le.done.call(ee, !1), le = r), ee
    }, G.prototype.stopAnimateTo = function () {
        le && le.done && le.done.call(ee, !0), le = r
    }, G.prototype.isAnimatingTo = function () {
        return !!le
    }, G.prototype.setScrollTop = function (t, r) {
        return ue = !0 === r, Oe ? qe = l.min(l.max(t, 0), Ae) : e.scrollTo(0, t), ee
    }, G.prototype.getScrollTop = function () {
        return Oe ? qe : e.pageYOffset || n.scrollTop || o.scrollTop || 0
    }, G.prototype.getMaxScrollTop = function () {
        return Ae
    }, G.prototype.on = function (e, t) {
        return ne[e] = t, ee
    }, G.prototype.off = function (e) {
        return delete ne[e], ee
    }, G.prototype.destroy = function () {
        var t;
        (t = e.cancelAnimationFrame || e[L.toLowerCase() + "CancelAnimationFrame"], !Oe && t || (t = function (t) {
            return e.clearTimeout(t)
        }), t)(ge), he(), Se(n, [y], [h, T, b]);
        for (var i = 0, l = te.length; i < l; i++) J(te[i].element);
        n.style.overflow = o.style.overflow = "auto", n.style.height = o.style.height = "auto", re && a.setStyle(re, "transform", "none"), ee = r, re = r, ne = r, oe = r, Ae = 0, Fe = 1, ae = r, ie = r, Ce = "down", De = -1, Pe = 0, Ve = 0, ze = !1, le = r, se = r, ce = r, fe = r, ue = r, Ne = 0, pe = r, Oe = !1, qe = 0, me = r
    };
    var K = function () {
        var a, i, s, m, g, d, v, h, y, T, b;
        de(n, [c, f, u, p].join(" "), function (e) {
            var n = e.changedTouches[0];
            for (m = e.target; 3 === m.nodeType;) m = m.parentNode;
            switch (g = n.clientY, d = n.clientX, y = e.timeStamp, F.test(m.tagName) || e.preventDefault(), e.type) {
                case c:
                    a && a.blur(), ee.stopAnimateTo(), a = m, i = v = g, s = d, y;
                    break;
                case f:
                    h = g - v, b = y - T, ee.setScrollTop(qe - h, !0), v = g, T = y;
                    break;
                default:
                case u:
                case p:
                    var o = i - g, S = s - d;
                    if (S * S + o * o < 49) {
                        if (!F.test(a.tagName)) {
                            a.focus();
                            var w = t.createEvent("MouseEvents");
                            w.initMouseEvent("click", !0, !0, e.view, 1, n.screenX, n.screenY, n.clientX, n.clientY, e.ctrlKey, e.altKey, e.shiftKey, e.metaKey, 0, null), a.dispatchEvent(w)
                        }
                        return
                    }
                    a = r;
                    var k = h / b;
                    k = l.max(l.min(k, 3), -3);
                    var E = l.abs(k / ie), x = k * E + .5 * ie * E * E, A = ee.getScrollTop() - x, C = 0;
                    A > Ae ? (C = (Ae - A) / x, A = Ae) : A < 0 && (C = -A / x, A = 0), E *= 1 - C, ee.animateTo(A + .5 | 0, {
                        easing: "outCubic",
                        duration: E
                    })
            }
        }), e.scrollTo(0, 0), n.style.overflow = o.style.overflow = "hidden"
    }, Y = function () {
        ze && (ze = !1, ye());
        var e, t, n = ee.getScrollTop(), o = Ee();
        if (le) o >= le.endTime ? (n = le.targetTop, e = le.done, le = r) : (t = le.easing((o - le.startTime) / le.duration), n = le.startTop + t * le.topDiff | 0), ee.setScrollTop(n, !0); else if (!ue) {
            fe.targetTop - n && (fe = {
                startTop: De,
                topDiff: n - De,
                targetTop: n,
                startTime: He,
                endTime: He + ce
            }), o <= fe.endTime && (t = _.sqrt((o - fe.startTime) / ce), n = fe.startTop + t * fe.topDiff | 0)
        }
        if (Oe && re && a.setStyle(re, "transform", "translate(0, " + -qe + "px) " + me), ue || De !== n) {
            ue = !1;
            var l = {curTop: n, lastTop: De, maxTop: Ae, direction: Ce = n > De ? "down" : n < De ? "up" : Ce};
            !1 !== (ne.beforerender && ne.beforerender.call(ee, l)) && (!function (e, t) {
                for (var r = 0, n = te.length; r < n; r++) {
                    var o, l, s = te[r], c = s.element, f = s.smoothScrolling ? e : t, u = s.keyFrames,
                        p = f < u[0].frame, h = f > u[u.length - 1].frame, y = u[p ? 0 : u.length - 1];
                    if (p || h) {
                        if (p && -1 === s.edge || h && 1 === s.edge) continue;
                        switch (Se(c, [p ? g : v], [g, d, v]), s.edge = p ? -1 : 1, s.edgeStrategy) {
                            case"reset":
                                J(c);
                                continue;
                            case"ease":
                                f = y.frame;
                                break;
                            default:
                            case"set":
                                var T = y.props;
                                for (o in T) i.call(T, o) && (l = Z(T[o].value), a.setStyle(c, o, l));
                                continue
                        }
                    } else 0 !== s.edge && (Se(c, [m, d], [g, v]), s.edge = 0);
                    for (var b = 0, S = u.length - 1; b < S; b++) if (f >= u[b].frame && f <= u[b + 1].frame) {
                        var w = u[b], k = u[b + 1];
                        for (o in w.props) if (i.call(w.props, o)) {
                            var E = (f - w.frame) / (k.frame - w.frame);
                            E = w.props[o].easing(E), l = W(w.props[o].value, k.props[o].value, E), l = Z(l), a.setStyle(c, o, l)
                        }
                        break
                    }
                }
            }(n, ee.getScrollTop()), De = n, ne.render && ne.render.call(ee, l)), e && e.call(ee, !1)
        }
        He = o
    }, R = function (e) {
        for (var t = 0, r = e.keyFrames.length; t < r; t++) {
            for (var n, o, a, i, l = e.keyFrames[t], s = {}; null !== (i = H.exec(l.props));) a = i[1], o = i[2], null !== (n = a.match(P)) ? (a = n[1], n = n[2]) : n = S, o = o.indexOf("!") ? U(o) : [o.slice(1)], s[a] = {
                value: o,
                easing: _[n]
            };
            l.props = s
        }
    }, U = function (e) {
        var t = [];
        return q.lastIndex = 0, e = e.replace(q, function (e) {
            return e.replace(N, function (e) {
                return e / 255 * 100 + "%"
            })
        }), M && (I.lastIndex = 0, e = e.replace(I, function (e) {
            return M + e
        })), e = e.replace(N, function (e) {
            return t.push(+e), "{?}"
        }), t.unshift(e), t
    }, X = function (e) {
        var t, r, n = {};
        for (t = 0, r = e.keyFrames.length; t < r; t++) j(e.keyFrames[t], n);
        for (n = {}, t = e.keyFrames.length - 1; t >= 0; t--) j(e.keyFrames[t], n)
    }, j = function (e, t) {
        var r;
        for (r in t) i.call(e.props, r) || (e.props[r] = t[r]);
        for (r in e.props) t[r] = e.props[r]
    }, W = function (e, t, r) {
        var n, o = e.length;
        if (o !== t.length) throw"Can't interpolate between \"" + e[0] + '" and "' + t[0] + '"';
        var a = [e[0]];
        for (n = 1; n < o; n++) a[n] = e[n] + (t[n] - e[n]) * r;
        return a
    }, Z = function (e) {
        var t = 1;
        return O.lastIndex = 0, e[0].replace(O, function () {
            return e[t++]
        })
    }, J = function (e, t) {
        for (var r, n, o = 0, a = (e = [].concat(e)).length; o < a; o++) n = e[o], (r = te[n[A]]) && (t ? (n.style.cssText = r.dirtyStyleAttr, Se(n, r.dirtyClassAttr)) : (r.dirtyStyleAttr = n.style.cssText, r.dirtyClassAttr = be(n), n.style.cssText = r.styleAttr, Se(n, r.classAttr)))
    }, Q = function () {
        me = "translateZ(0)", a.setStyle(re, "transform", me);
        var e = s(re), t = e.getPropertyValue("transform"), r = e.getPropertyValue(M + "transform");
        t && "none" !== t || r && "none" !== r || (me = "")
    };
    a.setStyle = function (e, t, r) {
        var n = e.style;
        if ("zIndex" === (t = t.replace(V, z).replace("-", ""))) isNaN(r) ? n[t] = r : n[t] = "" + (0 | r); else if ("float" === t) n.styleFloat = n.cssFloat = r; else try {
            L && (n[L + t.slice(0, 1).toUpperCase() + t.slice(1)] = r), n[t] = r
        } catch (e) {
        }
    };
    var ee, te, re, ne, oe, ae, ie, le, se, ce, fe, ue, pe, me, ge, de = a.addEvent = function (t, r, n) {
        for (var o, a = function (t) {
            return (t = t || e.event).target || (t.target = t.srcElement), t.preventDefault || (t.preventDefault = function () {
                t.returnValue = !1
            }), n.call(this, t)
        }, i = 0, l = (r = r.split(" ")).length; i < l; i++) o = r[i], t.addEventListener ? t.addEventListener(o, n, !1) : t.attachEvent("on" + o, a), Ie.push({
            element: t,
            name: o,
            listener: n
        })
    }, ve = a.removeEvent = function (e, t, r) {
        for (var n = 0, o = (t = t.split(" ")).length; n < o; n++) e.removeEventListener ? e.removeEventListener(t[n], r, !1) : e.detachEvent("on" + t[n], r)
    }, he = function () {
        for (var e, t = 0, r = Ie.length; t < r; t++) e = Ie[t], ve(e.element, e.name, e.listener);
        Ie = []
    }, ye = function () {
        var e = ee.getScrollTop();
        Ae = 0, oe && !Oe && (o.style.height = "auto"), function () {
            var e, t, r, o, a, i, s, c, f;
            for (c = 0, f = te.length; c < f; c++) for (t = (e = te[c]).element, r = e.anchorTarget, a = 0, i = (o = e.keyFrames).length; a < i; a++) {
                var u = (s = o[a]).offset;
                s.isPercentage && (u *= n.clientHeight, s.frame = u), "relative" === s.mode && (J(t), s.frame = ee.relativeToAbsolute(r, s.anchors[0], s.anchors[1]) - u, J(t, !0)), oe && !s.isEnd && s.frame > Ae && (Ae = s.frame)
            }
            for (Ae = l.max(Ae, Te()), c = 0, f = te.length; c < f; c++) {
                for (a = 0, i = (o = (e = te[c]).keyFrames).length; a < i; a++) (s = o[a]).isEnd && (s.frame = Ae - s.offset);
                e.keyFrames.sort(xe)
            }
        }(), oe && !Oe && (o.style.height = Ae + n.clientHeight + "px"), Oe ? ee.setScrollTop(l.min(ee.getScrollTop(), Ae)) : ee.setScrollTop(e, !0), ue = !0
    }, Te = function () {
        var e = re && re.offsetHeight || 0;
        return l.max(e, o.scrollHeight, o.offsetHeight, n.scrollHeight, n.offsetHeight, n.clientHeight) - n.clientHeight
    }, be = function (t) {
        var r = "className";
        return e.SVGElement && t instanceof e.SVGElement && (t = t[r], r = "baseVal"), t[r]
    }, Se = function (t, n, o) {
        var a = "className";
        if (e.SVGElement && t instanceof e.SVGElement && (t = t[a], a = "baseVal"), o !== r) {
            for (var i = t[a], l = 0, s = o.length; l < s; l++) i = ke(i).replace(ke(o[l]), " ");
            i = we(i);
            for (var c = 0, f = n.length; c < f; c++) -1 === ke(i).indexOf(ke(n[c])) && (i += " " + n[c]);
            t[a] = we(i)
        } else t[a] = n
    }, we = function (e) {
        return e.replace(C, "")
    }, ke = function (e) {
        return " " + e + " "
    }, Ee = Date.now || function () {
        return +new Date
    }, xe = function (e, t) {
        return e.frame - t.frame
    }, Ae = 0, Fe = 1, Ce = "down", De = -1, He = Ee(), Pe = 0, Ve = 0, ze = !1, Ne = 0, Oe = !1, qe = 0, Ie = []
}(window, document);