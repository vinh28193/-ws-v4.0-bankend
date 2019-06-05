<?php

use yii\db\Migration;

/**
 * Class m190425_022746_add_column_data_tracking
 */
class m190425_022746_add_column_data_tracking extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_data_tracking','seller_refund_amount',$this->decimal(18,2)->comment('Sô tiền seller hoàn'));
        $this->addColumn('draft_package_item','seller_refund_amount',$this->decimal(18,2)->comment('Sô tiền seller hoàn'));
        $this->addColumn('draft_package_item','draft_data_tracking_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190425_022746_add_column_data_tracking cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190425_022746_add_column_data_tracking cannot be reverted.\n";

        return false;
    }
    */
}
