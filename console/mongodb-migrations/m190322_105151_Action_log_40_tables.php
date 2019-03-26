<?php

class m190322_105151_Action_log_40_tables extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40','Action_log_40']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40','Action_log_40']);
    }
}
