var Base = function () {
};
Base.extend = function (t, i) {
    "use strict";
    var e = Base.prototype.extend;
    Base._prototyping = !0;
    var n = new this;
    e.call(n, t), n.base = function () {
    }, delete Base._prototyping;
    var s = n.constructor, o = n.constructor = function () {
        if (!Base._prototyping) if (this._constructing || this.constructor == o) this._constructing = !0, s.apply(this, arguments), delete this._constructing; else if (null !== arguments[0]) return (arguments[0].extend || e).call(arguments[0], n)
    };
    return o.ancestor = this, o.extend = this.extend, o.forEach = this.forEach, o.implement = this.implement, o.prototype = n, o.toString = this.toString, o.valueOf = function (t) {
        return "object" == t ? o : s.valueOf()
    }, e.call(o, i), "function" == typeof o.init && o.init(), o
}, Base.prototype = {
    extend: function (t, i) {
        if (arguments.length > 1) {
            var e = this[t];
            if (e && "function" == typeof i && (!e.valueOf || e.valueOf() != i.valueOf()) && /\bbase\b/.test(i)) {
                var n = i.valueOf();
                (i = function () {
                    var t = this.base || Base.prototype.base;
                    this.base = e;
                    var i = n.apply(this, arguments);
                    return this.base = t, i
                }).valueOf = function (t) {
                    return "object" == t ? i : n
                }, i.toString = Base.toString
            }
            this[t] = i
        } else if (t) {
            var s = Base.prototype.extend;
            Base._prototyping || "function" == typeof this || (s = this.extend || s);
            for (var o = {toSource: null}, a = ["constructor", "toString", "valueOf"], c = Base._prototyping ? 0 : 1; r = a[c++];) t[r] != o[r] && s.call(this, r, t[r]);
            for (var r in t) o[r] || s.call(this, r, t[r])
        }
        return this
    }
}, Base = Base.extend({
    constructor: function () {
        this.extend(arguments[0])
    }
}, {
    ancestor: Object, version: "1.1", forEach: function (t, i, e) {
        for (var n in t) void 0 === this.prototype[n] && i.call(e, t[n], n, t)
    }, implement: function () {
        for (var t = 0; t < arguments.length; t++) "function" == typeof arguments[t] ? arguments[t](this.prototype) : this.prototype.extend(arguments[t]);
        return this
    }, toString: function () {
        return String(this.valueOf())
    }
});
var FlipClock;
!function (t) {
    "use strict";
    (FlipClock = function (t, i, e) {
        return i instanceof Object && i instanceof Date == !1 && (e = i, i = 0), new FlipClock.Factory(t, i, e)
    }).Lang = {}, FlipClock.Base = Base.extend({
        buildDate: "2014-12-12",
        version: "0.7.7",
        constructor: function (i, e) {
            "object" != typeof i && (i = {}), "object" != typeof e && (e = {}), this.setOptions(t.extend(!0, {}, i, e))
        },
        callback: function (t) {
            if ("function" == typeof t) {
                for (var i = [], e = 1; e <= arguments.length; e++) arguments[e] && i.push(arguments[e]);
                t.apply(this, i)
            }
        },
        log: function (t) {
            window.console && console.log && console.log(t)
        },
        getOption: function (t) {
            return !!this[t] && this[t]
        },
        getOptions: function () {
            return this
        },
        setOption: function (t, i) {
            this[t] = i
        },
        setOptions: function (t) {
            for (var i in t) void 0 !== t[i] && this.setOption(i, t[i])
        }
    })
}(jQuery), function (t) {
    "use strict";
    FlipClock.Face = FlipClock.Base.extend({
        autoStart: !0,
        dividers: [],
        factory: !1,
        lists: [],
        constructor: function (t, i) {
            this.dividers = [], this.lists = [], this.base(i), this.factory = t
        },
        build: function () {
            this.autoStart && this.start()
        },
        createDivider: function (i, e, n) {
            "boolean" != typeof e && e || (n = e, e = i);
            var s = ['<span class="' + this.factory.classes.dot + ' top"></span>', '<span class="' + this.factory.classes.dot + ' bottom"></span>'].join("");
            n && (s = ""), i = this.factory.localize(i);
            var o = ['<span class="' + this.factory.classes.divider + " " + (e || "").toLowerCase() + '">', '<span class="' + this.factory.classes.label + '">' + (i || "") + "</span>", s, "</span>"],
                a = t(o.join(""));
            return this.dividers.push(a), a
        },
        createList: function (t, i) {
            "object" == typeof t && (i = t, t = 0);
            var e = new FlipClock.List(this.factory, t, i);
            return this.lists.push(e), e
        },
        reset: function () {
            this.factory.time = new FlipClock.Time(this.factory, this.factory.original ? Math.round(this.factory.original) : 0, {minimumDigits: this.factory.minimumDigits}), this.flip(this.factory.original, !1)
        },
        appendDigitToClock: function (t) {
            t.$el.append(!1)
        },
        addDigit: function (t) {
            var i = this.createList(t, {
                classes: {
                    active: this.factory.classes.active,
                    before: this.factory.classes.before,
                    flip: this.factory.classes.flip
                }
            });
            this.appendDigitToClock(i)
        },
        start: function () {
        },
        stop: function () {
        },
        autoIncrement: function () {
            this.factory.countdown ? this.decrement() : this.increment()
        },
        increment: function () {
            this.factory.time.addSecond()
        },
        decrement: function () {
            0 == this.factory.time.getTimeSeconds() ? this.factory.stop() : this.factory.time.subSecond()
        },
        flip: function (i, e) {
            var n = this;
            t.each(i, function (t, i) {
                var s = n.lists[t];
                s ? (e || i == s.digit || s.play(), s.select(i)) : n.addDigit(i)
            })
        }
    })
}(jQuery), function (t) {
    "use strict";
    FlipClock.Factory = FlipClock.Base.extend({
        animationRate: 1e3,
        autoStart: !0,
        callbacks: {destroy: !1, create: !1, init: !1, interval: !1, start: !1, stop: !1, reset: !1},
        classes: {
            active: "flip-clock-active",
            before: "flip-clock-before",
            divider: "flip-clock-divider",
            dot: "flip-clock-dot",
            label: "flip-clock-label",
            flip: "flip",
            play: "play",
            wrapper: "flip-clock-wrapper"
        },
        clockFace: "HourlyCounter",
        countdown: !1,
        defaultClockFace: "HourlyCounter",
        defaultLanguage: "english",
        $el: !1,
        face: !0,
        lang: !1,
        language: "english",
        minimumDigits: 0,
        original: !1,
        running: !1,
        time: !1,
        timer: !1,
        $wrapper: !1,
        constructor: function (i, e, n) {
            n || (n = {}), this.lists = [], this.running = !1, this.base(n), this.$el = t(i).addClass(this.classes.wrapper), this.$wrapper = this.$el, this.original = e instanceof Date ? e : e ? Math.round(e) : 0, this.time = new FlipClock.Time(this, this.original, {
                minimumDigits: this.minimumDigits,
                animationRate: this.animationRate
            }), this.timer = new FlipClock.Timer(this, n), this.loadLanguage(this.language), this.loadClockFace(this.clockFace, n), this.autoStart && this.start()
        },
        loadClockFace: function (t, i) {
            var e, n = !1;
            return t = t.ucfirst() + "Face", this.face.stop && (this.stop(), n = !0), this.$el.html(""), this.time.minimumDigits = this.minimumDigits, (e = FlipClock[t] ? new FlipClock[t](this, i) : new FlipClock[this.defaultClockFace + "Face"](this, i)).build(), this.face = e, n && this.start(), this.face
        },
        loadLanguage: function (t) {
            var i;
            return i = FlipClock.Lang[t.ucfirst()] ? FlipClock.Lang[t.ucfirst()] : FlipClock.Lang[t] ? FlipClock.Lang[t] : FlipClock.Lang[this.defaultLanguage], this.lang = i
        },
        localize: function (t, i) {
            var e = this.lang;
            if (!t) return null;
            var n = t.toLowerCase();
            return "object" == typeof i && (e = i), e && e[n] ? e[n] : t
        },
        start: function (t) {
            var i = this;
            i.running || i.countdown && !(i.countdown && i.time.time > 0) ? i.log("Trying to start timer when countdown already at 0") : (i.face.start(i.time), i.timer.start(function () {
                i.flip(), "function" == typeof t && t()
            }))
        },
        stop: function (t) {
            this.face.stop(), this.timer.stop(t);
            for (var i in this.lists) this.lists.hasOwnProperty(i) && this.lists[i].stop()
        },
        reset: function (t) {
            this.timer.reset(t), this.face.reset()
        },
        setTime: function (t) {
            this.time.time = t, this.flip(!0)
        },
        getTime: function (t) {
            return this.time
        },
        setCountdown: function (t) {
            var i = this.running;
            this.countdown = !!t, i && (this.stop(), this.start())
        },
        flip: function (t) {
            this.face.flip(!1, t)
        }
    })
}(jQuery), function (t) {
    "use strict";
    FlipClock.List = FlipClock.Base.extend({
        digit: 0,
        classes: {active: "flip-clock-active", before: "flip-clock-before", flip: "flip"},
        factory: !1,
        $el: !1,
        $obj: !1,
        items: [],
        lastDigit: 0,
        constructor: function (t, i, e) {
            this.factory = t, this.digit = i, this.lastDigit = i, this.$el = this.createList(), this.$obj = this.$el, i > 0 && this.select(i), this.factory.$el.append(this.$el)
        },
        select: function (t) {
            if (void 0 === t ? t = this.digit : this.digit = t, this.digit != this.lastDigit) {
                var i = this.$el.find("." + this.classes.before).removeClass(this.classes.before);
                this.$el.find("." + this.classes.active).removeClass(this.classes.active).addClass(this.classes.before), this.appendListItem(this.classes.active, this.digit), i.remove(), this.lastDigit = this.digit
            }
        },
        play: function () {
            this.$el.addClass(this.factory.classes.play)
        },
        stop: function () {
            var t = this;
            setTimeout(function () {
                t.$el.removeClass(t.factory.classes.play)
            }, this.factory.timer.interval)
        },
        createListItem: function (t, i) {
            return ['<li class="' + (t || "") + '">', '<a href="#">', '<div class="up">', '<div class="shadow"></div>', '<div class="inn">' + (i || "") + "</div>", "</div>", '<div class="down">', '<div class="shadow"></div>', '<div class="inn">' + (i || "") + "</div>", "</div>", "</a>", "</li>"].join("")
        },
        appendListItem: function (t, i) {
            var e = this.createListItem(t, i);
            this.$el.append(e)
        },
        createList: function () {
            var i = this.getPrevDigit() ? this.getPrevDigit() : this.digit;
            return t(['<ul class="' + this.classes.flip + " " + (this.factory.running ? this.factory.classes.play : "") + '">', this.createListItem(this.classes.before, i), this.createListItem(this.classes.active, this.digit), "</ul>"].join(""))
        },
        getNextDigit: function () {
            return 9 == this.digit ? 0 : this.digit + 1
        },
        getPrevDigit: function () {
            return 0 == this.digit ? 9 : this.digit - 1
        }
    })
}(jQuery), function (t) {
    "use strict";
    String.prototype.ucfirst = function () {
        return this.substr(0, 1).toUpperCase() + this.substr(1)
    }, t.fn.FlipClock = function (i, e) {
        return new FlipClock(t(this), i, e)
    }, t.fn.flipClock = function (i, e) {
        return t.fn.FlipClock(i, e)
    }
}(jQuery), function (t) {
    "use strict";
    FlipClock.Time = FlipClock.Base.extend({
        time: 0, factory: !1, minimumDigits: 0, constructor: function (t, i, e) {
            "object" != typeof e && (e = {}), e.minimumDigits || (e.minimumDigits = t.minimumDigits), this.base(e), this.factory = t, i && (this.time = i)
        }, convertDigitsToArray: function (t) {
            var i = [];
            t = t.toString();
            for (var e = 0; e < t.length; e++) t[e].match(/^\d*$/g) && i.push(t[e]);
            return i
        }, digit: function (t) {
            var i = this.toString(), e = i.length;
            return !!i[e - t] && i[e - t]
        }, digitize: function (i) {
            var e = [];
            if (t.each(i, function (t, i) {
                    1 == (i = i.toString()).length && (i = "0" + i);
                    for (var n = 0; n < i.length; n++) e.push(i.charAt(n))
                }), e.length > this.minimumDigits && (this.minimumDigits = e.length), this.minimumDigits > e.length) for (var n = e.length; n < this.minimumDigits; n++) e.unshift("0");
            return e
        }, getDateObject: function () {
            return this.time instanceof Date ? this.time : new Date((new Date).getTime() + 1e3 * this.getTimeSeconds())
        }, getDayCounter: function (t) {
            var i = [this.getDays(), this.getHours(!0), this.getMinutes(!0)];
            return t && i.push(this.getSeconds(!0)), this.digitize(i)
        }, getDays: function (t) {
            var i = this.getTimeSeconds() / 60 / 60 / 24;
            return t && (i %= 7), Math.floor(i)
        }, getHourCounter: function () {
            return this.digitize([this.getHours(), this.getMinutes(!0), this.getSeconds(!0)])
        }, getHourly: function () {
            return this.getHourCounter()
        }, getHours: function (t) {
            var i = this.getTimeSeconds() / 60 / 60;
            return t && (i %= 24), Math.floor(i)
        }, getMilitaryTime: function (t, i) {
            void 0 === i && (i = !0), t || (t = this.getDateObject());
            var e = [t.getHours(), t.getMinutes()];
            return !0 === i && e.push(t.getSeconds()), this.digitize(e)
        }, getMinutes: function (t) {
            var i = this.getTimeSeconds() / 60;
            return t && (i %= 60), Math.floor(i)
        }, getMinuteCounter: function () {
            return this.digitize([this.getMinutes(), this.getSeconds(!0)])
        }, getTimeSeconds: function (t) {
            return t || (t = new Date), this.time instanceof Date ? this.factory.countdown ? Math.max(this.time.getTime() / 1e3 - t.getTime() / 1e3, 0) : t.getTime() / 1e3 - this.time.getTime() / 1e3 : this.time
        }, getTime: function (t, i) {
            void 0 === i && (i = !0), t || (t = this.getDateObject()), console.log(t);
            var e = t.getHours(), n = [e > 12 ? e - 12 : 0 === e ? 12 : e, t.getMinutes()];
            return !0 === i && n.push(t.getSeconds()), this.digitize(n)
        }, getSeconds: function (t) {
            var i = this.getTimeSeconds();
            return t && (60 == i ? i = 0 : i %= 60), Math.ceil(i)
        }, getWeeks: function (t) {
            var i = this.getTimeSeconds() / 60 / 60 / 24 / 7;
            return t && (i %= 52), Math.floor(i)
        }, removeLeadingZeros: function (i, e) {
            var n = 0, s = [];
            return t.each(e, function (t, o) {
                t < i ? n += parseInt(e[t], 10) : s.push(e[t])
            }), 0 === n ? s : e
        }, addSeconds: function (t) {
            this.time instanceof Date ? this.time.setSeconds(this.time.getSeconds() + t) : this.time += t
        }, addSecond: function () {
            this.addSeconds(1)
        }, subSeconds: function (t) {
            this.time instanceof Date ? this.time.setSeconds(this.time.getSeconds() - t) : this.time -= t
        }, subSecond: function () {
            this.subSeconds(1)
        }, toString: function () {
            return this.getTimeSeconds().toString()
        }
    })
}(jQuery), function (t) {
    "use strict";
    FlipClock.Timer = FlipClock.Base.extend({
        callbacks: {
            destroy: !1,
            create: !1,
            init: !1,
            interval: !1,
            start: !1,
            stop: !1,
            reset: !1
        }, count: 0, factory: !1, interval: 1e3, animationRate: 1e3, constructor: function (t, i) {
            this.base(i), this.factory = t, this.callback(this.callbacks.init), this.callback(this.callbacks.create)
        }, getElapsed: function () {
            return this.count * this.interval
        }, getElapsedTime: function () {
            return new Date(this.time + this.getElapsed())
        }, reset: function (t) {
            clearInterval(this.timer), this.count = 0, this._setInterval(t), this.callback(this.callbacks.reset)
        }, start: function (t) {
            this.factory.running = !0, this._createTimer(t), this.callback(this.callbacks.start)
        }, stop: function (t) {
            this.factory.running = !1, this._clearInterval(t), this.callback(this.callbacks.stop), this.callback(t)
        }, _clearInterval: function () {
            clearInterval(this.timer)
        }, _createTimer: function (t) {
            this._setInterval(t)
        }, _destroyTimer: function (t) {
            this._clearInterval(), this.timer = !1, this.callback(t), this.callback(this.callbacks.destroy)
        }, _interval: function (t) {
            this.callback(this.callbacks.interval), this.callback(t), this.count++
        }, _setInterval: function (t) {
            var i = this;
            i._interval(t), i.timer = setInterval(function () {
                i._interval(t)
            }, this.interval)
        }
    })
}(jQuery), function (t) {
    FlipClock.TwentyFourHourClockFace = FlipClock.Face.extend({
        constructor: function (t, i) {
            this.base(t, i)
        }, build: function (i) {
            var e = this, n = this.factory.$el.find("ul");
            this.factory.time.time || (this.factory.original = new Date, this.factory.time = new FlipClock.Time(this.factory, this.factory.original)), (i = i || this.factory.time.getMilitaryTime(!1, this.showSeconds)).length > n.length && t.each(i, function (t, i) {
                e.createList(i)
            }), this.createDivider(), this.createDivider(), t(this.dividers[0]).insertBefore(this.lists[this.lists.length - 2].$el), t(this.dividers[1]).insertBefore(this.lists[this.lists.length - 4].$el), this.base()
        }, flip: function (t, i) {
            this.autoIncrement(), t = t || this.factory.time.getMilitaryTime(!1, this.showSeconds), this.base(t, i)
        }
    })
}(jQuery), function (t) {
    FlipClock.CounterFace = FlipClock.Face.extend({
        shouldAutoIncrement: !1, constructor: function (t, i) {
            "object" != typeof i && (i = {}), t.autoStart = !!i.autoStart, i.autoStart && (this.shouldAutoIncrement = !0), t.increment = function () {
                t.countdown = !1, t.setTime(t.getTime().getTimeSeconds() + 1)
            }, t.decrement = function () {
                t.countdown = !0;
                var i = t.getTime().getTimeSeconds();
                i > 0 && t.setTime(i - 1)
            }, t.setValue = function (i) {
                t.setTime(i)
            }, t.setCounter = function (i) {
                t.setTime(i)
            }, this.base(t, i)
        }, build: function () {
            var i = this, e = this.factory.$el.find("ul"),
                n = this.factory.getTime().digitize([this.factory.getTime().time]);
            n.length > e.length && t.each(n, function (t, e) {
                i.createList(e).select(e)
            }), t.each(this.lists, function (t, i) {
                i.play()
            }), this.base()
        }, flip: function (t, i) {
            this.shouldAutoIncrement && this.autoIncrement(), t || (t = this.factory.getTime().digitize([this.factory.getTime().time])), this.base(t, i)
        }, reset: function () {
            this.factory.time = new FlipClock.Time(this.factory, this.factory.original ? Math.round(this.factory.original) : 0), this.flip()
        }
    })
}(jQuery), function (t) {
    FlipClock.DailyCounterFace = FlipClock.Face.extend({
        showSeconds: !0, constructor: function (t, i) {
            this.base(t, i)
        }, build: function (i) {
            var e = this, n = this.factory.$el.find("ul"), s = 0;
            (i = i || this.factory.time.getDayCounter(this.showSeconds)).length > n.length && t.each(i, function (t, i) {
                e.createList(i)
            }), this.showSeconds ? t(this.createDivider("Seconds")).insertBefore(this.lists[this.lists.length - 2].$el) : s = 2, t(this.createDivider("Minutes")).insertBefore(this.lists[this.lists.length - 4 + s].$el), t(this.createDivider("Hours")).insertBefore(this.lists[this.lists.length - 6 + s].$el), t(this.createDivider("Days", !0)).insertBefore(this.lists[0].$el), this.base()
        }, flip: function (t, i) {
            t || (t = this.factory.time.getDayCounter(this.showSeconds)), this.autoIncrement(), this.base(t, i)
        }
    })
}(jQuery), function (t) {
    FlipClock.HourlyCounterFace = FlipClock.Face.extend({
        constructor: function (t, i) {
            this.base(t, i)
        }, build: function (i, e) {
            var n = this, s = this.factory.$el.find("ul");
            (e = e || this.factory.time.getHourCounter()).length > s.length && t.each(e, function (t, i) {
                n.createList(i)
            }), t(this.createDivider("Seconds")).insertBefore(this.lists[this.lists.length - 2].$el), t(this.createDivider("Minutes")).insertBefore(this.lists[this.lists.length - 4].$el), i || t(this.createDivider("Hours", !0)).insertBefore(this.lists[0].$el), this.base()
        }, flip: function (t, i) {
            t || (t = this.factory.time.getHourCounter()), this.autoIncrement(), this.base(t, i)
        }, appendDigitToClock: function (t) {
            this.base(t), this.dividers[0].insertAfter(this.dividers[0].next())
        }
    })
}(jQuery), function (t) {
    FlipClock.MinuteCounterFace = FlipClock.HourlyCounterFace.extend({
        clearExcessDigits: !1,
        constructor: function (t, i) {
            this.base(t, i)
        },
        build: function () {
            this.base(!0, this.factory.time.getMinuteCounter())
        },
        flip: function (t, i) {
            t || (t = this.factory.time.getMinuteCounter()), this.base(t, i)
        }
    })
}(jQuery), function (t) {
    FlipClock.TwelveHourClockFace = FlipClock.TwentyFourHourClockFace.extend({
        meridium: !1,
        meridiumText: "AM",
        build: function () {
            var i = this.factory.time.getTime(!1, this.showSeconds);
            this.base(i), this.meridiumText = this.getMeridium(), this.meridium = t(['<ul class="flip-clock-meridium">', "<li>", '<a href="#">' + this.meridiumText + "</a>", "</li>", "</ul>"].join("")), this.meridium.insertAfter(this.lists[this.lists.length - 1].$el)
        },
        flip: function (t, i) {
            this.meridiumText != this.getMeridium() && (this.meridiumText = this.getMeridium(), this.meridium.find("a").html(this.meridiumText)), this.base(this.factory.time.getTime(!1, this.showSeconds), i)
        },
        getMeridium: function () {
            return (new Date).getHours() >= 12 ? "PM" : "AM"
        },
        isPM: function () {
            return "PM" == this.getMeridium()
        },
        isAM: function () {
            return "AM" == this.getMeridium()
        }
    })
}(jQuery), function (t) {
    FlipClock.Lang.Arabic = {
        years: "سنوات",
        months: "شهور",
        days: "أيام",
        hours: "ساعات",
        minutes: "دقائق",
        seconds: "ثواني"
    }, FlipClock.Lang.ar = FlipClock.Lang.Arabic, FlipClock.Lang["ar-ar"] = FlipClock.Lang.Arabic, FlipClock.Lang.arabic = FlipClock.Lang.Arabic
}(jQuery), function (t) {
    FlipClock.Lang.Danish = {
        years: "År",
        months: "Måneder",
        days: "Dage",
        hours: "Timer",
        minutes: "Minutter",
        seconds: "Sekunder"
    }, FlipClock.Lang.da = FlipClock.Lang.Danish, FlipClock.Lang["da-dk"] = FlipClock.Lang.Danish, FlipClock.Lang.danish = FlipClock.Lang.Danish
}(jQuery), function (t) {
    FlipClock.Lang.German = {
        years: "Jahre",
        months: "Monate",
        days: "Tage",
        hours: "Stunden",
        minutes: "Minuten",
        seconds: "Sekunden"
    }, FlipClock.Lang.de = FlipClock.Lang.German, FlipClock.Lang["de-de"] = FlipClock.Lang.German, FlipClock.Lang.german = FlipClock.Lang.German
}(jQuery), function (t) {
    FlipClock.Lang.English = {
        years: "Years",
        months: "Months",
        days: "Days",
        hours: "Hours",
        minutes: "Minutes",
        seconds: "Seconds"
    }, FlipClock.Lang.en = FlipClock.Lang.English, FlipClock.Lang["en-us"] = FlipClock.Lang.English, FlipClock.Lang.english = FlipClock.Lang.English
}(jQuery), function (t) {
    FlipClock.Lang.Spanish = {
        years: "Años",
        months: "Meses",
        days: "Días",
        hours: "Horas",
        minutes: "Minutos",
        seconds: "Segundos"
    }, FlipClock.Lang.es = FlipClock.Lang.Spanish, FlipClock.Lang["es-es"] = FlipClock.Lang.Spanish, FlipClock.Lang.spanish = FlipClock.Lang.Spanish
}(jQuery), function (t) {
    FlipClock.Lang.Finnish = {
        years: "Vuotta",
        months: "Kuukautta",
        days: "Päivää",
        hours: "Tuntia",
        minutes: "Minuuttia",
        seconds: "Sekuntia"
    }, FlipClock.Lang.fi = FlipClock.Lang.Finnish, FlipClock.Lang["fi-fi"] = FlipClock.Lang.Finnish, FlipClock.Lang.finnish = FlipClock.Lang.Finnish
}(jQuery), function (t) {
    FlipClock.Lang.French = {
        years: "Ans",
        months: "Mois",
        days: "Jours",
        hours: "Heures",
        minutes: "Minutes",
        seconds: "Secondes"
    }, FlipClock.Lang.fr = FlipClock.Lang.French, FlipClock.Lang["fr-ca"] = FlipClock.Lang.French, FlipClock.Lang.french = FlipClock.Lang.French
}(jQuery), function (t) {
    FlipClock.Lang.Italian = {
        years: "Anni",
        months: "Mesi",
        days: "Giorni",
        hours: "Ore",
        minutes: "Minuti",
        seconds: "Secondi"
    }, FlipClock.Lang.it = FlipClock.Lang.Italian, FlipClock.Lang["it-it"] = FlipClock.Lang.Italian, FlipClock.Lang.italian = FlipClock.Lang.Italian
}(jQuery), function (t) {
    FlipClock.Lang.Latvian = {
        years: "Gadi",
        months: "Mēneši",
        days: "Dienas",
        hours: "Stundas",
        minutes: "Minūtes",
        seconds: "Sekundes"
    }, FlipClock.Lang.lv = FlipClock.Lang.Latvian, FlipClock.Lang["lv-lv"] = FlipClock.Lang.Latvian, FlipClock.Lang.latvian = FlipClock.Lang.Latvian
}(jQuery), function (t) {
    FlipClock.Lang.Dutch = {
        years: "Jaren",
        months: "Maanden",
        days: "Dagen",
        hours: "Uren",
        minutes: "Minuten",
        seconds: "Seconden"
    }, FlipClock.Lang.nl = FlipClock.Lang.Dutch, FlipClock.Lang["nl-be"] = FlipClock.Lang.Dutch, FlipClock.Lang.dutch = FlipClock.Lang.Dutch
}(jQuery), function (t) {
    FlipClock.Lang.Norwegian = {
        years: "År",
        months: "Måneder",
        days: "Dager",
        hours: "Timer",
        minutes: "Minutter",
        seconds: "Sekunder"
    }, FlipClock.Lang.no = FlipClock.Lang.Norwegian, FlipClock.Lang.nb = FlipClock.Lang.Norwegian, FlipClock.Lang["no-nb"] = FlipClock.Lang.Norwegian, FlipClock.Lang.norwegian = FlipClock.Lang.Norwegian
}(jQuery), function (t) {
    FlipClock.Lang.Portuguese = {
        years: "Anos",
        months: "Meses",
        days: "Dias",
        hours: "Horas",
        minutes: "Minutos",
        seconds: "Segundos"
    }, FlipClock.Lang.pt = FlipClock.Lang.Portuguese, FlipClock.Lang["pt-br"] = FlipClock.Lang.Portuguese, FlipClock.Lang.portuguese = FlipClock.Lang.Portuguese
}(jQuery), function (t) {
    FlipClock.Lang.Russian = {
        years: "лет",
        months: "месяцев",
        days: "дней",
        hours: "часов",
        minutes: "минут",
        seconds: "секунд"
    }, FlipClock.Lang.ru = FlipClock.Lang.Russian, FlipClock.Lang["ru-ru"] = FlipClock.Lang.Russian, FlipClock.Lang.russian = FlipClock.Lang.Russian
}(jQuery), function (t) {
    FlipClock.Lang.Swedish = {
        years: "År",
        months: "Månader",
        days: "Dagar",
        hours: "Timmar",
        minutes: "Minuter",
        seconds: "Sekunder"
    }, FlipClock.Lang.sv = FlipClock.Lang.Swedish, FlipClock.Lang["sv-se"] = FlipClock.Lang.Swedish, FlipClock.Lang.swedish = FlipClock.Lang.Swedish
}(jQuery), function (t) {
    FlipClock.Lang.Chinese = {
        years: "年",
        months: "月",
        days: "日",
        hours: "时",
        minutes: "分",
        seconds: "秒"
    }, FlipClock.Lang.zh = FlipClock.Lang.Chinese, FlipClock.Lang["zh-cn"] = FlipClock.Lang.Chinese, FlipClock.Lang.chinese = FlipClock.Lang.Chinese
}(jQuery);