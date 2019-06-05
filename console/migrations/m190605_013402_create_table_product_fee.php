<?php

use yii\db\Migration;

class m190605_013402_create_table_product_fee extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product_fee}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'order_id' => $this->integer(11)->comment('order id'),
            'product_id' => $this->integer(11)->notNull()->comment('Product Id'),
            'type' => $this->string(255)->comment('loại phí: đơn giá gốc, ship us, ship nhật , weshop fee, ...'),
            'name' => $this->string(60)->notNull(),
            'amount' => $this->decimal(18, 2)->comment('Amount'),
            'local_amount' => $this->decimal(18, 2)->comment('Local amount'),
            'discount_amount' => $this->decimal(18, 2)->comment('Discount of type fee'),
            'currency' => $this->string(255)->comment('loại tiền ngoại tệ'),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'remove' => $this->tinyInteger(4),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('idx-order_fee-order_id', '{{%product_fee}}', 'order_id');
    }

    public function down()
    {
        $this->dropTable('{{%product_fee}}');
    }
}
