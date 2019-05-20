var loginWallet = function () {
  var password = $('input[name=passwordWallet]').val();
    $('#ErrorPasswordWallet').html('');
  if(!password){
      $('#ErrorPasswordWallet').html('Vui lòng nhập mật khẩu');
      return;
  }
    $.ajax({
        url: 'my-weshop/api/wallet-service/login-wallet.html',
        method: 'POST',
        data: {
            password: password
        },
        success: function (res) {
            if(res.success){
                window.location.reload();
            }else {
                $('#ErrorPasswordWallet').html(res.message);
            }
        }
    });
};