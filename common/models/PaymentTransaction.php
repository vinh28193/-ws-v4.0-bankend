<?php


namespace common\models;

use common\models\db\PaymentTransaction as DbPaymentTransaction;

class PaymentTransaction extends DbPaymentTransaction
{

    const TRANSACTION_TYPE_PAYMENT = 'PAYMENT';
    const TRANSACTION_ADDFEE = 'ADDFEE';
    const TRANSACTION_TYPE_TOP_UP = 'TOP_UP';
    const TRANSACTION_TYPE_REFUND = 'REFUND';

    const TRANSACTION_STATUS_CREATED = 'CREATED';
    const TRANSACTION_STATUS_QUEUED = 'QUEUED';
    const TRANSACTION_STATUS_FAILED = 'FAILED';
    const TRANSACTION_STATUS_SUCCESS = 'SUCCESS';
    const TRANSACTION_STATUS_REPLACED = 'REPLACED';

    const PAYMENT_TYPE_BUY_NOW = 'buynow';
    const PAYMENT_TYPE_SHOPPING = 'shopping';
    const PAYMENT_TYPE_TOP_UP = 'top_up';
    const PAYMENT_TYPE_ADDFEE = 'addfee';
    const PAYMENT_TYPE_REFUND = 'refund';
}