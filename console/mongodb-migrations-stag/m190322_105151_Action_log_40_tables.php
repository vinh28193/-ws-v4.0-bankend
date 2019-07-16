<?php

class m190322_105151_Action_log_40_tables extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshoplog_v40_stag','Action_log_40']);
    }

    public function down()
    {
        $this->dropCollection(['Weshoplog_v40_stag','Action_log_40']);
    }
}
