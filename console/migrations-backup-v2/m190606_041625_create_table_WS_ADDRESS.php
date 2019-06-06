<?php

use yii\db\Migration;

class m190606_041625_create_table_WS_ADDRESS extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ADDRESS}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'email' => $this->string(255),
            'phone' => $this->string(255),
            'country_id' => $this->integer(),
            'country_name' => $this->string(255),
            'province_id' => $this->integer(),
            'province_name' => $this->string(255),
            'district_id' => $this->integer(),
            'district_name' => $this->string(255),
            'address' => $this->text(),
            'post_code' => $this->string(255),
            'store_id' => $this->integer(),
            'type' => $this->string(255),
            'is_default' => $this->integer(),
            'customer_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'remove' => $this->integer(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108594C00012$$', '{{%ADDRESS}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%ADDRESS}}');
    }
}
