<?php

use yii\db\Migration;

class m190605_013401_create_table_payment_method extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment_method}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(100)->notNull(),
            'code' => $this->string(32)->notNull(),
            'bank_code' => $this->string(32),
            'description' => $this->string(255),
            'icon' => $this->string(255),
            'group' => $this->integer(11)->notNull()->defaultValue('1')->comment('1: the tin dung, 2:ngan hang, 3:vi ngan luong'),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_at' => $this->integer(11)->comment('Updated at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%payment_method}}');
    }
}
