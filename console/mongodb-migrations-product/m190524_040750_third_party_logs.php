<?php

class m190524_040750_third_party_logs extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['weshopglobal_v40', 'third_party_logs']);
    }

    public function down()
    {
        $this->dropCollection(['weshopglobal_v40', 'third_party_logs']);
    }
}
