<?php

class m190325_104003_Payment_log_40 extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40','Payment_log_40']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40','Payment_log_40']);
    }
}
