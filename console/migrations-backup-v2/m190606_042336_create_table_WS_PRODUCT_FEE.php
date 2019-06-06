<?php

use yii\db\Migration;

class m190606_042336_create_table_WS_PRODUCT_FEE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PRODUCT_FEE}}', [
            'id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->comment('order id'),
            'product_id' => $this->integer()->notNull()->comment('Product Id'),
            'type' => $this->string(255)->comment('lo?i phi: d?n gia g?c, ship us, ship nh?t , weshop fee, ...'),
            'name' => $this->string(60)->notNull(),
            'amount' => $this->decimal()->comment('Amount'),
            'local_amount' => $this->decimal()->comment('Local amount'),
            'discount_amount' => $this->decimal()->comment('Discount of type fee'),
            'currency' => $this->string(255)->comment('lo?i ti?n ngo?i t?'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'remove' => $this->integer(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PRODUCT_FEE}}');
    }
}
