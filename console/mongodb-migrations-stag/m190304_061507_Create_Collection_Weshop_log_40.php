<?php

class m190304_061507_Create_Collection_Weshop_log_40 extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection('Weshoplog_v40_stag');
    }

    public function down()
    {
        $this->dropCollection('Weshoplog_v40_stag');
    }
}
