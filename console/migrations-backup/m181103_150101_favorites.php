<?php

use yii\db\Migration;

use yii\db\Schema;

class m181103_150101_favorites extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%favorites}}', [
            'id'         => $this->primaryKey(),
            'obj_id'     => $this->text()->notNull()->comment("id hoăc SKU Ebay / Amazon"),
            'obj_type'   => $this->text()->notNull()->comment("Thuộc tính của từng item Ebay hoặc Amazon lúc khách hàng xem sản phẩm. Dang Seriline"),
            'ip'         => $this->string(255)->notNull(),
            'created_at' => $this->bigInteger()->comment("created_at"),
            'updated_at' => $this->bigInteger()->comment("updated_at"),
            'created_by' => $this->bigInteger()->comment("created_by"),
            'updated_by' => $this->bigInteger()->comment("updated_by"),
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('{{%favorites}}');
    }
}
