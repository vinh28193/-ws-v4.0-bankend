<?php

use yii\db\Migration;

/**
 * Class m190709_103943_add_colunm_tracking_code
 */
class m190709_103943_add_colunm_tracking_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','tracking_codes',$this->text()->comment('Nhiều tracking cách nhau dấu ,'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','tracking_codes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190709_103943_add_colunm_tracking_code cannot be reverted.\n";

        return false;
    }
    */
}
