<?php

namespace common\modelsMongo;

use Yii;
use yii\mongodb\ActiveRecord;


class TrackingLogWs extends ActiveRecord
{
    public static function collectionName()
    {
        return ['Weshop_log_40','log_tracking'];
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
           

            'tracking',
            'user',
            'log_type',
        
            'request_ip',
            'note',
            'time_create',
            'object'

     
        ];
    }

    public function rules()
    {
        return [
            [[
                'created_at',
                'updated_at',
                
	            'tracking',
	            'user',
	            'log_type',
	        
	            'request_ip',
	            'note',
	            'time_create',
	            'object'


            ], 'safe'],
            [[ 'tracking','log_type','request_ip', 'note','object'], 'required'],
        ];
    }

   /*
   User_name tác động đến tracking được lưu ở note
   */
    public function attributeLabels()
    {

        return [
            '_id' => 'ID',
            //User : Who ai tác
            'tracking' => 'Mã tracking',
            'user_id' => 'id nhân viên ',
            'user_email' => 'Email nhân viên chat ',
            'user_name' => 'tên nhân viên chat',
            'Role' => 'Role của nhân viên đang thao tác vào action',

            //Action thao tác là gì ?
            'log_type' => 'vd :Us sendding',
            // time
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',

            // ENV nào bắn lên
            'user_app' => 'Tên Application Id ',
            'request_ip' => 'Địa chỉ thao tác',
            'store' => 'Cửa hàng',
            'time_warehosue' => 'Thời gian thiết lập tại warehouse',
            'boxme_warehosue' => 'Kho chứa hàng',
            'manifest'        => 'ip đơn hàng',
            'time_create' =>'Thời gian tạo'
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

        $limit = isset($limit) ? $limit : 100;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = TrackingLogWs::find()
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
