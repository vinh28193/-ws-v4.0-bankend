ws.sweetalert = function (msg, type, $option) {
    alert(type + ': ' + msg);
};

ws.sweetalert.prototype.fire = function ($option) {
    if (typeof Swal !== undefined && $.Deferred(Swal.fire)) {
        Swal.fire($option);
    }
    return false;
};

ws.sweetalert.prototype.resolveType = function (type, $option) {
    return $option;
};