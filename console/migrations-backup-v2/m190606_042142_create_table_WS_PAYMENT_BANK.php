<?php

use yii\db\Migration;

class m190606_042142_create_table_WS_PAYMENT_BANK extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PAYMENT_BANK}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID reference'),
            'name' => $this->string(100)->notNull(),
            'code' => $this->string(32)->notNull(),
            'icon' => $this->string(255),
            'url' => $this->string(255),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer()->comment('Created by'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'updated_by' => $this->integer()->comment('Updated by'),
            'updated_at' => $this->integer()->comment('Updated at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PAYMENT_BANK}}');
    }
}
