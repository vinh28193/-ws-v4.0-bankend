<?php

use yii\db\Migration;

class m190605_013402_create_table_promotion_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%promotion_user}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'customer_id' => $this->integer(11)->notNull()->comment('Tên chương trình'),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'is_used' => $this->smallInteger(6)->defaultValue('1')->comment('1:Used'),
            'used_order_id' => $this->integer(11)->comment('Used for order id '),
            'used_at' => $this->integer(11)->comment('Used at (timestamp)'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'promotion_id' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%promotion_user}}');
    }
}
