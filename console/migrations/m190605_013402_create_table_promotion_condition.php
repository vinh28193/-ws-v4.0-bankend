<?php

use yii\db\Migration;

class m190605_013402_create_table_promotion_condition extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%promotion_condition}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'promotion_id' => $this->string(11)->notNull()->comment('Promotion ID'),
            'name' => $this->string(80)->notNull()->comment('name of condition'),
            'value' => $this->text()->comment('mixed value'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_at' => $this->integer(11)->comment('Updated at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%promotion_condition}}');
    }
}
