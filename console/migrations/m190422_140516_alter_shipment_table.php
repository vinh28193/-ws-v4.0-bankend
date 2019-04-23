<?php

use yii\db\Migration;

/**
 * Class m190422_140516_alter_shipment_table
 */
class m190422_140516_alter_shipment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('shipment','courier_logo',$this->string(32)->comment('mã hãng vận chuyển'));
        $this->alterColumn('shipment','is_insurance',$this->smallInteger()->defaultValue(0)->after('is_hold')->comment('đánh dấu bảo hiểm'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('shipment','is_insurance');
        $this->alterColumn('shipment','courier_logo',$this->integer(11)->comment('mã hãng vận chuyển'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo 'm190422_140516_alter_shipment_table cannot be reverted.\n';

        return false;
    }
    */
}
