<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id ID
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $username
 * @property string $password_hash
 * @property int $gender
 * @property string $birthday
 * @property string $avatar
 * @property string $link_verify
 * @property int $email_verified
 * @property int $phone_verified
 * @property string $last_order_time
 * @property string $note_by_employee
 * @property int $type_customer  set 1 là Khách Buôn và 2 là Khách buôn - WholeSale Customer 
 * @property string $access_token
 * @property string $auth_client
 * @property string $verify_token
 * @property string $reset_password_token
 * @property int $store_id
 * @property int $active_shipping
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
 * @property string $created_at
 * @property string $updated_at
 * @property int $active
 * @property int $remove
 * @property string $version version 4.0
 * @property string $auth_key
 *
 * @property Store $store
 * @property Order[] $orders
 * @property Shipment[] $shipments
 * @property ShipmentReturned[] $shipmentReturneds
 */
class Customer extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'email_verified', 'phone_verified', 'type_customer', 'store_id', 'active_shipping', 'total_xu_start_date', 'total_xu_expired_date', 'usable_xu_start_date', 'usable_xu_expired_date', 'last_use_time', 'last_revenue_time', 'verify_code_expired_at', 'verify_code_count', 'created_at', 'updated_at', 'active', 'remove'], 'integer'],
            [['birthday', 'last_order_time'], 'safe'],
            [['note_by_employee'], 'string'],
            [['total_xu', 'usable_xu', 'last_use_xu', 'last_revenue_xu'], 'number'],
            [['first_name', 'last_name', 'email', 'phone', 'username', 'password_hash', 'avatar', 'link_verify', 'access_token', 'auth_client', 'verify_token', 'reset_password_token', 'verify_code_type', 'version', 'auth_key'], 'string', 'max' => 255],
            [['verify_code'], 'string', 'max' => 10],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'first_name' => Yii::t('db', 'First Name'),
            'last_name' => Yii::t('db', 'Last Name'),
            'email' => Yii::t('db', 'Email'),
            'phone' => Yii::t('db', 'Phone'),
            'username' => Yii::t('db', 'Username'),
            'password_hash' => Yii::t('db', 'Password Hash'),
            'gender' => Yii::t('db', 'Gender'),
            'birthday' => Yii::t('db', 'Birthday'),
            'avatar' => Yii::t('db', 'Avatar'),
            'link_verify' => Yii::t('db', 'Link Verify'),
            'email_verified' => Yii::t('db', 'Email Verified'),
            'phone_verified' => Yii::t('db', 'Phone Verified'),
            'last_order_time' => Yii::t('db', 'Last Order Time'),
            'note_by_employee' => Yii::t('db', 'Note By Employee'),
            'type_customer' => Yii::t('db', 'Type Customer'),
            'access_token' => Yii::t('db', 'Access Token'),
            'auth_client' => Yii::t('db', 'Auth Client'),
            'verify_token' => Yii::t('db', 'Verify Token'),
            'reset_password_token' => Yii::t('db', 'Reset Password Token'),
            'store_id' => Yii::t('db', 'Store ID'),
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
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'active' => Yii::t('db', 'Active'),
            'remove' => Yii::t('db', 'Remove'),
            'version' => Yii::t('db', 'Version'),
            'auth_key' => Yii::t('db', 'Auth Key'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipmentReturneds()
    {
        return $this->hasMany(ShipmentReturned::className(), ['customer_id' => 'id']);
    }
}
