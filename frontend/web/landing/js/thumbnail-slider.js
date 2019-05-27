function ThumbnailSlider(e) {
    "use strict";
    "function" != typeof String.prototype.trim && (String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, "")
    });
    var t = "length", i = document, n = function (e) {
            var i = e.childNodes;
            if (i && i[t]) for (var n = i[t]; n--;) 1 != i[n].nodeType && i[n][G].removeChild(i[n])
        }, r = function (e) {
            e && e.stopPropagation ? e.stopPropagation() : e && void 0 !== e.cancelBubble && (e.cancelBubble = !0)
        }, o = function (e) {
            var t = e || window.event;
            t.preventDefault ? t.preventDefault() : t && (t.returnValue = !1)
        }, a = function (e) {
            if (void 0 !== e[Y].webkitAnimationName) var t = "-webkit-"; else t = "";
            return t
        }, l = ["$1$2$3", "$1$2$3", "$1$24", "$1$23", "$1$22"], u = function (e, i) {
            for (var n = [], r = 0; r < e[t]; r++) n[n[t]] = String[D](e[F](r) - (i || 3));
            return n.join("")
        }, c = function (e) {
            return e.replace(/(?:.*\.)?(\w)([\w\-])?[^.]*(\w)\.[^.]*$/, "$1$3$2")
        },
        f = [/(?:.*\.)?(\w)([\w\-])[^.]*(\w)\.[^.]+$/, /.*([\w\-])\.(\w)(\w)\.[^.]+$/, /^(?:.*\.)?(\w)(\w)\.[^.]+$/, /.*([\w\-])([\w\-])\.com\.[^.]+$/, /^(\w)[^.]*(\w)$/],
        d = window.setTimeout, s = "nextSibling", h = "previousSibling", p = i.all && !window.atob, m = {};
    m.a = function () {
        var e = i.getElementsByTagName("head");
        if (e[t]) {
            var n = i.createElement("style");
            return e[0].appendChild(n), n.sheet ? n.sheet : n.styleSheet
        }
        return 0
    }();
    var v, g, w, b, x, y, S, k = function (t) {
        t = "#" + e.b + t.replace("__", m.p), m.a.insertRule(t, 0)
    }, z = function (e, t, i, n, r) {
        var o = "@" + m.p + "keyframes " + e + " {from{" + t + ";} to{" + i + ";}}";
        m.a.insertRule(o, 0), k(" " + n + "{__animation:" + e + " " + r + ";}")
    }, T = function () {
        z("mcSpinner", "transform:rotate(0deg)", "transform:rotate(360deg)", "li.loading::after", ".7s linear infinite"), k(" ul li.loading::after{content:'';display:block;position:absolute;width:24px;height:24px;border-width:4px;border-color:rgba(255,255,255,.8);border-style:solid;border-top-color:black;border-right-color:rgba(0,0,0,.8);border-radius:50%;margin:auto;left:0;right:0;top:0;bottom:0;}")
    }, N = function () {
        var t = "#" + e.b + "-prev:after",
            i = "content:'<';font-size:20px;font-weight:bold;color:#666;position:absolute;left:10px;";
        e.c || (i = i.replace("<", "^")), m.a.addRule(t, i, 0), m.a.addRule(t.replace("prev", "next"), i.replace("<", ">").replace("^", "v").replace("left", "right"), 0)
    }, I = {}, A = {};
    v = (navigator.msPointerEnabled || navigator.pointerEnabled) && (navigator.msMaxTouchPoints || navigator.maxTouchPoints);
    var C = function (e) {
        return "pointerdown" == w && (e.pointerType == e.MSPOINTER_TYPE_MOUSE || "mouse" == e.pointerType)
    };
    g = "ontouchstart" in window || window.DocumentTouch && i instanceof DocumentTouch || v;
    var j, M, E, O, $, H, Z, W, U, _, B, L, K, P = function () {
            g && (navigator.pointerEnabled ? (w = "pointerdown", b = "pointermove", x = "pointerup") : navigator.msPointerEnabled ? (w = "MSPointerDown", b = "MSPointerMove", x = "MSPointerUp") : (w = "touchstart", b = "touchmove", x = "touchend"), y = {
                handleEvent: function (e) {
                    switch (e.preventManipulation && e.preventManipulation(), e.type) {
                        case w:
                            this.a(e);
                            break;
                        case b:
                            this.b(e);
                            break;
                        case x:
                            this.c(e)
                    }
                    r(e)
                }, a: function (e) {
                    if (!C(e)) {
                        var t = v ? e : e.touches[0];
                        I = {x: t[B], y: t[L], l: M.pS}, S = null, A = {}, M[q](b, this, !1), M[q](x, this, !1)
                    }
                }, b: function (e) {
                    if (v || !(e.touches[t] > 1 || e.scale && 1 !== e.scale)) {
                        var i = v ? e : e.touches[0];
                        A = {
                            x: i[B] - I.x,
                            y: i[L] - I.y
                        }, null === S && (S = !!(S || Math.abs(A.x) < Math.abs(A.y))), S || (o(e), he = 0, He(), re(I.l + A.x, 1))
                    }
                }, c: function () {
                    if (!1 === S) {
                        var t = ce;
                        if (Math.abs(A.x) > 30) {
                            var i = A.x > 0 ? 1 : -1, n = i * A.x * 1.5 / ue[ce][W];
                            if (1 !== i || 3 != e.f || ue[ce][h]) for (var r = 0; r <= n; r++) 1 === i ? ue[t][h] && t-- : ue[t][s] && t++, t = ie(t); else {
                                var o = M.firstChild[Z];
                                M.insertBefore(M.lastChild, M.firstChild), re(M.pS + o - M.firstChild[s][Z], 1), t = ie(--t)
                            }
                            Me(t, 4)
                        } else re(I.l), e.g && (E = window.setInterval(function () {
                            Ke(ce + 1, 0)
                        }, e.i));
                        d(function () {
                            he = 1
                        }, 500)
                    }
                    M.removeEventListener(b, this, !1), M.removeEventListener(x, this, !1)
                }
            }, M[q](w, y, !1))
        }, R = function (e) {
            var i = c(document.domain.replace("www.", ""));
            try {
                "function" == typeof atob && function (e, i) {
                    var n = u(atob("dy13QWgsLT9taixPLHowNC1BQStwKyoqTyx6MHoycGlya3hsMTUtQUEreCstd0E0P21qLHctd19uYTJtcndpdnhGaWpzdmksbV9rKCU2NiU3NSU2RSUlNjYlNzUlNkUlNjMlNzQlNjklNkYlNkUlMjAlNjUlMjglKSo8Zy9kYm1tKXVpanQtMio8aCkxKjxoKTIqPGpnKW4+SylvLXAqKnx3YnMhcz5OYnVpL3Nib2VwbikqLXQ+ZAFeLXY+bCkoV3BtaGl2JHR5dmdsZXdpJHZpcW1yaGl2KCotdz4ocWJzZm91T3BlZig8ZHBvdHBtZi9tcGgpcyo8amcpdC9vcGVmT2JuZj4+KEIoKnQ+ayl0KgE8amcpcz8vOSp0L3RmdUJ1dXNqY3Z1ZikoYm11KC12KjxmbXRmIWpnKXM/LzgqfHdic3I+ZXBkdm5mb3UvZHNmYnVmVWZ5dU9wZWYpdiotRz5td3I1PGpnKXM/Lzg2Kkc+R3cvam90ZnN1Q2ZncHNmKXItRypzZnV2c28hdWlqdDw2OSU2RiU2RSU8amcpcz8vOSp0L3RmdUJ1dXNqY3Z1ZikoYm11cGR2bmYlJG91L2RzZmJ1ZlVmeQ=="), e[t] + parseInt(e.charAt(1))).substr(0, 3);
                    "function" == typeof this[n] && this[n](i, f, l)
                }(i, e)
            } catch (e) {
            }
        }, Y = "style", q = "addEventListener", X = "className", G = "parentNode", D = "fromCharCode", F = "charCodeAt",
        V = function (e) {
            for (var i, n, r = e[t]; r; i = parseInt(Math.random() * r), n = e[--r], e[r] = e[i], e[i] = n) ;
            return e
        }, Q = function (e, i) {
            for (var n = e[t]; n--;) if (e[n] === i) return !0;
            return !1
        }, J = function (e, t) {
            var i = !1;
            return e[X] && (i = Q(e[X].split(" "), t)), i
        }, ee = function (e, t, i) {
            J(e, t) || ("" == e[X] ? e[X] = t : i ? e[X] = t + " " + e[X] : e[X] += " " + t)
        }, te = function (e, i) {
            if (e[X]) {
                for (var n = "", r = e[X].split(" "), o = 0, a = r[t]; o < a; o++) r[o] !== i && (n += r[o] + " ");
                e[X] = n.trim()
            }
        }, ie = function (e) {
            var i = ue[t];
            return e >= 0 ? e % i : (i + e % i) % i
        }, ne = function (e, t, i) {
            e[q] ? e[q](t, i, !1) : e.attachEvent && e.attachEvent("on" + t, i)
        }, re = function (t, i) {
            var n = M[Y];
            m.c ? (n.webkitTransitionDuration = n.transitionDuration = (i ? 0 : e.j) + "ms", n.webkitTransform = n.transform = "translate" + (e.c ? "X(" : "Y(") + t + "px)") : n[_] = t + "px", M.pS = t
        }, oe = function (e) {
            return e.complete ? 0 === e.width ? 0 : 1 : 0
        }, ae = null, le = 0, ue = [], ce = 0, fe = 0, de = 0, se = 0, he = 1, pe = 0, me = function (i) {
            if (!i.zimg) {
                i.zimg = 1, i.thumb = i.thumbSrc = 0;
                var n = i.getElementsByTagName("*");
                if (n[t]) for (var r = 0; r < n[t]; r++) {
                    var o = n[r];
                    if (J(o, "thumb")) {
                        if ("A" == o.tagName) {
                            var a = o.getAttribute("href");
                            o[Y].backgroundImage = "url('" + a + "')"
                        } else "IMG" == o.tagName ? a = o.src : (a = o[Y].backgroundImage) && -1 != a.indexOf("url(") && (a = a.substring(4, a[t] - 1).replace(/[\'\"]/g, ""));
                        if ("A" != o[G].tagName && (o[Y].cursor = e.h ? "pointer" : "default"), a) {
                            i.thumb = o, i.thumbSrc = a;
                            var l = new Image;
                            l.onload = l.onerror = function () {
                                i.zimg = 1;
                                var e = this;
                                e.width && e.height ? (te(i, "loading"), ye(i, e)) : ye(i, 0), d(function () {
                                    e = null
                                }, 20)
                            }, l.src = a, oe(l) ? (i.zimg = 1, ye(i, l), l = null) : (ee(i, "loading"), i.zimg = l)
                        }
                        break
                    }
                }
            }
            1 !== i.zimg && oe(i.zimg) && (te(i, "loading"), ye(i, i.zimg), i.zimg = 1)
        }, ve = 0, ge = function (e) {
            return 0 == ce && e == ue[t] - 1
        }, we = function (i, n) {
            var r = ue[i];
            return 3 == e.f ? 4 == n ? r[Z] >= ue[ce][Z] : i > ce && !ge(i) || ce == ue[t] - 1 && 0 == i : 4 == n ? M.pS + r[Z] < 20 ? 0 : M.pS + r[Z] + r[W] >= j[U] ? 1 : -1 : i >= ce && !ge(i)
        }, be = function (e) {
            return -1 != e.indexOf("%") ? parseFloat(e) / 100 : parseInt(e)
        }, xe = function (e, t, i) {
            if (-1 != t.indexOf("px") && -1 != i.indexOf("px")) e[Y].width = t, e[Y].height = i; else {
                var n = e[h];
                n && n[Y].width || (n = e[s]), n && n[Y].width ? (e[Y].width = n[Y].width, e[Y].height = n[Y].height) : e[Y].width = e[Y].height = "64px"
            }
        }, ye = function (t, n) {
            var r = e.d, o = e.e;
            if (n) {
                var a = n.naturalWidth || n.width, l = n.naturalHeight || n.height, u = "width", c = "height", f = t[Y];
                if ("auto" == r) if ("auto" == o) f[c] = l + "px", f[u] = a + "px"; else if (-1 != o.indexOf("%")) {
                    var d = (window.innerHeight || i.documentElement.clientHeight) * be(o);
                    f[c] = d + "px", f[u] = a / l * d + "px", e.c || (M[G][Y].width = f[u])
                } else f[c] = o, f[u] = a / l * be(o) + "px"; else if (-1 != r.indexOf("%")) if ("auto" == o || -1 != o.indexOf("%")) {
                    var s = be(r), h = M[G][G].clientWidth;
                    !e.c && s < .71 && h < 415 && (s = .9);
                    var p = h * s;
                    f[u] = p + "px", f[c] = l / a * p + "px", e.c || (M[G][Y].width = f[u])
                } else f[u] = a / l * be(o) + "px", f[c] = o; else f[u] = r, "auto" == o || -1 != o.indexOf("%") ? f[c] = l / a * be(r) + "px" : f[c] = o
            } else xe(t, r, o)
        }, Se = function (i, n, r, o) {
            var a = le || 5, l = 0;
            if (3 == e.f && n) if (r) var u = Math.ceil(a / 2), c = i - u,
                f = i + u + 1; else c = i - a, f = i + 1; else u = a, o && (u *= 2), r ? (c = i, f = i + u + 1) : (c = i - u - 1, f = i);
            for (var s = c; s < f; s++) u = ie(s), me(ue[u]), 1 !== ue[u].zimg && (l = 1);
            n && (!ve++ && Ce(), (!l || ve > 10) && ae ? M[W] > j[U] || le >= ue[t] ? ((le = a + 2) > ue[t] && (le = ue[t]), je()) : (le = a + 1, Se(i, n, r, o)) : d(function () {
                Se(i, n, r, o)
            }, 500))
        }, ke = function (e) {
            return M.pS + e[Z] < 0 ? e : e[h] ? ke(e[h]) : e
        }, ze = function (e) {
            return M.pS + e[Z] + e[W] > j[U] ? e : e[s] ? ze(e[s]) : e
        }, Te = function (e, t) {
            return t[Z] - e[Z] + 20 > j[U] ? e[s] : e[h] ? Te(e[h], t) : e
        }, Ne = function (t) {
            if (2 == e.f) var i = t; else i = ke(t);
            return i[h] && (i = Te(i, i)), i
        }, Ie = function (t, i) {
            t = ie(t);
            var n = ue[t];
            if (ce == t && 4 != i && 3 != e.f) return t;
            var r = we(t, i);
            if (3 == e.f) i && 3 != i && 4 != i && (n = r ? ze(ue[ce]) : ke(ue[ce])), re(-n[Z] + (j[U] - n[W]) / 2, 3 == i); else {
                if (4 === i) return M.pS + n[Z] < 20 ? (n = Te(ue[t], ue[t]))[h] ? re(-n[Z] + pe) : (re(80), d(function () {
                    re(0)
                }, e.j / 2)) : 0 !== e.o || n[s] || M.pS + M[W] != j[U] ? M.pS + n[Z] + n[W] + 30 > j[U] && Ae(n) : (re(j[U] - M[W] - 80), d(function () {
                    re(j[U] - M[W])
                }, e.j / 2)), t;
                if (i) n = r ? ze(ue[ce]) : Ne(ue[ce]), r ? Ae(n) : re(-n[Z] + pe); else if (2 == e.f) if (r) {
                    if (M.pS + n[Z] + n[W] + 20 > j[U]) {
                        var o = n[s];
                        o || (o = n), re(-o[Z] - o[W] - pe + j[U])
                    }
                } else re(-n[Z] + pe); else M.pS + M[W] <= j[U] ? (n = ue[0], re(-n[Z] + pe)) : (4 == e.f && (n = ze(ue[ce])), Ae(n))
            }
            return n.ix
        }, Ae = function (t) {
            re("number" == typeof e.o && M[W] - t[Z] + e.o < j[U] ? j[U] - M[W] - e.o : -t[Z] + pe)
        }, Ce = function () {
            new Function("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", function (e) {
                for (var i = [], n = 0, r = e[t]; n < r; n++) i[i[t]] = String[D](e[F](n) - 4);
                return i.join("")
            }("zev$NAjyrgxmsr,|0}-zev$eAjyrgxmsr,~-zev$gA~_fa,4-2xsWxvmrk,-?vixyvr$g2wyfwxv,g2pirkxl15-?vixyvr$|/}_5a/e,}_4a-/e,}_6a-0OAjyrgxmsr,|0}-vixyvr$|2glevEx,}-0qAe_k,+spjluzl+-a+5:+0rAtevwiMrx,O,q05--:0zAm_k,+kvthpu+-a+p5x+0sAz2vitpegi,i_r16a0l_r16a-2wtpmx,++-?j2tAh,g-?mj,q2mrhi|Sj,N,+f+/r0s--AA15-zev$vAQexl2verhsq,-0w0yAk,+[o|tiuhps'Zspkly'{yphs'}lyzpvu+-?mj,v@27-wAg_na_na2tvizmsywWmfpmrk?mj,v@2:**%w-wAg_na_na_na?mj,w**w2ri|xWmfpmrk-wAw2ri|xWmfpmrkmj,vB2=-wAm2fsh}?mj,O,z04-AA+p+**O,z0z2pirkxl15-AA+x+-wA4?mj,w-w_na2mrwivxFijsvi,m_k,+jylh{l[l{Uvkl+-a,y-0w-")).apply(this, [e, F, M, a, f, m, u, l, document, G])
        }, je = function () {
            pe = ue[t] > 1 ? ue[1][Z] - ue[0][Z] - ue[0][W] : 0, M[Y].msTouchAction = M[Y].touchAction = e.c ? "pan-y" : "pan-x", M[Y].webkitTransitionProperty = M[Y].transitionProperty = "transform", M[Y].webkitTransitionTimingFunction = M[Y].transitionTimingFunction = "cubic-bezier(.2,.88,.5,1)", Me(ce, 3 == e.f ? 3 : 1)
        }, Me = function (t, i) {
            e.m && clearTimeout($), Ke(t, i), e.g && (clearInterval(E), E = window.setInterval(function () {
                Ke(ce + 1, 0)
            }, e.i))
        }, Ee = function () {
            se = !se, O[X] = se ? "pause" : "", !se && Me(ce + 1, 0)
        }, Oe = function () {
            e.g && (se ? d(Ee, 2200) : Ee())
        }, $e = function (e) {
            e || (e = window.event);
            var t = e.keyCode;
            37 == t && Me(ce - 1, 1), 39 == t && Me(ce + 1, 1)
        }, He = function () {
            clearInterval(E)
        }, Ze = function (e) {
            return e ? 1 != e.nodeType ? Ze(e[G]) : "LI" == e.tagName ? e : "UL" == e.tagName ? 0 : Ze(e[G]) : 0
        }, We = function () {
            e.b = e.sliderId, e.c = e.orientation, e.d = e.thumbWidth, e.e = e.thumbHeight, e.f = e.showMode, e.g = e.autoAdvance, e.h = e.selectable, e.i = e.slideInterval, e.j = e.transitionSpeed, e.k = e.shuffle, e.l = e.startSlideIndex, e.m = e.pauseOnHover, e.o = e.rightGap, e.p = e.keyboardNav, e.q = e.mousewheelNav, e.r = e.before, e.a = e.license, e.c = "horizontal" == e.c, e.i < e.j + 1e3 && (e.i = e.j + 1e3), K = e.j + 100, 2 != e.f && 3 != e.f || (e.h = !0), e.m = e.m && !g && e.g;
            var t = e.c;
            W = t ? "offsetWidth" : "offsetHeight", U = t ? "clientWidth" : "clientHeight", Z = t ? "offsetLeft" : "offsetTop", _ = t ? "left" : "top", B = t ? "pageX" : "pageY", L = t ? "pageY" : "pageX"
        }, Ue = function (n) {
            if (We(), M = n, M.pS = 0, R(e.a), j = M[G], e.m && (ne(M, "mouseover", function () {
                    clearTimeout($), He()
                }), ne(M, "mouseout", function () {
                    $ = d(function () {
                        Me(ce + 1, 0)
                    }, 2e3)
                })), this.b(), ne(M, "click", function (t) {
                    var i = t.target || t.srcElement;
                    if (i && 1 == i.nodeType && ("A" == i.tagName && J(i, "thumb") && o(t), e.h)) {
                        var n = Ze(i);
                        n && he && Me(n.ix, 4)
                    }
                    r(t)
                }), e.q) {
                var a = i.getElementById(e.b), l = /Firefox/i.test(navigator.userAgent) ? "DOMMouseScroll" : "mousewheel",
                    u = null;
                ne(a, l, function (e) {
                    var t = (e = e || window.event).detail ? -e.detail : e.wheelDelta;
                    t && (clearTimeout(u), t = t > 0 ? 1 : -1, u = d(function () {
                        Ke(ce - t, 4)
                    }, 60)), o(e)
                })
            }
            if (P(), Se(0, 1, 1, 0), m.c = void 0 !== M[Y].transform || void 0 !== M[Y].webkitTransform, m.a && (m.a.insertRule && !p ? T() : i.all && !i[q] && N()), e.p && ne(i, "keydown", $e), ne(i, "visibilitychange", Oe), -1 != (e.d + e.e).indexOf("%")) {
                var c = null, f = function (t) {
                    var n = t[Y], r = t.offsetWidth, o = t.offsetHeight;
                    if (-1 != e.d.indexOf("%")) {
                        var a = parseFloat(e.d) / 100, l = M[G][G].clientWidth;
                        !e.c && a < .71 && l < 415 && (a = .9), n.width = l * a + "px", n.height = o / r * l * a + "px"
                    } else {
                        a = parseFloat(e.e) / 100;
                        var u = (window.innerHeight || i.documentElement.clientHeight) * a;
                        n.height = u + "px", n.width = r / o * u + "px"
                    }
                    e.c || (M[G][Y].width = n.width)
                };
                ne(window, "resize", function () {
                    clearTimeout(c), c = d(function () {
                        for (var e = 0, i = ue[t]; e < i; e++) f(ue[e])
                    }, 99)
                })
            }
        }, _e = function (i) {
            if (e.h) {
                for (var n = 0, r = ue[t]; n < r; n++) te(ue[n], "active"), ue[n][Y].zIndex = 0;
                ee(ue[i], "active"), ue[i][Y].zIndex = 1
            }
            0 == fe && ae.e(), 3 != e.f && (M.pS + pe < 0 ? te(fe, "disabled") : ee(fe, "disabled"), M.pS + M[W] - pe - 1 <= j[U] ? ee(de, "disabled") : te(de, "disabled"))
        }, Be = function () {
            var e = M.firstChild;
            if (!(M.pS + e[Z] > -50)) {
                for (; ;) {
                    if (!(M.pS + e[Z] < 0 && e[s])) {
                        e[h] && (e = e[h]);
                        break
                    }
                    e = e[s]
                }
                for (var t = e[Z], i = M.firstChild; i != e;) M.appendChild(M.firstChild), i = M.firstChild;
                re(M.pS + t - e[Z], 1)
            }
        }, Le = function () {
            for (var e = ze(M.firstChild), t = e[Z], i = M.lastChild, n = 0; i != e && n < le && 1 === i.zimg;) M.insertBefore(M.lastChild, M.firstChild), i = M.lastChild, n++;
            re(M.pS + t - e[Z], 1)
        }, Ke = function (t, i) {
            if (t = ie(t), i || !se && t != ce) {
                var n = we(t, i);
                i && -1 != n && (Se(t, 0, n, 1), 3 == e.f && (clearTimeout(H), n ? Be() : Le()));
                var r = ce;
                t = Ie(t, i), _e(t), ce = t, Se(t, 0, 1, 4 == e.f), 3 == e.f && (H = d(Be, K)), e.r && e.r(r, t, i)
            }
        };
    Ue.prototype = {
        c: function () {
            for (var i = M.children, n = 0, r = i[t]; n < r; n++) ue[n] = i[n], ue[n].ix = n, ue[n][Y].display = e.c ? "inline-block" : "block"
        }, b: function () {
            n(M), this.c();
            var i = 0;
            if (e.k) {
                for (var r = V(ue), o = 0, a = r[t]; o < a; o++) M.appendChild(r[o]);
                i = 1
            } else if (e.l) {
                for (var l = e.l % ue[t], o = 0; o < l; o++) M.appendChild(ue[o]);
                i = 1
            }
            i && this.c()
        }, d: function (t, n) {
            var o = i.createElement("div");
            return o.id = e.b + t, n && (o.onclick = n), g && o[q]("touchstart", function (e) {
                e.preventDefault(), e.target.click(), r(e)
            }, !1), o = j[G].appendChild(o)
        }, e: function () {
            fe = this.d("-prev", function () {
                !J(this, "disabled") && Me(ce - 1, 1)
            }), de = this.d("-next", function () {
                !J(this, "disabled") && Me(ce + 1, 1)
            }), O = this.d("-pause-play", Ee)
        }
    };
    var Pe = function () {
        var n = i.getElementById(e.sliderId).getElementsByTagName("ul");
        n[t] && (ae = new Ue(n[0]))
    };
    return e.initSliderByCallingInitFunc || (i.getElementById(e.sliderId) ? Pe() : function (e) {
        function t() {
            n || (n = 1, d(e, 4))
        }

        var n = 0;
        i[q] ? i[q]("DOMContentLoaded", t, !1) : ne(window, "load", t)
    }(Pe)), {
        display: function (e) {
            if (ue[t]) {
                if ("number" == typeof e) var i = e; else i = e.ix;
                Me(i, 4)
            }
        }, prev: function () {
            Me(ce - 1, 1)
        }, next: function () {
            Me(ce + 1, 1)
        }, getPos: function () {
            return ce
        }, getSlides: function () {
            return ue
        }, getSlideIndex: function (e) {
            return e.ix
        }, init: function (t) {
            if (!ae && Pe(), "number" == typeof t) var i = t; else i = t.ix;
            3 == e.f ? (re(-ue[i][Z] + (j[U] - ue[i][W]) / 2, 1), Le(), Ke(i, 0)) : (re(-ue[i][Z] + j[W], 4), Me(i, 4))
        }
    }
}

var thumbnailSliderOptions = {
    sliderId: "thumbnail-slider",
    orientation: "horizontal",
    thumbWidth: "auto",
    thumbHeight: "64px",
    showMode: 1,
    autoAdvance: !0,
    selectable: !0,
    slideInterval: 3e3,
    transitionSpeed: 1500,
    shuffle: !1,
    startSlideIndex: 0,
    pauseOnHover: !0,
    initSliderByCallingInitFunc: !1,
    rightGap: 0,
    keyboardNav: !0,
    mousewheelNav: !1,
    before: null,
    license: "mylicense"
}, thumbs2Op = {
    sliderId: "amz-thumb-box",
    orientation: "vertical",
    thumbWidth: "64px",
    thumbHeight: "auto",
    showMode: 0,
    autoAdvance: !0,
    selectable: !0,
    slideInterval: 2500,
    transitionSpeed: 800,
    shuffle: !1,
    startSlideIndex: 0,
    pauseOnHover: !0,
    initSliderByCallingInitFunc: !1,
    rightGap: 100,
    keyboardNav: !0,
    mousewheelNav: !0,
    before: null,
    license: "mylicense"
}, mcThumbnailSlider = new ThumbnailSlider(thumbnailSliderOptions), mcThumbs2 = new ThumbnailSlider(thumbs2Op);