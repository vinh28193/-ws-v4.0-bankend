<?php

class m190425_014659_log_tracking extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshoplog_v40_stag', 'log_tracking']);
    }

    public function down()
    {
         $this->dropCollection(['Weshoplog_v40_stag', 'log_tracking']);

    }
}
