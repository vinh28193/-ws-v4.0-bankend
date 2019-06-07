<?php

use yii\db\Migration;

/**
 * Class m190525_071602_User_add_colum_02
 */
class m190525_071602_User_add_colum_02 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'uuid' , $this->string(255)->comment(" uuid tương đương fingerprint là  số đinh danh của user trên toàn hệ thống WS + hệ thống quảng cáo + hệ thống tracking "));
        $this->addColumn('user', 'token_fcm' , $this->string(255)->comment(" Google FCM notification"));
        $this->addColumn('user', 'token_apn' , $this->string(255)->comment(" Apple APN number notification"));
        //$this->addColumn('user', 'fingerprint' ,$this->string(255)->comment(" Mức độ Vip Của Khách Hàng không ap dụng cho nhân viên , theo thang điểm 0-5 số")->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190525_071602_User_add_colum_02 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190525_071602_User_add_colum_02 cannot be reverted.\n";

        return false;
    }
    */
}
