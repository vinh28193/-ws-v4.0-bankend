<?php

use yii\db\Migration;

class m190606_043004_create_table_WS_WAREHOUSE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%WAREHOUSE}}', [
            'id' => $this->integer()->notNull(),
            'name' => $this->string(255),
            'description' => $this->string(255),
            'district_id' => $this->integer(),
            'province_id' => $this->integer(),
            'country_id' => $this->integer(),
            'store_id' => $this->integer(),
            'address' => $this->string(255),
            'type' => $this->integer()->comment('1: Full Operation Warehouse , 2 : Transit Warehouse '),
            'warehouse_group' => $this->string(255)->comment('1: Nhom Transit , 2 : nhom l?u tr?, 3 : nhom note purchase. Cac nhom s? dc khai bao const '),
            'post_code' => $this->string(255),
            'telephone' => $this->string(255),
            'email' => $this->string(255),
            'contact_person' => $this->string(255),
            'ref_warehouse_id' => $this->integer(),
            'created_at' => $this->integer()->comment('th?i gian t?o'),
            'updated_at' => $this->integer()->comment('th?i gian c?p nh?t'),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%WAREHOUSE}}');
    }
}
