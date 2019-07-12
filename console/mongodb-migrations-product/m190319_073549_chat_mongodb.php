<?php

class m190319_073549_chat_mongodb extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['weshopglobal_v40','chat_mongo_ws']);
    }

    public function down()
    {
        $this->dropCollection(['weshopglobal_v40','chat_mongo_ws']);
    }
}
