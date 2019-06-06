<?php

use yii\db\Migration;

class m190606_042347_create_table_WS_PROMOTION extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PROMOTION}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID'),
            'name' => $this->string(100)->comment('Ten ch??ng trinh'),
            'code' => $this->string(32)->comment('T?o m? ch?y ch??ng trinh duy nh?t khong trung'),
            'message' => $this->string(255)->comment('message'),
            'description' => $this->text()->comment('description'),
            'type' => $this->integer()->defaultValue('1')->comment('1:Coupon/2:MultiCoupon/3:CouponRefund/4:Promotion/5:MultiProduct/6:MarketingCampaign/7:Others'),
            'discount_calculator' => $this->integer()->defaultValue('1')->comment('1:%,2:fix value'),
            'discount_fee' => $this->string(50)->comment('discount dung cho phi gi'),
            'discount_amount' => $this->decimal()->defaultValue('0')->comment('gia tr? d??c gi?m'),
            'discount_type' => $this->integer()->defaultValue('1')->comment('1:price,2 weight,3 quantity'),
            'discount_max_amount' => $this->decimal()->defaultValue('0')->comment('gia tr? max d??c gi?m khi discount type %'),
            'refund_order_id' => $this->integer()->comment('dung v?i discountType = CouponRefund'),
            'allow_instalment' => $this->integer()->defaultValue('0')->comment('0/ not for instalment, 1 for all'),
            'allow_multiple' => $this->integer()->defaultValue('1')->comment('1:true, 0: false'),
            'allow_order' => $this->integer()->defaultValue('1')->comment('1:true, 0: false'),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer()->comment('Created by'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'updated_by' => $this->integer()->comment('Updated by'),
            'updated_at' => $this->integer()->comment('Updated at (timestamp)'),
            'promotion_image' => $this->text(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108778C00022$$', '{{%PROMOTION}}', '', true);
        $this->createIndex('SYS_IL0000108778C00006$$', '{{%PROMOTION}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%PROMOTION}}');
    }
}
