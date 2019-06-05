<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `payment_bank`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190513_014119_create_payment_bank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment_bank', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(100)->notNull(),
            'code' => $this->string(32)->notNull(),
            'icon' => $this->string(255),
            'url' => $this->string(255),
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
        $this->dropTable('payment_bank');
    }
}
