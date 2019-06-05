<?php

use yii\db\Migration;

class m190605_013401_create_table_category_group extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category_group}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(255),
            'description' => $this->string(255),
            'store_id' => $this->integer(11),
            'parent_id' => $this->integer(11),
            'rule' => $this->text(),
            'rule_description' => $this->text(),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'active' => $this->tinyInteger(4),
            'remove' => $this->tinyInteger(4),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%category_group}}');
    }
}
