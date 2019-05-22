<?php
/**
 * @var $wallet array
 * @var $transaction_info array
 * @var $transaction_code string
 * @var $modal \frontend\modules\payment\models\OtpVerifyForm
 *
 */

use common\helpers\WeshopHelper;
use frontend\modules\account\views\widgets\HeaderContentWidget;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$listMethod = ArrayHelper::getValue(Yii::$app->params, 'list_method_withdraw');

$this->title = "Xác thực yêu cầu rút tiền";
$this->params = ['wallet','withdraw'];
echo HeaderContentWidget::widget(['title' => $this->title, 'stepUrl' => ['Rút tiền' => '/my-weshop/wallet/withdraw.html']]);
$checkOTP = isset($transaction_info['verify_expired_at']) && $transaction_info['verify_expired_at'] && $transaction_info['verify_expired_at'] > time();
?>

<div class="be-box">
    <div class="be-top">
        <div class="title">Thông tin số dư tài khoản</div>
    </div>
    <div class="be-body be-withdraw">
        <div class="be-table">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">Số dư tài khoản</th>
                    <th scope="col">Số dư đóng băng</th>
                    <th scope="col">Số dư khả dụng</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'current_balance')) ?></td>
                    <td><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'freeze_balance')) ?></td>
                    <td><?= WeshopHelper::showMoney(ArrayHelper::getValue($wallet, 'usable_balance')) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <ul class="withdraw-step">
            <li class="done">
                <div class="step">1</div>
                <p>Tạo Yêu cầu rút</p>
            </li>
            <li class="active">
                <div class="step">2</div>
                <p>Xác nhận yêu cầu rút</p>
            </li>
            <li>
                <div class="step">3</div>
                <p>Yêu cầu rút thành công</p>
            </li>
        </ul>
        <div class="title-2">Xác thực giao dịch rút tiền #<b><?= $transaction_code ?></b></div>
        <div class="withdraw-done">
            <div class="done-notice">
                <div class="w-25 m-auto">
                    <div id='verifyOtp'>
                    <?php
                    if($checkOTP){
                        echo Html::tag('div', 'Xác thực OTP', ['class' => 'modal-title']);
                        //                if ($msg !== null) {
                        //                    echo Html::tag('p', $msg,['class' => 'message-otp']);
                        //                }
                        $form = ActiveForm::begin([
                            'options' => [
                                'id' => 'otpVerifyForm'
                            ],
                            'action' => \yii\helpers\Url::toRoute('/account/api/wallet-service/verify-otp', true),
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => false,
                            'validateOnChange' => false,
                            'validateOnBlur' => false,
                            'validateOnType' => false,
                            'validationUrl' => '/payment/wallet/otp-verify-form-validate',
                        ]);
                        echo $form->field($modal, 'otpReceive')->hiddenInput()->label(false);
                        echo $form->field($modal, 'transactionCode')->hiddenInput()->label(false);
                        echo $form->field($modal, 'orderCode')->hiddenInput()->label(false);
                        echo $form->field($modal, 'returnUrl')->hiddenInput()->label(false);
                        echo $form->field($modal, 'cancelUrl')->hiddenInput()->label(false);
                        echo $form->field($modal, 'otpCode')->textInput();
                        echo $form->field($modal, 'captcha')->widget(Captcha::className(), [
                            'captchaAction' => '/otp/captcha',
                            'options' => ['class' => 'form-control'],
                            'template' => '<div class="input-group">{input}
                            <div class="input-group-append">
                                <span class="input-group-text">{image}</span>
                            </div>
                       </div>'
                        ]);
                        echo Html::tag('p', 'Bạn chưa nhận được mã OTP? <a href="javascript:void(0);" onclick="showSentOtp()">Gửi lại</a>');
                        echo Html::submitButton('Xác thực', ['class' => 'btn btn-success btn-block']);
                        ActiveForm::end();
                    }
                    ?>
                    </div>
                    <div id="resentotp" style="display: <?= $checkOTP ? 'none' : 'block' ?>">
                        <input type="hidden" value="<?= $transaction_code ?>" name="transaction_code">
                        <label>Cách nhận OTP</label>
                        <div class="form-check">
                            <input class="form-check-input" id="email_type" type="radio" <?= $transaction_info['verify_receive_type'] == 1 ? 'checked' : '' ?> value="1" checked name="typeOtp">
                            <label class="form-check-label" for="email_type"> Email (<?= $wallet['email'] ?>)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="sms_type" <?= $transaction_info['verify_receive_type'] == 0 ? 'checked' : '' ?> value="0" name="typeOtp">
                            <label class="form-check-label" for="sms_type"> SMS (<?= $wallet['customer_phone'] ?>)</label>
                        </div>
                        <div class="form-group mt-3">
                            <button class="btn btn-info" onclick="sendOtp()">Gửi OTP</button>
                        </div>
                    </div>
                </div>
            </div>
            <p>Nếu cần trợ giúp vui lòng liên hệ : Hotline: <b class="text-orange">1900.67.55</b>   |  Email: <a href="#" class="text-blue">support@weshop.com.vn</a></p>
        </div>
        <div class="be-notice">
            <div class="notice-title">Lưu ý:</div>
            - Tổng số tiền rút phải lớn hơn hoặc bằng 100.000đ và nhỏ hơn hoặc bằng Số dư khả dụng.<br/>
            - Phí rút tiền là: 3.000đ + 1%. Phí không vượt quá 10.000đ.<br/>
            - Quý khách lưu ý điền đúng thông tin tài khoản. Nếu quý khách điền sai thông tin mà Weshop đã thực hiện lệnh chuyển tiền thì quý khách sẽ phải chịu phí chuyển tiền của ngân hàng.
        </div>
    </div>
</div>
