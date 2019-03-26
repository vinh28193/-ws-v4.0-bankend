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
            'success',
            'created_at',
            'updated_at',
            'date',
            'user_id',
            'user_email',
            'user_name',
            'user_app',
            'user_request_suorce',
            'request_ip',
            'message',
            'user_avatars',
            'Order_path',
            'is_send_email_to_customer',
            'type_chat',
            'is_customer_vew',
            'is_employee_vew'
        ];
    }

    public function rules()
    {
        return [
            [['success', 'created_at',
                'updated_at', 'date', 'user_id',
                'user_email',
                'user_name',
                'user_app',
                'user_request_suorce',
                'request_ip',
                'message',
                'user_avatar',
                'Order_path',
                'is_send_email_to_customer',
                'type_chat',
                'is_customer_vew',
                'is_employee_vew'], 'safe'],
            [['message', 'Order_path', 'type_chat'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'success' => 'success true or false',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'date' => 'Date create data message',
            'user_id' => 'id nhân viên ',
            'user_email' => 'Email nhân viên chat ',
            'user_name' => 'tên nhân viên chat',
            'user_app' => 'Tên Application Id ',
            'user_request_suorce' => ' suorce gửi app chát Phân biệt : APP/FRONTEND/BACK_END ',
            'request_ip' => 'IP request send message',
            'user_avatar' => 'Hình đại diện của User',
            'Order_path' => 'link order details',
            'is_send_email_to_customer' => ' đánh đấu nội dung này có gửi tới email khách hàng không',
            'message' => 'nội dung Thông điện tin nhắn Text nội bộ chat hoặc chat',
            'type_chat' => 'TYPE_CHAT : GROUP_WS/WS_CUSTOMER : CHAT nội bộ trong WS : "GROUP_WS" hoặc nhân viên chat KH :"WS_CUSTOMER" ',
            'is_customer_vew' => ' Đanh dấu khách hàng đã xem Null / Id Customer',
            'is_employee_vew' => 'đánh đấu nhân viên nào đã xem , list Id user : null / id user'
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
            //->withFullRelations()
            //->filter($params)
            ->limit($limit)
            ->offset($offset);

        /*
        if (isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }

        if (isset($params['created_at'])) {
            $query->andFilterWhere(['created_at' => $params['created_at']]);
        }
        if (isset($params['updated_at'])) {
            $query->andFilterWhere(['updated_at' => $params['updated_at']]);
        }
        if (isset($params['receiver_email'])) {
            $query->andFilterWhere(['like', 'receiver_email', $params['receiver_email']]);
        }
        */

        /*

        if(isset($params['typeSearch']) and isset($params['keyword']) ){
            $query->andFilterWhere(['like',$params['typeSearch'],$params['keyword']]);
        }else{
            $query->andWhere(['or',
                ['like', 'id', $params['keyword']],
                ['like', 'seller_name', $params['keyword']],
                ['like', 'seller_store', $params['keyword']],
                ['like', 'portal', $params['keyword']],
            ]);
        }
        */

        /*
        if (isset($params['type_order'])) {
            $query->andFilterWhere(['type_order' => $params['type_order']]);
        }
        if (isset($params['current_status'])) {
            $query->andFilterWhere(['current_status' => $params['current_status']]);
        }
        if (isset($params['time_start']) and isset($params['time_end'])) {
            $query->andFilterWhere(['or',
                ['>=', 'created_at', $params['time_start']],
                ['<=', 'updated_at', $params['time_end']]
            ]);
        }
        */

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
