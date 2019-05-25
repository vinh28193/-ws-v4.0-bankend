<?php

use yii\db\Migration;

/**
 * Class m190525_075835_User_add_colum_03
 */
class m190525_075835_User_add_colum_03 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'last_update_uuid_time' , $this->dateTime()->comment("Thời gian update là "));
        $this->addColumn('user', 'last_update_uuid_time_by' , $this->bigInteger()->comment("update bởi ai . 99999 : mac dinh la Weshop admin"));
        $this->addColumn('user', 'client_id_ga' , $this->string(255)->comment(" dánh dau khách hàng ẩn danh không phải là khách hàng đăng kí --> đến khi chuyển đổi thành khách hàng user đăng kí"));
        $this->addColumn('user', 'last_update_client_id_ga_time' , $this->dateTime()->comment(" Thời gian sinh ra mã client_id_ga"));
        $this->addColumn('user', 'last_update_client_id_ga_time_by' , $this->bigInteger()->comment("update bởi ai . 99999 : mac dinh la Weshop admin"));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190525_075835_User_add_colum_03 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190525_075835_User_add_colum_03 cannot be reverted.\n";

        return false;
    }
    */
}
