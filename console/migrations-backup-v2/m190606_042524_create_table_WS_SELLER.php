<?php

use yii\db\Migration;

class m190606_042524_create_table_WS_SELLER extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SELLER}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'seller_name' => $this->string(255),
            'seller_link_store' => $this->text(),
            'seller_store_rate' => $this->string(255),
            'seller_store_description' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'seller_remove' => $this->integer(),
            'portal' => $this->text(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108602C00003$$', '{{%SELLER}}', '', true);
        $this->createIndex('SYS_IL0000108602C00005$$', '{{%SELLER}}', '', true);
        $this->createIndex('SYS_IL0000108602C00009$$', '{{%SELLER}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%SELLER}}');
    }
}
