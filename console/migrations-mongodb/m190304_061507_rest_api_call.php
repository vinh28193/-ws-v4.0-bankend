<?php

class m190304_061507_rest_api_call extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection('rest_api_call');
    }

    public function down()
    {
        $this->dropCollection('rest_api_call');
    }
}
