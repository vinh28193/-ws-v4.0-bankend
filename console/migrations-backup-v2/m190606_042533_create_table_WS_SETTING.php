<?php

use yii\db\Migration;

class m190606_042533_create_table_WS_SETTING extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SETTING}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID reference'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%SETTING}}');
    }
}
