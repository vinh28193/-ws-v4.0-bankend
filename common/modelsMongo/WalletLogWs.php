<?php

namespace common\modelsMongo;

use Yii;
use yii\mongodb\ActiveRecord;


class WalletLogWs extends ActiveRecord
{
    public static function collectionName()
    {
        return ['Weshop_log_40','Wallet_log_40'];
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
        return [
            '_id',
            'created_at',
            'updated_at',
            'date',

            'user_id',
            'user_email',
            'user_name',
            'user_avatar',

            'user_app',
            'user_request_suorce',
            'request_ip',

            'Role','user_id','data_input','data_output', 'action_path','status' ,'LogTypWalletWs','id'
        ];
    }

    public function rules()
    {
        return [
            [[
                'created_at',
                'updated_at',
                'date',

                'user_id',
                'user_email',
                'user_name',
                'user_avatar',

                'user_app',
                'user_request_suorce',
                'request_ip',

            ], 'safe'],
            [[ 'Role','user_id','data_input','data_output', 'action_path','status' ,'LogTypWalletWs','id' ], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',

            //User : Who ai tác
            'user_id' => 'id nhân viên ',
            'user_email' => 'Email nhân viên chat ',
            'user_name' => 'tên nhân viên chat',
            'user_avatar' => 'Hình đại diện của User',
            'Role' => 'Role của nhân viên đang thao tác vào action',

            //Action thao tác là gì ?
            'action_path' => 'Tên của action / nút bấm thao tác là gì ?',
            'LogTypWalletWs'=> 'Order', // LogType : Order | Product | PACKEAGE | PACKEGEITEM : and Id để join
            'id' => 'Id để join với LogTypWalletWs ',

            'status' => 'Trạng thái Thanh Toans',

            // data
            'data_input' => 'dữ liệu ban đầu trước khi ghi log',
            'data_output' => 'dữ liệu sau khi xử lý',

            // time
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'date' => 'Date create data',

            // ENV nào bắn lên
            'user_app' => 'Tên Application Id ',
            'user_request_suorce' => 'suorce gửi app chát Phân biệt : APP/FRONTEND/BACK_END ',
            'request_ip' => 'IP request send message',
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

        $query = WalletLogWs::find()
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
