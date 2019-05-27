var urlcomponent = {};
urlcomponent.quotes_step1 = function () {
    return "request.html"
}, urlcomponent.quotes = function () {
    return "request/quotes.html"
}, urlcomponent.quotes_detail = function (t) {
    return "order-" + t + "/bill.html"
}, urlcomponent.payment_office = function (t) {
    return "order-" + t + "/bill.html"
}, urlcomponent.cart = function () {
    return "shoppingcarts.html"
}, urlcomponent.carts = function () {
    return "/shoppingcarts.html"
}, urlcomponent.order_steep_one = function () {
    return "order.html"
}, urlcomponent.order_steep_two = function (t) {
    return "order-" + t + "/bill.html"
}, urlcomponent.item_detail = function (t, n) {
    return "item/" + textutils.createAlias(n) + "-" + t + ".html"
}, urlcomponent.wallet_success = function (t) {
    return "order-" + t + "/support.html"
}, urlcomponent.tracking_order = function (t) {
    return "order-" + t + "/tracking.html"
}, urlcomponent.searchAmazon = function (t) {
    return "amazon/search/" + t + ".html"
};