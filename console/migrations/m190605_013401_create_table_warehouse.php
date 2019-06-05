<?php

use yii\db\Migration;

class m190605_013401_create_table_warehouse extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%warehouse}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'description' => $this->string(255),
            'district_id' => $this->integer(11),
            'province_id' => $this->integer(11),
            'country_id' => $this->integer(11),
            'store_id' => $this->integer(11),
            'address' => $this->string(255),
            'type' => $this->integer(11)->comment('1: Full Operation Warehouse , 2 : Transit Warehouse '),
            'warehouse_group' => $this->string(255)->comment('1: Nhóm Transit , 2 : nhóm lưu trữ, 3 : nhóm note purchase. Các nhóm sẽ đc khai báo const '),
            'post_code' => $this->string(255),
            'telephone' => $this->string(255),
            'email' => $this->string(255),
            'contact_person' => $this->string(255),
            'ref_warehouse_id' => $this->integer(11),
            'created_at' => $this->bigInteger(20)->comment('thời gian tạo'),
            'updated_at' => $this->bigInteger(20)->comment('thời gian cập nhật'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('idx-warehouse-store_id', '{{%warehouse}}', 'store_id');
        $this->createIndex('idx-warehouse-province_id', '{{%warehouse}}', 'province_id');
        $this->createIndex('idx-warehouse-country_id', '{{%warehouse}}', 'country_id');
        $this->createIndex('idx-warehouse-district_id', '{{%warehouse}}', 'district_id');
        /*
        $this->addForeignKey('fk-warehouse-country_id', '{{%warehouse}}', 'country_id', '{{%system_country}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-warehouse-district_id', '{{%warehouse}}', 'district_id', '{{%system_district}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-warehouse-province_id', '{{%warehouse}}', 'province_id', '{{%system_state_province}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-warehouse-store_id', '{{%warehouse}}', 'store_id', '{{%store}}', 'id', 'CASCADE', 'CASCADE');
        */
    }

    public function down()
    {
        $this->dropTable('{{%warehouse}}');
    }
}
