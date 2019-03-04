<?php

class m190301_101956_logrouteapi extends \yii\mongodb\Migration
{
    public function up()
    {
       // $this->createCollection(['weshop_global','log_api_route']);
        $this->createCollection('weshop_global_log_api_route');
    }

    public function down()
    {
        $this->dropCollection('weshop_global_log_api_route');
    }

}
