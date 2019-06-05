<?php

use yii\db\Migration;

class m190605_013401_create_table_favorites extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%favorites}}', [
            'id' => $this->primaryKey(),
            'obj_id' => $this->text()->notNull()->comment('id hoăc SKU Ebay / Amazon'),
            'obj_type' => $this->text()->notNull()->comment('Thuộc tính của từng item Ebay hoặc Amazon lúc khách hàng xem sản phẩm. Dang Seriline'),
            'ip' => $this->string(255)->notNull(),
            'created_at' => $this->bigInteger(20)->comment('created_at'),
            'updated_at' => $this->bigInteger(20)->comment('updated_at'),
            'created_by' => $this->bigInteger(20)->comment('created_by'),
            'updated_by' => $this->bigInteger(20)->comment('updated_by'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%favorites}}');
    }
}
