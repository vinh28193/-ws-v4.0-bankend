<?php

use yii\db\Migration;

class m190606_042429_create_table_WS_PROMOTION_USER extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PROMOTION_USER}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID'),
            'customer_id' => $this->integer()->notNull()->comment('Ten ch??ng trinh'),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'is_used' => $this->integer()->defaultValue('1')->comment('1:Used'),
            'used_order_id' => $this->integer()->comment('Used for order id '),
            'used_at' => $this->integer()->comment('Used at (timestamp)'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'promotion_id' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PROMOTION_USER}}');
    }
}
