<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `payment_method`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190513_013948_create_payment_method_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment_method', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(100)->notNull(),
            'code' => $this->string(32)->notNull(),
            'bank_code' => $this->string(32)->null(),
            'description' => $this->string(255),
            'icon' =>  $this->string(255),
            'group' => $this->integer(11)->notNull()->defaultValue(1)->comment('1: the tin dung, 2:ngan hang, 3:vi ngan luong'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_at' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('payment_method');
    }
}
