<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "payment_gateway_logs".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $transaction_code_ws
 * @property mixed $transaction_code_request
 * @property mixed $request_content
 * @property mixed $response_content
 * @property mixed $type
 * @property mixed $url
 * @property mixed $response_status
 * @property mixed $create_time
 * @property mixed $update_time
 * @property mixed $response_time
 * @property mixed $amount
 * @property mixed $payment_method
 * @property mixed $payment_bank
 * @property mixed $installment_month
 * @property mixed $store_id
 */
class PaymentGatewayLogs extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'payment_gateway_logs'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'transaction_code_ws',
            'transaction_code_request',
            'request_content',
            'response_content',
            'type',
            'url',
            'response_status',
            'create_time',
            'update_time',
            'response_time',
            'amount',
            'payment_method',
            'payment_bank',
            'installment_month',
            'store_id',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_code_ws', 'transaction_code_request', 'request_content', 'response_content', 'type', 'url', 'response_status', 'create_time', 'update_time', 'response_time', 'amount', 'payment_method', 'payment_bank', 'installment_month', 'store_id'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'transaction_code_ws' => 'Transaction Code Ws',
            'transaction_code_request' => 'Transaction Code Request',
            'request_content' => 'Request Content',
            'response_content' => 'Response Content',
            'type' => 'Type',
            'url' => 'Url',
            'response_status' => 'Response Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'response_time' => 'Response Time',
            'amount' => 'Amount',
            'payment_method' => 'Payment Method',
            'payment_bank' => 'Payment Bank',
            'installment_month' => 'Installment Month',
            'store_id' => 'Store ID',
        ];
    }
}
