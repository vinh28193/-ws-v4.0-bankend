<?php

use yii\db\Migration;

/**
 * Class m190423_134333_add_coloum_tracking
 */
class m190423_134333_add_coloum_tracking extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('manifest','status',$this->string()->comment(''));
        $this->addColumn('draft_extension_tracking_map','draft_data_tracking_id',$this->integer()->comment(''));
        $this->addColumn('draft_data_tracking','type_tracking',$this->string()->comment('split, normal, unknown'));
        $this->addColumn('draft_package_item','type_tracking',$this->string()->comment('split, normal, unknown'));
        $this->addColumn('draft_missing_tracking','type_tracking',$this->string()->comment('split, normal, unknown'));
        $this->addColumn('draft_wasting_tracking','type_tracking',$this->string()->comment('split, normal, unknown'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190423_134333_add_coloum_tracking cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190423_134333_add_coloum_tracking cannot be reverted.\n";

        return false;
    }
    */
}
