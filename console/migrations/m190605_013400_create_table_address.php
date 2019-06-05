<?php

use yii\db\Migration;

class m190605_013400_create_table_address extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%address}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'email' => $this->string(255),
            'phone' => $this->string(255),
            'country_id' => $this->integer(11),
            'country_name' => $this->string(255),
            'province_id' => $this->integer(11),
            'province_name' => $this->string(255),
            'district_id' => $this->integer(11),
            'district_name' => $this->string(255),
            'address' => $this->text(),
            'post_code' => $this->string(255),
            'store_id' => $this->integer(11),
            'type' => $this->string(255),
            'is_default' => $this->integer(11),
            'customer_id' => $this->integer(11),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'remove' => $this->tinyInteger(4),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

        /*
        $this->createIndex('idx-address-store_id', '{{%address}}', 'store_id');
        $this->createIndex('idx-address-province_id', '{{%address}}', 'province_id');
        $this->createIndex('idx-address-customer_id', '{{%address}}', 'customer_id');
        $this->createIndex('idx-address-district_id', '{{%address}}', 'district_id');
        $this->createIndex('idx-address-country_id', '{{%address}}', 'country_id');
        */
    }

    public function down()
    {
        $this->dropTable('{{%address}}');
    }
}
