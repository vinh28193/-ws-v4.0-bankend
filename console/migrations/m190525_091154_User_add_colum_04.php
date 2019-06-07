<?php

use yii\db\Migration;

/**
 * Class m190525_091154_User_add_colum_04
 */
class m190525_091154_User_add_colum_04 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'last_token_fcm_time' , $this->dateTime()->comment("Thời gian update là "));
        $this->addColumn('user', 'last_token_fcm_by' , $this->bigInteger()->comment("update bởi ai . 99999 : mac dinh la Weshop admin"));
        $this->addColumn('user', 'last_token_apn_time' , $this->dateTime()->comment("Thời gian update là "));
        $this->addColumn('user', 'last_token_apn_time_by' , $this->bigInteger()->comment("update bởi ai . 99999 : mac dinh la Weshop admin"));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190525_091154_User_add_colum_04 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190525_091154_User_add_colum_04 cannot be reverted.\n";

        return false;
    }
    */
}
