<?php

use yii\db\Migration;

class m190606_041659_create_table_WS_CATEGORY_CUSTOM_POLICY extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%CATEGORY_CUSTOM_POLICY}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'name' => $this->string(255),
            'description' => $this->string(255),
            'code' => $this->string(255),
            'limit' => $this->integer(),
            'is_special' => $this->integer(),
            'min_price' => $this->decimal(),
            'max_price' => $this->decimal(),
            'custom_rate_fee' => $this->decimal(),
            'use_percentage' => $this->decimal(),
            'custom_fix_fee_per_unit' => $this->decimal(),
            'custom_fix_fee_per_weight' => $this->decimal(),
            'custom_fix_percent_per_weight' => $this->decimal(),
            'store_id' => $this->integer(),
            'item_maximum_per_category' => $this->integer(),
            'weight_maximum_per_category' => $this->decimal(),
            'sort_order' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'active' => $this->integer(),
            'remove' => $this->integer(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%CATEGORY_CUSTOM_POLICY}}');
    }
}
