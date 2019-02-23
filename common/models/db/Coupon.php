<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property int $id ID
 * @property string $name
 * @property string $code
 * @property string $message thông báo khi áp dụng coupon
 * @property string $type_coupon REFUND, COUPON ... hệ thống tự sinh, và phải là 1 const
 * @property string $type_amount percent,money. nếu là money sẽ lấy theo tiền local.
 * @property int $store_id
 * @property string $amount đơn vị: % or tiền local. phụ thuộc vào type_amount
 * @property string $percent_for tính % cho cái gì. Theo các cel trong bảng tính giá. A1 , A2. Nếu để trống sẽ mặc định tính theo giá tổng
 * @property int $created_by id người tạo
 * @property string $start_time
 * @property string $end_time
 * @property int $limit_customer_count_use giới hạn số lần sử dụng cho 1 user
 * @property int $limit_count_use giới hạn số lần sử dụng
 * @property int $count_use số lần sử dụng
 * @property string $limit_amount_use giới hạn số tiền tối đa thể sử dụng
 * @property string $limit_amount_use_order giới hạn số tiền tối đa thể sử dụng cho 1 order
 * @property string $for_email coupon cho email nào
 * @property string $for_portal coupon cho portal nào
 * @property string $for_category coupon cho category nào
 * @property string $for_min_order_amount coupon cho giá trị tối thiểu của 1 đơn hàng . đơn vị tiền local
 * @property string $for_max_order_amount coupon cho giá trị tối đa của 1 đơn hàng . đơn vị tiền local
 * @property string $total_amount_used tổng số tiền đã trừ từ coupon này
 * @property string $used_first_time
 * @property string $used_last_time
 * @property int $can_use_instalment
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 *
 * @property User $createdBy
 * @property Store $store
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'created_by', 'start_time', 'end_time', 'limit_customer_count_use', 'limit_count_use', 'count_use', 'used_first_time', 'used_last_time', 'can_use_instalment', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['amount', 'limit_amount_use', 'limit_amount_use_order', 'total_amount_used'], 'number'],
            [['name', 'code', 'message', 'type_coupon', 'type_amount', 'percent_for', 'for_email', 'for_portal', 'for_category', 'for_min_order_amount', 'for_max_order_amount'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'name' => 'Name',
            'code' => 'Code',
            'message' => 'Message',
            'type_coupon' => 'Type Coupon',
            'type_amount' => 'Type Amount',
            'store_id' => 'Store ID',
            'amount' => 'Amount',
            'percent_for' => 'Percent For',
            'created_by' => 'Created By',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'limit_customer_count_use' => 'Limit Customer Count Use',
            'limit_count_use' => 'Limit Count Use',
            'count_use' => 'Count Use',
            'limit_amount_use' => 'Limit Amount Use',
            'limit_amount_use_order' => 'Limit Amount Use Order',
            'for_email' => 'For Email',
            'for_portal' => 'For Portal',
            'for_category' => 'For Category',
            'for_min_order_amount' => 'For Min Order Amount',
            'for_max_order_amount' => 'For Max Order Amount',
            'total_amount_used' => 'Total Amount Used',
            'used_first_time' => 'Used First Time',
            'used_last_time' => 'Used Last Time',
            'can_use_instalment' => 'Can Use Instalment',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }
}
