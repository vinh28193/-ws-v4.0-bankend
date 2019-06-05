<?php

use yii\db\Migration;

class m190605_013401_create_table_system_district extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_district}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(255),
            'name_local' => $this->string(255),
            'name_alias' => $this->string(255),
            'display_order' => $this->integer(11),
            'province_id' => $this->integer(11),
            'country_id' => $this->integer(11),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'remove' => $this->tinyInteger(4),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

        /*
        $this->createIndex('idx-system_district-country_id', '{{%system_district}}', 'country_id');
        $this->createIndex('idx-system_district-province_id', '{{%system_district}}', 'province_id');
        */
        /*
        $this->addForeignKey('fk-system_district-country_id', '{{%system_district}}', 'country_id', '{{%system_country}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-system_district-province_id', '{{%system_district}}', 'province_id', '{{%system_state_province}}', 'id', 'CASCADE', 'CASCADE');
        */
    }

    public function down()
    {
        $this->dropTable('{{%system_district}}');
    }
}
