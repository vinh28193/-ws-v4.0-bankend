<?php

class m190325_104003_Payment_log_40 extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshoplog_v40','Payment_log_40']);
    }

    public function down()
    {
        $this->dropCollection(['Weshoplog_v40','Payment_log_40']);
    }
}
