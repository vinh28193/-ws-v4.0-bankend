<?php


namespace common\models\logs;


class PaymentGatewayLogs extends \common\modelsMongo\PaymentGatewayLogs
{
    const TYPE_CALLBACK = 'CALL_BACK';
    const TYPE_CHECK_PAYMENT = 'CHECK_PAYMENT';
    const TYPE_CHECK_PAYMENT_FAIL = 'CHECK_PAYMENT_FAIL';
    const TYPE_CALLBACK_FAIL = 'CALL_BACK_FAIL';
}