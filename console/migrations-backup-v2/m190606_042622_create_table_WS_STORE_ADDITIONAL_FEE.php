<?php

use yii\db\Migration;

class m190606_042622_create_table_WS_STORE_ADDITIONAL_FEE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%STORE_ADDITIONAL_FEE}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID reference'),
            'name' => $this->string(50)->notNull()->comment('Fee Name'),
            'label' => $this->string(80)->notNull()->comment('Label of fee'),
            'currency' => $this->string(11)->notNull()->defaultValue('\'USD\'')->comment('Currency (USD/VND)'),
            'description' => $this->text()->comment('Description'),
            'condition_data' => $this->binary()->comment('Fee Data'),
            'condition_description' => $this->text()->comment('Fee Rules Description'),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer()->comment('Created by'),
            'created_time' => $this->integer()->comment('Created at (timestamp)'),
            'updated_by' => $this->integer()->comment('Updated by'),
            'updated_time' => $this->integer()->comment('Updated at (timestamp)'),
            'fee_rate' => $this->decimal()->defaultValue('0.00')->comment('Fee Rate'),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108614C00008$$', '{{%STORE_ADDITIONAL_FEE}}', '', true);
        $this->createIndex('SYS_IL0000108614C00006$$', '{{%STORE_ADDITIONAL_FEE}}', '', true);
        $this->createIndex('SYS_IL0000108614C00007$$', '{{%STORE_ADDITIONAL_FEE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%STORE_ADDITIONAL_FEE}}');
    }
}
