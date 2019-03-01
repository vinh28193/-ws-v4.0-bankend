<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 16:37
 */

/* @var $this \yii\web\View */
$config = [
    'page' => 'checkout',
    'store' => 1,
];

\weshop\payment\PaymentAssets::register($this);

$this->registerJs('wsPayment.init('.\yii\helpers\Json::htmlEncode($config).')');