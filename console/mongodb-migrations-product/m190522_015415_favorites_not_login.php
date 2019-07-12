<?php

class m190522_015415_favorites_not_login extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['weshopglobal_v40', 'favorites']);
    }

    public function down()
    {
        $this->dropCollection(['weshopglobal_v40', 'favorites']);
    }
}
