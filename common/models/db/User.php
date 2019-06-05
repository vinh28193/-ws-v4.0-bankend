<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $scopes nhiều scope cách nhau bằng dấu ,. scope chính đặt tại đầu
 * @property int $store_id
 * @property string $locale
 * @property string $github  user co link github
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property int $gender
 * @property string $birthday
 * @property string $avatar
 * @property string $link_verify
 * @property int $email_verified
 * @property int $phone_verified
 * @property string $last_order_time
 * @property string $note_by_employee
 * @property int $type_customer  set 1 là Khách Lẻ và 2 là Khách buôn - WholeSale Customer 
 * @property int $employee  1 Là Nhân viên , 0 là khách hàng
 * @property int $active_shipping 0 là Khách thường , 1 là khách buôn cho phép shiping
 * @property string $total_xu
 * @property string $total_xu_start_date Thoi điểm bắt đầu điểm tích lũy 
 * @property string $total_xu_expired_date Thoi điểm reset điểm tích lũy về 0
 * @property string $usable_xu //tổng số xu có thể sử dụng (tgian 1 tháng)
 * @property string $usable_xu_start_date Thoi điểm bắt đầu điểm tích lũy 
 * @property string $usable_xu_expired_date Thoi điểm reset điểm tích lũy về 0
 * @property string $last_use_xu
 * @property string $last_use_time
 * @property string $last_revenue_xu
 * @property string $last_revenue_time
 * @property string $verify_code
 * @property string $verify_code_expired_at
 * @property int $verify_code_count
 * @property string $verify_code_type
 * @property int $remove  0 là chưa xóa , tức là ẩn , 1 là đánh dấu đã xóa
 * @property int $vip  Mức độ Vip Của Khách Hàng không ap dụng cho nhân viên , theo thang điểm 0-5 số
 * @property string $uuid  uuid tương đương fingerprint là  số đinh danh của user trên toàn hệ thống WS + hệ thống quảng cáo + hệ thống tracking 
 * @property string $token_fcm  Google FCM notification
 * @property string $token_apn  Apple APN number notification
 * @property string $last_update_uuid_time Thời gian update là 
 * @property string $last_update_uuid_time_by update bởi ai . 99999 : mac dinh la Weshop admin
 * @property string $client_id_ga  dánh dau khách hàng ẩn danh không phải là khách hàng đăng kí --> đến khi chuyển đổi thành khách hàng user đăng kí
 * @property string $last_update_client_id_ga_time  Thời gian sinh ra mã client_id_ga
 * @property string $last_update_client_id_ga_time_by update bởi ai . 99999 : mac dinh la Weshop admin
 * @property string $last_token_fcm_time Thời gian update là 
 * @property string $last_token_fcm_by update bởi ai . 99999 : mac dinh la Weshop admin
 * @property string $last_token_apn_time Thời gian update là 
 * @property string $last_token_apn_time_by update bởi ai . 99999 : mac dinh la Weshop admin
 *
 * @property Order[] $orders
 * @property Order[] $orders0
 */
class User extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at', 'store_id', 'gender', 'email_verified', 'phone_verified', 'type_customer', 'employee', 'active_shipping', 'total_xu_start_date', 'total_xu_expired_date', 'usable_xu_start_date', 'usable_xu_expired_date', 'last_use_time', 'last_revenue_time', 'verify_code_expired_at', 'verify_code_count', 'remove', 'vip', 'last_update_uuid_time_by', 'last_update_client_id_ga_time_by', 'last_token_fcm_by', 'last_token_apn_time_by'], 'integer'],
            [['birthday', 'last_order_time', 'last_update_uuid_time', 'last_update_client_id_ga_time', 'last_token_fcm_time', 'last_token_apn_time'], 'safe'],
            [['note_by_employee'], 'string'],
            [['total_xu', 'usable_xu', 'last_use_xu', 'last_revenue_xu'], 'number'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'first_name', 'last_name', 'phone', 'avatar', 'link_verify', 'verify_code_type', 'uuid', 'token_fcm', 'token_apn', 'client_id_ga'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['scopes', 'github'], 'string', 'max' => 500],
            [['locale', 'verify_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'username' => Yii::t('db', 'Username'),
            'auth_key' => Yii::t('db', 'Auth Key'),
            'password_hash' => Yii::t('db', 'Password Hash'),
            'password_reset_token' => Yii::t('db', 'Password Reset Token'),
            'email' => Yii::t('db', 'Email'),
            'status' => Yii::t('db', 'Status'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'scopes' => Yii::t('db', 'Scopes'),
            'store_id' => Yii::t('db', 'Store ID'),
            'locale' => Yii::t('db', 'Locale'),
            'github' => Yii::t('db', 'Github'),
            'first_name' => Yii::t('db', 'First Name'),
            'last_name' => Yii::t('db', 'Last Name'),
            'phone' => Yii::t('db', 'Phone'),
            'gender' => Yii::t('db', 'Gender'),
            'birthday' => Yii::t('db', 'Birthday'),
            'avatar' => Yii::t('db', 'Avatar'),
            'link_verify' => Yii::t('db', 'Link Verify'),
            'email_verified' => Yii::t('db', 'Email Verified'),
            'phone_verified' => Yii::t('db', 'Phone Verified'),
            'last_order_time' => Yii::t('db', 'Last Order Time'),
            'note_by_employee' => Yii::t('db', 'Note By Employee'),
            'type_customer' => Yii::t('db', 'Type Customer'),
            'employee' => Yii::t('db', 'Employee'),
            'active_shipping' => Yii::t('db', 'Active Shipping'),
            'total_xu' => Yii::t('db', 'Total Xu'),
            'total_xu_start_date' => Yii::t('db', 'Total Xu Start Date'),
            'total_xu_expired_date' => Yii::t('db', 'Total Xu Expired Date'),
            'usable_xu' => Yii::t('db', 'Usable Xu'),
            'usable_xu_start_date' => Yii::t('db', 'Usable Xu Start Date'),
            'usable_xu_expired_date' => Yii::t('db', 'Usable Xu Expired Date'),
            'last_use_xu' => Yii::t('db', 'Last Use Xu'),
            'last_use_time' => Yii::t('db', 'Last Use Time'),
            'last_revenue_xu' => Yii::t('db', 'Last Revenue Xu'),
            'last_revenue_time' => Yii::t('db', 'Last Revenue Time'),
            'verify_code' => Yii::t('db', 'Verify Code'),
            'verify_code_expired_at' => Yii::t('db', 'Verify Code Expired At'),
            'verify_code_count' => Yii::t('db', 'Verify Code Count'),
            'verify_code_type' => Yii::t('db', 'Verify Code Type'),
            'remove' => Yii::t('db', 'Remove'),
            'vip' => Yii::t('db', 'Vip'),
            'uuid' => Yii::t('db', 'Uuid'),
            'token_fcm' => Yii::t('db', 'Token Fcm'),
            'token_apn' => Yii::t('db', 'Token Apn'),
            'last_update_uuid_time' => Yii::t('db', 'Last Update Uuid Time'),
            'last_update_uuid_time_by' => Yii::t('db', 'Last Update Uuid Time By'),
            'client_id_ga' => Yii::t('db', 'Client Id Ga'),
            'last_update_client_id_ga_time' => Yii::t('db', 'Last Update Client Id Ga Time'),
            'last_update_client_id_ga_time_by' => Yii::t('db', 'Last Update Client Id Ga Time By'),
            'last_token_fcm_time' => Yii::t('db', 'Last Token Fcm Time'),
            'last_token_fcm_by' => Yii::t('db', 'Last Token Fcm By'),
            'last_token_apn_time' => Yii::t('db', 'Last Token Apn Time'),
            'last_token_apn_time_by' => Yii::t('db', 'Last Token Apn Time By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['sale_support_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders0()
    {
        return $this->hasMany(Order::className(), ['purchase_assignee_id' => 'id']);
    }
}
