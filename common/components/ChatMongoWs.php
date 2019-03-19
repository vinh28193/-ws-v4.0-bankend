<?php

namespace common\components;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "rest_api_call".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $address
 * @property mixed $status
 */
class ChatMongoWs extends ActiveRecord
{
    public static function collectionName()
    {
        return 'chat_mongo_ws';
    }

    public function attributes()
    {
        return [
            '_id',
            'success',
            'timestamp',
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
            [['success', 'timestamp','date' ,'user_id',
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
           [['message','Order_path','type_chat'], 'required'] ,
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'success' => 'success true or false',
            'timestamp' => 'timestamp',
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
            'type_chat'=>'TYPE_CHAT : GROUP_WS/WS_CUSTOMER : CHAT nội bộ trong WS : "GROUP_WS" hoặc nhân viên chat KH :"WS_CUSTOMER" ',
            'is_customer_vew' => ' Đanh dấu khách hàng đã xem Null / Id Customer',
            'is_employee_vew' => 'đánh đấu nhân viên nào đã xem , list Id user : null / id user'
        ];
    }
}
