<?php

use yii\db\Migration;

class m190606_042651_create_table_WS_SYSTEM_DISTRICT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SYSTEM_DISTRICT}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'name' => $this->string(255),
            'name_local' => $this->string(255),
            'name_alias' => $this->string(255),
            'display_order' => $this->integer(),
            'province_id' => $this->integer(),
            'country_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'remove' => $this->integer(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%SYSTEM_DISTRICT}}');
    }
}
