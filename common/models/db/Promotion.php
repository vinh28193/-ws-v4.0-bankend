<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%promotion}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property string $name Tên chương trình
 * @property string $code Tạo mã chạy chương trình duy nhất không trung
 * @property string $message message
 * @property string $description description
 * @property int $type 1:Coupon/2:MultiCoupon/3:CouponRefund/4:Promotion/5:MultiProduct/6:MarketingCampaign/7:Others
 * @property int $discount_calculator 1:%,2:fix value
 * @property string $discount_fee discount dùng cho phí gì
 * @property string $discount_amount giá trị được giảm
 * @property int $discount_type 1:price,2 weight,3 quantity
 * @property string $discount_max_amount giá trị max được giảm khi discount type %
 * @property int $refund_order_Id dùng với discountType = CouponRefund
 * @property int $allow_instalment 0/ not for instalment, 1 for all
 * @property int $allow_multiple 1:true, 0: false
 * @property int $allow_order 1:true, 0: false
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 * @property string $promotion_image
 */
class Promotion extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%promotion}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'type', 'discount_calculator', 'discount_type', 'refund_order_Id', 'allow_instalment', 'allow_multiple', 'allow_order', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['description', 'promotion_image'], 'string'],
            [['discount_amount', 'discount_max_amount'], 'number'],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 32],
            [['message'], 'string', 'max' => 255],
            [['discount_fee'], 'string', 'max' => 50],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'store_id' => Yii::t('db', 'Store ID'),
            'name' => Yii::t('db', 'Name'),
            'code' => Yii::t('db', 'Code'),
            'message' => Yii::t('db', 'Message'),
            'description' => Yii::t('db', 'Description'),
            'type' => Yii::t('db', 'Type'),
            'discount_calculator' => Yii::t('db', 'Discount Calculator'),
            'discount_fee' => Yii::t('db', 'Discount Fee'),
            'discount_amount' => Yii::t('db', 'Discount Amount'),
            'discount_type' => Yii::t('db', 'Discount Type'),
            'discount_max_amount' => Yii::t('db', 'Discount Max Amount'),
            'refund_order_Id' => Yii::t('db', 'Refund Order ID'),
            'allow_instalment' => Yii::t('db', 'Allow Instalment'),
            'allow_multiple' => Yii::t('db', 'Allow Multiple'),
            'allow_order' => Yii::t('db', 'Allow Order'),
            'status' => Yii::t('db', 'Status'),
            'created_by' => Yii::t('db', 'Created By'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'promotion_image' => Yii::t('db', 'Promotion Image'),
        ];
    }
}
