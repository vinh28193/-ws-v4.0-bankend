<?php

use yii\db\Migration;

/**
 * Class m190619_100706_add_column_facebook_account_id
 */
class m190619_100706_add_column_facebook_account_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}','facebook_acc_kit_id',$this->string(50)->comment('id facebook'));
        $this->addColumn('{{%user}}','facebook_acc_kit_token',$this->string(255)->comment('token refresh auth code facebook'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}','facebook_acc_kit_id');
        $this->dropColumn('{{%user}}','facebook_acc_kit_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190619_100706_add_column_facebook_account_id cannot be reverted.\n";

        return false;
    }
    */
}
