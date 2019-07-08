<?php

use common\models\SystemDistrict;
use common\models\SystemZipcode;
use frontend\modules\payment\models\ShippingForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \yii\web\View $this
 * @var ShippingForm $shippingForm
 */
/** @var  $storeManager common\components\StoreManager */
$storeManager = Yii::$app->storeManager;
$isID = $storeManager->store->country_code === 'ID';

?>
<footer class="footer">
    <div class="top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 item-box">
                    <div class="title"><?= Yii::t('frontend', 'Accept payment:') ?></div>
                    <ul>
                        <li><a href="#"><img src="<?= Url::to('/img/pay_master.png', true); ?>" alt="" title=""/></a>
                        </li>
                        <li><a href="#"><img src="<?= Url::to('/img/pay_visa.png', true); ?>" alt="" title=""/></a></li>
                        <li><a href="#"><img src="<?= Url::to('/img/pay_jcb.png', true); ?>" alt="" title=""/></a></li>
                    </ul>
                </div>
                <div class="col-md-6 item-box">
                    <div class="title"><?= Yii::t('frontend', 'Co-operate:') ?></div>
                    <ul>
                        <li><a href="#"><img src="<?= Url::to('/img/operate_ebay.png', true); ?>" alt="" title=""/></a>
                        </li>
                        <li><a href="#"><img src="<?= Url::to('/img/operate_amz.png', true); ?>" alt="" title=""/></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="info">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="title"><?= Yii::t('frontend', 'About Weshop:') ?></div>
                            <ul>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#aboutus') ?>"><?= Yii::t('frontend', 'About us') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#privacypolicy') ?>"><?= Yii::t('frontend', 'Privacy Policy') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#term&condition') ?>"><?= Yii::t('frontend', 'Terms & Condition') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#contact') ?>"><?= Yii::t('frontend', 'Contact us') ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="title"><?= Yii::t('frontend', 'Help') ?></div>
                            <ul>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#how2pay') ?>"><?= Yii::t('frontend', 'How to pay') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#termsrefund') ?>"><?= Yii::t('frontend', 'Terms Refund') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#prohibited') ?>"><?= Yii::t('frontend', 'Prohibited Items') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#importtaxduty') ?>"><?= Yii::t('frontend', 'Import Tax Duty') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#privacyPolicy') ?>"><?= Yii::t('frontend', 'Privacy Policy') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#term&condition') ?>"><?= Yii::t('frontend', 'Terms & Condition') ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="title"><?= Yii::t('frontend', 'Value Added Services') ?></div>
                            <ul>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#inspectionservice') ?>"><?= Yii::t('frontend', 'Inspection Service') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#consolidateshipment') ?>"><?= Yii::t('frontend', 'Consolidate shipments') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#repackingservice') ?>"><?= Yii::t('frontend', 'Repacking service') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#shippinginsurance') ?>"><?= Yii::t('frontend', 'Shipping Insurance') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#feedaystorage') ?>"><?= Yii::t('frontend', 'Free 60days storage') ?></a>
                                </li>
                                <li><a target="_blank" rel="nofollow"
                                       href="<?= Yii::t('frontend', '#allvas') ?>"><?= Yii::t('frontend', 'View all service VAS') ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="title-register"><?= Yii::t('frontend', 'Sign up to receive promotion news') ?></div>
                    <div class="form-group contact">
                        <div class="input-group">
                            <input class="form-control" type="text"
                                   id="email-getdeal"
                                   placeholder="<?= Yii::t('frontend', 'Enter email to get hot deals') ?>">
                            <span class="input-group-btn">
                                        <button type="button" onclick="formSubmit()" class="btn btn-default"><i
                                                    class="contact-ico"></i></button>
                                    </span>
                        </div>
                    </div>
                    <div class="sticker-bct"><a href="#" target="_blank"><img
                                    src="<?= Url::to('/img/chung_nhan_bct.png', true); ?>" alt=""
                                    title=""/></a></div>
                    <div class="connect" style="color: #fff">
                        <span><?= Yii::t('frontend', 'Connect with Weshop:') ?></span>
                        <a rel="nofollow" href="<?= Yii::t('frontend', '#facebookWeshop') ?>" target="_blank">
                            <i class="fa fa-facebook-square" style="font-size: 30px; padding: 0px;"></i>
                        </a> |
                        <a rel="nofollow" href="<?= Yii::t('frontend', '#youtubeWeshop') ?>" target="_blank">
                            <i class="fa fa-instagram" style="font-size: 30px; padding: 0px;"></i>
                        </a>
                    </div>
                    <script>
                        var formSubmit = function () {
                            var email = document.getElementById("email-getdeal").value;
                            Intercom('update', {email: email, "subscriber": "1"});
                            Intercom('showNewMessage', "<?= Yii::t('frontend', 'Hello Weshop!') ?>");
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <div class="bot">
        <div class="container">
            <div class="title"><?= Yii::t('frontend', 'WESHOP VIET NAM - WORLD WIDE SHOPPING MADE EASY') ?></div>
            <ul>
                <li><?= Yii::t('frontend', '<b>Ha Noi:</b> 3rd floor, VTC Online building No. 18 Tam Trinh Street, Minh Khai Ward, Hai Ba Trung District, Hanoi City, Vietnam') ?></li>
                <li><?= Yii::t('frontend', '<b> Ho Chi Minh: </b> 6th floor, Sumikura building, 18H Cong Hoa, Ward 4, Tan Binh District') ?></li>
                <li><?= Yii::t('frontend', '<b> Business number: </b> 0106693837 by the City Department of Planning and Investment. Hanoi first issued on November 18, 2014') ?></li>
            </ul>
        </div>
    </div>
</footer>
<div class="loading_new" id="loading" style="display:none;">
    <div class="loading-inner-new">
        <img src="/img/gif/loading.gif">
    </div>
</div>
<!-- Modal login waller -->
<div class="modal" id="loginWallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Đăng nhập ví</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <i class="icon password"></i>
                    <input type="password" name="passwordWallet" class="form-control" placeholder="Mật khẩu">
                    <label style="color: red" id="ErrorPasswordWallet"></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="ws.loginWallet()">Đăng nhập</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="NotifyConfirm" tabindex="-1" role="dialog" aria-labelledby="NotifyConfirmTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="modal-content" role="document">
        <div class="modal-content">
            <div class="modal-header" id="NotifyConfirmHeader">
                <div class="modal-title"
                     id="NotifyConfirmTitle"><?= Yii::t('frontend', 'Notify upgrade search') ?></div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div id="NotifyConfirmMessage">
                        <?= Yii::t('frontend', 'Notify') ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" style="display: none" class="btn btn-primary"
                        id="NotifyConfirmBtnSubmit"><?= Yii::t('frontend', 'Confirm') ?></button>
                <button type="button" class="btn btn-secondary" id="NotifyConfirmBtnClose"
                        data-dismiss="modal"><?= Yii::t('frontend', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal-address" tabindex="-1" role="dialog" aria-labelledby="NotifyConfirmTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title title"><?= Yii::t('frontend', 'Select Your Address') ?></div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="font-weight-bold">
                        <?= Yii::t('frontend', 'Please enter your address so we can display shipping fee correctly.') ?>
                    </div>
                </div>
                <?php if ($isID): ?>
                    <div class="form-group">
                        <?php
                        echo Select2::widget([
                            'data' => $shippingForm->getProvinces(),
                            'id' => 'zipcode_default',
                            'name' => 'zipcode_default',
                            'value' => $shippingForm->receiver_province_id,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 4,
                                'placeholder' => Yii::t('frontend', 'Enter your post code'),
                                'ajax' => [
                                    'url' => Url::toRoute(['/data/get-zip-code']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('wsAddress.zipAjaxParam'),
                                    'processResults' => new JsExpression('wsAddress.zipAjaxProcessResults'),
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('wsAddress.formatZipAjaxResults'),
                                'templateSelection' => new JsExpression('wsAddress.formatZipAjaxSelection'),

                            ],
                        ]);
                        ?>
                    </div>
                <?php else: ?>
                    <div class="form-group"
                         style="<?= (Yii::$app->storeManager->getId() == 7) ? 'display:none;' : '' ?>">
                        <?=
                        Select2::widget([
                            'data' => $shippingForm->getProvinces(),
                            'id' => 'city_default',
                            'name' => 'city_default',
                            'value' => $shippingForm->receiver_province_id,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => Yii::t('frontend', 'Choose the province'),
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="form-group">
                        <?=
                        DepDrop::widget([
                            'type' => DepDrop::TYPE_SELECT2,
                            'name' => 'district_default',
                            'attribute' => 'district_default',
                            'value' => $shippingForm->receiver_district_id,
                            'select2Options' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'pluginOptions' => [
                                'depends' => ['city_default'],
                                'placeholder' => Yii::t('frontend', 'Choose the district'),
                                'url' => '/checkout/shipping/sub-district',
                                'loadingText' => Yii::t('frontend', 'Loading district ...'),
                                'initialize' => true,
                            ],
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button"
                        id="setDefaultAddressSmBtn"
                        class="btn btn-success"><?= Yii::t('frontend', 'Confirm') ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    window.intercomSettings = {
        <?php
        if(!Yii::$app->user->isGuest){
        /** @var \common\models\User $user_login */
        $user_login = Yii::$app->user->getIdentity();
        ?>
        name: "<?= implode(' ', [$user_login->last_name, $user_login->first_name]) ?>", // Full name
        email: "<?= $user_login->email ?>", // Email address
        created_at: "<?= $user_login->created_at ?>", // Signup date as a Unix timestamp
        <?php } ?>
        app_id: "wc8x0ims"
    };
</script>
<script>(function () {
        var w = window;
        var ic = w.Intercom;
        if (typeof ic === "function") {
            ic('reattach_activator');
            ic('update', w.intercomSettings);
        } else {
            var d = document;
            var i = function () {
                i.c(arguments);
            };
            i.q = [];
            i.c = function (args) {
                i.q.push(args);
            };
            w.Intercom = i;
            var l = function () {
                var s = d.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://widget.intercom.io/widget/wc8x0ims';
                var x = d.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            };
            if (w.attachEvent) {
                w.attachEvent('onload', l);
            } else {
                w.addEventListener('load', l, false);
            }
        }
    })();</script>


