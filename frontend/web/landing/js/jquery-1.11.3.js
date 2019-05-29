!function (e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function (e) {
        if (!e.document) throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function (e, t) {
    var n = [], r = n.slice, i = n.concat, o = n.push, a = n.indexOf, s = {}, l = s.toString, u = s.hasOwnProperty,
        c = {}, f = "1.11.3", d = function (e, t) {
            return new d.fn.init(e, t)
        }, p = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, h = /^-ms-/, m = /-([\da-z])/gi, g = function (e, t) {
            return t.toUpperCase()
        };

    function y(e) {
        var t = "length" in e && e.length, n = d.type(e);
        return "function" !== n && !d.isWindow(e) && (!(1 !== e.nodeType || !t) || ("array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e))
    }

    d.fn = d.prototype = {
        jquery: f, constructor: d, selector: "", length: 0, toArray: function () {
            return r.call(this)
        }, get: function (e) {
            return null != e ? e < 0 ? this[e + this.length] : this[e] : r.call(this)
        }, pushStack: function (e) {
            var t = d.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        }, each: function (e, t) {
            return d.each(this, e, t)
        }, map: function (e) {
            return this.pushStack(d.map(this, function (t, n) {
                return e.call(t, n, t)
            }))
        }, slice: function () {
            return this.pushStack(r.apply(this, arguments))
        }, first: function () {
            return this.eq(0)
        }, last: function () {
            return this.eq(-1)
        }, eq: function (e) {
            var t = this.length, n = +e + (e < 0 ? t : 0);
            return this.pushStack(n >= 0 && n < t ? [this[n]] : [])
        }, end: function () {
            return this.prevObject || this.constructor(null)
        }, push: o, sort: n.sort, splice: n.splice
    }, d.extend = d.fn.extend = function () {
        var e, t, n, r, i, o, a = arguments[0] || {}, s = 1, l = arguments.length, u = !1;
        for ("boolean" == typeof a && (u = a, a = arguments[s] || {}, s++), "object" == typeof a || d.isFunction(a) || (a = {}), s === l && (a = this, s--); s < l; s++) if (null != (i = arguments[s])) for (r in i) e = a[r], a !== (n = i[r]) && (u && n && (d.isPlainObject(n) || (t = d.isArray(n))) ? (t ? (t = !1, o = e && d.isArray(e) ? e : []) : o = e && d.isPlainObject(e) ? e : {}, a[r] = d.extend(u, o, n)) : void 0 !== n && (a[r] = n));
        return a
    }, d.extend({
        expando: "jQuery" + (f + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (e) {
            throw new Error(e)
        }, noop: function () {
        }, isFunction: function (e) {
            return "function" === d.type(e)
        }, isArray: Array.isArray || function (e) {
            return "array" === d.type(e)
        }, isWindow: function (e) {
            return null != e && e == e.window
        }, isNumeric: function (e) {
            return !d.isArray(e) && e - parseFloat(e) + 1 >= 0
        }, isEmptyObject: function (e) {
            var t;
            for (t in e) return !1;
            return !0
        }, isPlainObject: function (e) {
            var t;
            if (!e || "object" !== d.type(e) || e.nodeType || d.isWindow(e)) return !1;
            try {
                if (e.constructor && !u.call(e, "constructor") && !u.call(e.constructor.prototype, "isPrototypeOf")) return !1
            } catch (e) {
                return !1
            }
            if (c.ownLast) for (t in e) return u.call(e, t);
            for (t in e) ;
            return void 0 === t || u.call(e, t)
        }, type: function (e) {
            return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? s[l.call(e)] || "object" : typeof e
        }, globalEval: function (t) {
            t && d.trim(t) && (e.execScript || function (t) {
                e.eval.call(e, t)
            })(t)
        }, camelCase: function (e) {
            return e.replace(h, "ms-").replace(m, g)
        }, nodeName: function (e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        }, each: function (e, t, n) {
            var r = 0, i = e.length, o = y(e);
            if (n) {
                if (o) for (; r < i && !1 !== t.apply(e[r], n); r++) ; else for (r in e) if (!1 === t.apply(e[r], n)) break
            } else if (o) for (; r < i && !1 !== t.call(e[r], r, e[r]); r++) ; else for (r in e) if (!1 === t.call(e[r], r, e[r])) break;
            return e
        }, trim: function (e) {
            return null == e ? "" : (e + "").replace(p, "")
        }, makeArray: function (e, t) {
            var n = t || [];
            return null != e && (y(Object(e)) ? d.merge(n, "string" == typeof e ? [e] : e) : o.call(n, e)), n
        }, inArray: function (e, t, n) {
            var r;
            if (t) {
                if (a) return a.call(t, e, n);
                for (r = t.length, n = n ? n < 0 ? Math.max(0, r + n) : n : 0; n < r; n++) if (n in t && t[n] === e) return n
            }
            return -1
        }, merge: function (e, t) {
            for (var n = +t.length, r = 0, i = e.length; r < n;) e[i++] = t[r++];
            if (n != n) for (; void 0 !== t[r];) e[i++] = t[r++];
            return e.length = i, e
        }, grep: function (e, t, n) {
            for (var r = [], i = 0, o = e.length, a = !n; i < o; i++) !t(e[i], i) !== a && r.push(e[i]);
            return r
        }, map: function (e, t, n) {
            var r, o = 0, a = e.length, s = [];
            if (y(e)) for (; o < a; o++) null != (r = t(e[o], o, n)) && s.push(r); else for (o in e) null != (r = t(e[o], o, n)) && s.push(r);
            return i.apply([], s)
        }, guid: 1, proxy: function (e, t) {
            var n, i, o;
            if ("string" == typeof t && (o = e[t], t = e, e = o), d.isFunction(e)) return n = r.call(arguments, 2), (i = function () {
                return e.apply(t || this, n.concat(r.call(arguments)))
            }).guid = e.guid = e.guid || d.guid++, i
        }, now: function () {
            return +new Date
        }, support: c
    }), d.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (e, t) {
        s["[object " + t + "]"] = t.toLowerCase()
    });
    var v = function (e) {
        var t, n, r, i, o, a, s, l, u, c, f, d, p, h, m, g, y, v, b, x = "sizzle" + 1 * new Date, w = e.document, T = 0,
            C = 0, N = ae(), E = ae(), k = ae(), S = function (e, t) {
                return e === t && (f = !0), 0
            }, A = 1 << 31, D = {}.hasOwnProperty, j = [], L = j.pop, H = j.push, q = j.push, _ = j.slice,
            M = function (e, t) {
                for (var n = 0, r = e.length; n < r; n++) if (e[n] === t) return n;
                return -1
            },
            F = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            O = "[\\x20\\t\\r\\n\\f]", B = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", P = B.replace("w", "w#"),
            R = "\\[" + O + "*(" + B + ")(?:" + O + "*([*^$|!~]?=)" + O + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + P + "))|)" + O + "*\\]",
            W = ":(" + B + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + R + ")*)|.*)\\)|)",
            $ = new RegExp(O + "+", "g"), z = new RegExp("^" + O + "+|((?:^|[^\\\\])(?:\\\\.)*)" + O + "+$", "g"),
            I = new RegExp("^" + O + "*," + O + "*"), X = new RegExp("^" + O + "*([>+~]|" + O + ")" + O + "*"),
            U = new RegExp("=" + O + "*([^\\]'\"]*?)" + O + "*\\]", "g"), V = new RegExp(W),
            J = new RegExp("^" + P + "$"), Y = {
                ID: new RegExp("^#(" + B + ")"),
                CLASS: new RegExp("^\\.(" + B + ")"),
                TAG: new RegExp("^(" + B.replace("w", "w*") + ")"),
                ATTR: new RegExp("^" + R),
                PSEUDO: new RegExp("^" + W),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + O + "*(even|odd|(([+-]|)(\\d*)n|)" + O + "*(?:([+-]|)" + O + "*(\\d+)|))" + O + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + F + ")$", "i"),
                needsContext: new RegExp("^" + O + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + O + "*((?:-\\d)?\\d*)" + O + "*\\)|)(?=[^-]|$)", "i")
            }, G = /^(?:input|select|textarea|button)$/i, Q = /^h\d$/i, K = /^[^{]+\{\s*\[native \w/,
            Z = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, ee = /[+~]/, te = /'|\\/g,
            ne = new RegExp("\\\\([\\da-f]{1,6}" + O + "?|(" + O + ")|.)", "ig"), re = function (e, t, n) {
                var r = "0x" + t - 65536;
                return r != r || n ? t : r < 0 ? String.fromCharCode(r + 65536) : String.fromCharCode(r >> 10 | 55296, 1023 & r | 56320)
            }, ie = function () {
                d()
            };
        try {
            q.apply(j = _.call(w.childNodes), w.childNodes), j[w.childNodes.length].nodeType
        } catch (e) {
            q = {
                apply: j.length ? function (e, t) {
                    H.apply(e, _.call(t))
                } : function (e, t) {
                    for (var n = e.length, r = 0; e[n++] = t[r++];) ;
                    e.length = n - 1
                }
            }
        }

        function oe(e, t, r, i) {
            var o, s, u, c, f, h, y, v, T, C;
            if ((t ? t.ownerDocument || t : w) !== p && d(t), t = t || p, r = r || [], c = t.nodeType, "string" != typeof e || !e || 1 !== c && 9 !== c && 11 !== c) return r;
            if (!i && m) {
                if (11 !== c && (o = Z.exec(e))) if (u = o[1]) {
                    if (9 === c) {
                        if (!(s = t.getElementById(u)) || !s.parentNode) return r;
                        if (s.id === u) return r.push(s), r
                    } else if (t.ownerDocument && (s = t.ownerDocument.getElementById(u)) && b(t, s) && s.id === u) return r.push(s), r
                } else {
                    if (o[2]) return q.apply(r, t.getElementsByTagName(e)), r;
                    if ((u = o[3]) && n.getElementsByClassName) return q.apply(r, t.getElementsByClassName(u)), r
                }
                if (n.qsa && (!g || !g.test(e))) {
                    if (v = y = x, T = t, C = 1 !== c && e, 1 === c && "object" !== t.nodeName.toLowerCase()) {
                        for (h = a(e), (y = t.getAttribute("id")) ? v = y.replace(te, "\\$&") : t.setAttribute("id", v), v = "[id='" + v + "'] ", f = h.length; f--;) h[f] = v + ge(h[f]);
                        T = ee.test(e) && he(t.parentNode) || t, C = h.join(",")
                    }
                    if (C) try {
                        return q.apply(r, T.querySelectorAll(C)), r
                    } catch (e) {
                    } finally {
                        y || t.removeAttribute("id")
                    }
                }
            }
            return l(e.replace(z, "$1"), t, r, i)
        }

        function ae() {
            var e = [];
            return function t(n, i) {
                return e.push(n + " ") > r.cacheLength && delete t[e.shift()], t[n + " "] = i
            }
        }

        function se(e) {
            return e[x] = !0, e
        }

        function le(e) {
            var t = p.createElement("div");
            try {
                return !!e(t)
            } catch (e) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function ue(e, t) {
            for (var n = e.split("|"), i = e.length; i--;) r.attrHandle[n[i]] = t
        }

        function ce(e, t) {
            var n = t && e,
                r = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || A) - (~e.sourceIndex || A);
            if (r) return r;
            if (n) for (; n = n.nextSibling;) if (n === t) return -1;
            return e ? 1 : -1
        }

        function fe(e) {
            return function (t) {
                return "input" === t.nodeName.toLowerCase() && t.type === e
            }
        }

        function de(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }

        function pe(e) {
            return se(function (t) {
                return t = +t, se(function (n, r) {
                    for (var i, o = e([], n.length, t), a = o.length; a--;) n[i = o[a]] && (n[i] = !(r[i] = n[i]))
                })
            })
        }

        function he(e) {
            return e && void 0 !== e.getElementsByTagName && e
        }

        n = oe.support = {}, o = oe.isXML = function (e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return !!t && "HTML" !== t.nodeName
        }, d = oe.setDocument = function (e) {
            var t, i, a = e ? e.ownerDocument || e : w;
            return a !== p && 9 === a.nodeType && a.documentElement ? (p = a, h = a.documentElement, (i = a.defaultView) && i !== i.top && (i.addEventListener ? i.addEventListener("unload", ie, !1) : i.attachEvent && i.attachEvent("onunload", ie)), m = !o(a), n.attributes = le(function (e) {
                return e.className = "i", !e.getAttribute("className")
            }), n.getElementsByTagName = le(function (e) {
                return e.appendChild(a.createComment("")), !e.getElementsByTagName("*").length
            }), n.getElementsByClassName = K.test(a.getElementsByClassName), n.getById = le(function (e) {
                return h.appendChild(e).id = x, !a.getElementsByName || !a.getElementsByName(x).length
            }), n.getById ? (r.find.ID = function (e, t) {
                if (void 0 !== t.getElementById && m) {
                    var n = t.getElementById(e);
                    return n && n.parentNode ? [n] : []
                }
            }, r.filter.ID = function (e) {
                var t = e.replace(ne, re);
                return function (e) {
                    return e.getAttribute("id") === t
                }
            }) : (delete r.find.ID, r.filter.ID = function (e) {
                var t = e.replace(ne, re);
                return function (e) {
                    var n = void 0 !== e.getAttributeNode && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }), r.find.TAG = n.getElementsByTagName ? function (e, t) {
                return void 0 !== t.getElementsByTagName ? t.getElementsByTagName(e) : n.qsa ? t.querySelectorAll(e) : void 0
            } : function (e, t) {
                var n, r = [], i = 0, o = t.getElementsByTagName(e);
                if ("*" === e) {
                    for (; n = o[i++];) 1 === n.nodeType && r.push(n);
                    return r
                }
                return o
            }, r.find.CLASS = n.getElementsByClassName && function (e, t) {
                if (m) return t.getElementsByClassName(e)
            }, y = [], g = [], (n.qsa = K.test(a.querySelectorAll)) && (le(function (e) {
                h.appendChild(e).innerHTML = "<a id='" + x + "'></a><select id='" + x + "-\f]' msallowcapture=''><option selected=''></option></select>", e.querySelectorAll("[msallowcapture^='']").length && g.push("[*^$]=" + O + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || g.push("\\[" + O + "*(?:value|" + F + ")"), e.querySelectorAll("[id~=" + x + "-]").length || g.push("~="), e.querySelectorAll(":checked").length || g.push(":checked"), e.querySelectorAll("a#" + x + "+*").length || g.push(".#.+[+~]")
            }), le(function (e) {
                var t = a.createElement("input");
                t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && g.push("name" + O + "*[*^$|!~]?="), e.querySelectorAll(":enabled").length || g.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), g.push(",.*:")
            })), (n.matchesSelector = K.test(v = h.matches || h.webkitMatchesSelector || h.mozMatchesSelector || h.oMatchesSelector || h.msMatchesSelector)) && le(function (e) {
                n.disconnectedMatch = v.call(e, "div"), v.call(e, "[s!='']:x"), y.push("!=", W)
            }), g = g.length && new RegExp(g.join("|")), y = y.length && new RegExp(y.join("|")), t = K.test(h.compareDocumentPosition), b = t || K.test(h.contains) ? function (e, t) {
                var n = 9 === e.nodeType ? e.documentElement : e, r = t && t.parentNode;
                return e === r || !(!r || 1 !== r.nodeType || !(n.contains ? n.contains(r) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(r)))
            } : function (e, t) {
                if (t) for (; t = t.parentNode;) if (t === e) return !0;
                return !1
            }, S = t ? function (e, t) {
                if (e === t) return f = !0, 0;
                var r = !e.compareDocumentPosition - !t.compareDocumentPosition;
                return r || (1 & (r = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1) || !n.sortDetached && t.compareDocumentPosition(e) === r ? e === a || e.ownerDocument === w && b(w, e) ? -1 : t === a || t.ownerDocument === w && b(w, t) ? 1 : c ? M(c, e) - M(c, t) : 0 : 4 & r ? -1 : 1)
            } : function (e, t) {
                if (e === t) return f = !0, 0;
                var n, r = 0, i = e.parentNode, o = t.parentNode, s = [e], l = [t];
                if (!i || !o) return e === a ? -1 : t === a ? 1 : i ? -1 : o ? 1 : c ? M(c, e) - M(c, t) : 0;
                if (i === o) return ce(e, t);
                for (n = e; n = n.parentNode;) s.unshift(n);
                for (n = t; n = n.parentNode;) l.unshift(n);
                for (; s[r] === l[r];) r++;
                return r ? ce(s[r], l[r]) : s[r] === w ? -1 : l[r] === w ? 1 : 0
            }, a) : p
        }, oe.matches = function (e, t) {
            return oe(e, null, null, t)
        }, oe.matchesSelector = function (e, t) {
            if ((e.ownerDocument || e) !== p && d(e), t = t.replace(U, "='$1']"), n.matchesSelector && m && (!y || !y.test(t)) && (!g || !g.test(t))) try {
                var r = v.call(e, t);
                if (r || n.disconnectedMatch || e.document && 11 !== e.document.nodeType) return r
            } catch (e) {
            }
            return oe(t, p, null, [e]).length > 0
        }, oe.contains = function (e, t) {
            return (e.ownerDocument || e) !== p && d(e), b(e, t)
        }, oe.attr = function (e, t) {
            (e.ownerDocument || e) !== p && d(e);
            var i = r.attrHandle[t.toLowerCase()],
                o = i && D.call(r.attrHandle, t.toLowerCase()) ? i(e, t, !m) : void 0;
            return void 0 !== o ? o : n.attributes || !m ? e.getAttribute(t) : (o = e.getAttributeNode(t)) && o.specified ? o.value : null
        }, oe.error = function (e) {
            throw new Error("Syntax error, unrecognized expression: " + e)
        }, oe.uniqueSort = function (e) {
            var t, r = [], i = 0, o = 0;
            if (f = !n.detectDuplicates, c = !n.sortStable && e.slice(0), e.sort(S), f) {
                for (; t = e[o++];) t === e[o] && (i = r.push(o));
                for (; i--;) e.splice(r[i], 1)
            }
            return c = null, e
        }, i = oe.getText = function (e) {
            var t, n = "", r = 0, o = e.nodeType;
            if (o) {
                if (1 === o || 9 === o || 11 === o) {
                    if ("string" == typeof e.textContent) return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling) n += i(e)
                } else if (3 === o || 4 === o) return e.nodeValue
            } else for (; t = e[r++];) n += i(t);
            return n
        }, (r = oe.selectors = {
            cacheLength: 50,
            createPseudo: se,
            match: Y,
            attrHandle: {},
            find: {},
            relative: {
                ">": {dir: "parentNode", first: !0},
                " ": {dir: "parentNode"},
                "+": {dir: "previousSibling", first: !0},
                "~": {dir: "previousSibling"}
            },
            preFilter: {
                ATTR: function (e) {
                    return e[1] = e[1].replace(ne, re), e[3] = (e[3] || e[4] || e[5] || "").replace(ne, re), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                }, CHILD: function (e) {
                    return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || oe.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && oe.error(e[0]), e
                }, PSEUDO: function (e) {
                    var t, n = !e[6] && e[2];
                    return Y.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && V.test(n) && (t = a(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                }
            },
            filter: {
                TAG: function (e) {
                    var t = e.replace(ne, re).toLowerCase();
                    return "*" === e ? function () {
                        return !0
                    } : function (e) {
                        return e.nodeName && e.nodeName.toLowerCase() === t
                    }
                }, CLASS: function (e) {
                    var t = N[e + " "];
                    return t || (t = new RegExp("(^|" + O + ")" + e + "(" + O + "|$)")) && N(e, function (e) {
                        return t.test("string" == typeof e.className && e.className || void 0 !== e.getAttribute && e.getAttribute("class") || "")
                    })
                }, ATTR: function (e, t, n) {
                    return function (r) {
                        var i = oe.attr(r, e);
                        return null == i ? "!=" === t : !t || (i += "", "=" === t ? i === n : "!=" === t ? i !== n : "^=" === t ? n && 0 === i.indexOf(n) : "*=" === t ? n && i.indexOf(n) > -1 : "$=" === t ? n && i.slice(-n.length) === n : "~=" === t ? (" " + i.replace($, " ") + " ").indexOf(n) > -1 : "|=" === t && (i === n || i.slice(0, n.length + 1) === n + "-"))
                    }
                }, CHILD: function (e, t, n, r, i) {
                    var o = "nth" !== e.slice(0, 3), a = "last" !== e.slice(-4), s = "of-type" === t;
                    return 1 === r && 0 === i ? function (e) {
                        return !!e.parentNode
                    } : function (t, n, l) {
                        var u, c, f, d, p, h, m = o !== a ? "nextSibling" : "previousSibling", g = t.parentNode,
                            y = s && t.nodeName.toLowerCase(), v = !l && !s;
                        if (g) {
                            if (o) {
                                for (; m;) {
                                    for (f = t; f = f[m];) if (s ? f.nodeName.toLowerCase() === y : 1 === f.nodeType) return !1;
                                    h = m = "only" === e && !h && "nextSibling"
                                }
                                return !0
                            }
                            if (h = [a ? g.firstChild : g.lastChild], a && v) {
                                for (p = (u = (c = g[x] || (g[x] = {}))[e] || [])[0] === T && u[1], d = u[0] === T && u[2], f = p && g.childNodes[p]; f = ++p && f && f[m] || (d = p = 0) || h.pop();) if (1 === f.nodeType && ++d && f === t) {
                                    c[e] = [T, p, d];
                                    break
                                }
                            } else if (v && (u = (t[x] || (t[x] = {}))[e]) && u[0] === T) d = u[1]; else for (; (f = ++p && f && f[m] || (d = p = 0) || h.pop()) && ((s ? f.nodeName.toLowerCase() !== y : 1 !== f.nodeType) || !++d || (v && ((f[x] || (f[x] = {}))[e] = [T, d]), f !== t));) ;
                            return (d -= i) === r || d % r == 0 && d / r >= 0
                        }
                    }
                }, PSEUDO: function (e, t) {
                    var n, i = r.pseudos[e] || r.setFilters[e.toLowerCase()] || oe.error("unsupported pseudo: " + e);
                    return i[x] ? i(t) : i.length > 1 ? (n = [e, e, "", t], r.setFilters.hasOwnProperty(e.toLowerCase()) ? se(function (e, n) {
                        for (var r, o = i(e, t), a = o.length; a--;) e[r = M(e, o[a])] = !(n[r] = o[a])
                    }) : function (e) {
                        return i(e, 0, n)
                    }) : i
                }
            },
            pseudos: {
                not: se(function (e) {
                    var t = [], n = [], r = s(e.replace(z, "$1"));
                    return r[x] ? se(function (e, t, n, i) {
                        for (var o, a = r(e, null, i, []), s = e.length; s--;) (o = a[s]) && (e[s] = !(t[s] = o))
                    }) : function (e, i, o) {
                        return t[0] = e, r(t, null, o, n), t[0] = null, !n.pop()
                    }
                }), has: se(function (e) {
                    return function (t) {
                        return oe(e, t).length > 0
                    }
                }), contains: se(function (e) {
                    return e = e.replace(ne, re), function (t) {
                        return (t.textContent || t.innerText || i(t)).indexOf(e) > -1
                    }
                }), lang: se(function (e) {
                    return J.test(e || "") || oe.error("unsupported lang: " + e), e = e.replace(ne, re).toLowerCase(), function (t) {
                        var n;
                        do {
                            if (n = m ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang")) return (n = n.toLowerCase()) === e || 0 === n.indexOf(e + "-")
                        } while ((t = t.parentNode) && 1 === t.nodeType);
                        return !1
                    }
                }), target: function (t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id
                }, root: function (e) {
                    return e === h
                }, focus: function (e) {
                    return e === p.activeElement && (!p.hasFocus || p.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                }, enabled: function (e) {
                    return !1 === e.disabled
                }, disabled: function (e) {
                    return !0 === e.disabled
                }, checked: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && !!e.checked || "option" === t && !!e.selected
                }, selected: function (e) {
                    return e.parentNode && e.parentNode.selectedIndex, !0 === e.selected
                }, empty: function (e) {
                    for (e = e.firstChild; e; e = e.nextSibling) if (e.nodeType < 6) return !1;
                    return !0
                }, parent: function (e) {
                    return !r.pseudos.empty(e)
                }, header: function (e) {
                    return Q.test(e.nodeName)
                }, input: function (e) {
                    return G.test(e.nodeName)
                }, button: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                }, text: function (e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                }, first: pe(function () {
                    return [0]
                }), last: pe(function (e, t) {
                    return [t - 1]
                }), eq: pe(function (e, t, n) {
                    return [n < 0 ? n + t : n]
                }), even: pe(function (e, t) {
                    for (var n = 0; n < t; n += 2) e.push(n);
                    return e
                }), odd: pe(function (e, t) {
                    for (var n = 1; n < t; n += 2) e.push(n);
                    return e
                }), lt: pe(function (e, t, n) {
                    for (var r = n < 0 ? n + t : n; --r >= 0;) e.push(r);
                    return e
                }), gt: pe(function (e, t, n) {
                    for (var r = n < 0 ? n + t : n; ++r < t;) e.push(r);
                    return e
                })
            }
        }).pseudos.nth = r.pseudos.eq;
        for (t in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0}) r.pseudos[t] = fe(t);
        for (t in{submit: !0, reset: !0}) r.pseudos[t] = de(t);

        function me() {
        }

        function ge(e) {
            for (var t = 0, n = e.length, r = ""; t < n; t++) r += e[t].value;
            return r
        }

        function ye(e, t, n) {
            var r = t.dir, i = n && "parentNode" === r, o = C++;
            return t.first ? function (t, n, o) {
                for (; t = t[r];) if (1 === t.nodeType || i) return e(t, n, o)
            } : function (t, n, a) {
                var s, l, u = [T, o];
                if (a) {
                    for (; t = t[r];) if ((1 === t.nodeType || i) && e(t, n, a)) return !0
                } else for (; t = t[r];) if (1 === t.nodeType || i) {
                    if ((s = (l = t[x] || (t[x] = {}))[r]) && s[0] === T && s[1] === o) return u[2] = s[2];
                    if (l[r] = u, u[2] = e(t, n, a)) return !0
                }
            }
        }

        function ve(e) {
            return e.length > 1 ? function (t, n, r) {
                for (var i = e.length; i--;) if (!e[i](t, n, r)) return !1;
                return !0
            } : e[0]
        }

        function be(e, t, n, r, i) {
            for (var o, a = [], s = 0, l = e.length, u = null != t; s < l; s++) (o = e[s]) && (n && !n(o, r, i) || (a.push(o), u && t.push(s)));
            return a
        }

        function xe(e, t, n, r, i, o) {
            return r && !r[x] && (r = xe(r)), i && !i[x] && (i = xe(i, o)), se(function (o, a, s, l) {
                var u, c, f, d = [], p = [], h = a.length, m = o || function (e, t, n) {
                        for (var r = 0, i = t.length; r < i; r++) oe(e, t[r], n);
                        return n
                    }(t || "*", s.nodeType ? [s] : s, []), g = !e || !o && t ? m : be(m, d, e, s, l),
                    y = n ? i || (o ? e : h || r) ? [] : a : g;
                if (n && n(g, y, s, l), r) for (u = be(y, p), r(u, [], s, l), c = u.length; c--;) (f = u[c]) && (y[p[c]] = !(g[p[c]] = f));
                if (o) {
                    if (i || e) {
                        if (i) {
                            for (u = [], c = y.length; c--;) (f = y[c]) && u.push(g[c] = f);
                            i(null, y = [], u, l)
                        }
                        for (c = y.length; c--;) (f = y[c]) && (u = i ? M(o, f) : d[c]) > -1 && (o[u] = !(a[u] = f))
                    }
                } else y = be(y === a ? y.splice(h, y.length) : y), i ? i(null, a, y, l) : q.apply(a, y)
            })
        }

        function we(e) {
            for (var t, n, i, o = e.length, a = r.relative[e[0].type], s = a || r.relative[" "], l = a ? 1 : 0, c = ye(function (e) {
                return e === t
            }, s, !0), f = ye(function (e) {
                return M(t, e) > -1
            }, s, !0), d = [function (e, n, r) {
                var i = !a && (r || n !== u) || ((t = n).nodeType ? c(e, n, r) : f(e, n, r));
                return t = null, i
            }]; l < o; l++) if (n = r.relative[e[l].type]) d = [ye(ve(d), n)]; else {
                if ((n = r.filter[e[l].type].apply(null, e[l].matches))[x]) {
                    for (i = ++l; i < o && !r.relative[e[i].type]; i++) ;
                    return xe(l > 1 && ve(d), l > 1 && ge(e.slice(0, l - 1).concat({value: " " === e[l - 2].type ? "*" : ""})).replace(z, "$1"), n, l < i && we(e.slice(l, i)), i < o && we(e = e.slice(i)), i < o && ge(e))
                }
                d.push(n)
            }
            return ve(d)
        }

        return me.prototype = r.filters = r.pseudos, r.setFilters = new me, a = oe.tokenize = function (e, t) {
            var n, i, o, a, s, l, u, c = E[e + " "];
            if (c) return t ? 0 : c.slice(0);
            for (s = e, l = [], u = r.preFilter; s;) {
                n && !(i = I.exec(s)) || (i && (s = s.slice(i[0].length) || s), l.push(o = [])), n = !1, (i = X.exec(s)) && (n = i.shift(), o.push({
                    value: n,
                    type: i[0].replace(z, " ")
                }), s = s.slice(n.length));
                for (a in r.filter) !(i = Y[a].exec(s)) || u[a] && !(i = u[a](i)) || (n = i.shift(), o.push({
                    value: n,
                    type: a,
                    matches: i
                }), s = s.slice(n.length));
                if (!n) break
            }
            return t ? s.length : s ? oe.error(e) : E(e, l).slice(0)
        }, s = oe.compile = function (e, t) {
            var n, i, o, s, l, c, f = [], d = [], h = k[e + " "];
            if (!h) {
                for (t || (t = a(e)), n = t.length; n--;) (h = we(t[n]))[x] ? f.push(h) : d.push(h);
                (h = k(e, (i = d, s = (o = f).length > 0, l = i.length > 0, c = function (e, t, n, a, c) {
                    var f, d, h, m = 0, g = "0", y = e && [], v = [], b = u, x = e || l && r.find.TAG("*", c),
                        w = T += null == b ? 1 : Math.random() || .1, C = x.length;
                    for (c && (u = t !== p && t); g !== C && null != (f = x[g]); g++) {
                        if (l && f) {
                            for (d = 0; h = i[d++];) if (h(f, t, n)) {
                                a.push(f);
                                break
                            }
                            c && (T = w)
                        }
                        s && ((f = !h && f) && m--, e && y.push(f))
                    }
                    if (m += g, s && g !== m) {
                        for (d = 0; h = o[d++];) h(y, v, t, n);
                        if (e) {
                            if (m > 0) for (; g--;) y[g] || v[g] || (v[g] = L.call(a));
                            v = be(v)
                        }
                        q.apply(a, v), c && !e && v.length > 0 && m + o.length > 1 && oe.uniqueSort(a)
                    }
                    return c && (T = w, u = b), y
                }, s ? se(c) : c))).selector = e
            }
            return h
        }, l = oe.select = function (e, t, i, o) {
            var l, u, c, f, d, p = "function" == typeof e && e, h = !o && a(e = p.selector || e);
            if (i = i || [], 1 === h.length) {
                if ((u = h[0] = h[0].slice(0)).length > 2 && "ID" === (c = u[0]).type && n.getById && 9 === t.nodeType && m && r.relative[u[1].type]) {
                    if (!(t = (r.find.ID(c.matches[0].replace(ne, re), t) || [])[0])) return i;
                    p && (t = t.parentNode), e = e.slice(u.shift().value.length)
                }
                for (l = Y.needsContext.test(e) ? 0 : u.length; l-- && (c = u[l], !r.relative[f = c.type]);) if ((d = r.find[f]) && (o = d(c.matches[0].replace(ne, re), ee.test(u[0].type) && he(t.parentNode) || t))) {
                    if (u.splice(l, 1), !(e = o.length && ge(u))) return q.apply(i, o), i;
                    break
                }
            }
            return (p || s(e, h))(o, t, !m, i, ee.test(e) && he(t.parentNode) || t), i
        }, n.sortStable = x.split("").sort(S).join("") === x, n.detectDuplicates = !!f, d(), n.sortDetached = le(function (e) {
            return 1 & e.compareDocumentPosition(p.createElement("div"))
        }), le(function (e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || ue("type|href|height|width", function (e, t, n) {
            if (!n) return e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), n.attributes && le(function (e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || ue("value", function (e, t, n) {
            if (!n && "input" === e.nodeName.toLowerCase()) return e.defaultValue
        }), le(function (e) {
            return null == e.getAttribute("disabled")
        }) || ue(F, function (e, t, n) {
            var r;
            if (!n) return !0 === e[t] ? t.toLowerCase() : (r = e.getAttributeNode(t)) && r.specified ? r.value : null
        }), oe
    }(e);
    d.find = v, d.expr = v.selectors, d.expr[":"] = d.expr.pseudos, d.unique = v.uniqueSort, d.text = v.getText, d.isXMLDoc = v.isXML, d.contains = v.contains;
    var b = d.expr.match.needsContext, x = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, w = /^.[^:#\[\.,]*$/;

    function T(e, t, n) {
        if (d.isFunction(t)) return d.grep(e, function (e, r) {
            return !!t.call(e, r, e) !== n
        });
        if (t.nodeType) return d.grep(e, function (e) {
            return e === t !== n
        });
        if ("string" == typeof t) {
            if (w.test(t)) return d.filter(t, e, n);
            t = d.filter(t, e)
        }
        return d.grep(e, function (e) {
            return d.inArray(e, t) >= 0 !== n
        })
    }

    d.filter = function (e, t, n) {
        var r = t[0];
        return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === r.nodeType ? d.find.matchesSelector(r, e) ? [r] : [] : d.find.matches(e, d.grep(t, function (e) {
            return 1 === e.nodeType
        }))
    }, d.fn.extend({
        find: function (e) {
            var t, n = [], r = this, i = r.length;
            if ("string" != typeof e) return this.pushStack(d(e).filter(function () {
                for (t = 0; t < i; t++) if (d.contains(r[t], this)) return !0
            }));
            for (t = 0; t < i; t++) d.find(e, r[t], n);
            return (n = this.pushStack(i > 1 ? d.unique(n) : n)).selector = this.selector ? this.selector + " " + e : e, n
        }, filter: function (e) {
            return this.pushStack(T(this, e || [], !1))
        }, not: function (e) {
            return this.pushStack(T(this, e || [], !0))
        }, is: function (e) {
            return !!T(this, "string" == typeof e && b.test(e) ? d(e) : e || [], !1).length
        }
    });
    var C, N = e.document, E = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/;
    (d.fn.init = function (e, t) {
        var n, r;
        if (!e) return this;
        if ("string" == typeof e) {
            if (!(n = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3 ? [null, e, null] : E.exec(e)) || !n[1] && t) return !t || t.jquery ? (t || C).find(e) : this.constructor(t).find(e);
            if (n[1]) {
                if (t = t instanceof d ? t[0] : t, d.merge(this, d.parseHTML(n[1], t && t.nodeType ? t.ownerDocument || t : N, !0)), x.test(n[1]) && d.isPlainObject(t)) for (n in t) d.isFunction(this[n]) ? this[n](t[n]) : this.attr(n, t[n]);
                return this
            }
            if ((r = N.getElementById(n[2])) && r.parentNode) {
                if (r.id !== n[2]) return C.find(e);
                this.length = 1, this[0] = r
            }
            return this.context = N, this.selector = e, this
        }
        return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : d.isFunction(e) ? void 0 !== C.ready ? C.ready(e) : e(d) : (void 0 !== e.selector && (this.selector = e.selector, this.context = e.context), d.makeArray(e, this))
    }).prototype = d.fn, C = d(N);
    var k = /^(?:parents|prev(?:Until|All))/, S = {children: !0, contents: !0, next: !0, prev: !0};

    function A(e, t) {
        do {
            e = e[t]
        } while (e && 1 !== e.nodeType);
        return e
    }

    d.extend({
        dir: function (e, t, n) {
            for (var r = [], i = e[t]; i && 9 !== i.nodeType && (void 0 === n || 1 !== i.nodeType || !d(i).is(n));) 1 === i.nodeType && r.push(i), i = i[t];
            return r
        }, sibling: function (e, t) {
            for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    }), d.fn.extend({
        has: function (e) {
            var t, n = d(e, this), r = n.length;
            return this.filter(function () {
                for (t = 0; t < r; t++) if (d.contains(this, n[t])) return !0
            })
        }, closest: function (e, t) {
            for (var n, r = 0, i = this.length, o = [], a = b.test(e) || "string" != typeof e ? d(e, t || this.context) : 0; r < i; r++) for (n = this[r]; n && n !== t; n = n.parentNode) if (n.nodeType < 11 && (a ? a.index(n) > -1 : 1 === n.nodeType && d.find.matchesSelector(n, e))) {
                o.push(n);
                break
            }
            return this.pushStack(o.length > 1 ? d.unique(o) : o)
        }, index: function (e) {
            return e ? "string" == typeof e ? d.inArray(this[0], d(e)) : d.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        }, add: function (e, t) {
            return this.pushStack(d.unique(d.merge(this.get(), d(e, t))))
        }, addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), d.each({
        parent: function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        }, parents: function (e) {
            return d.dir(e, "parentNode")
        }, parentsUntil: function (e, t, n) {
            return d.dir(e, "parentNode", n)
        }, next: function (e) {
            return A(e, "nextSibling")
        }, prev: function (e) {
            return A(e, "previousSibling")
        }, nextAll: function (e) {
            return d.dir(e, "nextSibling")
        }, prevAll: function (e) {
            return d.dir(e, "previousSibling")
        }, nextUntil: function (e, t, n) {
            return d.dir(e, "nextSibling", n)
        }, prevUntil: function (e, t, n) {
            return d.dir(e, "previousSibling", n)
        }, siblings: function (e) {
            return d.sibling((e.parentNode || {}).firstChild, e)
        }, children: function (e) {
            return d.sibling(e.firstChild)
        }, contents: function (e) {
            return d.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : d.merge([], e.childNodes)
        }
    }, function (e, t) {
        d.fn[e] = function (n, r) {
            var i = d.map(this, t, n);
            return "Until" !== e.slice(-5) && (r = n), r && "string" == typeof r && (i = d.filter(r, i)), this.length > 1 && (S[e] || (i = d.unique(i)), k.test(e) && (i = i.reverse())), this.pushStack(i)
        }
    });
    var D, j = /\S+/g, L = {};

    function H() {
        N.addEventListener ? (N.removeEventListener("DOMContentLoaded", q, !1), e.removeEventListener("load", q, !1)) : (N.detachEvent("onreadystatechange", q), e.detachEvent("onload", q))
    }

    function q() {
        (N.addEventListener || "load" === event.type || "complete" === N.readyState) && (H(), d.ready())
    }

    d.Callbacks = function (e) {
        var t, n, r, i, o, a, s, l, u = [],
            c = !(e = "string" == typeof e ? L[e] || (n = L[t = e] = {}, d.each(t.match(j) || [], function (e, t) {
                n[t] = !0
            }), n) : d.extend({}, e)).once && [], f = function (t) {
                for (i = e.memory && t, o = !0, s = l || 0, l = 0, a = u.length, r = !0; u && s < a; s++) if (!1 === u[s].apply(t[0], t[1]) && e.stopOnFalse) {
                    i = !1;
                    break
                }
                r = !1, u && (c ? c.length && f(c.shift()) : i ? u = [] : p.disable())
            }, p = {
                add: function () {
                    if (u) {
                        var t = u.length;
                        !function t(n) {
                            d.each(n, function (n, r) {
                                var i = d.type(r);
                                "function" === i ? e.unique && p.has(r) || u.push(r) : r && r.length && "string" !== i && t(r)
                            })
                        }(arguments), r ? a = u.length : i && (l = t, f(i))
                    }
                    return this
                }, remove: function () {
                    return u && d.each(arguments, function (e, t) {
                        for (var n; (n = d.inArray(t, u, n)) > -1;) u.splice(n, 1), r && (n <= a && a--, n <= s && s--)
                    }), this
                }, has: function (e) {
                    return e ? d.inArray(e, u) > -1 : !(!u || !u.length)
                }, empty: function () {
                    return u = [], a = 0, this
                }, disable: function () {
                    return u = c = i = void 0, this
                }, disabled: function () {
                    return !u
                }, lock: function () {
                    return c = void 0, i || p.disable(), this
                }, locked: function () {
                    return !c
                }, fireWith: function (e, t) {
                    return !u || o && !c || (t = [e, (t = t || []).slice ? t.slice() : t], r ? c.push(t) : f(t)), this
                }, fire: function () {
                    return p.fireWith(this, arguments), this
                }, fired: function () {
                    return !!o
                }
            };
        return p
    }, d.extend({
        Deferred: function (e) {
            var t = [["resolve", "done", d.Callbacks("once memory"), "resolved"], ["reject", "fail", d.Callbacks("once memory"), "rejected"], ["notify", "progress", d.Callbacks("memory")]],
                n = "pending", r = {
                    state: function () {
                        return n
                    }, always: function () {
                        return i.done(arguments).fail(arguments), this
                    }, then: function () {
                        var e = arguments;
                        return d.Deferred(function (n) {
                            d.each(t, function (t, o) {
                                var a = d.isFunction(e[t]) && e[t];
                                i[o[1]](function () {
                                    var e = a && a.apply(this, arguments);
                                    e && d.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[o[0] + "With"](this === r ? n.promise() : this, a ? [e] : arguments)
                                })
                            }), e = null
                        }).promise()
                    }, promise: function (e) {
                        return null != e ? d.extend(e, r) : r
                    }
                }, i = {};
            return r.pipe = r.then, d.each(t, function (e, o) {
                var a = o[2], s = o[3];
                r[o[1]] = a.add, s && a.add(function () {
                    n = s
                }, t[1 ^ e][2].disable, t[2][2].lock), i[o[0]] = function () {
                    return i[o[0] + "With"](this === i ? r : this, arguments), this
                }, i[o[0] + "With"] = a.fireWith
            }), r.promise(i), e && e.call(i, i), i
        }, when: function (e) {
            var t, n, i, o = 0, a = r.call(arguments), s = a.length,
                l = 1 !== s || e && d.isFunction(e.promise) ? s : 0, u = 1 === l ? e : d.Deferred(),
                c = function (e, n, i) {
                    return function (o) {
                        n[e] = this, i[e] = arguments.length > 1 ? r.call(arguments) : o, i === t ? u.notifyWith(n, i) : --l || u.resolveWith(n, i)
                    }
                };
            if (s > 1) for (t = new Array(s), n = new Array(s), i = new Array(s); o < s; o++) a[o] && d.isFunction(a[o].promise) ? a[o].promise().done(c(o, i, a)).fail(u.reject).progress(c(o, n, t)) : --l;
            return l || u.resolveWith(i, a), u.promise()
        }
    }), d.fn.ready = function (e) {
        return d.ready.promise().done(e), this
    }, d.extend({
        isReady: !1, readyWait: 1, holdReady: function (e) {
            e ? d.readyWait++ : d.ready(!0)
        }, ready: function (e) {
            if (!0 === e ? !--d.readyWait : !d.isReady) {
                if (!N.body) return setTimeout(d.ready);
                d.isReady = !0, !0 !== e && --d.readyWait > 0 || (D.resolveWith(N, [d]), d.fn.triggerHandler && (d(N).triggerHandler("ready"), d(N).off("ready")))
            }
        }
    }), d.ready.promise = function (t) {
        if (!D) if (D = d.Deferred(), "complete" === N.readyState) setTimeout(d.ready); else if (N.addEventListener) N.addEventListener("DOMContentLoaded", q, !1), e.addEventListener("load", q, !1); else {
            N.attachEvent("onreadystatechange", q), e.attachEvent("onload", q);
            var n = !1;
            try {
                n = null == e.frameElement && N.documentElement
            } catch (e) {
            }
            n && n.doScroll && function e() {
                if (!d.isReady) {
                    try {
                        n.doScroll("left")
                    } catch (t) {
                        return setTimeout(e, 50)
                    }
                    H(), d.ready()
                }
            }()
        }
        return D.promise(t)
    };
    var _, M = "undefined";
    for (_ in d(c)) break;
    c.ownLast = "0" !== _, c.inlineBlockNeedsLayout = !1, d(function () {
        var e, t, n, r;
        (n = N.getElementsByTagName("body")[0]) && n.style && (t = N.createElement("div"), (r = N.createElement("div")).style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(r).appendChild(t), typeof t.style.zoom !== M && (t.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1", c.inlineBlockNeedsLayout = e = 3 === t.offsetWidth, e && (n.style.zoom = 1)), n.removeChild(r))
    }), function () {
        var e = N.createElement("div");
        if (null == c.deleteExpando) {
            c.deleteExpando = !0;
            try {
                delete e.test
            } catch (e) {
                c.deleteExpando = !1
            }
        }
        e = null
    }(), d.acceptData = function (e) {
        var t = d.noData[(e.nodeName + " ").toLowerCase()], n = +e.nodeType || 1;
        return (1 === n || 9 === n) && (!t || !0 !== t && e.getAttribute("classid") === t)
    };
    var F = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, O = /([A-Z])/g;

    function B(e, t, n) {
        if (void 0 === n && 1 === e.nodeType) {
            var r = "data-" + t.replace(O, "-$1").toLowerCase();
            if ("string" == typeof(n = e.getAttribute(r))) {
                try {
                    n = "true" === n || "false" !== n && ("null" === n ? null : +n + "" === n ? +n : F.test(n) ? d.parseJSON(n) : n)
                } catch (e) {
                }
                d.data(e, t, n)
            } else n = void 0
        }
        return n
    }

    function P(e) {
        var t;
        for (t in e) if (("data" !== t || !d.isEmptyObject(e[t])) && "toJSON" !== t) return !1;
        return !0
    }

    function R(e, t, r, i) {
        if (d.acceptData(e)) {
            var o, a, s = d.expando, l = e.nodeType, u = l ? d.cache : e, c = l ? e[s] : e[s] && s;
            if (c && u[c] && (i || u[c].data) || void 0 !== r || "string" != typeof t) return c || (c = l ? e[s] = n.pop() || d.guid++ : s), u[c] || (u[c] = l ? {} : {toJSON: d.noop}), "object" != typeof t && "function" != typeof t || (i ? u[c] = d.extend(u[c], t) : u[c].data = d.extend(u[c].data, t)), a = u[c], i || (a.data || (a.data = {}), a = a.data), void 0 !== r && (a[d.camelCase(t)] = r), "string" == typeof t ? null == (o = a[t]) && (o = a[d.camelCase(t)]) : o = a, o
        }
    }

    function W(e, t, n) {
        if (d.acceptData(e)) {
            var r, i, o = e.nodeType, a = o ? d.cache : e, s = o ? e[d.expando] : d.expando;
            if (a[s]) {
                if (t && (r = n ? a[s] : a[s].data)) {
                    i = (t = d.isArray(t) ? t.concat(d.map(t, d.camelCase)) : t in r ? [t] : (t = d.camelCase(t)) in r ? [t] : t.split(" ")).length;
                    for (; i--;) delete r[t[i]];
                    if (n ? !P(r) : !d.isEmptyObject(r)) return
                }
                (n || (delete a[s].data, P(a[s]))) && (o ? d.cleanData([e], !0) : c.deleteExpando || a != a.window ? delete a[s] : a[s] = null)
            }
        }
    }

    d.extend({
        cache: {},
        noData: {"applet ": !0, "embed ": !0, "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"},
        hasData: function (e) {
            return !!(e = e.nodeType ? d.cache[e[d.expando]] : e[d.expando]) && !P(e)
        },
        data: function (e, t, n) {
            return R(e, t, n)
        },
        removeData: function (e, t) {
            return W(e, t)
        },
        _data: function (e, t, n) {
            return R(e, t, n, !0)
        },
        _removeData: function (e, t) {
            return W(e, t, !0)
        }
    }), d.fn.extend({
        data: function (e, t) {
            var n, r, i, o = this[0], a = o && o.attributes;
            if (void 0 === e) {
                if (this.length && (i = d.data(o), 1 === o.nodeType && !d._data(o, "parsedAttrs"))) {
                    for (n = a.length; n--;) a[n] && 0 === (r = a[n].name).indexOf("data-") && B(o, r = d.camelCase(r.slice(5)), i[r]);
                    d._data(o, "parsedAttrs", !0)
                }
                return i
            }
            return "object" == typeof e ? this.each(function () {
                d.data(this, e)
            }) : arguments.length > 1 ? this.each(function () {
                d.data(this, e, t)
            }) : o ? B(o, e, d.data(o, e)) : void 0
        }, removeData: function (e) {
            return this.each(function () {
                d.removeData(this, e)
            })
        }
    }), d.extend({
        queue: function (e, t, n) {
            var r;
            if (e) return t = (t || "fx") + "queue", r = d._data(e, t), n && (!r || d.isArray(n) ? r = d._data(e, t, d.makeArray(n)) : r.push(n)), r || []
        }, dequeue: function (e, t) {
            t = t || "fx";
            var n = d.queue(e, t), r = n.length, i = n.shift(), o = d._queueHooks(e, t);
            "inprogress" === i && (i = n.shift(), r--), i && ("fx" === t && n.unshift("inprogress"), delete o.stop, i.call(e, function () {
                d.dequeue(e, t)
            }, o)), !r && o && o.empty.fire()
        }, _queueHooks: function (e, t) {
            var n = t + "queueHooks";
            return d._data(e, n) || d._data(e, n, {
                empty: d.Callbacks("once memory").add(function () {
                    d._removeData(e, t + "queue"), d._removeData(e, n)
                })
            })
        }
    }), d.fn.extend({
        queue: function (e, t) {
            var n = 2;
            return "string" != typeof e && (t = e, e = "fx", n--), arguments.length < n ? d.queue(this[0], e) : void 0 === t ? this : this.each(function () {
                var n = d.queue(this, e, t);
                d._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && d.dequeue(this, e)
            })
        }, dequeue: function (e) {
            return this.each(function () {
                d.dequeue(this, e)
            })
        }, clearQueue: function (e) {
            return this.queue(e || "fx", [])
        }, promise: function (e, t) {
            var n, r = 1, i = d.Deferred(), o = this, a = this.length, s = function () {
                --r || i.resolveWith(o, [o])
            };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; a--;) (n = d._data(o[a], e + "queueHooks")) && n.empty && (r++, n.empty.add(s));
            return s(), i.promise(t)
        }
    });
    var $ = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, z = ["Top", "Right", "Bottom", "Left"], I = function (e, t) {
        return e = t || e, "none" === d.css(e, "display") || !d.contains(e.ownerDocument, e)
    }, X = d.access = function (e, t, n, r, i, o, a) {
        var s = 0, l = e.length, u = null == n;
        if ("object" === d.type(n)) {
            i = !0;
            for (s in n) d.access(e, t, s, n[s], !0, o, a)
        } else if (void 0 !== r && (i = !0, d.isFunction(r) || (a = !0), u && (a ? (t.call(e, r), t = null) : (u = t, t = function (e, t, n) {
                return u.call(d(e), n)
            })), t)) for (; s < l; s++) t(e[s], n, a ? r : r.call(e[s], s, t(e[s], n)));
        return i ? e : u ? t.call(e) : l ? t(e[0], n) : o
    }, U = /^(?:checkbox|radio)$/i;
    !function () {
        var e = N.createElement("input"), t = N.createElement("div"), n = N.createDocumentFragment();
        if (t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", c.leadingWhitespace = 3 === t.firstChild.nodeType, c.tbody = !t.getElementsByTagName("tbody").length, c.htmlSerialize = !!t.getElementsByTagName("link").length, c.html5Clone = "<:nav></:nav>" !== N.createElement("nav").cloneNode(!0).outerHTML, e.type = "checkbox", e.checked = !0, n.appendChild(e), c.appendChecked = e.checked, t.innerHTML = "<textarea>x</textarea>", c.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue, n.appendChild(t), t.innerHTML = "<input type='radio' checked='checked' name='t'/>", c.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, c.noCloneEvent = !0, t.attachEvent && (t.attachEvent("onclick", function () {
                c.noCloneEvent = !1
            }), t.cloneNode(!0).click()), null == c.deleteExpando) {
            c.deleteExpando = !0;
            try {
                delete t.test
            } catch (e) {
                c.deleteExpando = !1
            }
        }
    }(), function () {
        var t, n, r = N.createElement("div");
        for (t in{
            submit: !0,
            change: !0,
            focusin: !0
        }) n = "on" + t, (c[t + "Bubbles"] = n in e) || (r.setAttribute(n, "t"), c[t + "Bubbles"] = !1 === r.attributes[n].expando);
        r = null
    }();
    var V = /^(?:input|select|textarea)$/i, J = /^key/, Y = /^(?:mouse|pointer|contextmenu)|click/,
        G = /^(?:focusinfocus|focusoutblur)$/, Q = /^([^.]*)(?:\.(.+)|)$/;

    function K() {
        return !0
    }

    function Z() {
        return !1
    }

    function ee() {
        try {
            return N.activeElement
        } catch (e) {
        }
    }

    function te(e) {
        var t = ne.split("|"), n = e.createDocumentFragment();
        if (n.createElement) for (; t.length;) n.createElement(t.pop());
        return n
    }

    d.event = {
        global: {},
        add: function (e, t, n, r, i) {
            var o, a, s, l, u, c, f, p, h, m, g, y = d._data(e);
            if (y) {
                for (n.handler && (n = (l = n).handler, i = l.selector), n.guid || (n.guid = d.guid++), (a = y.events) || (a = y.events = {}), (c = y.handle) || ((c = y.handle = function (e) {
                    return typeof d === M || e && d.event.triggered === e.type ? void 0 : d.event.dispatch.apply(c.elem, arguments)
                }).elem = e), s = (t = (t || "").match(j) || [""]).length; s--;) h = g = (o = Q.exec(t[s]) || [])[1], m = (o[2] || "").split(".").sort(), h && (u = d.event.special[h] || {}, h = (i ? u.delegateType : u.bindType) || h, u = d.event.special[h] || {}, f = d.extend({
                    type: h,
                    origType: g,
                    data: r,
                    handler: n,
                    guid: n.guid,
                    selector: i,
                    needsContext: i && d.expr.match.needsContext.test(i),
                    namespace: m.join(".")
                }, l), (p = a[h]) || ((p = a[h] = []).delegateCount = 0, u.setup && !1 !== u.setup.call(e, r, m, c) || (e.addEventListener ? e.addEventListener(h, c, !1) : e.attachEvent && e.attachEvent("on" + h, c))), u.add && (u.add.call(e, f), f.handler.guid || (f.handler.guid = n.guid)), i ? p.splice(p.delegateCount++, 0, f) : p.push(f), d.event.global[h] = !0);
                e = null
            }
        },
        remove: function (e, t, n, r, i) {
            var o, a, s, l, u, c, f, p, h, m, g, y = d.hasData(e) && d._data(e);
            if (y && (c = y.events)) {
                for (u = (t = (t || "").match(j) || [""]).length; u--;) if (h = g = (s = Q.exec(t[u]) || [])[1], m = (s[2] || "").split(".").sort(), h) {
                    for (f = d.event.special[h] || {}, p = c[h = (r ? f.delegateType : f.bindType) || h] || [], s = s[2] && new RegExp("(^|\\.)" + m.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = o = p.length; o--;) a = p[o], !i && g !== a.origType || n && n.guid !== a.guid || s && !s.test(a.namespace) || r && r !== a.selector && ("**" !== r || !a.selector) || (p.splice(o, 1), a.selector && p.delegateCount--, f.remove && f.remove.call(e, a));
                    l && !p.length && (f.teardown && !1 !== f.teardown.call(e, m, y.handle) || d.removeEvent(e, h, y.handle), delete c[h])
                } else for (h in c) d.event.remove(e, h + t[u], n, r, !0);
                d.isEmptyObject(c) && (delete y.handle, d._removeData(e, "events"))
            }
        },
        trigger: function (t, n, r, i) {
            var o, a, s, l, c, f, p, h = [r || N], m = u.call(t, "type") ? t.type : t,
                g = u.call(t, "namespace") ? t.namespace.split(".") : [];
            if (s = f = r = r || N, 3 !== r.nodeType && 8 !== r.nodeType && !G.test(m + d.event.triggered) && (m.indexOf(".") >= 0 && (m = (g = m.split(".")).shift(), g.sort()), a = m.indexOf(":") < 0 && "on" + m, (t = t[d.expando] ? t : new d.Event(m, "object" == typeof t && t)).isTrigger = i ? 2 : 3, t.namespace = g.join("."), t.namespace_re = t.namespace ? new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = r), n = null == n ? [t] : d.makeArray(n, [t]), c = d.event.special[m] || {}, i || !c.trigger || !1 !== c.trigger.apply(r, n))) {
                if (!i && !c.noBubble && !d.isWindow(r)) {
                    for (l = c.delegateType || m, G.test(l + m) || (s = s.parentNode); s; s = s.parentNode) h.push(s), f = s;
                    f === (r.ownerDocument || N) && h.push(f.defaultView || f.parentWindow || e)
                }
                for (p = 0; (s = h[p++]) && !t.isPropagationStopped();) t.type = p > 1 ? l : c.bindType || m, (o = (d._data(s, "events") || {})[t.type] && d._data(s, "handle")) && o.apply(s, n), (o = a && s[a]) && o.apply && d.acceptData(s) && (t.result = o.apply(s, n), !1 === t.result && t.preventDefault());
                if (t.type = m, !i && !t.isDefaultPrevented() && (!c._default || !1 === c._default.apply(h.pop(), n)) && d.acceptData(r) && a && r[m] && !d.isWindow(r)) {
                    (f = r[a]) && (r[a] = null), d.event.triggered = m;
                    try {
                        r[m]()
                    } catch (e) {
                    }
                    d.event.triggered = void 0, f && (r[a] = f)
                }
                return t.result
            }
        },
        dispatch: function (e) {
            e = d.event.fix(e);
            var t, n, i, o, a, s, l = r.call(arguments), u = (d._data(this, "events") || {})[e.type] || [],
                c = d.event.special[e.type] || {};
            if (l[0] = e, e.delegateTarget = this, !c.preDispatch || !1 !== c.preDispatch.call(this, e)) {
                for (s = d.event.handlers.call(this, e, u), t = 0; (o = s[t++]) && !e.isPropagationStopped();) for (e.currentTarget = o.elem, a = 0; (i = o.handlers[a++]) && !e.isImmediatePropagationStopped();) e.namespace_re && !e.namespace_re.test(i.namespace) || (e.handleObj = i, e.data = i.data, void 0 !== (n = ((d.event.special[i.origType] || {}).handle || i.handler).apply(o.elem, l)) && !1 === (e.result = n) && (e.preventDefault(), e.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, e), e.result
            }
        },
        handlers: function (e, t) {
            var n, r, i, o, a = [], s = t.delegateCount, l = e.target;
            if (s && l.nodeType && (!e.button || "click" !== e.type)) for (; l != this; l = l.parentNode || this) if (1 === l.nodeType && (!0 !== l.disabled || "click" !== e.type)) {
                for (i = [], o = 0; o < s; o++) void 0 === i[n = (r = t[o]).selector + " "] && (i[n] = r.needsContext ? d(n, this).index(l) >= 0 : d.find(n, this, null, [l]).length), i[n] && i.push(r);
                i.length && a.push({elem: l, handlers: i})
            }
            return s < t.length && a.push({elem: this, handlers: t.slice(s)}), a
        },
        fix: function (e) {
            if (e[d.expando]) return e;
            var t, n, r, i = e.type, o = e, a = this.fixHooks[i];
            for (a || (this.fixHooks[i] = a = Y.test(i) ? this.mouseHooks : J.test(i) ? this.keyHooks : {}), r = a.props ? this.props.concat(a.props) : this.props, e = new d.Event(o), t = r.length; t--;) e[n = r[t]] = o[n];
            return e.target || (e.target = o.srcElement || N), 3 === e.target.nodeType && (e.target = e.target.parentNode), e.metaKey = !!e.metaKey, a.filter ? a.filter(e, o) : e
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "), filter: function (e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function (e, t) {
                var n, r, i, o = t.button, a = t.fromElement;
                return null == e.pageX && null != t.clientX && (i = (r = e.target.ownerDocument || N).documentElement, n = r.body, e.pageX = t.clientX + (i && i.scrollLeft || n && n.scrollLeft || 0) - (i && i.clientLeft || n && n.clientLeft || 0), e.pageY = t.clientY + (i && i.scrollTop || n && n.scrollTop || 0) - (i && i.clientTop || n && n.clientTop || 0)), !e.relatedTarget && a && (e.relatedTarget = a === e.target ? t.toElement : a), e.which || void 0 === o || (e.which = 1 & o ? 1 : 2 & o ? 3 : 4 & o ? 2 : 0), e
            }
        },
        special: {
            load: {noBubble: !0}, focus: {
                trigger: function () {
                    if (this !== ee() && this.focus) try {
                        return this.focus(), !1
                    } catch (e) {
                    }
                }, delegateType: "focusin"
            }, blur: {
                trigger: function () {
                    if (this === ee() && this.blur) return this.blur(), !1
                }, delegateType: "focusout"
            }, click: {
                trigger: function () {
                    if (d.nodeName(this, "input") && "checkbox" === this.type && this.click) return this.click(), !1
                }, _default: function (e) {
                    return d.nodeName(e.target, "a")
                }
            }, beforeunload: {
                postDispatch: function (e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        simulate: function (e, t, n, r) {
            var i = d.extend(new d.Event, n, {type: e, isSimulated: !0, originalEvent: {}});
            r ? d.event.trigger(i, null, t) : d.event.dispatch.call(t, i), i.isDefaultPrevented() && n.preventDefault()
        }
    }, d.removeEvent = N.removeEventListener ? function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    } : function (e, t, n) {
        var r = "on" + t;
        e.detachEvent && (typeof e[r] === M && (e[r] = null), e.detachEvent(r, n))
    }, d.Event = function (e, t) {
        if (!(this instanceof d.Event)) return new d.Event(e, t);
        e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && !1 === e.returnValue ? K : Z) : this.type = e, t && d.extend(this, t), this.timeStamp = e && e.timeStamp || d.now(), this[d.expando] = !0
    }, d.Event.prototype = {
        isDefaultPrevented: Z,
        isPropagationStopped: Z,
        isImmediatePropagationStopped: Z,
        preventDefault: function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = K, e && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        },
        stopPropagation: function () {
            var e = this.originalEvent;
            this.isPropagationStopped = K, e && (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        },
        stopImmediatePropagation: function () {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = K, e && e.stopImmediatePropagation && e.stopImmediatePropagation(), this.stopPropagation()
        }
    }, d.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function (e, t) {
        d.event.special[e] = {
            delegateType: t, bindType: t, handle: function (e) {
                var n, r = e.relatedTarget, i = e.handleObj;
                return r && (r === this || d.contains(this, r)) || (e.type = i.origType, n = i.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), c.submitBubbles || (d.event.special.submit = {
        setup: function () {
            if (d.nodeName(this, "form")) return !1;
            d.event.add(this, "click._submit keypress._submit", function (e) {
                var t = e.target, n = d.nodeName(t, "input") || d.nodeName(t, "button") ? t.form : void 0;
                n && !d._data(n, "submitBubbles") && (d.event.add(n, "submit._submit", function (e) {
                    e._submit_bubble = !0
                }), d._data(n, "submitBubbles", !0))
            })
        }, postDispatch: function (e) {
            e._submit_bubble && (delete e._submit_bubble, this.parentNode && !e.isTrigger && d.event.simulate("submit", this.parentNode, e, !0))
        }, teardown: function () {
            if (d.nodeName(this, "form")) return !1;
            d.event.remove(this, "._submit")
        }
    }), c.changeBubbles || (d.event.special.change = {
        setup: function () {
            if (V.test(this.nodeName)) return "checkbox" !== this.type && "radio" !== this.type || (d.event.add(this, "propertychange._change", function (e) {
                "checked" === e.originalEvent.propertyName && (this._just_changed = !0)
            }), d.event.add(this, "click._change", function (e) {
                this._just_changed && !e.isTrigger && (this._just_changed = !1), d.event.simulate("change", this, e, !0)
            })), !1;
            d.event.add(this, "beforeactivate._change", function (e) {
                var t = e.target;
                V.test(t.nodeName) && !d._data(t, "changeBubbles") && (d.event.add(t, "change._change", function (e) {
                    !this.parentNode || e.isSimulated || e.isTrigger || d.event.simulate("change", this.parentNode, e, !0)
                }), d._data(t, "changeBubbles", !0))
            })
        }, handle: function (e) {
            var t = e.target;
            if (this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type) return e.handleObj.handler.apply(this, arguments)
        }, teardown: function () {
            return d.event.remove(this, "._change"), !V.test(this.nodeName)
        }
    }), c.focusinBubbles || d.each({focus: "focusin", blur: "focusout"}, function (e, t) {
        var n = function (e) {
            d.event.simulate(t, e.target, d.event.fix(e), !0)
        };
        d.event.special[t] = {
            setup: function () {
                var r = this.ownerDocument || this, i = d._data(r, t);
                i || r.addEventListener(e, n, !0), d._data(r, t, (i || 0) + 1)
            }, teardown: function () {
                var r = this.ownerDocument || this, i = d._data(r, t) - 1;
                i ? d._data(r, t, i) : (r.removeEventListener(e, n, !0), d._removeData(r, t))
            }
        }
    }), d.fn.extend({
        on: function (e, t, n, r, i) {
            var o, a;
            if ("object" == typeof e) {
                "string" != typeof t && (n = n || t, t = void 0);
                for (o in e) this.on(o, t, n, e[o], i);
                return this
            }
            if (null == n && null == r ? (r = t, n = t = void 0) : null == r && ("string" == typeof t ? (r = n, n = void 0) : (r = n, n = t, t = void 0)), !1 === r) r = Z; else if (!r) return this;
            return 1 === i && (a = r, (r = function (e) {
                return d().off(e), a.apply(this, arguments)
            }).guid = a.guid || (a.guid = d.guid++)), this.each(function () {
                d.event.add(this, e, r, n, t)
            })
        }, one: function (e, t, n, r) {
            return this.on(e, t, n, r, 1)
        }, off: function (e, t, n) {
            var r, i;
            if (e && e.preventDefault && e.handleObj) return r = e.handleObj, d(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler), this;
            if ("object" == typeof e) {
                for (i in e) this.off(i, t, e[i]);
                return this
            }
            return !1 !== t && "function" != typeof t || (n = t, t = void 0), !1 === n && (n = Z), this.each(function () {
                d.event.remove(this, e, n, t)
            })
        }, trigger: function (e, t) {
            return this.each(function () {
                d.event.trigger(e, t, this)
            })
        }, triggerHandler: function (e, t) {
            var n = this[0];
            if (n) return d.event.trigger(e, t, n, !0)
        }
    });
    var ne = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
        re = / jQuery\d+="(?:null|\d+)"/g, ie = new RegExp("<(?:" + ne + ")[\\s/>]", "i"), oe = /^\s+/,
        ae = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, se = /<([\w:]+)/,
        le = /<tbody/i, ue = /<|&#?\w+;/, ce = /<(?:script|style|link)/i, fe = /checked\s*(?:[^=]|=\s*.checked.)/i,
        de = /^$|\/(?:java|ecma)script/i, pe = /^true\/(.*)/, he = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, me = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            legend: [1, "<fieldset>", "</fieldset>"],
            area: [1, "<map>", "</map>"],
            param: [1, "<object>", "</object>"],
            thead: [1, "<table>", "</table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: c.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
        }, ge = te(N).appendChild(N.createElement("div"));

    function ye(e, t) {
        var n, r, i = 0,
            o = typeof e.getElementsByTagName !== M ? e.getElementsByTagName(t || "*") : typeof e.querySelectorAll !== M ? e.querySelectorAll(t || "*") : void 0;
        if (!o) for (o = [], n = e.childNodes || e; null != (r = n[i]); i++) !t || d.nodeName(r, t) ? o.push(r) : d.merge(o, ye(r, t));
        return void 0 === t || t && d.nodeName(e, t) ? d.merge([e], o) : o
    }

    function ve(e) {
        U.test(e.type) && (e.defaultChecked = e.checked)
    }

    function be(e, t) {
        return d.nodeName(e, "table") && d.nodeName(11 !== t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function xe(e) {
        return e.type = (null !== d.find.attr(e, "type")) + "/" + e.type, e
    }

    function we(e) {
        var t = pe.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function Te(e, t) {
        for (var n, r = 0; null != (n = e[r]); r++) d._data(n, "globalEval", !t || d._data(t[r], "globalEval"))
    }

    function Ce(e, t) {
        if (1 === t.nodeType && d.hasData(e)) {
            var n, r, i, o = d._data(e), a = d._data(t, o), s = o.events;
            if (s) {
                delete a.handle, a.events = {};
                for (n in s) for (r = 0, i = s[n].length; r < i; r++) d.event.add(t, n, s[n][r])
            }
            a.data && (a.data = d.extend({}, a.data))
        }
    }

    function Ne(e, t) {
        var n, r, i;
        if (1 === t.nodeType) {
            if (n = t.nodeName.toLowerCase(), !c.noCloneEvent && t[d.expando]) {
                i = d._data(t);
                for (r in i.events) d.removeEvent(t, r, i.handle);
                t.removeAttribute(d.expando)
            }
            "script" === n && t.text !== e.text ? (xe(t).text = e.text, we(t)) : "object" === n ? (t.parentNode && (t.outerHTML = e.outerHTML), c.html5Clone && e.innerHTML && !d.trim(t.innerHTML) && (t.innerHTML = e.innerHTML)) : "input" === n && U.test(e.type) ? (t.defaultChecked = t.checked = e.checked, t.value !== e.value && (t.value = e.value)) : "option" === n ? t.defaultSelected = t.selected = e.defaultSelected : "input" !== n && "textarea" !== n || (t.defaultValue = e.defaultValue)
        }
    }

    me.optgroup = me.option, me.tbody = me.tfoot = me.colgroup = me.caption = me.thead, me.th = me.td, d.extend({
        clone: function (e, t, n) {
            var r, i, o, a, s, l = d.contains(e.ownerDocument, e);
            if (c.html5Clone || d.isXMLDoc(e) || !ie.test("<" + e.nodeName + ">") ? o = e.cloneNode(!0) : (ge.innerHTML = e.outerHTML, ge.removeChild(o = ge.firstChild)), !(c.noCloneEvent && c.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || d.isXMLDoc(e))) for (r = ye(o), s = ye(e), a = 0; null != (i = s[a]); ++a) r[a] && Ne(i, r[a]);
            if (t) if (n) for (s = s || ye(e), r = r || ye(o), a = 0; null != (i = s[a]); a++) Ce(i, r[a]); else Ce(e, o);
            return (r = ye(o, "script")).length > 0 && Te(r, !l && ye(e, "script")), r = s = i = null, o
        }, buildFragment: function (e, t, n, r) {
            for (var i, o, a, s, l, u, f, p = e.length, h = te(t), m = [], g = 0; g < p; g++) if ((o = e[g]) || 0 === o) if ("object" === d.type(o)) d.merge(m, o.nodeType ? [o] : o); else if (ue.test(o)) {
                for (s = s || h.appendChild(t.createElement("div")), l = (se.exec(o) || ["", ""])[1].toLowerCase(), f = me[l] || me._default, s.innerHTML = f[1] + o.replace(ae, "<$1></$2>") + f[2], i = f[0]; i--;) s = s.lastChild;
                if (!c.leadingWhitespace && oe.test(o) && m.push(t.createTextNode(oe.exec(o)[0])), !c.tbody) for (i = (o = "table" !== l || le.test(o) ? "<table>" !== f[1] || le.test(o) ? 0 : s : s.firstChild) && o.childNodes.length; i--;) d.nodeName(u = o.childNodes[i], "tbody") && !u.childNodes.length && o.removeChild(u);
                for (d.merge(m, s.childNodes), s.textContent = ""; s.firstChild;) s.removeChild(s.firstChild);
                s = h.lastChild
            } else m.push(t.createTextNode(o));
            for (s && h.removeChild(s), c.appendChecked || d.grep(ye(m, "input"), ve), g = 0; o = m[g++];) if ((!r || -1 === d.inArray(o, r)) && (a = d.contains(o.ownerDocument, o), s = ye(h.appendChild(o), "script"), a && Te(s), n)) for (i = 0; o = s[i++];) de.test(o.type || "") && n.push(o);
            return s = null, h
        }, cleanData: function (e, t) {
            for (var r, i, o, a, s = 0, l = d.expando, u = d.cache, f = c.deleteExpando, p = d.event.special; null != (r = e[s]); s++) if ((t || d.acceptData(r)) && (a = (o = r[l]) && u[o])) {
                if (a.events) for (i in a.events) p[i] ? d.event.remove(r, i) : d.removeEvent(r, i, a.handle);
                u[o] && (delete u[o], f ? delete r[l] : typeof r.removeAttribute !== M ? r.removeAttribute(l) : r[l] = null, n.push(o))
            }
        }
    }), d.fn.extend({
        text: function (e) {
            return X(this, function (e) {
                return void 0 === e ? d.text(this) : this.empty().append((this[0] && this[0].ownerDocument || N).createTextNode(e))
            }, null, e, arguments.length)
        }, append: function () {
            return this.domManip(arguments, function (e) {
                1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || be(this, e).appendChild(e)
            })
        }, prepend: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = be(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        }, before: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        }, after: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        }, remove: function (e, t) {
            for (var n, r = e ? d.filter(e, this) : this, i = 0; null != (n = r[i]); i++) t || 1 !== n.nodeType || d.cleanData(ye(n)), n.parentNode && (t && d.contains(n.ownerDocument, n) && Te(ye(n, "script")), n.parentNode.removeChild(n));
            return this
        }, empty: function () {
            for (var e, t = 0; null != (e = this[t]); t++) {
                for (1 === e.nodeType && d.cleanData(ye(e, !1)); e.firstChild;) e.removeChild(e.firstChild);
                e.options && d.nodeName(e, "select") && (e.options.length = 0)
            }
            return this
        }, clone: function (e, t) {
            return e = null != e && e, t = null == t ? e : t, this.map(function () {
                return d.clone(this, e, t)
            })
        }, html: function (e) {
            return X(this, function (e) {
                var t = this[0] || {}, n = 0, r = this.length;
                if (void 0 === e) return 1 === t.nodeType ? t.innerHTML.replace(re, "") : void 0;
                if ("string" == typeof e && !ce.test(e) && (c.htmlSerialize || !ie.test(e)) && (c.leadingWhitespace || !oe.test(e)) && !me[(se.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = e.replace(ae, "<$1></$2>");
                    try {
                        for (; n < r; n++) 1 === (t = this[n] || {}).nodeType && (d.cleanData(ye(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (e) {
                    }
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        }, replaceWith: function () {
            var e = arguments[0];
            return this.domManip(arguments, function (t) {
                e = this.parentNode, d.cleanData(ye(this)), e && e.replaceChild(t, this)
            }), e && (e.length || e.nodeType) ? this : this.remove()
        }, detach: function (e) {
            return this.remove(e, !0)
        }, domManip: function (e, t) {
            e = i.apply([], e);
            var n, r, o, a, s, l, u = 0, f = this.length, p = this, h = f - 1, m = e[0], g = d.isFunction(m);
            if (g || f > 1 && "string" == typeof m && !c.checkClone && fe.test(m)) return this.each(function (n) {
                var r = p.eq(n);
                g && (e[0] = m.call(this, n, r.html())), r.domManip(e, t)
            });
            if (f && (n = (l = d.buildFragment(e, this[0].ownerDocument, !1, this)).firstChild, 1 === l.childNodes.length && (l = n), n)) {
                for (o = (a = d.map(ye(l, "script"), xe)).length; u < f; u++) r = l, u !== h && (r = d.clone(r, !0, !0), o && d.merge(a, ye(r, "script"))), t.call(this[u], r, u);
                if (o) for (s = a[a.length - 1].ownerDocument, d.map(a, we), u = 0; u < o; u++) r = a[u], de.test(r.type || "") && !d._data(r, "globalEval") && d.contains(s, r) && (r.src ? d._evalUrl && d._evalUrl(r.src) : d.globalEval((r.text || r.textContent || r.innerHTML || "").replace(he, "")));
                l = n = null
            }
            return this
        }
    }), d.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (e, t) {
        d.fn[e] = function (e) {
            for (var n, r = 0, i = [], a = d(e), s = a.length - 1; r <= s; r++) n = r === s ? this : this.clone(!0), d(a[r])[t](n), o.apply(i, n.get());
            return this.pushStack(i)
        }
    });
    var Ee, ke, Se = {};

    function Ae(t, n) {
        var r, i = d(n.createElement(t)).appendTo(n.body),
            o = e.getDefaultComputedStyle && (r = e.getDefaultComputedStyle(i[0])) ? r.display : d.css(i[0], "display");
        return i.detach(), o
    }

    function De(e) {
        var t = N, n = Se[e];
        return n || ("none" !== (n = Ae(e, t)) && n || ((t = ((Ee = (Ee || d("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement))[0].contentWindow || Ee[0].contentDocument).document).write(), t.close(), n = Ae(e, t), Ee.detach()), Se[e] = n), n
    }

    c.shrinkWrapBlocks = function () {
        return null != ke ? ke : (ke = !1, (t = N.getElementsByTagName("body")[0]) && t.style ? (e = N.createElement("div"), (n = N.createElement("div")).style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", t.appendChild(n).appendChild(e), typeof e.style.zoom !== M && (e.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1", e.appendChild(N.createElement("div")).style.width = "5px", ke = 3 !== e.offsetWidth), t.removeChild(n), ke) : void 0);
        var e, t, n
    };
    var je, Le, He = /^margin/, qe = new RegExp("^(" + $ + ")(?!px)[a-z%]+$", "i"), _e = /^(top|right|bottom|left)$/;

    function Me(e, t) {
        return {
            get: function () {
                var n = e();
                if (null != n) {
                    if (!n) return (this.get = t).apply(this, arguments);
                    delete this.get
                }
            }
        }
    }

    e.getComputedStyle ? (je = function (t) {
        return t.ownerDocument.defaultView.opener ? t.ownerDocument.defaultView.getComputedStyle(t, null) : e.getComputedStyle(t, null)
    }, Le = function (e, t, n) {
        var r, i, o, a, s = e.style;
        return a = (n = n || je(e)) ? n.getPropertyValue(t) || n[t] : void 0, n && ("" !== a || d.contains(e.ownerDocument, e) || (a = d.style(e, t)), qe.test(a) && He.test(t) && (r = s.width, i = s.minWidth, o = s.maxWidth, s.minWidth = s.maxWidth = s.width = a, a = n.width, s.width = r, s.minWidth = i, s.maxWidth = o)), void 0 === a ? a : a + ""
    }) : N.documentElement.currentStyle && (je = function (e) {
        return e.currentStyle
    }, Le = function (e, t, n) {
        var r, i, o, a, s = e.style;
        return null == (a = (n = n || je(e)) ? n[t] : void 0) && s && s[t] && (a = s[t]), qe.test(a) && !_e.test(t) && (r = s.left, (o = (i = e.runtimeStyle) && i.left) && (i.left = e.currentStyle.left), s.left = "fontSize" === t ? "1em" : a, a = s.pixelLeft + "px", s.left = r, o && (i.left = o)), void 0 === a ? a : a + "" || "auto"
    }), function () {
        var t, n, r, i, o, a, s;

        function l() {
            var t, n, r, l;
            (n = N.getElementsByTagName("body")[0]) && n.style && (t = N.createElement("div"), (r = N.createElement("div")).style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(r).appendChild(t), t.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", i = o = !1, s = !0, e.getComputedStyle && (i = "1%" !== (e.getComputedStyle(t, null) || {}).top, o = "4px" === (e.getComputedStyle(t, null) || {width: "4px"}).width, (l = t.appendChild(N.createElement("div"))).style.cssText = t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", l.style.marginRight = l.style.width = "0", t.style.width = "1px", s = !parseFloat((e.getComputedStyle(l, null) || {}).marginRight), t.removeChild(l)), t.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", (l = t.getElementsByTagName("td"))[0].style.cssText = "margin:0;border:0;padding:0;display:none", (a = 0 === l[0].offsetHeight) && (l[0].style.display = "", l[1].style.display = "none", a = 0 === l[0].offsetHeight), n.removeChild(r))
        }

        (t = N.createElement("div")).innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", (n = (r = t.getElementsByTagName("a")[0]) && r.style) && (n.cssText = "float:left;opacity:.5", c.opacity = "0.5" === n.opacity, c.cssFloat = !!n.cssFloat, t.style.backgroundClip = "content-box", t.cloneNode(!0).style.backgroundClip = "", c.clearCloneStyle = "content-box" === t.style.backgroundClip, c.boxSizing = "" === n.boxSizing || "" === n.MozBoxSizing || "" === n.WebkitBoxSizing, d.extend(c, {
            reliableHiddenOffsets: function () {
                return null == a && l(), a
            }, boxSizingReliable: function () {
                return null == o && l(), o
            }, pixelPosition: function () {
                return null == i && l(), i
            }, reliableMarginRight: function () {
                return null == s && l(), s
            }
        }))
    }(), d.swap = function (e, t, n, r) {
        var i, o, a = {};
        for (o in t) a[o] = e.style[o], e.style[o] = t[o];
        i = n.apply(e, r || []);
        for (o in t) e.style[o] = a[o];
        return i
    };
    var Fe = /alpha\([^)]*\)/i, Oe = /opacity\s*=\s*([^)]*)/, Be = /^(none|table(?!-c[ea]).+)/,
        Pe = new RegExp("^(" + $ + ")(.*)$", "i"), Re = new RegExp("^([+-])=(" + $ + ")", "i"),
        We = {position: "absolute", visibility: "hidden", display: "block"},
        $e = {letterSpacing: "0", fontWeight: "400"}, ze = ["Webkit", "O", "Moz", "ms"];

    function Ie(e, t) {
        if (t in e) return t;
        for (var n = t.charAt(0).toUpperCase() + t.slice(1), r = t, i = ze.length; i--;) if ((t = ze[i] + n) in e) return t;
        return r
    }

    function Xe(e, t) {
        for (var n, r, i, o = [], a = 0, s = e.length; a < s; a++) (r = e[a]).style && (o[a] = d._data(r, "olddisplay"), n = r.style.display, t ? (o[a] || "none" !== n || (r.style.display = ""), "" === r.style.display && I(r) && (o[a] = d._data(r, "olddisplay", De(r.nodeName)))) : (i = I(r), (n && "none" !== n || !i) && d._data(r, "olddisplay", i ? n : d.css(r, "display"))));
        for (a = 0; a < s; a++) (r = e[a]).style && (t && "none" !== r.style.display && "" !== r.style.display || (r.style.display = t ? o[a] || "" : "none"));
        return e
    }

    function Ue(e, t, n) {
        var r = Pe.exec(t);
        return r ? Math.max(0, r[1] - (n || 0)) + (r[2] || "px") : t
    }

    function Ve(e, t, n, r, i) {
        for (var o = n === (r ? "border" : "content") ? 4 : "width" === t ? 1 : 0, a = 0; o < 4; o += 2) "margin" === n && (a += d.css(e, n + z[o], !0, i)), r ? ("content" === n && (a -= d.css(e, "padding" + z[o], !0, i)), "margin" !== n && (a -= d.css(e, "border" + z[o] + "Width", !0, i))) : (a += d.css(e, "padding" + z[o], !0, i), "padding" !== n && (a += d.css(e, "border" + z[o] + "Width", !0, i)));
        return a
    }

    function Je(e, t, n) {
        var r = !0, i = "width" === t ? e.offsetWidth : e.offsetHeight, o = je(e),
            a = c.boxSizing && "border-box" === d.css(e, "boxSizing", !1, o);
        if (i <= 0 || null == i) {
            if (((i = Le(e, t, o)) < 0 || null == i) && (i = e.style[t]), qe.test(i)) return i;
            r = a && (c.boxSizingReliable() || i === e.style[t]), i = parseFloat(i) || 0
        }
        return i + Ve(e, t, n || (a ? "border" : "content"), r, o) + "px"
    }

    function Ye(e, t, n, r, i) {
        return new Ye.prototype.init(e, t, n, r, i)
    }

    d.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = Le(e, "opacity");
                        return "" === n ? "1" : n
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {float: c.cssFloat ? "cssFloat" : "styleFloat"},
        style: function (e, t, n, r) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var i, o, a, s = d.camelCase(t), l = e.style;
                if (t = d.cssProps[s] || (d.cssProps[s] = Ie(l, s)), a = d.cssHooks[t] || d.cssHooks[s], void 0 === n) return a && "get" in a && void 0 !== (i = a.get(e, !1, r)) ? i : l[t];
                if ("string" === (o = typeof n) && (i = Re.exec(n)) && (n = (i[1] + 1) * i[2] + parseFloat(d.css(e, t)), o = "number"), null != n && n == n && ("number" !== o || d.cssNumber[s] || (n += "px"), c.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (l[t] = "inherit"), !(a && "set" in a && void 0 === (n = a.set(e, n, r))))) try {
                    l[t] = n
                } catch (e) {
                }
            }
        },
        css: function (e, t, n, r) {
            var i, o, a, s = d.camelCase(t);
            return t = d.cssProps[s] || (d.cssProps[s] = Ie(e.style, s)), (a = d.cssHooks[t] || d.cssHooks[s]) && "get" in a && (o = a.get(e, !0, n)), void 0 === o && (o = Le(e, t, r)), "normal" === o && t in $e && (o = $e[t]), "" === n || n ? (i = parseFloat(o), !0 === n || d.isNumeric(i) ? i || 0 : o) : o
        }
    }), d.each(["height", "width"], function (e, t) {
        d.cssHooks[t] = {
            get: function (e, n, r) {
                if (n) return Be.test(d.css(e, "display")) && 0 === e.offsetWidth ? d.swap(e, We, function () {
                    return Je(e, t, r)
                }) : Je(e, t, r)
            }, set: function (e, n, r) {
                var i = r && je(e);
                return Ue(0, n, r ? Ve(e, t, r, c.boxSizing && "border-box" === d.css(e, "boxSizing", !1, i), i) : 0)
            }
        }
    }), c.opacity || (d.cssHooks.opacity = {
        get: function (e, t) {
            return Oe.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
        }, set: function (e, t) {
            var n = e.style, r = e.currentStyle, i = d.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "",
                o = r && r.filter || n.filter || "";
            n.zoom = 1, (t >= 1 || "" === t) && "" === d.trim(o.replace(Fe, "")) && n.removeAttribute && (n.removeAttribute("filter"), "" === t || r && !r.filter) || (n.filter = Fe.test(o) ? o.replace(Fe, i) : o + " " + i)
        }
    }), d.cssHooks.marginRight = Me(c.reliableMarginRight, function (e, t) {
        if (t) return d.swap(e, {display: "inline-block"}, Le, [e, "marginRight"])
    }), d.each({margin: "", padding: "", border: "Width"}, function (e, t) {
        d.cssHooks[e + t] = {
            expand: function (n) {
                for (var r = 0, i = {}, o = "string" == typeof n ? n.split(" ") : [n]; r < 4; r++) i[e + z[r] + t] = o[r] || o[r - 2] || o[0];
                return i
            }
        }, He.test(e) || (d.cssHooks[e + t].set = Ue)
    }), d.fn.extend({
        css: function (e, t) {
            return X(this, function (e, t, n) {
                var r, i, o = {}, a = 0;
                if (d.isArray(t)) {
                    for (r = je(e), i = t.length; a < i; a++) o[t[a]] = d.css(e, t[a], !1, r);
                    return o
                }
                return void 0 !== n ? d.style(e, t, n) : d.css(e, t)
            }, e, t, arguments.length > 1)
        }, show: function () {
            return Xe(this, !0)
        }, hide: function () {
            return Xe(this)
        }, toggle: function (e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                I(this) ? d(this).show() : d(this).hide()
            })
        }
    }), d.Tween = Ye, Ye.prototype = {
        constructor: Ye, init: function (e, t, n, r, i, o) {
            this.elem = e, this.prop = n, this.easing = i || "swing", this.options = t, this.start = this.now = this.cur(), this.end = r, this.unit = o || (d.cssNumber[n] ? "" : "px")
        }, cur: function () {
            var e = Ye.propHooks[this.prop];
            return e && e.get ? e.get(this) : Ye.propHooks._default.get(this)
        }, run: function (e) {
            var t, n = Ye.propHooks[this.prop];
            return this.options.duration ? this.pos = t = d.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : Ye.propHooks._default.set(this), this
        }
    }, Ye.prototype.init.prototype = Ye.prototype, Ye.propHooks = {
        _default: {
            get: function (e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = d.css(e.elem, e.prop, "")) && "auto" !== t ? t : 0 : e.elem[e.prop]
            }, set: function (e) {
                d.fx.step[e.prop] ? d.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[d.cssProps[e.prop]] || d.cssHooks[e.prop]) ? d.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }
        }
    }, Ye.propHooks.scrollTop = Ye.propHooks.scrollLeft = {
        set: function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, d.easing = {
        linear: function (e) {
            return e
        }, swing: function (e) {
            return .5 - Math.cos(e * Math.PI) / 2
        }
    }, d.fx = Ye.prototype.init, d.fx.step = {};
    var Ge, Qe, Ke, Ze, et, tt, nt, rt = /^(?:toggle|show|hide)$/,
        it = new RegExp("^(?:([+-])=|)(" + $ + ")([a-z%]*)$", "i"), ot = /queueHooks$/, at = [function (e, t, n) {
            var r, i, o, a, s, l, u, f = this, p = {}, h = e.style, m = e.nodeType && I(e), g = d._data(e, "fxshow");
            n.queue || (null == (s = d._queueHooks(e, "fx")).unqueued && (s.unqueued = 0, l = s.empty.fire, s.empty.fire = function () {
                s.unqueued || l()
            }), s.unqueued++, f.always(function () {
                f.always(function () {
                    s.unqueued--, d.queue(e, "fx").length || s.empty.fire()
                })
            }));
            1 === e.nodeType && ("height" in t || "width" in t) && (n.overflow = [h.overflow, h.overflowX, h.overflowY], u = d.css(e, "display"), "inline" === ("none" === u ? d._data(e, "olddisplay") || De(e.nodeName) : u) && "none" === d.css(e, "float") && (c.inlineBlockNeedsLayout && "inline" !== De(e.nodeName) ? h.zoom = 1 : h.display = "inline-block"));
            n.overflow && (h.overflow = "hidden", c.shrinkWrapBlocks() || f.always(function () {
                h.overflow = n.overflow[0], h.overflowX = n.overflow[1], h.overflowY = n.overflow[2]
            }));
            for (r in t) if (i = t[r], rt.exec(i)) {
                if (delete t[r], o = o || "toggle" === i, i === (m ? "hide" : "show")) {
                    if ("show" !== i || !g || void 0 === g[r]) continue;
                    m = !0
                }
                p[r] = g && g[r] || d.style(e, r)
            } else u = void 0;
            if (d.isEmptyObject(p)) "inline" === ("none" === u ? De(e.nodeName) : u) && (h.display = u); else {
                g ? "hidden" in g && (m = g.hidden) : g = d._data(e, "fxshow", {}), o && (g.hidden = !m), m ? d(e).show() : f.done(function () {
                    d(e).hide()
                }), f.done(function () {
                    var t;
                    d._removeData(e, "fxshow");
                    for (t in p) d.style(e, t, p[t])
                });
                for (r in p) a = ct(m ? g[r] : 0, r, f), r in g || (g[r] = a.start, m && (a.end = a.start, a.start = "width" === r || "height" === r ? 1 : 0))
            }
        }], st = {
            "*": [function (e, t) {
                var n = this.createTween(e, t), r = n.cur(), i = it.exec(t), o = i && i[3] || (d.cssNumber[e] ? "" : "px"),
                    a = (d.cssNumber[e] || "px" !== o && +r) && it.exec(d.css(n.elem, e)), s = 1, l = 20;
                if (a && a[3] !== o) {
                    o = o || a[3], i = i || [], a = +r || 1;
                    do {
                        a /= s = s || ".5", d.style(n.elem, e, a + o)
                    } while (s !== (s = n.cur() / r) && 1 !== s && --l)
                }
                return i && (a = n.start = +a || +r || 0, n.unit = o, n.end = i[1] ? a + (i[1] + 1) * i[2] : +i[2]), n
            }]
        };

    function lt() {
        return setTimeout(function () {
            Ge = void 0
        }), Ge = d.now()
    }

    function ut(e, t) {
        var n, r = {height: e}, i = 0;
        for (t = t ? 1 : 0; i < 4; i += 2 - t) r["margin" + (n = z[i])] = r["padding" + n] = e;
        return t && (r.opacity = r.width = e), r
    }

    function ct(e, t, n) {
        for (var r, i = (st[t] || []).concat(st["*"]), o = 0, a = i.length; o < a; o++) if (r = i[o].call(n, t, e)) return r
    }

    function ft(e, t, n) {
        var r, i, o = 0, a = at.length, s = d.Deferred().always(function () {
            delete l.elem
        }), l = function () {
            if (i) return !1;
            for (var t = Ge || lt(), n = Math.max(0, u.startTime + u.duration - t), r = 1 - (n / u.duration || 0), o = 0, a = u.tweens.length; o < a; o++) u.tweens[o].run(r);
            return s.notifyWith(e, [u, r, n]), r < 1 && a ? n : (s.resolveWith(e, [u]), !1)
        }, u = s.promise({
            elem: e,
            props: d.extend({}, t),
            opts: d.extend(!0, {specialEasing: {}}, n),
            originalProperties: t,
            originalOptions: n,
            startTime: Ge || lt(),
            duration: n.duration,
            tweens: [],
            createTween: function (t, n) {
                var r = d.Tween(e, u.opts, t, n, u.opts.specialEasing[t] || u.opts.easing);
                return u.tweens.push(r), r
            },
            stop: function (t) {
                var n = 0, r = t ? u.tweens.length : 0;
                if (i) return this;
                for (i = !0; n < r; n++) u.tweens[n].run(1);
                return t ? s.resolveWith(e, [u, t]) : s.rejectWith(e, [u, t]), this
            }
        }), c = u.props;
        for (!function (e, t) {
            var n, r, i, o, a;
            for (n in e) if (i = t[r = d.camelCase(n)], o = e[n], d.isArray(o) && (i = o[1], o = e[n] = o[0]), n !== r && (e[r] = o, delete e[n]), (a = d.cssHooks[r]) && "expand" in a) {
                o = a.expand(o), delete e[r];
                for (n in o) n in e || (e[n] = o[n], t[n] = i)
            } else t[r] = i
        }(c, u.opts.specialEasing); o < a; o++) if (r = at[o].call(u, e, c, u.opts)) return r;
        return d.map(c, ct, u), d.isFunction(u.opts.start) && u.opts.start.call(e, u), d.fx.timer(d.extend(l, {
            elem: e,
            anim: u,
            queue: u.opts.queue
        })), u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always)
    }

    d.Animation = d.extend(ft, {
        tweener: function (e, t) {
            d.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
            for (var n, r = 0, i = e.length; r < i; r++) n = e[r], st[n] = st[n] || [], st[n].unshift(t)
        }, prefilter: function (e, t) {
            t ? at.unshift(e) : at.push(e)
        }
    }), d.speed = function (e, t, n) {
        var r = e && "object" == typeof e ? d.extend({}, e) : {
            complete: n || !n && t || d.isFunction(e) && e,
            duration: e,
            easing: n && t || t && !d.isFunction(t) && t
        };
        return r.duration = d.fx.off ? 0 : "number" == typeof r.duration ? r.duration : r.duration in d.fx.speeds ? d.fx.speeds[r.duration] : d.fx.speeds._default, null != r.queue && !0 !== r.queue || (r.queue = "fx"), r.old = r.complete, r.complete = function () {
            d.isFunction(r.old) && r.old.call(this), r.queue && d.dequeue(this, r.queue)
        }, r
    }, d.fn.extend({
        fadeTo: function (e, t, n, r) {
            return this.filter(I).css("opacity", 0).show().end().animate({opacity: t}, e, n, r)
        }, animate: function (e, t, n, r) {
            var i = d.isEmptyObject(e), o = d.speed(t, n, r), a = function () {
                var t = ft(this, d.extend({}, e), o);
                (i || d._data(this, "finish")) && t.stop(!0)
            };
            return a.finish = a, i || !1 === o.queue ? this.each(a) : this.queue(o.queue, a)
        }, stop: function (e, t, n) {
            var r = function (e) {
                var t = e.stop;
                delete e.stop, t(n)
            };
            return "string" != typeof e && (n = t, t = e, e = void 0), t && !1 !== e && this.queue(e || "fx", []), this.each(function () {
                var t = !0, i = null != e && e + "queueHooks", o = d.timers, a = d._data(this);
                if (i) a[i] && a[i].stop && r(a[i]); else for (i in a) a[i] && a[i].stop && ot.test(i) && r(a[i]);
                for (i = o.length; i--;) o[i].elem !== this || null != e && o[i].queue !== e || (o[i].anim.stop(n), t = !1, o.splice(i, 1));
                !t && n || d.dequeue(this, e)
            })
        }, finish: function (e) {
            return !1 !== e && (e = e || "fx"), this.each(function () {
                var t, n = d._data(this), r = n[e + "queue"], i = n[e + "queueHooks"], o = d.timers,
                    a = r ? r.length : 0;
                for (n.finish = !0, d.queue(this, e, []), i && i.stop && i.stop.call(this, !0), t = o.length; t--;) o[t].elem === this && o[t].queue === e && (o[t].anim.stop(!0), o.splice(t, 1));
                for (t = 0; t < a; t++) r[t] && r[t].finish && r[t].finish.call(this);
                delete n.finish
            })
        }
    }), d.each(["toggle", "show", "hide"], function (e, t) {
        var n = d.fn[t];
        d.fn[t] = function (e, r, i) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(ut(t, !0), e, r, i)
        }
    }), d.each({
        slideDown: ut("show"),
        slideUp: ut("hide"),
        slideToggle: ut("toggle"),
        fadeIn: {opacity: "show"},
        fadeOut: {opacity: "hide"},
        fadeToggle: {opacity: "toggle"}
    }, function (e, t) {
        d.fn[e] = function (e, n, r) {
            return this.animate(t, e, n, r)
        }
    }), d.timers = [], d.fx.tick = function () {
        var e, t = d.timers, n = 0;
        for (Ge = d.now(); n < t.length; n++) (e = t[n])() || t[n] !== e || t.splice(n--, 1);
        t.length || d.fx.stop(), Ge = void 0
    }, d.fx.timer = function (e) {
        d.timers.push(e), e() ? d.fx.start() : d.timers.pop()
    }, d.fx.interval = 13, d.fx.start = function () {
        Qe || (Qe = setInterval(d.fx.tick, d.fx.interval))
    }, d.fx.stop = function () {
        clearInterval(Qe), Qe = null
    }, d.fx.speeds = {slow: 600, fast: 200, _default: 400}, d.fn.delay = function (e, t) {
        return e = d.fx && d.fx.speeds[e] || e, t = t || "fx", this.queue(t, function (t, n) {
            var r = setTimeout(t, e);
            n.stop = function () {
                clearTimeout(r)
            }
        })
    }, (Ze = N.createElement("div")).setAttribute("className", "t"), Ze.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", tt = Ze.getElementsByTagName("a")[0], nt = (et = N.createElement("select")).appendChild(N.createElement("option")), Ke = Ze.getElementsByTagName("input")[0], tt.style.cssText = "top:1px", c.getSetAttribute = "t" !== Ze.className, c.style = /top/.test(tt.getAttribute("style")), c.hrefNormalized = "/a" === tt.getAttribute("href"), c.checkOn = !!Ke.value, c.optSelected = nt.selected, c.enctype = !!N.createElement("form").enctype, et.disabled = !0, c.optDisabled = !nt.disabled, (Ke = N.createElement("input")).setAttribute("value", ""), c.input = "" === Ke.getAttribute("value"), Ke.value = "t", Ke.setAttribute("type", "radio"), c.radioValue = "t" === Ke.value;
    var dt = /\r/g;
    d.fn.extend({
        val: function (e) {
            var t, n, r, i = this[0];
            return arguments.length ? (r = d.isFunction(e), this.each(function (n) {
                var i;
                1 === this.nodeType && (null == (i = r ? e.call(this, n, d(this).val()) : e) ? i = "" : "number" == typeof i ? i += "" : d.isArray(i) && (i = d.map(i, function (e) {
                    return null == e ? "" : e + ""
                })), (t = d.valHooks[this.type] || d.valHooks[this.nodeName.toLowerCase()]) && "set" in t && void 0 !== t.set(this, i, "value") || (this.value = i))
            })) : i ? (t = d.valHooks[i.type] || d.valHooks[i.nodeName.toLowerCase()]) && "get" in t && void 0 !== (n = t.get(i, "value")) ? n : "string" == typeof(n = i.value) ? n.replace(dt, "") : null == n ? "" : n : void 0
        }
    }), d.extend({
        valHooks: {
            option: {
                get: function (e) {
                    var t = d.find.attr(e, "value");
                    return null != t ? t : d.trim(d.text(e))
                }
            }, select: {
                get: function (e) {
                    for (var t, n, r = e.options, i = e.selectedIndex, o = "select-one" === e.type || i < 0, a = o ? null : [], s = o ? i + 1 : r.length, l = i < 0 ? s : o ? i : 0; l < s; l++) if (((n = r[l]).selected || l === i) && (c.optDisabled ? !n.disabled : null === n.getAttribute("disabled")) && (!n.parentNode.disabled || !d.nodeName(n.parentNode, "optgroup"))) {
                        if (t = d(n).val(), o) return t;
                        a.push(t)
                    }
                    return a
                }, set: function (e, t) {
                    for (var n, r, i = e.options, o = d.makeArray(t), a = i.length; a--;) if (r = i[a], d.inArray(d.valHooks.option.get(r), o) >= 0) try {
                        r.selected = n = !0
                    } catch (e) {
                        r.scrollHeight
                    } else r.selected = !1;
                    return n || (e.selectedIndex = -1), i
                }
            }
        }
    }), d.each(["radio", "checkbox"], function () {
        d.valHooks[this] = {
            set: function (e, t) {
                if (d.isArray(t)) return e.checked = d.inArray(d(e).val(), t) >= 0
            }
        }, c.checkOn || (d.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var pt, ht, mt = d.expr.attrHandle, gt = /^(?:checked|selected)$/i, yt = c.getSetAttribute, vt = c.input;
    d.fn.extend({
        attr: function (e, t) {
            return X(this, d.attr, e, t, arguments.length > 1)
        }, removeAttr: function (e) {
            return this.each(function () {
                d.removeAttr(this, e)
            })
        }
    }), d.extend({
        attr: function (e, t, n) {
            var r, i, o = e.nodeType;
            if (e && 3 !== o && 8 !== o && 2 !== o) return typeof e.getAttribute === M ? d.prop(e, t, n) : (1 === o && d.isXMLDoc(e) || (t = t.toLowerCase(), r = d.attrHooks[t] || (d.expr.match.bool.test(t) ? ht : pt)), void 0 === n ? r && "get" in r && null !== (i = r.get(e, t)) ? i : null == (i = d.find.attr(e, t)) ? void 0 : i : null !== n ? r && "set" in r && void 0 !== (i = r.set(e, n, t)) ? i : (e.setAttribute(t, n + ""), n) : void d.removeAttr(e, t))
        }, removeAttr: function (e, t) {
            var n, r, i = 0, o = t && t.match(j);
            if (o && 1 === e.nodeType) for (; n = o[i++];) r = d.propFix[n] || n, d.expr.match.bool.test(n) ? vt && yt || !gt.test(n) ? e[r] = !1 : e[d.camelCase("default-" + n)] = e[r] = !1 : d.attr(e, n, ""), e.removeAttribute(yt ? n : r)
        }, attrHooks: {
            type: {
                set: function (e, t) {
                    if (!c.radioValue && "radio" === t && d.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        }
    }), ht = {
        set: function (e, t, n) {
            return !1 === t ? d.removeAttr(e, n) : vt && yt || !gt.test(n) ? e.setAttribute(!yt && d.propFix[n] || n, n) : e[d.camelCase("default-" + n)] = e[n] = !0, n
        }
    }, d.each(d.expr.match.bool.source.match(/\w+/g), function (e, t) {
        var n = mt[t] || d.find.attr;
        mt[t] = vt && yt || !gt.test(t) ? function (e, t, r) {
            var i, o;
            return r || (o = mt[t], mt[t] = i, i = null != n(e, t, r) ? t.toLowerCase() : null, mt[t] = o), i
        } : function (e, t, n) {
            if (!n) return e[d.camelCase("default-" + t)] ? t.toLowerCase() : null
        }
    }), vt && yt || (d.attrHooks.value = {
        set: function (e, t, n) {
            if (!d.nodeName(e, "input")) return pt && pt.set(e, t, n);
            e.defaultValue = t
        }
    }), yt || (pt = {
        set: function (e, t, n) {
            var r = e.getAttributeNode(n);
            if (r || e.setAttributeNode(r = e.ownerDocument.createAttribute(n)), r.value = t += "", "value" === n || t === e.getAttribute(n)) return t
        }
    }, mt.id = mt.name = mt.coords = function (e, t, n) {
        var r;
        if (!n) return (r = e.getAttributeNode(t)) && "" !== r.value ? r.value : null
    }, d.valHooks.button = {
        get: function (e, t) {
            var n = e.getAttributeNode(t);
            if (n && n.specified) return n.value
        }, set: pt.set
    }, d.attrHooks.contenteditable = {
        set: function (e, t, n) {
            pt.set(e, "" !== t && t, n)
        }
    }, d.each(["width", "height"], function (e, t) {
        d.attrHooks[t] = {
            set: function (e, n) {
                if ("" === n) return e.setAttribute(t, "auto"), n
            }
        }
    })), c.style || (d.attrHooks.style = {
        get: function (e) {
            return e.style.cssText || void 0
        }, set: function (e, t) {
            return e.style.cssText = t + ""
        }
    });
    var bt = /^(?:input|select|textarea|button|object)$/i, xt = /^(?:a|area)$/i;
    d.fn.extend({
        prop: function (e, t) {
            return X(this, d.prop, e, t, arguments.length > 1)
        }, removeProp: function (e) {
            return e = d.propFix[e] || e, this.each(function () {
                try {
                    this[e] = void 0, delete this[e]
                } catch (e) {
                }
            })
        }
    }), d.extend({
        propFix: {for: "htmlFor", class: "className"}, prop: function (e, t, n) {
            var r, i, o = e.nodeType;
            if (e && 3 !== o && 8 !== o && 2 !== o) return (1 !== o || !d.isXMLDoc(e)) && (t = d.propFix[t] || t, i = d.propHooks[t]), void 0 !== n ? i && "set" in i && void 0 !== (r = i.set(e, n, t)) ? r : e[t] = n : i && "get" in i && null !== (r = i.get(e, t)) ? r : e[t]
        }, propHooks: {
            tabIndex: {
                get: function (e) {
                    var t = d.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : bt.test(e.nodeName) || xt.test(e.nodeName) && e.href ? 0 : -1
                }
            }
        }
    }), c.hrefNormalized || d.each(["href", "src"], function (e, t) {
        d.propHooks[t] = {
            get: function (e) {
                return e.getAttribute(t, 4)
            }
        }
    }), c.optSelected || (d.propHooks.selected = {
        get: function (e) {
            var t = e.parentNode;
            return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
        }
    }), d.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        d.propFix[this.toLowerCase()] = this
    }), c.enctype || (d.propFix.enctype = "encoding");
    var wt = /[\t\r\n\f]/g;
    d.fn.extend({
        addClass: function (e) {
            var t, n, r, i, o, a, s = 0, l = this.length, u = "string" == typeof e && e;
            if (d.isFunction(e)) return this.each(function (t) {
                d(this).addClass(e.call(this, t, this.className))
            });
            if (u) for (t = (e || "").match(j) || []; s < l; s++) if (r = 1 === (n = this[s]).nodeType && (n.className ? (" " + n.className + " ").replace(wt, " ") : " ")) {
                for (o = 0; i = t[o++];) r.indexOf(" " + i + " ") < 0 && (r += i + " ");
                a = d.trim(r), n.className !== a && (n.className = a)
            }
            return this
        }, removeClass: function (e) {
            var t, n, r, i, o, a, s = 0, l = this.length, u = 0 === arguments.length || "string" == typeof e && e;
            if (d.isFunction(e)) return this.each(function (t) {
                d(this).removeClass(e.call(this, t, this.className))
            });
            if (u) for (t = (e || "").match(j) || []; s < l; s++) if (r = 1 === (n = this[s]).nodeType && (n.className ? (" " + n.className + " ").replace(wt, " ") : "")) {
                for (o = 0; i = t[o++];) for (; r.indexOf(" " + i + " ") >= 0;) r = r.replace(" " + i + " ", " ");
                a = e ? d.trim(r) : "", n.className !== a && (n.className = a)
            }
            return this
        }, toggleClass: function (e, t) {
            var n = typeof e;
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : d.isFunction(e) ? this.each(function (n) {
                d(this).toggleClass(e.call(this, n, this.className, t), t)
            }) : this.each(function () {
                if ("string" === n) for (var t, r = 0, i = d(this), o = e.match(j) || []; t = o[r++];) i.hasClass(t) ? i.removeClass(t) : i.addClass(t); else n !== M && "boolean" !== n || (this.className && d._data(this, "__className__", this.className), this.className = this.className || !1 === e ? "" : d._data(this, "__className__") || "")
            })
        }, hasClass: function (e) {
            for (var t = " " + e + " ", n = 0, r = this.length; n < r; n++) if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(wt, " ").indexOf(t) >= 0) return !0;
            return !1
        }
    }), d.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
        d.fn[t] = function (e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), d.fn.extend({
        hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }, bind: function (e, t, n) {
            return this.on(e, null, t, n)
        }, unbind: function (e, t) {
            return this.off(e, null, t)
        }, delegate: function (e, t, n, r) {
            return this.on(t, e, n, r)
        }, undelegate: function (e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    });
    var Tt = d.now(), Ct = /\?/,
        Nt = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    d.parseJSON = function (t) {
        if (e.JSON && e.JSON.parse) return e.JSON.parse(t + "");
        var n, r = null, i = d.trim(t + "");
        return i && !d.trim(i.replace(Nt, function (e, t, i, o) {
            return n && t && (r = 0), 0 === r ? e : (n = i || t, r += !o - !i, "")
        })) ? Function("return " + i)() : d.error("Invalid JSON: " + t)
    }, d.parseXML = function (t) {
        var n, r;
        if (!t || "string" != typeof t) return null;
        try {
            e.DOMParser ? (r = new DOMParser, n = r.parseFromString(t, "text/xml")) : ((n = new ActiveXObject("Microsoft.XMLDOM")).async = "false", n.loadXML(t))
        } catch (e) {
            n = void 0
        }
        return n && n.documentElement && !n.getElementsByTagName("parsererror").length || d.error("Invalid XML: " + t), n
    };
    var Et, kt, St = /#.*$/, At = /([?&])_=[^&]*/, Dt = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm, jt = /^(?:GET|HEAD)$/,
        Lt = /^\/\//, Ht = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, qt = {}, _t = {},
        Mt = "*/".concat("*");
    try {
        kt = location.href
    } catch (e) {
        (kt = N.createElement("a")).href = "", kt = kt.href
    }

    function Ft(e) {
        return function (t, n) {
            "string" != typeof t && (n = t, t = "*");
            var r, i = 0, o = t.toLowerCase().match(j) || [];
            if (d.isFunction(n)) for (; r = o[i++];) "+" === r.charAt(0) ? (r = r.slice(1) || "*", (e[r] = e[r] || []).unshift(n)) : (e[r] = e[r] || []).push(n)
        }
    }

    function Ot(e, t, n, r) {
        var i = {}, o = e === _t;

        function a(s) {
            var l;
            return i[s] = !0, d.each(e[s] || [], function (e, s) {
                var u = s(t, n, r);
                return "string" != typeof u || o || i[u] ? o ? !(l = u) : void 0 : (t.dataTypes.unshift(u), a(u), !1)
            }), l
        }

        return a(t.dataTypes[0]) || !i["*"] && a("*")
    }

    function Bt(e, t) {
        var n, r, i = d.ajaxSettings.flatOptions || {};
        for (r in t) void 0 !== t[r] && ((i[r] ? e : n || (n = {}))[r] = t[r]);
        return n && d.extend(!0, e, n), e
    }

    Et = Ht.exec(kt.toLowerCase()) || [], d.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: kt,
            type: "GET",
            isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(Et[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Mt,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {xml: /xml/, html: /html/, json: /json/},
            responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
            converters: {"* text": String, "text html": !0, "text json": d.parseJSON, "text xml": d.parseXML},
            flatOptions: {url: !0, context: !0}
        },
        ajaxSetup: function (e, t) {
            return t ? Bt(Bt(e, d.ajaxSettings), t) : Bt(d.ajaxSettings, e)
        },
        ajaxPrefilter: Ft(qt),
        ajaxTransport: Ft(_t),
        ajax: function (e, t) {
            "object" == typeof e && (t = e, e = void 0), t = t || {};
            var n, r, i, o, a, s, l, u, c = d.ajaxSetup({}, t), f = c.context || c,
                p = c.context && (f.nodeType || f.jquery) ? d(f) : d.event, h = d.Deferred(),
                m = d.Callbacks("once memory"), g = c.statusCode || {}, y = {}, v = {}, b = 0, x = "canceled", w = {
                    readyState: 0, getResponseHeader: function (e) {
                        var t;
                        if (2 === b) {
                            if (!u) for (u = {}; t = Dt.exec(o);) u[t[1].toLowerCase()] = t[2];
                            t = u[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    }, getAllResponseHeaders: function () {
                        return 2 === b ? o : null
                    }, setRequestHeader: function (e, t) {
                        var n = e.toLowerCase();
                        return b || (e = v[n] = v[n] || e, y[e] = t), this
                    }, overrideMimeType: function (e) {
                        return b || (c.mimeType = e), this
                    }, statusCode: function (e) {
                        var t;
                        if (e) if (b < 2) for (t in e) g[t] = [g[t], e[t]]; else w.always(e[w.status]);
                        return this
                    }, abort: function (e) {
                        var t = e || x;
                        return l && l.abort(t), T(0, t), this
                    }
                };
            if (h.promise(w).complete = m.add, w.success = w.done, w.error = w.fail, c.url = ((e || c.url || kt) + "").replace(St, "").replace(Lt, Et[1] + "//"), c.type = t.method || t.type || c.method || c.type, c.dataTypes = d.trim(c.dataType || "*").toLowerCase().match(j) || [""], null == c.crossDomain && (n = Ht.exec(c.url.toLowerCase()), c.crossDomain = !(!n || n[1] === Et[1] && n[2] === Et[2] && (n[3] || ("http:" === n[1] ? "80" : "443")) === (Et[3] || ("http:" === Et[1] ? "80" : "443")))), c.data && c.processData && "string" != typeof c.data && (c.data = d.param(c.data, c.traditional)), Ot(qt, c, t, w), 2 === b) return w;
            (s = d.event && c.global) && 0 == d.active++ && d.event.trigger("ajaxStart"), c.type = c.type.toUpperCase(), c.hasContent = !jt.test(c.type), i = c.url, c.hasContent || (c.data && (i = c.url += (Ct.test(i) ? "&" : "?") + c.data, delete c.data), !1 === c.cache && (c.url = At.test(i) ? i.replace(At, "$1_=" + Tt++) : i + (Ct.test(i) ? "&" : "?") + "_=" + Tt++)), c.ifModified && (d.lastModified[i] && w.setRequestHeader("If-Modified-Since", d.lastModified[i]), d.etag[i] && w.setRequestHeader("If-None-Match", d.etag[i])), (c.data && c.hasContent && !1 !== c.contentType || t.contentType) && w.setRequestHeader("Content-Type", c.contentType), w.setRequestHeader("Accept", c.dataTypes[0] && c.accepts[c.dataTypes[0]] ? c.accepts[c.dataTypes[0]] + ("*" !== c.dataTypes[0] ? ", " + Mt + "; q=0.01" : "") : c.accepts["*"]);
            for (r in c.headers) w.setRequestHeader(r, c.headers[r]);
            if (c.beforeSend && (!1 === c.beforeSend.call(f, w, c) || 2 === b)) return w.abort();
            x = "abort";
            for (r in{success: 1, error: 1, complete: 1}) w[r](c[r]);
            if (l = Ot(_t, c, t, w)) {
                w.readyState = 1, s && p.trigger("ajaxSend", [w, c]), c.async && c.timeout > 0 && (a = setTimeout(function () {
                    w.abort("timeout")
                }, c.timeout));
                try {
                    b = 1, l.send(y, T)
                } catch (e) {
                    if (!(b < 2)) throw e;
                    T(-1, e)
                }
            } else T(-1, "No Transport");

            function T(e, t, n, r) {
                var u, y, v, x, T, C = t;
                2 !== b && (b = 2, a && clearTimeout(a), l = void 0, o = r || "", w.readyState = e > 0 ? 4 : 0, u = e >= 200 && e < 300 || 304 === e, n && (x = function (e, t, n) {
                    for (var r, i, o, a, s = e.contents, l = e.dataTypes; "*" === l[0];) l.shift(), void 0 === i && (i = e.mimeType || t.getResponseHeader("Content-Type"));
                    if (i) for (a in s) if (s[a] && s[a].test(i)) {
                        l.unshift(a);
                        break
                    }
                    if (l[0] in n) o = l[0]; else {
                        for (a in n) {
                            if (!l[0] || e.converters[a + " " + l[0]]) {
                                o = a;
                                break
                            }
                            r || (r = a)
                        }
                        o = o || r
                    }
                    if (o) return o !== l[0] && l.unshift(o), n[o]
                }(c, w, n)), x = function (e, t, n, r) {
                    var i, o, a, s, l, u = {}, c = e.dataTypes.slice();
                    if (c[1]) for (a in e.converters) u[a.toLowerCase()] = e.converters[a];
                    for (o = c.shift(); o;) if (e.responseFields[o] && (n[e.responseFields[o]] = t), !l && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = o, o = c.shift()) if ("*" === o) o = l; else if ("*" !== l && l !== o) {
                        if (!(a = u[l + " " + o] || u["* " + o])) for (i in u) if ((s = i.split(" "))[1] === o && (a = u[l + " " + s[0]] || u["* " + s[0]])) {
                            !0 === a ? a = u[i] : !0 !== u[i] && (o = s[0], c.unshift(s[1]));
                            break
                        }
                        if (!0 !== a) if (a && e.throws) t = a(t); else try {
                            t = a(t)
                        } catch (e) {
                            return {state: "parsererror", error: a ? e : "No conversion from " + l + " to " + o}
                        }
                    }
                    return {state: "success", data: t}
                }(c, x, w, u), u ? (c.ifModified && ((T = w.getResponseHeader("Last-Modified")) && (d.lastModified[i] = T), (T = w.getResponseHeader("etag")) && (d.etag[i] = T)), 204 === e || "HEAD" === c.type ? C = "nocontent" : 304 === e ? C = "notmodified" : (C = x.state, y = x.data, u = !(v = x.error))) : (v = C, !e && C || (C = "error", e < 0 && (e = 0))), w.status = e, w.statusText = (t || C) + "", u ? h.resolveWith(f, [y, C, w]) : h.rejectWith(f, [w, C, v]), w.statusCode(g), g = void 0, s && p.trigger(u ? "ajaxSuccess" : "ajaxError", [w, c, u ? y : v]), m.fireWith(f, [w, C]), s && (p.trigger("ajaxComplete", [w, c]), --d.active || d.event.trigger("ajaxStop")))
            }

            return w
        },
        getJSON: function (e, t, n) {
            return d.get(e, t, n, "json")
        },
        getScript: function (e, t) {
            return d.get(e, void 0, t, "script")
        }
    }), d.each(["get", "post"], function (e, t) {
        d[t] = function (e, n, r, i) {
            return d.isFunction(n) && (i = i || r, r = n, n = void 0), d.ajax({
                url: e,
                type: t,
                dataType: i,
                data: n,
                success: r
            })
        }
    }), d._evalUrl = function (e) {
        return d.ajax({url: e, type: "GET", dataType: "script", async: !1, global: !1, throws: !0})
    }, d.fn.extend({
        wrapAll: function (e) {
            if (d.isFunction(e)) return this.each(function (t) {
                d(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = d(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;) e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        }, wrapInner: function (e) {
            return d.isFunction(e) ? this.each(function (t) {
                d(this).wrapInner(e.call(this, t))
            }) : this.each(function () {
                var t = d(this), n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        }, wrap: function (e) {
            var t = d.isFunction(e);
            return this.each(function (n) {
                d(this).wrapAll(t ? e.call(this, n) : e)
            })
        }, unwrap: function () {
            return this.parent().each(function () {
                d.nodeName(this, "body") || d(this).replaceWith(this.childNodes)
            }).end()
        }
    }), d.expr.filters.hidden = function (e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !c.reliableHiddenOffsets() && "none" === (e.style && e.style.display || d.css(e, "display"))
    }, d.expr.filters.visible = function (e) {
        return !d.expr.filters.hidden(e)
    };
    var Pt = /%20/g, Rt = /\[\]$/, Wt = /\r?\n/g, $t = /^(?:submit|button|image|reset|file)$/i,
        zt = /^(?:input|select|textarea|keygen)/i;

    function It(e, t, n, r) {
        var i;
        if (d.isArray(t)) d.each(t, function (t, i) {
            n || Rt.test(e) ? r(e, i) : It(e + "[" + ("object" == typeof i ? t : "") + "]", i, n, r)
        }); else if (n || "object" !== d.type(t)) r(e, t); else for (i in t) It(e + "[" + i + "]", t[i], n, r)
    }

    d.param = function (e, t) {
        var n, r = [], i = function (e, t) {
            t = d.isFunction(t) ? t() : null == t ? "" : t, r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
        };
        if (void 0 === t && (t = d.ajaxSettings && d.ajaxSettings.traditional), d.isArray(e) || e.jquery && !d.isPlainObject(e)) d.each(e, function () {
            i(this.name, this.value)
        }); else for (n in e) It(n, e[n], t, i);
        return r.join("&").replace(Pt, "+")
    }, d.fn.extend({
        serialize: function () {
            return d.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                var e = d.prop(this, "elements");
                return e ? d.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !d(this).is(":disabled") && zt.test(this.nodeName) && !$t.test(e) && (this.checked || !U.test(e))
            }).map(function (e, t) {
                var n = d(this).val();
                return null == n ? null : d.isArray(n) ? d.map(n, function (e) {
                    return {name: t.name, value: e.replace(Wt, "\r\n")}
                }) : {name: t.name, value: n.replace(Wt, "\r\n")}
            }).get()
        }
    }), d.ajaxSettings.xhr = void 0 !== e.ActiveXObject ? function () {
        return !this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && Jt() || function () {
            try {
                return new e.ActiveXObject("Microsoft.XMLHTTP")
            } catch (e) {
            }
        }()
    } : Jt;
    var Xt = 0, Ut = {}, Vt = d.ajaxSettings.xhr();

    function Jt() {
        try {
            return new e.XMLHttpRequest
        } catch (e) {
        }
    }

    e.attachEvent && e.attachEvent("onunload", function () {
        for (var e in Ut) Ut[e](void 0, !0)
    }), c.cors = !!Vt && "withCredentials" in Vt, (Vt = c.ajax = !!Vt) && d.ajaxTransport(function (e) {
        var t;
        if (!e.crossDomain || c.cors) return {
            send: function (n, r) {
                var i, o = e.xhr(), a = ++Xt;
                if (o.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields) for (i in e.xhrFields) o[i] = e.xhrFields[i];
                e.mimeType && o.overrideMimeType && o.overrideMimeType(e.mimeType), e.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest");
                for (i in n) void 0 !== n[i] && o.setRequestHeader(i, n[i] + "");
                o.send(e.hasContent && e.data || null), t = function (n, i) {
                    var s, l, u;
                    if (t && (i || 4 === o.readyState)) if (delete Ut[a], t = void 0, o.onreadystatechange = d.noop, i) 4 !== o.readyState && o.abort(); else {
                        u = {}, s = o.status, "string" == typeof o.responseText && (u.text = o.responseText);
                        try {
                            l = o.statusText
                        } catch (e) {
                            l = ""
                        }
                        s || !e.isLocal || e.crossDomain ? 1223 === s && (s = 204) : s = u.text ? 200 : 404
                    }
                    u && r(s, l, u, o.getAllResponseHeaders())
                }, e.async ? 4 === o.readyState ? setTimeout(t) : o.onreadystatechange = Ut[a] = t : t()
            }, abort: function () {
                t && t(void 0, !0)
            }
        }
    }), d.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /(?:java|ecma)script/},
        converters: {
            "text script": function (e) {
                return d.globalEval(e), e
            }
        }
    }), d.ajaxPrefilter("script", function (e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), d.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var t, n = N.head || d("head")[0] || N.documentElement;
            return {
                send: function (r, i) {
                    (t = N.createElement("script")).async = !0, e.scriptCharset && (t.charset = e.scriptCharset), t.src = e.url, t.onload = t.onreadystatechange = function (e, n) {
                        (n || !t.readyState || /loaded|complete/.test(t.readyState)) && (t.onload = t.onreadystatechange = null, t.parentNode && t.parentNode.removeChild(t), t = null, n || i(200, "success"))
                    }, n.insertBefore(t, n.firstChild)
                }, abort: function () {
                    t && t.onload(void 0, !0)
                }
            }
        }
    });
    var Yt = [], Gt = /(=)\?(?=&|$)|\?\?/;
    d.ajaxSetup({
        jsonp: "callback", jsonpCallback: function () {
            var e = Yt.pop() || d.expando + "_" + Tt++;
            return this[e] = !0, e
        }
    }), d.ajaxPrefilter("json jsonp", function (t, n, r) {
        var i, o, a,
            s = !1 !== t.jsonp && (Gt.test(t.url) ? "url" : "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && Gt.test(t.data) && "data");
        if (s || "jsonp" === t.dataTypes[0]) return i = t.jsonpCallback = d.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, s ? t[s] = t[s].replace(Gt, "$1" + i) : !1 !== t.jsonp && (t.url += (Ct.test(t.url) ? "&" : "?") + t.jsonp + "=" + i), t.converters["script json"] = function () {
            return a || d.error(i + " was not called"), a[0]
        }, t.dataTypes[0] = "json", o = e[i], e[i] = function () {
            a = arguments
        }, r.always(function () {
            e[i] = o, t[i] && (t.jsonpCallback = n.jsonpCallback, Yt.push(i)), a && d.isFunction(o) && o(a[0]), a = o = void 0
        }), "script"
    }), d.parseHTML = function (e, t, n) {
        if (!e || "string" != typeof e) return null;
        "boolean" == typeof t && (n = t, t = !1), t = t || N;
        var r = x.exec(e), i = !n && [];
        return r ? [t.createElement(r[1])] : (r = d.buildFragment([e], t, i), i && i.length && d(i).remove(), d.merge([], r.childNodes))
    };
    var Qt = d.fn.load;
    d.fn.load = function (e, t, n) {
        if ("string" != typeof e && Qt) return Qt.apply(this, arguments);
        var r, i, o, a = this, s = e.indexOf(" ");
        return s >= 0 && (r = d.trim(e.slice(s, e.length)), e = e.slice(0, s)), d.isFunction(t) ? (n = t, t = void 0) : t && "object" == typeof t && (o = "POST"), a.length > 0 && d.ajax({
            url: e,
            type: o,
            dataType: "html",
            data: t
        }).done(function (e) {
            i = arguments, a.html(r ? d("<div>").append(d.parseHTML(e)).find(r) : e)
        }).complete(n && function (e, t) {
            a.each(n, i || [e.responseText, t, e])
        }), this
    }, d.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        d.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), d.expr.filters.animated = function (e) {
        return d.grep(d.timers, function (t) {
            return e === t.elem
        }).length
    };
    var Kt = e.document.documentElement;

    function Zt(e) {
        return d.isWindow(e) ? e : 9 === e.nodeType && (e.defaultView || e.parentWindow)
    }

    d.offset = {
        setOffset: function (e, t, n) {
            var r, i, o, a, s, l, u = d.css(e, "position"), c = d(e), f = {};
            "static" === u && (e.style.position = "relative"), s = c.offset(), o = d.css(e, "top"), l = d.css(e, "left"), ("absolute" === u || "fixed" === u) && d.inArray("auto", [o, l]) > -1 ? (a = (r = c.position()).top, i = r.left) : (a = parseFloat(o) || 0, i = parseFloat(l) || 0), d.isFunction(t) && (t = t.call(e, n, s)), null != t.top && (f.top = t.top - s.top + a), null != t.left && (f.left = t.left - s.left + i), "using" in t ? t.using.call(e, f) : c.css(f)
        }
    }, d.fn.extend({
        offset: function (e) {
            if (arguments.length) return void 0 === e ? this : this.each(function (t) {
                d.offset.setOffset(this, e, t)
            });
            var t, n, r = {top: 0, left: 0}, i = this[0], o = i && i.ownerDocument;
            return o ? (t = o.documentElement, d.contains(t, i) ? (typeof i.getBoundingClientRect !== M && (r = i.getBoundingClientRect()), n = Zt(o), {
                top: r.top + (n.pageYOffset || t.scrollTop) - (t.clientTop || 0),
                left: r.left + (n.pageXOffset || t.scrollLeft) - (t.clientLeft || 0)
            }) : r) : void 0
        }, position: function () {
            if (this[0]) {
                var e, t, n = {top: 0, left: 0}, r = this[0];
                return "fixed" === d.css(r, "position") ? t = r.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), d.nodeName(e[0], "html") || (n = e.offset()), n.top += d.css(e[0], "borderTopWidth", !0), n.left += d.css(e[0], "borderLeftWidth", !0)), {
                    top: t.top - n.top - d.css(r, "marginTop", !0),
                    left: t.left - n.left - d.css(r, "marginLeft", !0)
                }
            }
        }, offsetParent: function () {
            return this.map(function () {
                for (var e = this.offsetParent || Kt; e && !d.nodeName(e, "html") && "static" === d.css(e, "position");) e = e.offsetParent;
                return e || Kt
            })
        }
    }), d.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (e, t) {
        var n = /Y/.test(t);
        d.fn[e] = function (r) {
            return X(this, function (e, r, i) {
                var o = Zt(e);
                if (void 0 === i) return o ? t in o ? o[t] : o.document.documentElement[r] : e[r];
                o ? o.scrollTo(n ? d(o).scrollLeft() : i, n ? i : d(o).scrollTop()) : e[r] = i
            }, e, r, arguments.length, null)
        }
    }), d.each(["top", "left"], function (e, t) {
        d.cssHooks[t] = Me(c.pixelPosition, function (e, n) {
            if (n) return n = Le(e, t), qe.test(n) ? d(e).position()[t] + "px" : n
        })
    }), d.each({Height: "height", Width: "width"}, function (e, t) {
        d.each({padding: "inner" + e, content: t, "": "outer" + e}, function (n, r) {
            d.fn[r] = function (r, i) {
                var o = arguments.length && (n || "boolean" != typeof r),
                    a = n || (!0 === r || !0 === i ? "margin" : "border");
                return X(this, function (t, n, r) {
                    var i;
                    return d.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (i = t.documentElement, Math.max(t.body["scroll" + e], i["scroll" + e], t.body["offset" + e], i["offset" + e], i["client" + e])) : void 0 === r ? d.css(t, n, a) : d.style(t, n, r, a)
                }, t, o ? r : void 0, o, null)
            }
        })
    }), d.fn.size = function () {
        return this.length
    }, d.fn.andSelf = d.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function () {
        return d
    });
    var en = e.jQuery, tn = e.$;
    return d.noConflict = function (t) {
        return e.$ === d && (e.$ = tn), t && e.jQuery === d && (e.jQuery = en), d
    }, typeof t === M && (e.jQuery = e.$ = d), d
});