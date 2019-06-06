<?php

use yii\db\Migration;

class m190606_042718_create_table_WS_SYSTEM_STATE_PROVINCE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SYSTEM_STATE_PROVINCE}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'country_id' => $this->integer()->comment('id n??c'),
            'name' => $this->string(255),
            'name_local' => $this->string(255),
            'name_alias' => $this->string(255),
            'display_order' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'remove' => $this->integer(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%SYSTEM_STATE_PROVINCE}}');
    }
}
