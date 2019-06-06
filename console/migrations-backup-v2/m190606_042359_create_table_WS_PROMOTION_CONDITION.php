<?php

use yii\db\Migration;

class m190606_042359_create_table_WS_PROMOTION_CONDITION extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PROMOTION_CONDITION}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID'),
            'promotion_id' => $this->string(11)->notNull()->comment('Promotion ID'),
            'name' => $this->string(80)->notNull()->comment('name of condition'),
            'value' => $this->text()->comment('mixed value'),
            'created_by' => $this->integer()->comment('Created by'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'updated_by' => $this->integer()->comment('Updated by'),
            'updated_at' => $this->integer()->comment('Updated at (timestamp)'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108784C00005$$', '{{%PROMOTION_CONDITION}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%PROMOTION_CONDITION}}');
    }
}
