<?php

class m190325_104114_Boxme_log_40_send_Return_shipment extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['Weshop_log_40','Boxme_log_40_send_Return_shipment']);
    }

    public function down()
    {
        $this->dropCollection(['Weshop_log_40','Boxme_log_40_send_Return_shipment']);
    }
}
