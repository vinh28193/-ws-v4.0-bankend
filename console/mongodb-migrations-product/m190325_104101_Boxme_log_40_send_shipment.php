<?php

class m190325_104101_Boxme_log_40_send_shipment extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshoplog_v40','Boxme_log_40_send_shipment']);
    }

    public function down()
    {
        $this->dropCollection(['Weshoplog_v40','Boxme_log_40_send_shipment']);
    }
}
