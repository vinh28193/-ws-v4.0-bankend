<?php

use yii\db\Migration;

/**
 * Class m190312_050003_promotion_table
 */
class m190312_050003_promotion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('promotion', [
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'code' => $this->string(255)->unique()->comment("Tạo mã chạy chương trình duy nhất không trung"),

            'message' => $this->text()->comment(""),
            'discountType' => $this->string(255)->comment('Coupon/MultiCoupon/CouponRefund/MultiProduct/MarketingCampaign/Others'),
            'status' => $this->string(200)->comment('1: Active, 0: Suspend'),
            'storeId' => $this->integer(11)->notNull()->comment(""),
            'discountCalculateType' => $this->string(11)->comment('A1: ProductPrice, A2: US State Tax, A3: US Shipping Fee, USPrice: A1 + A2 + A3, A4: Internaltional Shipping Fee, A5: Import Tax, A6: Custome Fee, A7: LastMile Delivery, ShipFee: A4 + A7, A8: WeshopFee, A9: GST, A0: TotalAmount,XU:Chương trình xu'),
            'discountPercentage' => $this->integer(11)->notNull()->comment('%'),
            'discountAmount' => $this->decimal(18, 2)->comment('Số tiền được xử dung giảm giá cho chương trình . Ví Dụ : giảm 200k hoặc 50% phí dịch vụ vận chuyển'),
            'discountOverWeight' => $this->decimal(18, 2)->comment('Số lượng cân nặng được giảm'),
            'refundBinCode' => $this->string(11)->comment('dùng với discountType = CouponRefund'),

            'created_time' => $this->bigInteger()->comment("Update qua behaviors tự động"),
            'updated_time' => $this->bigInteger()->comment("Update qua behaviors tự động"),

            'createUserId' => $this->integer(11)->comment("nhan vien tao"),
            'conditionXuRating' => $this->decimal(18, 2)->comment('Tỉ giá quy đổi 1 xu ra tiền local mặc định là 200k/1xu'),
            'conditionStartTime' => $this->bigInteger()->comment(""),
            'conditionEndTime' => $this->bigInteger()->comment(""),
            'conditionLimitUsageCount' => $this->integer(11)->comment('giới hạn số lần sử dụng'),
            'conditionLimitUsageAmount' => $this->decimal(18, 2)->comment('giới hạn số tiền sử dụng'),
            'conditionLimitByCustomerUsageCount' => $this->integer(11)->comment('giới hạn số lần sử dụng theo khách hàng'),
            'conditionLimitByCustomerUsageAmount' => $this->decimal(18, 2)->comment('giới hạn số tiền sử dụng theo khách hàng'),
            'conditionSourceTypes' => $this->string(11)->comment('EBAY/AMAZON'),
            'conditionCategoryAlias' => $this->text()->comment('String: các alias cách nhau bởi dấu phẩy'),
            'conditionCustomerEmails' => $this->text()->comment('String: các mail cách nhau bởi dấu phẩy'),
            'conditionOrderMaxAmount' => $this->decimal(18, 2)->comment('giới hạn max với số tiền thanh toán của 1 đơn hàng'),
            'conditionOrderMinAmount' => $this->decimal(18, 2)->comment('giới hạn min với số tiền thanh toán của 1 đơn hàng'),
            'conditionOrderXuRevenueMinAmount' => $this->decimal(18, 2)->comment('Gioi han min de duoc cong xu'),
            'conditionOrderXuRevenueMaxAmount' => $this->decimal(18, 2)->comment('Gioi han max de duoc cong xu'),
            'conditionMinWeight' => $this->float(11, 0)->comment('Giới hạn cân nặng được áp dụng'),
            'conditionDayOfWeek' => $this->string(255)->comment('eg: Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'),
            'conditionNewCustomer' => $this->integer(11)->comment('Áp dụng cho khách hàng mới 1: true ,0 false'),
            'conditionMinOrderExist' => $this->integer(11)->comment('số lượng đơn hàng đã thanh toán tối thiểu'),
            'conditionPortal' => $this->string(255)->comment('eg:"ebay,amzon,amzon-jp,amzon-uk"'),
            'couponCode' => $this->string(255)->unique()->comment('dùng với discountType = Coupon hoặc CouponRefund'),
            'usedOrderCountTotal' => $this->integer(11)->comment(""),
            'usedDiscountAmountTotal' => $this->decimal(18, 2)->comment(""),
            'usedFirstTime' => $this->bigInteger()->comment(""),
            'usedLastTime' => $this->bigInteger()->comment(""),
            'ListEmail' => $this->text()->comment(""),
            'conditionCheckService' => $this->string(255)->comment('Any other condition //Bank Payment....'),
            'discountMaxAmount' => $this->decimal(18, 2)->comment(""),
            'checkInstalment' => $this->integer(11)->comment('0/ not for instalment, 1 for all'),
            'allowMultiplePromotion' => $this->integer(11)->comment('1:true, 0: false'),
            'allowConditionOrder' => $this->integer(11)->comment('default 0 meaning check order, 1 check order_item'),
        ], $tableOptions);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190312_050003_promotion_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190312_050003_promotion_table cannot be reverted.\n";

        return false;
    }
    */
}
