<?php

use yii\db\Migration;

class m190606_041957_create_table_WS_LIST_ACCOUNT_PURCHASE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%LIST_ACCOUNT_PURCHASE}}', [
            'id' => $this->integer()->notNull(),
            'account' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'type' => $this->string(255)->notNull(),
            'active' => $this->integer()->notNull()->defaultValue('0'),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%LIST_ACCOUNT_PURCHASE}}');
    }
}
