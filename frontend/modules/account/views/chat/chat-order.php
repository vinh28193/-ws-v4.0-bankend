<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 5/23/2019
 * Time: 2:14 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $formChat frontend\modules\account\models\ChatFrom */

$js = <<<JS
$(document).on("beforeSubmit", "form#orderChatForm", function (e) {
        e.preventDefault();
        var form = $(this);
        if (form.find('.has-error').length) {
                return false;
        }
        ws.ajax('/account/chat/create-chat', {
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
              $.pjax.reload({container: "#employee", url: "/chat/$code/order.html"});
            }
          
        });
        
        return false; // Cancel form submitting.
});
JS;
$this->registerJs($js);

?>
<?php Pjax::begin(['id' => 'employee']);?>
<div class="row">
    <div class="col-lg-12 p-0">
        <div class="modal-body p-0">
            <div class="ng-star-inserted">
                <div class="col-md-12 m-0 col-xl-12 chat">
                    <div class="card m-0 p-0">
                        <div class="card-header msg_head bg-info">
                            <div class="d-flex bd-highlight">
                                <h3 class="text-white">Trao đổi với nhân viên (12345)</h3>
                            </div>
                        </div>
                        <div class="card-body msg_card_body" style="max-height: 300px" #scrollMe [scrollTop]="scrollMe.scrollHeight">
                            <div>
                                <div>
                                    <?php foreach ($formChat->getMessages() as $chat) { ?>
                                        <div class="d-flex <?php if (Yii::$app->user->identity->email == $chat->user_email) {
                                            echo 'justify-content-end';
                                        } else {
                                            echo 'justify-content-start';
                                        } ?>">
                                            <?php if (Yii::$app->user->identity->email != $chat->user_email) { ?>
                                                <div class="img_cont_msg pt-2">
                                                    <img src="../img/weshop_small_logo.png"
                                                         class="rounded-circle user_img_msg" width="54px" height="15px">
                                                </div>
                                            <?php } ?>
                                            <div>
                                                <span class=" msg_cotainer_send <?php if (Yii::$app->user->identity->email == $chat->user_email) {
                                                    echo 'owner-chat';
                                                } else {
                                                    echo 'float-left ml-2 other-chat';
                                                } ?>"><?= $chat->message ?></span><br>
                                                <div class="<?php if (Yii::$app->user->identity->email == $chat->user_email) {
                                                    echo 'float-right';
                                                } else {
                                                    echo 'float-left';
                                                } ?> mr-2 text-gray">
                                                    <p class="text-darkgray"><span
                                                                class="mr-3 text-gray"><?= $chat->user_name ?></span><span
                                                                class="text-gray"><?= $chat->date ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-body">
                                <?php
                                // phai ajax validate, xem /frontend/modules/payment/views/otp-verify
                                // phai ajax submit, xem /frontend/modules/payment/views/otp-verify document beforeSubmit
                                $form = ActiveForm::begin([
                                    'id' => 'orderChatForm'
                                ]);
                                echo $form->field($formChat, 'orderCode')->hiddenInput()->label(false);
                                echo $form->field($formChat, 'chatText', ['options' => ['class' => 'input-group'],'template' => "{input}{error}".Html::submitButton('Gửi', ['class' => 'btn btn-default pl-5 pr-5 style-sb'])])->textarea(['rows' => '4','class' => 'form-control type_msg', 'placeholder' => 'Nhập gửi nội dung trao đổi.
Nhấn Shift + Enter để xuống dòng.
Enter để gửi']);

                                ActiveForm::end();
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-right mt-2">
        <button class="btn btn-danger btn-sm" data-dismiss="modal">Hủy Bỏ</button>
    </div>
</div>
<?php Pjax::end(); ?>