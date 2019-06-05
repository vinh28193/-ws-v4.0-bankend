<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `payment_provider`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190513_014005_create_payment_provider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment_provider', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(100)->notNull(),
            'code' => $this->string(32)->notNull(),
            'description' => $this->string(255)->null(),
            'return_url' => $this->string(255),
            'cancel_url' => $this->string(255),
            'submit_url' => $this->string(255),
            'background_url' => $this->string(255),
            'image_url' => $this->string(255),
            'logo_url' => $this->string(255),
            'pending_url' => $this->string(255),
            'rc' => $this->integer(11)->null(),
            'alg' => $this->string(10)->null(),
            'bmod' => $this->string(255)->null(),
            'merchant_id' => $this->string(32)->null(),
            'secret_key' => $this->string(255)->null(),
            'aes_iv' => $this->string(50)->null(),
            'portal' => $this->string(20)->null(),
            'token' => $this->string(20)->null(),
            'wsdl' => $this->string(50)->null(),
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
        $this->dropTable('payment_provider');
    }
}
