<?php

/**
 *
 */
use yii\db\Migration;

class m190605_013401_create_table_list_account_purchase extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%list_account_purchase}}', [
            'id' => $this->primaryKey(),
            'account' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'type' => $this->string(255)->notNull(),
            'active' => $this->integer(11)->notNull()->defaultValue('1'),
            'updated_at' => $this->integer(11),
            'created_at' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%list_account_purchase}}');
    }
}
