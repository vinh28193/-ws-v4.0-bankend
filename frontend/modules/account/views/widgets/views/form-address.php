<?php
/**
 * @var $address \common\models\Address
 * @var $user \common\models\User
 */
$provincies = \common\models\SystemStateProvince::select2DataForCountry($address && $address->country_id ? $address->country_id : $user->store->country_id);
$district = \common\models\SystemDistrict::selectData($address && $address->province_id ? $address->province_id : $provincies[0]['id']);
?>
<div class="payment-form">
    <div class="form-group">
        <i class="icon user"></i>
        <input type="text" id="shipping-full_name" class="form-control" name="shipping-full_name" placeholder="<?= Yii::t('frontend', 'Full Name') ?>">
        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <i class="icon phone"></i>
            <input type="number" id="shipping-phone" class="form-control" name="shipping-phone" placeholder="<?= Yii::t('frontend', 'Phone') ?>">
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <i class="icon email"></i>
            <input type="email" id="shipping-email" class="form-control" name="shipping-email" placeholder="<?= Yii::t('frontend', 'Email') ?>">
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <i class="icon globe"></i>
            <select id="shipping_province_id" class="form-control" name="shippingform-receiver_province_id" onchange="ws.province_change('shipping_province_id','shipping_district_id')">
                <?php foreach ($provincies as $provincy){
                    $selected = $address && $address->province_id == $provincy['id'] ? 'selected' : '';
                    echo "<option ".$selected." value='".$provincy['id']."'>".$provincy['name']."</option>";
                }?>
            </select>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <i class="icon city"></i>
            <select id="shipping_district_id" class="form-control" name="shippingform-receiver_district_id" onchange="ws.district_change('shipping_district_id','shipping_zipcode')">
                <?php foreach ($district as $id => $name){
                    $selected = $address && $address->district_id == $id ? 'selected' : '';
                    echo "<option ".$selected." value='".$id."'>".$name."</option>";
                }?>
            </select>
            <div class="help-block"></div>
        </div>
    </div>
    <?php if($user->store_id == \common\components\StoreManager::STORE_ID) {?>
        <div class="form-group">
            <div class="form-group">
                <i class="icon mapmaker"></i>
                <input
                        type="number"
                        id="shipping_zipcode"
                        class="form-control"
                        name="shipping_zipcode"
                        list="list_zipcode_shipping"
                        placeholder="<?= Yii::t('frontend', 'Zip code') ?>"
                        onkeyup="ws.zipcode_keyup('shipping_zipcode','list_zipcode_shipping')"
                        onchange="ws.zipcode_Change('shipping_zipcode','shipping_province_id','shipping_district_id')"
                >
                <datalist id="list_zipcode_shipping"></datalist>
                <div class="help-block"></div>
            </div>
        </div>
    <?php }?>
    <div class="form-group">
        <div class="form-group">
            <i class="icon mapmaker"></i>
            <input type="text" id="shipping_address" class="form-control" name="shipping_address" placeholder="<?= Yii::t('frontend', 'Address') ?>">
            <div class="help-block"></div>
        </div>
    </div>
</div>