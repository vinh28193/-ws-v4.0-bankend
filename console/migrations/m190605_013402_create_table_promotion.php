<?php

use yii\db\Migration;

class m190605_013402_create_table_promotion extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%promotion}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'name' => $this->string(100)->comment('Tên chương trình'),
            'code' => $this->string(32)->comment('Tạo mã chạy chương trình duy nhất không trung'),
            'message' => $this->string(255)->comment('message'),
            'description' => $this->text()->comment('description'),
            'type' => $this->smallInteger(6)->defaultValue('1')->comment('1:Coupon/2:MultiCoupon/3:CouponRefund/4:Promotion/5:MultiProduct/6:MarketingCampaign/7:Others'),
            'discount_calculator' => $this->smallInteger(6)->defaultValue('1')->comment('1:%,2:fix value'),
            'discount_fee' => $this->string(50)->comment('discount dùng cho phí gì'),
            'discount_amount' => $this->decimal(10)->defaultValue('0')->comment('giá trị được giảm'),
            'discount_type' => $this->smallInteger(6)->defaultValue('1')->comment('1:price,2 weight,3 quantity'),
            'discount_max_amount' => $this->decimal(10)->defaultValue('0')->comment('giá trị max được giảm khi discount type %'),
            'refund_order_Id' => $this->integer(11)->comment('dùng với discountType = CouponRefund'),
            'allow_instalment' => $this->smallInteger(6)->defaultValue('0')->comment('0/ not for instalment, 1 for all'),
            'allow_multiple' => $this->smallInteger(6)->defaultValue('1')->comment('1:true, 0: false'),
            'allow_order' => $this->smallInteger(6)->defaultValue('1')->comment('1:true, 0: false'),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_at' => $this->integer(11)->comment('Updated at (timestamp)'),
            'promotion_image' => $this->text(),
        ], $tableOptions);

        $this->createIndex('code', '{{%promotion}}', 'code', true);
    }

    public function down()
    {
        $this->dropTable('{{%promotion}}');
    }
}
