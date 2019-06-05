<?php

use yii\db\Migration;

/**
 * Class m190504_044819_create_column_3table
 */
class m190504_044819_create_column_3table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tracking_code','stock_in_us',$this->integer());
        $this->addColumn('tracking_code','stock_out_us',$this->integer());
        $this->addColumn('tracking_code','stock_in_local',$this->integer());
        $this->addColumn('tracking_code','stock_out_local',$this->integer());

        $this->addColumn('draft_data_tracking','stock_in_us',$this->integer());
        $this->addColumn('draft_data_tracking','stock_out_us',$this->integer());
        $this->addColumn('draft_data_tracking','stock_in_local',$this->integer());
        $this->addColumn('draft_data_tracking','stock_out_local',$this->integer());

        $this->addColumn('package','stock_in_us',$this->integer());
        $this->addColumn('package','stock_out_us',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190504_044819_create_column_3table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190504_044819_create_column_3table cannot be reverted.\n";

        return false;
    }
    */
}
