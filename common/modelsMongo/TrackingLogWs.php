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

    
            $limit = 20;
            $page          = Yii::$app->getRequest()->getQueryParam('page');
            $perPage       = Yii::$app->getRequest()->getQueryParam('perPage');
            $trackingcode  = Yii::$app->getRequest()->getQueryParam('trackingcode');
            $request_ip    = Yii::$app->getRequest()->getQueryParam('request_ip');
            $log_type      = Yii::$app->getRequest()->getQueryParam('log_type');
            $user          = Yii::$app->getRequest()->getQueryParam('user');
            $note          = Yii::$app->getRequest()->getQueryParam('note');
            $sort_time     = Yii::$app->getRequest()->getQueryParam('sort_time');
            $query = TrackingLogWs::find();
           
            if(!isset($page))
            {
                $page = 1;
            }
            if(isset($trackingcode)){
                 $query->andWhere(['tracking' =>$trackingcode]);
            }
            if(isset($request_ip)){
                 $query->andWhere(['request_ip' =>$request_ip]);
            }
            if(isset($log_type)){
                 $query->andWhere(['like', 'log_type', $log_type]);
            }
            if(isset($user)){
                 $query->andWhere(['like', 'user', $user]);
            }
            // if(isset($note)){
            //      $query->andWhere(['like', 'note', $note]);
            // }
            // if ($timeStart && $timeEnd){
            //     $query->andWhere(['or',
            //         ['>=', 'created_at', $timeStart],
            //         ['<=', 'created_at', $timeEnd]
            //     ]);
            //  }
            $query->orderBy('created_at desc');
            if(isset($sort_time) && $sort_time == 2)
            {
               $query->orderBy('created_at ASC');
            }

            $query->limit($limit)
            ->offset($page* $limit - $limit);
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