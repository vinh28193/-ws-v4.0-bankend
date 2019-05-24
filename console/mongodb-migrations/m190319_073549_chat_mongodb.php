<?php

class m190319_073549_chat_mongodb extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['weshop_global_40','chat_mongo_ws']);
    }

    public function down()
    {
        $this->dropCollection(['weshop_global_40','chat_mongo_ws']);
    }
}
