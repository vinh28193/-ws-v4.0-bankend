<?php

class m190304_061507_Create_Collection_Weshop_log_40 extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection('Weshop_log_40');
    }

    public function down()
    {
        $this->dropCollection('Weshop_log_40');
    }
}
