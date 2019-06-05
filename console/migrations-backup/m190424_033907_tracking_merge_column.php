<?php

use yii\db\Migration;

/**
 * Class m190424_033907_tracking_merge_column
 */
class m190424_033907_tracking_merge_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_data_tracking','tracking_merge',$this->text()->comment('List tracking đã được merge'));
        $this->addColumn('draft_missing_tracking','tracking_merge',$this->text()->comment('List tracking đã được merge'));
        $this->addColumn('draft_wasting_tracking','tracking_merge',$this->text()->comment('List tracking đã được merge'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190424_033907_tracking_merge_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190424_033907_tracking_merge_column cannot be reverted.\n";

        return false;
    }
    */
}
