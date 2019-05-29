function Popup() {
    this.open = function (o, i, s) {
        0 != $("#" + o).length && $("#" + o).remove(), $("body:first").append('<div id="' + o + '" class="popup">                    <div class="popup-wrapper">                    <div class="popup-title">                            <a class="popup-close"></a>                            <div class="ptitle-right">' + i + '</div>                            <div class="ptitle-left"></div>                    </div>                    <div class="popup-content">' + s + "</div>\x3c!--popup-content--\x3e                </div>\x3c!--popup-wrapper--\x3e            </div>\x3c!--popup--\x3e"), $("#" + o + " .popup-close").click(function () {
            popup.close(o)
        }), popup.resetPos(), $("#" + o).show("drop"), shadow.show(), $("#" + o).resize(function () {
            popup.resetPos()
        }), $(window).resize(function () {
            popup.resetPos()
        }), $("html, body").animate({scrollTop: $("body").offset().top + 150}, 700)
    }, this.close = function (o) {
        $("#" + o).hide("drop", function () {
            $("#" + o).remove()
        }), $(".ui-effects-wrapper").remove(), shadow.hide()
    }, this.resetPos = function () {
        iWidthWindow = $(window).width(), iHeightWindow = $(window).height();
        var o = (iWidthWindow - $(".popup").width()) / 2;
        iHeightWindow, $(".popup").height();
        $(".popup").css({left: o + "px"})
    }, this.err = function (o) {
        this.open("popup-err", "Message", '<div class="popup-label"><span class="icon-error-info"></span>Error</div>            <div class="form-message">            \t<p>' + o + "</p>            </div>\x3c!--form-message--\x3e")
    }, this.msg = function (o) {
        this.open("popup-msg", "Message", '<div class="popup-label"><span class="icon-message-info"></span>Warning</div>            <div class="form-message">            \t<p>' + o + "</p>            </div>\x3c!--form-message--\x3e")
    }
}

function Loading() {
    this.show = function () {
        0 == $("#loading").length && $("body:first").append('<div class="loading" style="display:none;"><div class="loading-inner-new"><img src="/images/gif/loading64.gif"></div></div>'), popup.resetPos(), $("#loading").show("fade")
    }, this.hide = function () {
        $("#loading").hide("fade")
    }
}

function Shadow() {
    this.show = function () {
        0 == $("#shadow").length && $("body:first").append('<div class="overlay" id="shadow"></div>'), $("#shadow").show()
    }, this.hide = function () {
        $("#shadow").hide()
    }
}

function Text() {
    this.subString = function (o, i) {
        return i.length > o ? i.substring(0, o) + "..." : i
    }
}

var loading = new Loading, shadow = new Shadow, popup = new Popup, text = new Text, common = {};
common.search = function () {
    console.log("vao");
    var o = $("#searchKeyword").val();
    if ((o = o.replace(" ", "+")).length > 2) {
        var i = $("#searchCategory").val(), s = "";
        0 != i && (s = UrlUtility.buildUrl({categoryIds: [i]})), window.location = baseUrl + "search/" + o + ".html" + s
    } else popup.msg("Tá»« khÃ³a pháº£i chá»©a Ã­t nháº¥t 3 kÃ½ tá»±"), $("#searchKeyword").focus()
};