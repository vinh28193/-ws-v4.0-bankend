<?php

use yii\db\Migration;

/**
 * Class m190312_064940_promotion_Log_table
 */
class m190312_064940_promotion_Log_table extends Migration
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
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190312_064940_promotion_Log_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190312_064940_promotion_Log_table cannot be reverted.\n";

        return false;
    }
    */
}
