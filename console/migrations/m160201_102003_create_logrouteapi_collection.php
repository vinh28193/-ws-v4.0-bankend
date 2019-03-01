<?php

class m160201_102003_create_logrouteapi_collection extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection('logrouteapi');
    }

    public function down()
    {
        $this->dropCollection('logrouteapi');
    }
}
