var voucher = {};
voucher.reload = function () {
    var setting = {
        name: "formatCurrency",
        colorize: false,
        region: 'vi-VN',
        global: true,
        roundToDecimalPlace: 0,
        eventOnDecimalsEntered: false
    };
    for(var ind = 1; ind < 121 ; ind ++){
        $('#code_voucher_'+ind).html(voucher.randomText(12));
        $('#title_voucher_'+ind).html("Voucher "+voucher.randomText(5));
        var num = Math.floor((Math.random() * 50000) + 1)+'00';
        $('#count_temp').html(num);
        $('#count_temp').formatCurrency(false, setting);
        $('#amount_voucher_'+ind).html($('#count_temp').html().replace('₫',"VNĐ"));
        // var data_tem = {};
        // var xx = 0;
        // $.each(data_voucher,function (k,v) {
        //     if(v.price > num){
        //         data_tem[xx] = v;
        //         xx ++;
        //     }
        // });
        //
        // var data_one = data_tem[parseInt((Math.random() * xx))];
        // if(ind === 1){
        //     console.log(data_one.link);
        // }
        // $('#url_voucher_'+ind).attr('href',data_one.link);
    }

};
voucher.randomText = function (max) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    for (var i = 0; i < max; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
};
var i =0;
voucher.loopChange = function () {
    voucher.reload();
    setTimeout(function () {
            voucher.reload();
            voucher.loopChange();
    }, 3600000) //3600000
};
voucher.detail = function (index) {
 var data = {};
 data.code = $('#code_voucher_'+index).html();
 data.name = $('#title_voucher_'+index).html();
 data.amount = $('#amount_voucher_'+index).html();
    ajax({
        service: "/weshop/service/test/getdetailvoucher",
        data: data,
        loading: true,
        method: "POST",
        done: function (e) {
            if (e.success) {
                window.location.assign("/voucher/dp/voucher-mua-hang-ebay-"+e.data.id+".html");
            } else {
                popup.msg(e.message);
            }
        }
    })
};
voucher.buyNow = function (id) {
    var data = {};
    data.id = id;
    ajax({
        service: "/weshop/service/test/checkout",
        data: data,
        loading: true,
        method: "POST",
        done: function (e) {
            if (e.success) {
                window.location.assign("/voucherCheckout.html?type=voucher");
            } else {
                popup.msg(e.message);
            }
        }
    })
};
voucher.checkout = function () {
    var data = {};
    var values = {};
    if ($('#form-add').length > 0) {
        $('#form-add').serializeArray().map(function (x) {
            values[x.name] = $.trim(x.value);
        });
    }
    ajax({
        service: "/weshop/service/test/create-order-voucher",
        data: values,
        loading: true,
        method: "POST",
        done: function (e) {
            if (e.success) {
                $("#paymentMessage").modal('show');
            } else {
                popup.msg(e.message);
            }
        }
    });
};