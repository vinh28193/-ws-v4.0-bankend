<?php

use yii\db\Migration;

class m190605_013402_create_table_setting extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%setting}}');
    }
}
