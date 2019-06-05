<?php

use yii\db\Migration;

/**
 * Class m190316_033915_alter_shipment_code_column_from_shipment_table
 */
class m190316_033915_alter_shipment_code_column_from_shipment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('shipment', 'shipment_code',$this->string(32)->comment('mã phiếu giao, BM_CODE'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('shipment', 'shipment_code', $this->integer(11)->comment('mã phiếu giao, BM_CODE'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190316_033915_alter_shipment_code_column_from_shipment_table cannot be reverted.\n";

        return false;
    }
    */
}
