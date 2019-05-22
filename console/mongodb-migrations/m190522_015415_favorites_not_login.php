<?php

class m190522_015415_favorites_not_login extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40', 'favorites']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40', 'favorites']);
    }
}
