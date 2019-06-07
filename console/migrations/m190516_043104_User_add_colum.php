<?php

use yii\db\Migration;

/**
 * Class m190516_043104_User_add_colum
 */
class m190516_043104_User_add_colum extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'first_name',$this->string(255)->comment(""));

        $this->addColumn('user', 'last_name' , $this->string(255)->comment(""));
        $this->addColumn('user', 'phone' , $this->string(255)->comment(""));

        $this->addColumn('user', 'gender' , $this->tinyInteger(4)->comment(""));
        $this->addColumn('user', 'birthday' , $this->dateTime()->comment(""));
        $this->addColumn('user', 'avatar' , $this->string(255)->comment(""));
        $this->addColumn('user', 'link_verify' , $this->string(255)->comment(""));
        $this->addColumn('user', 'email_verified' , $this->tinyInteger(4)->comment(""));
        $this->addColumn('user', 'phone_verified' , $this->tinyInteger(4)->comment(""));
        $this->addColumn('user', 'last_order_time' , $this->dateTime()->comment(""));
        $this->addColumn('user', 'note_by_employee' , $this->text()->comment(""));
        $this->addColumn('user', 'type_customer' , $this->integer(11)->comment(" set 1 là Khách Lẻ và 2 là Khách buôn - WholeSale Customer "));

        $this->addColumn('user', 'employee' , $this->integer(11)->comment(" 1 Là Nhân viên , 0 là khách hàng")->defaultValue(0));
        $this->addColumn('user', 'active_shipping' , $this->integer(11)->defaultValue(0)->comment("0 là Khách thường , 1 là khách buôn cho phép shiping"));
        $this->addColumn('user', 'total_xu' , $this->decimal(18,1)->defaultValue(0)->comment(""));
        $this->addColumn('user', 'total_xu_start_date' , $this->bigInteger()->comment("Thoi điểm bắt đầu điểm tích lũy "));
        $this->addColumn('user', 'total_xu_expired_date' , $this->bigInteger()->comment("Thoi điểm reset điểm tích lũy về 0"));
        $this->addColumn('user', 'usable_xu' , $this->decimal(18,2)->defaultValue(0)->comment("//tổng số xu có thể sử dụng (tgian 1 tháng)"));
        $this->addColumn('user', 'usable_xu_start_date' , $this->bigInteger()->comment("Thoi điểm bắt đầu điểm tích lũy "));
        $this->addColumn('user', 'usable_xu_expired_date' , $this->bigInteger()->comment("Thoi điểm reset điểm tích lũy về 0"));
        $this->addColumn('user', 'last_use_xu' , $this->decimal(18,2)->comment(""));
        $this->addColumn('user', 'last_use_time' , $this->bigInteger()->comment(""));
        $this->addColumn('user', 'last_revenue_xu' , $this->decimal(18,2)->defaultValue(0)->comment(""));
        $this->addColumn('user', 'last_revenue_time' , $this->bigInteger()->comment(""));
        $this->addColumn('user', 'verify_code' , $this->string(10)->comment(""));
        $this->addColumn('user', 'verify_code_expired_at' , $this->bigInteger()->comment(""));
        $this->addColumn('user', 'verify_code_count' , $this->integer(11)->comment(""));
        $this->addColumn('user', 'verify_code_type' , $this->string(255)->comment(""));

        $this->addColumn('user', 'remove' , $this->tinyInteger(4)->defaultValue(0)->comment(" 0 là chưa xóa , tức là ẩn , 1 là đánh dấu đã xóa"));
        $this->addColumn('user', 'vip' , $this->integer(11)->comment(" Mức độ Vip Của Khách Hàng không ap dụng cho nhân viên , theo thang điểm 0-5 số")->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190516_043104_User_add_colum cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190516_043104_User_add_colum cannot be reverted.\n";

        return false;
    }
    */
}
