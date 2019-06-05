<?php

use yii\db\Migration;

class m190605_013400_create_table_system_state_province extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_state_province}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'country_id' => $this->integer(11)->comment('id nước'),
            'name' => $this->string(255),
            'name_local' => $this->string(255),
            'name_alias' => $this->string(255),
            'display_order' => $this->integer(11),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'remove' => $this->tinyInteger(4),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);


        $this->createIndex('idx-system_state_province-country_id', '{{%system_state_province}}', 'country_id');

        /*
         * @Phuchc Nâng cấp lên version mới Oracle bỏ phần này
        $this->addForeignKey('fk-system_state_province-country_id', '{{%system_state_province}}', 'country_id', '{{%system_country}}', 'id', 'CASCADE', 'CASCADE');
        */
    }

    public function down()
    {
        $this->dropTable('{{%system_state_province}}');
    }
}
