<?php

namespace common\modelsMongo;

use Yii;
use yii\mongodb\ActiveRecord;


class PushNotifications extends ActiveRecord
{
    public static function collectionName()
    {
        return ['weshop_global_40','push_notifications'];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $reflection = new \ReflectionClass($this);
        if($reflection->getShortName() === 'ActiveRecord'){
            return $behaviors;
        }

        $timestamp = [];
        if ($this->hasAttribute('created_at')) {
            $timestamp[self::EVENT_BEFORE_INSERT][] = 'created_at';
        }
        if ($this->hasAttribute('updated_at')) {
            $timestamp[self::EVENT_BEFORE_UPDATE][] = 'updated_at';
        }

        $behaviors = !empty($timestamp) ? array_merge($behaviors, [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => $timestamp,
            ],
        ]) : $behaviors;

        return $behaviors;
    }

    public function attributes()
    {
        /**
        {
            "_id": ObjectId,
            "token_fcm": String,
            "subscribed_on": Date,
            "user_id": Integer,
            "fingerprint": String,
            "details": [
                "browser" : String,
                "os" : String,
                "osVersion" : String,
                "device" : String,
                "deviceType" : String,
                "deviceVendor" : String,
                "cpu" : String
            ]
        }
         */

        return [
            '_id',
            'token_fcm',
            'subscribed_on',
            'order_code',

            'fingerprint',

            'user_id',
            'user_email',
            'user_name',
            'details',
            'order_list',
            'nv'

        ];
    }

    public function rules()
    {
        return [
            [[
                'created_at',
                'updated_at',
                'token_fcm',
                'subscribed_on',
                'order_code',

                'fingerprint',

                'user_id',
                'user_email',
                'user_name',
                'details',
                'order_list',
                'nv'

            ], 'safe'],
            [[ 'token_fcm','fingerprint','user_id','user_email', 'user_name','details','order_list','nv'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',

            //User : Who Nhân subscribed_on Notification
            'user_id' => 'id nhân viên ',
            'user_email' => 'Email nhân viên chat ',
            'user_name' => 'tên nhân viên chat',

            // time
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',

            // Infor Field Notification
            'token_fcm' => 'Token FCM nhân Notification',
            'order_code' => 'Mãn đơn hàng nhận Notification',
            'subscribed_on' =>' Ngày User click vào button nhận Thống báo  ',
            'fingerprint' => 'UUID devices : đinh danh cua mỗi thiết bị nhận thông báo , Mỗi một người dùng có N thiết bị nhận thông báo',
            'details' => ' Thông tin Về Thiết bị ',
            'order_list' => 'Danh sách đơn hàng',
            'nv'=> 'môi trường bắn Notification  WEB/ APP:'
            /*
                 "details": [
                    "browser" : String,
                    "os" : String,
                    "osVersion" : String,
                    "device" : String,
                    "deviceType" : String,
                    "deviceVendor" : String,
                    "cpu" : String
                ]
             */

            ];
    }

    // Optional sort/filter params: page,limit,order,search[name],search[email],search[id]... etc

    static public function search($params)
    {
        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $order = Yii::$app->getRequest()->getQueryParam('order');
        $search = Yii::$app->getRequest()->getQueryParam('search');
        if (isset($search)) {
            $params = $search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = PushNotifications::find()
            ->limit($limit)
            ->offset($offset);

        if (isset($order)) {
            $query->orderBy($order);
        }


        if (isset($order)) {
            $query->orderBy($order);
        }

        $additional_info = [
            'currentPage' => $page,
            'pageCount' => $page,
            'perPage' => $limit,
            'totalCount' => (int)$query->count()
        ];

        $data = new \stdClass();
        $data->_items = $query->all();
        $data->_links = '';
        $data->_meta = $additional_info;
        return $data;

    }

}
