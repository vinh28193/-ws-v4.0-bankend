<?php

class m190325_104040_Boxme_log_40_Inspection extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40','Boxme_log_40_Inspection']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40','Boxme_log_40_Inspection']);
    }
}
