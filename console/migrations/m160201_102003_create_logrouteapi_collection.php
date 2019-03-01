<?php

class m160201_102003_create_logrouteapi_collection extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection('weshop_global_log_api_route');
    }

    public function down()
    {
        $this->dropCollection('weshop_global_log_api_route');
    }
}
