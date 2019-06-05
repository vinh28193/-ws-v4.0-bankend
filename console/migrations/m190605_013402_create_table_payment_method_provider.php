<?php

use yii\db\Migration;

class m190605_013402_create_table_payment_method_provider extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment_method_provider}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'payment_method_id' => $this->integer(11)->notNull(),
            'payment_provider_id' => $this->integer(11)->notNull(),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_at' => $this->integer(11)->comment('Updated at (timestamp)'),
        ], $tableOptions);

        /*
        $this->createIndex('idx-payment_method_provider-payment_provider', '{{%payment_method_provider}}', 'payment_provider_id');
        $this->createIndex('idx-payment_method_provider-payment_method', '{{%payment_method_provider}}', 'payment_method_id');
        */
        /*
        $this->addForeignKey('fk-payment_method_provider-payment_method', '{{%payment_method_provider}}', 'payment_method_id', '{{%payment_method}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-payment_method_provider-payment_provider', '{{%payment_method_provider}}', 'payment_provider_id', '{{%payment_provider}}', 'id', 'RESTRICT', 'RESTRICT');
        */
    }

    public function down()
    {
        $this->dropTable('{{%payment_method_provider}}');
    }
}
