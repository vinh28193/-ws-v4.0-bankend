<?php

use yii\db\Migration;

class m190606_041720_create_table_WS_COUNTRY extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%COUNTRY}}', [
            'code' => $this->string(32)->notNull(),
            'name' => $this->string(255)->notNull(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%COUNTRY}}');
    }
}
