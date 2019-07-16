<?php

class m190524_040750_third_party_logs extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40_stag', 'third_party_logs']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40_stag', 'third_party_logs']);
    }
}
