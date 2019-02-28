<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `payment_provider`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190228_084636_create_payment_provider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment_provider', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(32)->notNull()->comment('Name'),
            'resource' => $this->binary()->notNull()->comment('Object Resource'),
            'submit_url' => $this->text(255)->notNull()->comment('Submit Address'),
            'submit_method' => $this->text(10)->notNull()->comment('Submit Method'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_at' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),
        ]);

        // creates index for column `store_id`
        $this->createIndex(
            'idx-payment_provider-store_id',
            'payment_provider',
            'store_id'
        );

        // add foreign key for table `store`
        $this->addForeignKey(
            'fk-payment_provider-store_id',
            'payment_provider',
            'store_id',
            'store',
            'store_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `store`
        $this->dropForeignKey(
            'fk-payment_provider-store_id',
            'payment_provider'
        );

        // drops index for column `store_id`
        $this->dropIndex(
            'idx-payment_provider-store_id',
            'payment_provider'
        );

        $this->dropTable('payment_provider');
    }
}
