<?php

use yii\db\Migration;

class m190606_042700_create_table_WS_SYSTEM_DISTRICT_MAPPING extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SYSTEM_DISTRICT_MAPPING}}', [
            'id' => $this->integer()->notNull(),
            'district_id' => $this->integer()->comment('id system_district'),
            'province_id' => $this->integer()->comment('id system_province'),
            'box_me_district_id' => $this->integer()->comment('id district box me'),
            'box_me_province_id' => $this->integer()->comment('id province box me'),
            'district_name' => $this->string(255),
            'province_name' => $this->string(255),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%SYSTEM_DISTRICT_MAPPING}}');
    }
}
