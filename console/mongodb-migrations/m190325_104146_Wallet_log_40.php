<?php

class m190325_104146_Wallet_log_40 extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40','Wallet_log_40']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40','Wallet_log_40']);
    }
}
