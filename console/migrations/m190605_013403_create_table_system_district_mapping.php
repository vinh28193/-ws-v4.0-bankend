<?php

use yii\db\Migration;

class m190605_013403_create_table_system_district_mapping extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_district_mapping}}', [
            'id' => $this->primaryKey(),
            'district_id' => $this->integer(11)->comment('id system_district'),
            'province_id' => $this->integer(11)->comment('id system_province'),
            'box_me_district_id' => $this->integer(11)->comment('id district box me'),
            'box_me_province_id' => $this->integer(11)->comment('id province box me'),
            'district_name' => $this->string(255),
            'province_name' => $this->string(255),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%system_district_mapping}}');
    }
}
