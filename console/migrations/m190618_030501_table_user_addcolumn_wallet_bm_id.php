<?php

use yii\db\Migration;

/**
 * Class m190618_030501_table_user_addcolumn_wallet_bm_id
 */
class m190618_030501_table_user_addcolumn_wallet_bm_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}','bm_wallet_id',$this->integer()->comment('Id user của boxme')->after('username'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}','bm_wallet_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190618_030501_table_user_addcolumn_wallet_bm_id cannot be reverted.\n";

        return false;
    }
    */
}
