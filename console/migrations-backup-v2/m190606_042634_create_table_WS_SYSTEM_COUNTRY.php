<?php

use yii\db\Migration;

class m190606_042634_create_table_WS_SYSTEM_COUNTRY extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SYSTEM_COUNTRY}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'name' => $this->string(255),
            'country_code' => $this->string(255),
            'country_code_2' => $this->string(255),
            'language' => $this->string(255)->comment('N?u co nhi?u , vi?t cach nhau b?ng d?u ph?y'),
            'status' => $this->string(255),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%SYSTEM_COUNTRY}}');
    }
}
