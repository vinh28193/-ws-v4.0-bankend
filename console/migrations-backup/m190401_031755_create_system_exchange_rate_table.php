<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `system_exchange_rate`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190401_031755_create_system_exchange_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('system_exchange_rate', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'form' => $this->string(10)->notNull()->comment('form currency'),
            'to' => $this->string(10)->notNull()->comment('to currency'),
            'rate' => $this->decimal(18, 2)->notNull()->comment('current exchange rate'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'sync_at' => $this->dateTime()->defaultValue(null)->comment('Sync At'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('system_exchange_rate');
    }
}
