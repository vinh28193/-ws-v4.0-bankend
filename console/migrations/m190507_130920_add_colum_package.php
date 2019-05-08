<?php

use yii\db\Migration;

/**
 * Class m190507_130920_add_colum_package
 */
class m190507_130920_add_colum_package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('package','insurance',$this->integer()->defaultValue(0)->comment('0: auto, 1: insurance, 2: unInsurance'));
        $this->addColumn('package','pack_wood',$this->integer()->defaultValue(0)->comment('0: unInsurance, 1: insurance'));
        $this->addColumn('delivery_note','insurance',$this->integer()->defaultValue(0)->comment('0: auto, 1: insurance, 2: unInsurance'));
        $this->addColumn('delivery_note','pack_wood',$this->integer()->defaultValue(0)->comment('0: unInsurance, 1: insurance'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190507_130920_add_colum_package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190507_130920_add_colum_package cannot be reverted.\n";

        return false;
    }
    */
}
