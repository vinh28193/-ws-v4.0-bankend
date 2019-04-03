<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "request_get_detail_box_me".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $manifest_code
 * @property mixed $manifest_id
 * @property mixed $count_request
 * @property mixed $send_request_latest
 * @property mixed $get_response_latest
 * @property mixed $time_run_latest
 * @property mixed $store_id
 * @property mixed $status
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $created_by
 * @property mixed $updated_by
 */
class RequestGetDetailBoxMe extends \yii\mongodb\ActiveRecord
{
    const STATUS_NEW = "NEW";
    const STATUS_PROCESSING = "PROCESSING";
    const STATUS_GET_DONE = "GET_DONE";
    const STATUS_GET_FAIL = "GET_FAIL";

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'request_get_detail_box_me'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'manifest_code',
            'manifest_id',
            'count_request',
            'send_request_latest',
            'get_response_latest',
            'time_run_latest',
            'store_id',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manifest_code', 'manifest_id', 'count_request', 'send_request_latest', 'get_response_latest', 'time_run_latest', 'store_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'manifest_code' => 'Manifest Code',
            'manifest_id' => 'Manifest ID',
            'count_request' => 'Count Request',
            'send_request_latest' => 'Send Request Latest',
            'get_response_latest' => 'Get Response Latest',
            'time_run_latest' => 'Time Run Latest',
            'store_id' => 'Store Id',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
