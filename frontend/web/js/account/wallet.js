var withdraw_data = {
    method: 'nl',
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
$('button[name=submit_withdraw]').click(function () {
    if(withdraw_data.method === 'nl'){
        withdraw_data.email = $('input[name=email]').val();
        if(!withdraw_data.email){
            ws.sweetalert("Vui lòng nhập email tài khoản ngân lượng.");
            return;
        }
    }else if(withdraw_data.method === 'nl'){
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
    withdraw_data.amount = $('input[name=amount]').val();
    if(!withdraw_data.amount || withdraw_data.amount < 100000 ){
        ws.sweetalert("Vui lòng nhập số tiền trên 100.000đ.");
        return;
    }
    $.ajax({
        url: '/my-wallet/withdraw.html',
        method: 'POST',
        data: withdraw_data,
        success: function (res) {
            if(res.success){

            }else {
                ws.sweetalert(res.message);
            }
        }
    });
});