<?php

use yii\db\Migration;

/**
 * Class m190403_070909_update_column_exchange_rate
 */
class m190403_070909_update_column_exchange_rate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('system_exchange_rate','form','from');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190403_070909_update_column_exchange_rate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190403_070909_update_column_exchange_rate cannot be reverted.\n";

        return false;
    }
    */
}
