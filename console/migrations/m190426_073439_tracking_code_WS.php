<?php

use yii\db\Migration;

/**
 * Class m190426_073439_tracking_code_WS
 */
class m190426_073439_tracking_code_WS extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_data_tracking','ws_tracking_code',$this->string()->comment('Mã tracking của weshop'));
        $this->addColumn('draft_package_item','ws_tracking_code',$this->string()->comment('Mã tracking của weshop'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190426_073439_tracking_code_WS cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190426_073439_tracking_code_WS cannot be reverted.\n";

        return false;
    }
    */
}
