<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `payment_method_bank`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190513_014102_create_payment_method_bank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment_method_bank', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'payment_method_id' => $this->integer(11)->notNull(),
            'payment_bank_id' => $this->integer(11)->notNull(),
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
        $this->dropTable('payment_method_bank');
    }
}
