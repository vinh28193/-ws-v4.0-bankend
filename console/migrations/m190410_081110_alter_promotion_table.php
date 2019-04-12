<?php

use common\components\db\Migration;

/**
 * Class m190410_081110_alter_promotion_table
 */
class m190410_081110_alter_promotion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('DROP TABLE IF EXISTS `promotion`');
        $this->createTable('promotion', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'name' => $this->string(100)->comment('Tên chương trình'),
            'code' => $this->string(32)->unique()->comment('Tạo mã chạy chương trình duy nhất không trung'),
            'message' => $this->string(255)->comment('message'),
            'description' => $this->text()->comment('description'),
            'type' => $this->smallInteger()->defaultValue(1)->comment('1:Coupon/2:MultiCoupon/3:CouponRefund/4:Promotion/5:MultiProduct/6:MarketingCampaign/7:Others'),
            'discount_calculator' => $this->smallInteger()->defaultValue(1)->comment('1:%,2:fix value'),
            'discount_fee' => $this->string(50)->comment("discount dùng cho phí gì"),
            'discount_amount' => $this->decimal()->defaultValue(0)->comment('giá trị được giảm'),
            'discount_type' => $this->smallInteger()->defaultValue(1)->comment('1:price,2 weight,3 quantity'),
            'discount_max_amount' => $this->decimal()->defaultValue(0)->comment('giá trị max được giảm khi discount type %'),
            'refund_order_Id' => $this->integer(11)->comment('dùng với discountType = CouponRefund'),
            'allow_instalment' => $this->smallInteger()->defaultValue(0)->comment('0/ not for instalment, 1 for all'),
            'allow_multiple' => $this->smallInteger()->defaultValue(1)->comment('1:true, 0: false'),
            'allow_order' => $this->smallInteger()->defaultValue(1)->comment('1:true, 0: false'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_at' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),
        ]);

        $this->createTable('promotion_condition', [
            'id' => $this->primaryKey()->comment("ID"),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'promotion_id' => $this->string(11)->comment("Promotion ID"),
            'name' => $this->string(80)->notNull()->comment('name of condition'),
            'value' => $this->binary()->notNull()->comment('mixed value'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_at' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),

        ]);
        $this->createTable('promotion_condition_config', [
            'id' => $this->primaryKey()->comment("ID"),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'name' => $this->string(80)->notNull()->comment('name of condition'),
            'operator' => $this->string(10)->notNull()->comment('Operator of condition'),
            'type_cast' => $this->string(10)->notNull()->defaultValue('integer')->comment('php type cast (integer,string,float ..etc)'),
            'description' => $this->text()->comment('description'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_at' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),
        ]);

        $this->createTable('promotion_user', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'customer_id' => $this->integer(11)->notNull()->comment('Tên chương trình'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'is_used' => $this->smallInteger()->defaultValue(1)->comment('1:Used'),
            'used_order_id' => $this->integer(11)->comment('Used for order id '),
            'used_at' => $this->integer(11)->defaultValue(null)->comment('Used at (timestamp)'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
        ]);

        $this->execute('DROP TABLE IF EXISTS `promotion_log`');
        $this->createTable('promotion_log', [
            'id' => $this->primaryKey()->comment("ID"),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'promotion_id' => $this->string(11)->comment("Promotion ID"),
            'customer_id' => $this->integer(11)->comment("Customer ID"),
            'order_id' => $this->string(32)->comment("Order ID"),
            'revenue_xu' => $this->decimal(18, 1)->comment('Số xu kiếm được'),
            'discount_amount' => $this->decimal(18, 2)->comment('Số tiền giảm giá'),
            'status' => $this->string(255)->comment('SUCCESS/FAIL'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP TABLE IF EXISTS `promotion`');
        $this->createTable('promotion', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(255)->comment(''),
            'code' => $this->string(255)->unique()->comment('Tạo mã chạy chương trình duy nhất không trung'),

            'message' => $this->text()->comment(''),
            'discountType' => $this->string(255)->comment('Coupon/MultiCoupon/CouponRefund/MultiProduct/MarketingCampaign/Others'),
            'status' => $this->string(200)->comment('1: Active, 0: Suspend'),
            'storeId' => $this->integer(11)->notNull()->comment(''),
            'discountCalculateType' => $this->string(11)->comment('A1: ProductPrice, A2: US State Tax, A3: US Shipping Fee, USPrice: A1 + A2 + A3, A4: Internaltional Shipping Fee, A5: Import Tax, A6: Custome Fee, A7: LastMile Delivery, ShipFee: A4 + A7, A8: WeshopFee, A9: GST, A0: TotalAmount,XU:Chương trình xu'),
            'discountPercentage' => $this->integer(11)->notNull()->comment('%'),
            'discountAmount' => $this->decimal(18, 2)->comment('Số tiền được xử dung giảm giá cho chương trình . Ví Dụ : giảm 200k hoặc 50% phí dịch vụ vận chuyển'),
            'discountOverWeight' => $this->decimal(18, 2)->comment('Số lượng cân nặng được giảm'),
            'refundBinCode' => $this->string(11)->comment('dùng với discountType = CouponRefund'),

            'created_time' => $this->bigInteger()->comment('Update qua behaviors tự động'),
            'updated_time' => $this->bigInteger()->comment('Update qua behaviors tự động'),

            'createUserId' => $this->integer(11)->comment('nhan vien tao'),
            'conditionXuRating' => $this->decimal(18, 2)->comment('Tỉ giá quy đổi 1 xu ra tiền local mặc định là 200k/1xu'),
            'conditionStartTime' => $this->bigInteger()->comment(''),
            'conditionEndTime' => $this->bigInteger()->comment(''),
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
            'conditionPortal' => $this->string(255)->comment('eg:ebay,amzon,amzon-jp,amzon-uk'),
            'couponCode' => $this->string(255)->unique()->comment('dùng với discountType = Coupon hoặc CouponRefund'),
            'usedOrderCountTotal' => $this->integer(11)->comment(''),
            'usedDiscountAmountTotal' => $this->decimal(18, 2)->comment(''),
            'usedFirstTime' => $this->bigInteger()->comment(''),
            'usedLastTime' => $this->bigInteger()->comment(''),
            'ListEmail' => $this->text()->comment(''),
            'conditionCheckService' => $this->string(255)->comment('Any other condition //Bank Payment....'),
            'discountMaxAmount' => $this->decimal(18, 2)->comment(''),
            'checkInstalment' => $this->integer(11)->comment('0/ not for instalment, 1 for all'),
            'allowMultiplePromotion' => $this->integer(11)->comment('1:true, 0: false'),
            'allowConditionOrder' => $this->integer(11)->comment('default 0 meaning check order, 1 check order_item'),
        ]);

        $this->dropTable('promotion_condition');
        $this->dropTable('promotion_condition_config');
        $this->dropTable('promotion_user');
        $this->execute('DROP TABLE IF EXISTS `promotion_log`');
        $this->createTable('promotion_log', [
            'id' => $this->primaryKey()->comment("ID"),
            'promotion_id' => $this->string(11)->comment(""),
            'promotion_type' => $this->string(255)->comment('PROMOTION/COUPON/XU'),
            'coupon_code' => $this->string(255)->comment(""),
            'order_bin' => $this->string(255)->comment(""),
            'revenue_xu' => $this->decimal(18, 1)->comment('Số xu kiếm được'),
            'discount_amount' => $this->decimal(18, 2)->comment('Số tiền giảm giá'),
            'customer_id' => $this->integer(11)->comment(""),
            'customer_email' => $this->string(255)->comment(""),
            'status' => $this->string(255)->comment('SUCCESS/FAIL'),
            'created_time' => $this->bigInteger()->comment("Update qua behaviors tự động"),
            'updated_time' => $this->bigInteger()->comment("Update qua behaviors tự động"),
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo 'm190410_081110_alter_promotion_table cannot be reverted.\n';

        return false;
    }
    */
}
