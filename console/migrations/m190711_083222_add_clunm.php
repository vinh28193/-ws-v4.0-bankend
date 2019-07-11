<?php

use yii\db\Migration;

/**
 * Class m190711_083222_add_clunm
 */
class m190711_083222_add_clunm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','vip_end_time',$this->integer()->comment('Thời gian vip hết hạn'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','vip_end_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190711_083222_add_clunm cannot be reverted.\n";

        return false;
    }
    */
}
