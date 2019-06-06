<?php

use yii\db\Migration;

class m190606_041936_create_table_WS_FAVORITES extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%FAVORITES}}', [
            'id' => $this->integer()->notNull(),
            'obj_id' => $this->text()->notNull()->comment('id hoac SKU Ebay / Amazon'),
            'obj_type' => $this->text()->notNull()->comment('Thu?c tinh c?a t?ng item Ebay ho?c Amazon luc khach hang xem s?n ph?m. Dang Seriline'),
            'ip' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->comment('created_at'),
            'updated_at' => $this->integer()->comment('updated_at'),
            'created_by' => $this->integer()->comment('created_by'),
            'updated_by' => $this->integer()->comment('updated_by'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108676C00002$$', '{{%FAVORITES}}', '', true);
        $this->createIndex('SYS_IL0000108676C00003$$', '{{%FAVORITES}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%FAVORITES}}');
    }
}
