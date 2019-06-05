<?php

use yii\db\Migration;

class m190605_013403_create_table_system_exchange_rate extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_exchange_rate}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('ID'),
            'store_id' => $this->integer(11),
            'from' => $this->string(10)->notNull()->comment('form currency'),
            'to' => $this->string(10)->notNull()->comment('to currency'),
            'rate' => $this->decimal(18, 2)->notNull()->comment('current exchange rate'),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'sync_at' => $this->dateTime()->comment('Sync At'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%system_exchange_rate}}');
    }
}
