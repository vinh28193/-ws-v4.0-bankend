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
    $('#amount').html(withdraw_data.amount + ' đ');
    $('#fee').html(withdraw_data.fee + ' đ');
    $('#total_amout').html(withdraw_data.total_amount + ' đ');
};
$('button[name=submit_withdraw]').click(function () {
    getAmount();
    if(withdraw_data.method === 'NL'){
        withdraw_data.email = $('input[name=email]').val();
        if(!withdraw_data.email){
            ws.sweetalert("Vui lòng nhập email tài khoản ngân lượng.");
            return;
        }
    }else if(withdraw_data.method === 'BANK'){
        withdraw_data.bank_id = $('input[name=bank_id]').val();
        withdraw_data.bank_account_name = $('input[name=bank_account_name]').val();
        withdraw_data.bank_account_number = $('input[name=bank_account_number]').val();
        if(!withdraw_data.bank_id || !withdraw_data.bank_account_number || !withdraw_data.bank_account_name){
            ws.sweetalert("Vui lòng nhập đầy đủ thông tin tài khoản ngân hàng.");
            return;
        }
    }else {
        ws.sweetalert("Vui lòng chọn phương thức rút tiền.");
        return;
    }
    if(!withdraw_data.total_amount || withdraw_data.total_amount < 100000 ){
        ws.sweetalert("Vui lòng nhập số tiền trên 100.000đ.");
        return;
    }
    withdraw_data.password = $('input[name=password]').val();
    if(!withdraw_data.password){
        ws.sweetalert("Vui lòng nhập mật khẩu.");
        return;
    }
    $.ajax({
        url: '/my-wallet/withdraw.html',
        method: 'POST',
        data: withdraw_data,
        success: function (res) {
            if(res.success){
                location.assign('/my-weshop/wallet/withdraw/'+res.data.code+'.html')
            }else {
                ws.sweetalert(res.message);
            }
        }
    });
});
var sendOtp = function () {
  var type = $('input[name=typeOtp]:checked').val();
  var transaction_code = $('input[name=transaction_code]').val();
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
                ws.sweetalert(res.message);
            }
        }
    });
};