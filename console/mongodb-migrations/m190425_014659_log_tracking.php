<?php

class m190425_014659_log_tracking extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40', 'log_tracking']);
    }

    public function down()
    {
         $this->dropCollection(['Weshop_log_40', 'log_tracking']);

    }
}
