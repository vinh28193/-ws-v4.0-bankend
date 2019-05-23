<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ChatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chat Mongo Ws';
$this->params['breadcrumbs'][] = $this->title;
?>
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
                        <div class="card-body msg_card_body" #scrollMe [scrollTop]="scrollMe.scrollHeight">
                            <div>
                                <div>
                                   <?php foreach ($model as $chat) {?>
                                       <div class="d-flex <?php if (Yii::$app->user->identity->email == $chat->user_email) {echo 'justify-content-end';} else {echo 'justify-content-start';} ?> mb-4">
                                           <?php if (Yii::$app->user->identity->email != $chat->user_email) { ?>
                                               <div class="img_cont_msg float-right">
                                                   <img src="../img/weshop_small_logo.png"
                                                        class="rounded-circle user_img_msg"  width="54px" height="15px">
                                               </div>
                                           <?php } ?>
                                           <div>
                                               <span class=" msg_cotainer_send <?php if (Yii::$app->user->identity->email == $chat->user_email) {echo 'float-right';} else {echo 'float-left ml-2';} ?>"><?= $chat->message ?></span><br>
                                               <div class="<?php if (Yii::$app->user->identity->email == $chat->user_email) {echo 'float-right';} else {echo 'float-left';} ?> mr-2 text-gray">
                                                   <p class="text-darkgray"><span class="mr-3"><?= $chat->user_name ?></span><span><?= $chat->date ?></span></p>
                                               </div>
                                           </div>
                                       </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-body">
                                <div class="">
                                    <?php $form = ActiveForm::begin([

                                    ]); ?>
                                    <div class="form-group">
                                        <textarea name="message" rows="5" class="form-control type_msg"
                                                  placeholder="Nhập gửi nội dung trao đổi.
Nhấn Shift + Enter để xuống dòng.
Enter để gửi"></textarea>

                                            <button style="height: 97px; border: 1px solid #ced4da; border-radius: 0" class="btn btn-default pl-5 pr-5" type="submit"><span style="font-weight: 500">Gửi</span></button>

                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-right mt-2">
        <button class="btn btn-danger btn-sm" data-dismiss="modal" >Hủy Bỏ</button>
    </div>
</div>
