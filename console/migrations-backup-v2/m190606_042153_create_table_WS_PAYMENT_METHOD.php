<?php

use yii\db\Migration;

class m190606_042153_create_table_WS_PAYMENT_METHOD extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PAYMENT_METHOD}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID reference'),
            'name' => $this->string(100)->notNull(),
            'code' => $this->string(32)->notNull(),
            'bank_code' => $this->string(32),
            'description' => $this->string(255),
            'icon' => $this->string(255),
            'group' => $this->integer()->notNull()->defaultValue('0')->comment('1: the tin dung, 2:ngan hang, 3:vi ngan luong'),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer()->comment('Created by'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'updated_by' => $this->integer()->comment('Updated by'),
            'updated_at' => $this->integer()->comment('Updated at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PAYMENT_METHOD}}');
    }
}
