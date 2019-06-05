<?php

use yii\db\Migration;

/**
 * Class m190327_034307_create_table_list_account_and_table_list_card
 */
class m190327_034307_create_table_list_account_and_table_list_card extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('list_account_purchase',[
            'id' => $this->primaryKey(),
            'account' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'active' => $this->integer()->notNull()->defaultValue(1),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

        $this->createTable('purchase_payment_card',[
            'id' => $this->primaryKey(),
            'card_code' => $this->string(),
            'balance' => $this->decimal(),
            'current_balance' => $this->decimal(),
            'last_transaction_time' => $this->integer(),
            'last_amount' => $this->integer(),
            'store_id' => $this->integer(),
            'status' => $this->integer()->defaultValue(1),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190327_034307_create_table_list_account_and_table_list_card cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190327_034307_create_table_list_account_and_table_list_card cannot be reverted.\n";

        return false;
    }
    */
}
