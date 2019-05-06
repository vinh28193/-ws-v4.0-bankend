<?php

use yii\db\Migration;

/**
 * Class m190506_022807_update_column_table_tracking_code
 */
class m190506_022807_update_column_table_tracking_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tracking_code','warehouse_us_id',$this->integer()->after('weshop_tag'));
        $this->addColumn('tracking_code','warehouse_us_name',$this->string()->after('warehouse_us_id'));
        $this->addColumn('tracking_code','warehouse_local_id',$this->integer()->after('warehouse_us_name'));
        $this->renameColumn('tracking_code','warehouse_alias','warehouse_local_name');
        $this->renameColumn('tracking_code','warehouse_tag','warehouse_local_tag');
        $this->renameColumn('tracking_code','warehouse_note','warehouse_local_note');
        $this->renameColumn('tracking_code','warehouse_status','warehouse_local_status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190506_022807_update_column_table_tracking_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190506_022807_update_column_table_tracking_code cannot be reverted.\n";

        return false;
    }
    */
}
