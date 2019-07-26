<?php

namespace common\modelsMongo;

use common\models\User;
use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "chat_mongo_ws".
 *
 * @property \MongoId|string $_id
 * @property mixed $success
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $date
 * @property mixed $user_id
 * @property mixed $user_email
 * @property mixed $user_name
 * @property mixed $user_app
 * @property mixed $user_request_suorce
 * @property mixed $request_ip
 * @property mixed $message
 * @property mixed $user_avatars
 * @property mixed $Order_path
 * @property mixed $is_send_email_to_customer
 * @property mixed $type_chat
 * @property mixed $link_image
 * @property mixed $is_customer_vew
 * @property mixed $is_employee_vew
 */
class ChatMongoWs extends ActiveRecord
{
    const TYPE_WS_CUSTOMER = 'WS_CUSTOMER';
    const TYPE_GROUP_WS = 'GROUP_WS';

    const REQUEST_SOURCE_BACK_END = 'BACK_END';
    const REQUEST_SOURCE_FRONTEND = 'FRONTEND';
    const REQUEST_SOURCE_APP = 'APP';

    public static function collectionName()
    {
        return ['weshop_global_40','chat_mongo_ws'];
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
            'link_image',
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
                'link_image',
                'is_customer_vew',
                'is_employee_vew'], 'safe'],
            [['user_id','message', 'Order_path', 'type_chat'], 'required'],
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
            'user_request_suorce' => 'suorce gửi app chát Phân biệt : APP/FRONTEND/BACK_END ',
            'request_ip' => 'IP request send message',
            'user_avatar' => 'Hình đại diện của User',
            'Order_path' => 'link order details',
            'is_send_email_to_customer' => ' đánh đấu nội dung này có gửi tới email khách hàng không',
            'message' => 'nội dung Thông điện tin nhắn Text nội bộ chat hoặc chat',
            'type_chat' => 'TYPE_CHAT : GROUP_WS/WS_CUSTOMER : CHAT nội bộ trong WS : "GROUP_WS" hoặc nhân viên chat KH :"WS_CUSTOMER" ',
            'link_image' => 'Link anh',
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

        $query = ChatMongoWs::find()
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
    public static function SendMessage($message,$order_code,$type_chat = self::TYPE_WS_CUSTOMER,$source = self::REQUEST_SOURCE_BACK_END,$isSendmail = false){
        try{
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            if ($user){
                $chat = new self();
                $chat->success = true;
                $chat->message = $message;
                $chat->date = Yii::$app->getFormatter()->asDatetime('now');
                $chat->user_id = $user->id;
                $chat->user_email = $user->email;
                $chat->user_name = $user->username;
                $chat->user_app = null;
                $chat->user_request_suorce = $source;
                $chat->request_ip = Yii::$app->getRequest()->getUserIP();
                $chat->user_avatars = $user->avatar ? $user->avatar : null;
                $chat->Order_path = $order_code;
                $chat->is_send_email_to_customer = $isSendmail;
                $chat->type_chat = $type_chat;
                $chat->is_customer_vew = $type_chat;
                $chat->is_employee_vew = $type_chat;
                return $chat->save();
            }
        }catch (\Exception $exception){
            Yii::error($exception);
        }
        return false;
    }
}
