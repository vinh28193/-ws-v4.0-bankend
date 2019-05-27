!function () {
    this.loading = new function () {
        this.show = function () {
            $(".loading_new").css("display", "block")
        }, this.hide = function () {
            $(".loading_new").css("display", "none")
        }
    }, this.popup = new function () {
        this.open = function (o, t, i, e, n, s) {
            if ($("#" + o).length > 0 && $("#" + o).remove(), "" === n && (n = ""), $("body:first").append('<div class="modal fade in" id="' + o + '" style="' + (void 0 !== n ? "width:800px" : "") + '" >                <div class="modal-dialog" >                    <div class="modal-content">                        <div class="modal-header">                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                            ' + (null === t || "" === t ? "" : '<h4 class="modal-title">' + t + "</h4>") + '                        </div>                        <div class="modal-body">' + i + '</div>                        <div class="modal-footer"></div>                    </div>                </div>            </div>'), $("#" + o + " .close").click(function () {
                    popup.close(o)
                }), e) for (var p = 0; p < e.length; p++) $("#" + o + " .modal-footer").append('<button type="button" class="btn ' + e[p].style + '" id="popup-cmd-' + o + "-" + p + '">' + e[p].title + "</button>"), $("#popup-cmd-" + o + "-" + p).click(e[p].fn);
            var a = {};
            void 0 !== s && 1 == s && (a.backdrop = "static"), $("#" + o).modal(a), $("body").keydown(function (t) {
                27 === t.keyCode && popup.close(o)
            })
        }, this.close = function (o) {
            $("#" + o).removeClass("fade").modal("hide"), $("#" + o).remove();
            var t = !1;
            $(".modal").each(function () {
                $(this).is(":visible") && (t = !0)
            }), t || ($("body").removeClass("modal-open"), $("body").css("padding-right", "0px"), $(".modal-backdrop").remove())
        }, this.msg = function (o, t) {
            this.open("popup-msg", textutils.getLanguageByKey(languages.js_title_popup, "Notification"), '<div style="min-width: 300px">' + o + "</div>", [{
                title: textutils.getLanguageByKey(languages.js_agee_popup, "Agree"),
                style: "btn-primary",
                fn: function () {
                    t && t(), popup.close("popup-msg")
                }
            }])
        }, this.confirm = function (o, t) {
            this.open("popup-confirm", "Confirm", '<div class="container" style="min-width: 300px">' + o + "</div>", [{
                title: textutils.getLanguageByKey(languages.js_agee_popup, "Agree"),
                style: "btn-primary",
                fn: function () {
                    t(), popup.close("popup-confirm")
                }
            }, {
                title: textutils.getLanguageByKey(languages.js_reject_popup, "Reject"), fn: function () {
                    popup.close("popup-confirm")
                }
            }])
        }
    }
}();