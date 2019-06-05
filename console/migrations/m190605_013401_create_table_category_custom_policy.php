<?php

use yii\db\Migration;

class m190605_013401_create_table_category_custom_policy extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category_custom_policy}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(255),
            'description' => $this->string(255),
            'code' => $this->string(255),
            'limit' => $this->integer(11),
            'is_special' => $this->integer(11),
            'min_price' => $this->decimal(18, 2),
            'max_price' => $this->decimal(18, 2),
            'custom_rate_fee' => $this->decimal(18, 2),
            'use_percentage' => $this->decimal(18, 2),
            'custom_fix_fee_per_unit' => $this->decimal(18, 2),
            'custom_fix_fee_per_weight' => $this->decimal(18, 2),
            'custom_fix_percent_per_weight' => $this->decimal(18, 2),
            'store_id' => $this->integer(11),
            'item_maximum_per_category' => $this->integer(11),
            'weight_maximum_per_category' => $this->decimal(18, 2),
            'sort_order' => $this->integer(11),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'active' => $this->tinyInteger(4),
            'remove' => $this->tinyInteger(4),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%category_custom_policy}}');
    }
}
