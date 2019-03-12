<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "promotion".
 *
 * @property int $id ID
 * @property string $name
 * @property string $code Tạo mã chạy chương trình duy nhất không trung
 * @property string $message
 * @property string $discountType Coupon/MultiCoupon/CouponRefund/MultiProduct/MarketingCampaign/Others
 * @property string $status 1: Active, 0: Suspend
 * @property int $storeId
 * @property string $discountCalculateType A1: ProductPrice, A2: US State Tax, A3: US Shipping Fee, USPrice: A1 + A2 + A3, A4: Internaltional Shipping Fee, A5: Import Tax, A6: Custome Fee, A7: LastMile Delivery, ShipFee: A4 + A7, A8: WeshopFee, A9: GST, A0: TotalAmount,XU:Chương trình xu
 * @property int $discountPercentage %
 * @property string $discountAmount Số tiền được xử dung giảm giá cho chương trình . Ví Dụ : giảm 200k hoặc 50% phí dịch vụ vận chuyển
 * @property string $discountOverWeight Số lượng cân nặng được giảm
 * @property string $refundBinCode dùng với discountType = CouponRefund
 * @property string $created_time Update qua behaviors tự động
 * @property string $updated_time Update qua behaviors tự động
 * @property int $createUserId nhan vien tao
 * @property string $conditionXuRating Tỉ giá quy đổi 1 xu ra tiền local mặc định là 200k/1xu
 * @property string $conditionStartTime
 * @property string $conditionEndTime
 * @property int $conditionLimitUsageCount giới hạn số lần sử dụng
 * @property string $conditionLimitUsageAmount giới hạn số tiền sử dụng
 * @property int $conditionLimitByCustomerUsageCount giới hạn số lần sử dụng theo khách hàng
 * @property string $conditionLimitByCustomerUsageAmount giới hạn số tiền sử dụng theo khách hàng
 * @property string $conditionSourceTypes EBAY/AMAZON
 * @property string $conditionCategoryAlias String: các alias cách nhau bởi dấu phẩy
 * @property string $conditionCustomerEmails String: các mail cách nhau bởi dấu phẩy
 * @property string $conditionOrderMaxAmount giới hạn max với số tiền thanh toán của 1 đơn hàng
 * @property string $conditionOrderMinAmount giới hạn min với số tiền thanh toán của 1 đơn hàng
 * @property string $conditionOrderXuRevenueMinAmount Gioi han min de duoc cong xu
 * @property string $conditionOrderXuRevenueMaxAmount Gioi han max de duoc cong xu
 * @property double $conditionMinWeight Giới hạn cân nặng được áp dụng
 * @property string $conditionDayOfWeek eg: Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday
 * @property int $conditionNewCustomer Áp dụng cho khách hàng mới 1: true ,0 false
 * @property int $conditionMinOrderExist số lượng đơn hàng đã thanh toán tối thiểu
 * @property string $conditionPortal eg:"ebay,amzon,amzon-jp,amzon-uk"
 * @property string $couponCode dùng với discountType = Coupon hoặc CouponRefund
 * @property int $usedOrderCountTotal
 * @property string $usedDiscountAmountTotal
 * @property string $usedFirstTime
 * @property string $usedLastTime
 * @property string $ListEmail
 * @property string $conditionCheckService Any other condition //Bank Payment....
 * @property string $discountMaxAmount
 * @property int $checkInstalment 0/ not for instalment, 1 for all
 * @property int $allowMultiplePromotion 1:true, 0: false
 * @property int $allowConditionOrder default 0 meaning check order, 1 check order_item
 */
class Promotion extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'conditionCategoryAlias', 'conditionCustomerEmails', 'ListEmail'], 'string'],
            [['storeId', 'discountPercentage'], 'required'],
            [['storeId', 'discountPercentage', 'created_time', 'updated_time', 'createUserId', 'conditionStartTime', 'conditionEndTime', 'conditionLimitUsageCount', 'conditionLimitByCustomerUsageCount', 'conditionNewCustomer', 'conditionMinOrderExist', 'usedOrderCountTotal', 'usedFirstTime', 'usedLastTime', 'checkInstalment', 'allowMultiplePromotion', 'allowConditionOrder'], 'integer'],
            [['discountAmount', 'discountOverWeight', 'conditionXuRating', 'conditionLimitUsageAmount', 'conditionLimitByCustomerUsageAmount', 'conditionOrderMaxAmount', 'conditionOrderMinAmount', 'conditionOrderXuRevenueMinAmount', 'conditionOrderXuRevenueMaxAmount', 'conditionMinWeight', 'usedDiscountAmountTotal', 'discountMaxAmount'], 'number'],
            [['name', 'code', 'discountType', 'conditionDayOfWeek', 'conditionPortal', 'couponCode', 'conditionCheckService'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 200],
            [['discountCalculateType', 'refundBinCode', 'conditionSourceTypes'], 'string', 'max' => 11],
            [['code'], 'unique'],
            [['couponCode'], 'unique'],
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
            'discountType' => 'Discount Type',
            'status' => 'Status',
            'storeId' => 'Store ID',
            'discountCalculateType' => 'Discount Calculate Type',
            'discountPercentage' => 'Discount Percentage',
            'discountAmount' => 'Discount Amount',
            'discountOverWeight' => 'Discount Over Weight',
            'refundBinCode' => 'Refund Bin Code',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'createUserId' => 'Create User ID',
            'conditionXuRating' => 'Condition Xu Rating',
            'conditionStartTime' => 'Condition Start Time',
            'conditionEndTime' => 'Condition End Time',
            'conditionLimitUsageCount' => 'Condition Limit Usage Count',
            'conditionLimitUsageAmount' => 'Condition Limit Usage Amount',
            'conditionLimitByCustomerUsageCount' => 'Condition Limit By Customer Usage Count',
            'conditionLimitByCustomerUsageAmount' => 'Condition Limit By Customer Usage Amount',
            'conditionSourceTypes' => 'Condition Source Types',
            'conditionCategoryAlias' => 'Condition Category Alias',
            'conditionCustomerEmails' => 'Condition Customer Emails',
            'conditionOrderMaxAmount' => 'Condition Order Max Amount',
            'conditionOrderMinAmount' => 'Condition Order Min Amount',
            'conditionOrderXuRevenueMinAmount' => 'Condition Order Xu Revenue Min Amount',
            'conditionOrderXuRevenueMaxAmount' => 'Condition Order Xu Revenue Max Amount',
            'conditionMinWeight' => 'Condition Min Weight',
            'conditionDayOfWeek' => 'Condition Day Of Week',
            'conditionNewCustomer' => 'Condition New Customer',
            'conditionMinOrderExist' => 'Condition Min Order Exist',
            'conditionPortal' => 'Condition Portal',
            'couponCode' => 'Coupon Code',
            'usedOrderCountTotal' => 'Used Order Count Total',
            'usedDiscountAmountTotal' => 'Used Discount Amount Total',
            'usedFirstTime' => 'Used First Time',
            'usedLastTime' => 'Used Last Time',
            'ListEmail' => 'List Email',
            'conditionCheckService' => 'Condition Check Service',
            'discountMaxAmount' => 'Discount Max Amount',
            'checkInstalment' => 'Check Instalment',
            'allowMultiplePromotion' => 'Allow Multiple Promotion',
            'allowConditionOrder' => 'Allow Condition Order',
        ];
    }
}
