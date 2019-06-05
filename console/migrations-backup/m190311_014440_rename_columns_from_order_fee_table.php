<?php

use yii\db\Migration;

/**
 * Class m190311_014440_rename_columns_from_order_fee_table
 */
class m190311_014440_rename_columns_from_order_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('order_fee', 'type_fee', 'type');
        $this->renameColumn('order_fee', 'amount_local', 'local_amount');
        $this->addColumn('order_fee', 'name', $this->string(60)->notNull()->after('type'));
        $this->alterColumn('order_fee','amount',$this->decimal(18,2)->after('name')->comment("Amount"));
        $this->alterColumn('order_fee','local_amount',$this->decimal(18,2)->after('amount')->comment("Local amount"));
        $this->alterColumn('order_fee','discount_amount',$this->decimal(18,2)->after('local_amount')->comment("Discount of type fee"));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order_fee', 'name');
        $this->renameColumn('order_fee', 'local_amount', 'amount_local');
        $this->renameColumn('order_fee', 'type', 'type_fee');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190311_014440_rename_columns_from_order_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
