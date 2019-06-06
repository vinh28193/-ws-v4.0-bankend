<?php

use yii\db\Migration;

class m190606_042205_create_table_WS_PAYMENT_METHOD_BANK extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PAYMENT_METHOD_BANK}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID reference'),
            'payment_method_id' => $this->integer()->notNull(),
            'payment_bank_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer()->comment('Created by'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'updated_by' => $this->integer()->comment('Updated by'),
            'updated_at' => $this->integer()->comment('Updated at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PAYMENT_METHOD_BANK}}');
    }
}
