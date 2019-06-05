<?php

use yii\db\Migration;

class m190605_013401_create_table_payment_provider extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment_provider}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(100)->notNull(),
            'code' => $this->string(32)->notNull(),
            'description' => $this->string(255),
            'return_url' => $this->string(255),
            'cancel_url' => $this->string(255),
            'submit_url' => $this->string(255),
            'background_url' => $this->string(255),
            'image_url' => $this->string(255),
            'logo_url' => $this->string(255),
            'pending_url' => $this->string(255),
            'rc' => $this->integer(11),
            'alg' => $this->string(10),
            'bmod' => $this->string(255),
            'merchant_id' => $this->string(32),
            'secret_key' => $this->string(255),
            'aes_iv' => $this->string(50),
            'portal' => $this->string(20),
            'token' => $this->string(20),
            'wsdl' => $this->string(50),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_at' => $this->integer(11)->comment('Updated at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%payment_provider}}');
    }
}
