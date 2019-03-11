<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id ID
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $username
 * @property string $password_hard
 * @property int $gender
 * @property string $birthday
 * @property string $avatar
 * @property string $link_verify
 * @property int $email_verified
 * @property int $phone_verified
 * @property string $last_order_time
 * @property string $note_by_employee
 * @property int $type_customer
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
 *
 * @property Address[] $addresses
 * @property Store $store
 * @property Order[] $orders
 * @property Shipment[] $shipments
 * @property ShipmentReturned[] $shipmentReturneds
 * @property WalletTransaction[] $walletTransactions
 */
class Customer extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
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
            [['first_name', 'last_name', 'email', 'phone', 'username', 'password_hard', 'avatar', 'link_verify', 'access_token', 'auth_client', 'verify_token', 'reset_password_token', 'verify_code_type'], 'string', 'max' => 255],
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
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'username' => 'User Name',
            'password_hard' => 'Password Hard',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'avatar' => 'Avatar',
            'link_verify' => 'Link Verify',
            'email_verified' => 'Email Verified',
            'phone_verified' => 'Phone Verified',
            'last_order_time' => 'Last Order Time',
            'note_by_employee' => 'Note By Employee',
            'type_customer' => 'Type Customer',
            'access_token' => 'Access Token',
            'auth_client' => 'Auth Client',
            'verify_token' => 'Verify Token',
            'reset_password_token' => 'Reset Password Token',
            'store_id' => 'Store ID',
            'active_shipping' => 'Active Shipping',
            'total_xu' => 'Total Xu',
            'total_xu_start_date' => 'Total Xu Start Date',
            'total_xu_expired_date' => 'Total Xu Expired Date',
            'usable_xu' => 'Usable Xu',
            'usable_xu_start_date' => 'Usable Xu Start Date',
            'usable_xu_expired_date' => 'Usable Xu Expired Date',
            'last_use_xu' => 'Last Use Xu',
            'last_use_time' => 'Last Use Time',
            'last_revenue_xu' => 'Last Revenue Xu',
            'last_revenue_time' => 'Last Revenue Time',
            'verify_code' => 'Verify Code',
            'verify_code_expired_at' => 'Verify Code Expired At',
            'verify_code_count' => 'Verify Code Count',
            'verify_code_type' => 'Verify Code Type',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'active' => 'Active',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['customer_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTransactions()
    {
        return $this->hasMany(WalletTransaction::className(), ['customer_id' => 'id']);
    }
}
