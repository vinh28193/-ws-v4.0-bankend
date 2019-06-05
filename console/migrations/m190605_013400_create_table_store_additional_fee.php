<?php

use yii\db\Migration;

class m190605_013400_create_table_store_additional_fee extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%store_additional_fee}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(50)->notNull()->comment('Fee Name'),
            'label' => $this->string(80)->notNull()->comment('Label of fee'),
            'currency' => $this->string(11)->notNull()->defaultValue('USD')->comment('Currency (USD/VND)'),
            'description' => $this->text()->comment('Description'),
            'condition_data' => $this->binary()->comment('Fee Data'),
            'condition_description' => $this->text()->comment('Fee Rules Description'),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_time' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_time' => $this->integer(11)->comment('Updated at (timestamp)'),
            'fee_rate' => $this->decimal(18, 2)->defaultValue('0.00')->comment('Fee Rate'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%store_additional_fee}}');
    }
}
