<?php

use yii\db\Migration;

/**
 * Class m190427_034443_modify_3_table_Package
 */
class m190427_034443_modify_3_table_Package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('package_item');
        $this->renameTable('package','delivery_note');
        $this->renameTable('draft_package_item','package');
        $this->renameColumn('package','package_id','delivery_note_id');
        $this->renameColumn('package','package_code','delivery_note_code');
        $this->renameColumn('delivery_note','package_code','delivery_note_code');
        $this->renameColumn('delivery_note','package_weight','delivery_note_weight');
        $this->renameColumn('delivery_note','package_change_weight','delivery_note_change_weight');
        $this->renameColumn('delivery_note','package_dimension_l','delivery_note_dimension_l');
        $this->renameColumn('delivery_note','package_dimension_w','delivery_note_dimension_w');
        $this->renameColumn('delivery_note','package_dimension_h','delivery_note_dimension_h');

        $this->renameColumn('tracking_code','package_id','delivery_note_id');
        $this->renameColumn('tracking_code','package_code','delivery_note_code');
        $this->renameColumn('tracking_code','package_item_id','package_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190427_034443_modify_3_table_Package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190427_034443_modify_3_table_Package cannot be reverted.\n";

        return false;
    }
    */
}
