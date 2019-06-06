var withdraw_data = {
    method: 'NL',
    email: '',
    bank_id: '',
    bank_account_number: '',
    bank_account_name: '',
    total_amount: '',
    amount: '',
    fee: '',
    password: '',
};
var changeMethod = function (method) {
    withdraw_data.method = method;
};
$('input[name=amount]').keyup(function () {
    getAmount();
});
var getAmount = function(){
    withdraw_data.amount = Number($('input[name=amount]').val());
    withdraw_data.fee = 3000+(withdraw_data.amount*0.01);
    withdraw_data.total_amount = withdraw_data.fee + withdraw_data.amount;
    $('#amount').html(formatMoney(withdraw_data.amount ) + ' đ');
    $('#fee').html(formatMoney(withdraw_data.fee) + ' đ');
    $('#total_amout').html(formatMoney(withdraw_data.total_amount) + ' đ');
};
var formatMoney = function (n) {
    var c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
$('button[name=submit_withdraw]').click(function () {
    getAmount();
    if(withdraw_data.method === 'NL'){
        withdraw_data.email = $('input[name=email]').val();
        if(!withdraw_data.email){
            ws.notifyError("Vui lòng nhập email tài khoản ngân lượng.");
            return;
        }
    }else if(withdraw_data.method === 'BANK'){
        withdraw_data.bank_id = $('select[name=bank_id]').val();
        withdraw_data.bank_account_name = $('input[name=bank_account_name]').val();
        withdraw_data.bank_account_number = $('input[name=bank_account_number]').val();
        console.log(withdraw_data);
        if(!withdraw_data.bank_id || !withdraw_data.bank_account_number || !withdraw_data.bank_account_name){
            ws.notifyError("Vui lòng nhập đầy đủ thông tin tài khoản ngân hàng.");
            return;
        }
    }else {
        ws.notifyError("Vui lòng chọn phương thức rút tiền.");
        return;
    }
    if(!withdraw_data.total_amount || withdraw_data.total_amount < 100000 ){
        ws.notifyError("Vui lòng nhập số tiền trên 100.000đ.");
        return;
    }
    withdraw_data.password = $('input[name=password]').val();
    if(!withdraw_data.password){
        ws.notifyError("Vui lòng nhập mật khẩu.");
        return;
    }
    ws.loading(true);
    $.ajax({
        url: '/my-wallet/withdraw.html',
        method: 'POST',
        data: withdraw_data,
        success: function (res) {
            if(res.success){
                location.assign('/my-weshop/wallet/withdraw/'+res.data.code+'.html')
            }else {
                ws.loading(false);
                ws.notifyError(res.message);
            }
        }
    });
});
var sendOtp = function () {
  var type = $('input[name=typeOtp]:checked').val();
  var transaction_code = $('input[name=transaction_code]').val();
    ws.loading(true);
    $.ajax({
        url: '/my-wallet/sent-otp.html',
        method: 'POST',
        data: {
            type: type,
            transaction_code: transaction_code,
        },
        success: function (res) {
            if(res.success){
                location.assign('/my-weshop/wallet/withdraw/'+res.data.code+'.html')
            }else {
                ws.loading(false);
                ws.notifyError(res.message);
            }
        }
    });
};
var cancelWithdraw = function () {
  var transaction_code = $('#wallet_transaction_code').html();
    ws.loading(true);
    $.ajax({
        url: '/my-wallet/cancel-withdraw.html',
        method: 'POST',
        data: {
            transaction_code: transaction_code,
        },
        success: function (res) {
            if(res.success){
                location.reload();
            }else {
                ws.loading(false);
                ws.notifyError(res.message);
            }
        }
    });
};
var showSentOtp = function () {
    $('#resentotp').css('display','block');
    $('#verifyOtp').css('display','none');
};