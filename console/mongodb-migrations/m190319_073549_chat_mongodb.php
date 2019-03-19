<?php

class m190319_073549_chat_mongodb extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection('chat_mongo_ws');
    }

    public function down()
    {
        $this->dropCollection('chat_mongo_ws');
    }
}
