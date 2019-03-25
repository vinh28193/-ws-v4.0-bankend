<?php

class m190325_104101_Boxme_log_40_send_shipment extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40','Boxme_log_40_send_shipment']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40','Boxme_log_40_send_shipment']);
    }
}
