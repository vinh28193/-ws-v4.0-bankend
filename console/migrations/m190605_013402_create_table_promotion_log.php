<?php

use yii\db\Migration;

class m190605_013402_create_table_promotion_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%promotion_log}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'promotion_id' => $this->string(11)->comment('Promotion ID'),
            'customer_id' => $this->integer(11)->comment('Customer ID'),
            'order_id' => $this->string(32)->comment('Order ID'),
            'revenue_xu' => $this->decimal(18, 1)->comment('Số xu kiếm được'),
            'discount_amount' => $this->decimal(18, 2)->comment('Số tiền giảm giá'),
            'status' => $this->string(255)->comment('SUCCESS/FAIL'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%promotion_log}}');
    }
}
