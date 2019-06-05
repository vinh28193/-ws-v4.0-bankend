<?php

use yii\db\Migration;

class m190605_013400_create_table_system_country extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_country}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(255),
            'country_code' => $this->string(255),
            'country_code_2' => $this->string(255),
            'language' => $this->string(255)->comment('Nếu có nhiều , viết cách nhau bằng dấu phẩy'),
            'status' => $this->string(255),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%system_country}}');
    }
}
