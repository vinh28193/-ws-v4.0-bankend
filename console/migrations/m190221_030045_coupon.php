<?php

use yii\db\Migration;

/**
 * Class m190221_030045_coupon
 */
class m190221_030045_coupon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /***ToDo Coupon Có 2 loại trừ tiền hoặc % Phí dịch vụ
         * Coupon  Có thể là chương trinh của Mar Ví dụ giám giá 50% phí dịch vụ Weshop , hoặc chạy mã VALENTIN200 trừ 200K vào sản phẩm và cho KH mới hoặc 1 tập cho  sẵn
        ****/
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('coupon',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'code' => $this->string(255)->comment(""),
            'message' => $this->string(255)->comment("thông báo khi áp dụng coupon"),
            'type_coupon' => $this->string(255)->comment("REFUND, COUPON ... hệ thống tự sinh, và phải là 1 const"),
            'type_amount' => $this->string(255)->comment("percent,money. nếu là money sẽ lấy theo tiền local."),
            'store_id' => $this->integer(11)->comment(""),
            'amount' => $this->decimal(18,2)->comment("đơn vị: % or tiền local. phụ thuộc vào type_amount"),
            'percent_for' => $this->string()->comment("tính % cho cái gì. Theo các cel trong bảng tính giá. A1 , A2. Nếu để trống sẽ mặc định tính theo giá tổng"),
            'created_by' => $this->integer(11)->comment("id người tạo"),
            'start_time' => $this->bigInteger()->comment(""),
            'end_time' => $this->bigInteger()->comment(""),
            'limit_customer_count_use' => $this->integer()->comment("giới hạn số lần sử dụng cho 1 user"),
            'limit_count_use' => $this->integer()->comment("giới hạn số lần sử dụng"),
            'count_use' => $this->integer()->comment("số lần sử dụng"),
            'limit_amount_use' => $this->decimal(18,2)->comment("giới hạn số tiền tối đa thể sử dụng"),
            'limit_amount_use_order' => $this->decimal(18,2)->comment("giới hạn số tiền tối đa thể sử dụng cho 1 order"),
            'for_email' => $this->string(255)->comment("coupon cho email nào"),
            'for_portal' => $this->string(255)->comment("coupon cho portal nào"),
            'for_category' => $this->string(255)->comment("coupon cho category nào"),
            'for_min_order_amount' => $this->string(255)->comment("coupon cho giá trị tối thiểu của 1 đơn hàng . đơn vị tiền local"),
            'for_max_order_amount' => $this->string(255)->comment("coupon cho giá trị tối đa của 1 đơn hàng . đơn vị tiền local"),
            'total_amount_used' => $this->decimal(18,2)->comment("tổng số tiền đã trừ từ coupon này"),
            'used_first_time' => $this->bigInteger()->comment(""),
            'used_last_time' => $this->bigInteger()->comment(""),
            'can_use_instalment' => $this->tinyInteger()->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

//        foreach ($this->list as $data){
//            $this->dropIndex('idx-coupon-'.$data['column'], 'coupon');
//            $this->dropForeignKey('fk-coupon-'.$data['column'], 'coupon');
//        }
//        $this->dropTable('coupon');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_030045_coupon cannot be reverted.\n";

        return false;
    }
    */
}
