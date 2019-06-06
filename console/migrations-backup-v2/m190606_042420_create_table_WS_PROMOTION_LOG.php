<?php

use yii\db\Migration;

class m190606_042420_create_table_WS_PROMOTION_LOG extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PROMOTION_LOG}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID'),
            'promotion_id' => $this->string(11)->comment('Promotion ID'),
            'customer_id' => $this->integer()->comment('Customer ID'),
            'order_id' => $this->string(32)->comment('Order ID'),
            'revenue_xu' => $this->decimal()->comment('S? xu ki?m d??c'),
            'discount_amount' => $this->decimal()->comment('S? ti?n gi?m gia'),
            'status' => $this->string(255)->comment('SUCCESS/FAIL'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PROMOTION_LOG}}');
    }
}
