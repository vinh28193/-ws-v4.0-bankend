<?php

use yii\db\Migration;

class m190606_042708_create_table_WS_SYSTEM_EXCHANGE_RATE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SYSTEM_EXCHANGE_RATE}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer(),
            'from' => $this->string(10)->notNull()->comment('form currency'),
            'to' => $this->string(10)->notNull()->comment('to currency'),
            'rate' => $this->decimal()->notNull()->comment('current exchange rate'),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'sync_at' => $this->timestamp()->comment('Sync At'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%SYSTEM_EXCHANGE_RATE}}');
    }
}
