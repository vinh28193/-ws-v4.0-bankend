<?php

use yii\db\Migration;

class m190605_013403_create_table_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'username' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255),
            'email' => $this->string(255)->notNull(),
            'status' => $this->smallInteger(6)->notNull()->defaultValue('10'),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'scopes' => $this->string(500)->comment('nhiều scope cách nhau bằng dấu ,. scope chính đặt tại đầu'),
            'store_id' => $this->integer(11),
            'locale' => $this->string(10),
            'github' => $this->string(500)->comment(' user co link github'),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'phone' => $this->string(255),
            'gender' => $this->tinyInteger(4),
            'birthday' => $this->dateTime(),
            'avatar' => $this->string(255),
            'link_verify' => $this->string(255),
            'email_verified' => $this->tinyInteger(4),
            'phone_verified' => $this->tinyInteger(4),
            'last_order_time' => $this->dateTime(),
            'note_by_employee' => $this->text(),
            'type_customer' => $this->integer(11)->comment(' set 1 là Khách Lẻ và 2 là Khách buôn - WholeSale Customer '),
            'employee' => $this->integer(11)->defaultValue('0')->comment(' 1 Là Nhân viên , 0 là khách hàng'),
            'active_shipping' => $this->integer(11)->defaultValue('0')->comment('0 là Khách thường , 1 là khách buôn cho phép shiping'),
            'total_xu' => $this->decimal(18, 1)->defaultValue('0.0'),
            'total_xu_start_date' => $this->bigInteger(20)->comment('Thoi điểm bắt đầu điểm tích lũy '),
            'total_xu_expired_date' => $this->bigInteger(20)->comment('Thoi điểm reset điểm tích lũy về 0'),
            'usable_xu' => $this->decimal(18, 2)->defaultValue('0.00')->comment('//tổng số xu có thể sử dụng (tgian 1 tháng)'),
            'usable_xu_start_date' => $this->bigInteger(20)->comment('Thoi điểm bắt đầu điểm tích lũy '),
            'usable_xu_expired_date' => $this->bigInteger(20)->comment('Thoi điểm reset điểm tích lũy về 0'),
            'last_use_xu' => $this->decimal(18, 2),
            'last_use_time' => $this->bigInteger(20),
            'last_revenue_xu' => $this->decimal(18, 2)->defaultValue('0.00'),
            'last_revenue_time' => $this->bigInteger(20),
            'verify_code' => $this->string(10),
            'verify_code_expired_at' => $this->bigInteger(20),
            'verify_code_count' => $this->integer(11),
            'verify_code_type' => $this->string(255),
            'remove' => $this->tinyInteger(4)->defaultValue('0')->comment(' 0 là chưa xóa , tức là ẩn , 1 là đánh dấu đã xóa'),
            'vip' => $this->integer(11)->defaultValue('0')->comment(' Mức độ Vip Của Khách Hàng không ap dụng cho nhân viên , theo thang điểm 0-5 số'),
            'uuid' => $this->string(255)->comment(' uuid tương đương fingerprint là  số đinh danh của user trên toàn hệ thống WS + hệ thống quảng cáo + hệ thống tracking '),
            'token_fcm' => $this->string(255)->comment(' Google FCM notification'),
            'token_apn' => $this->string(255)->comment(' Apple APN number notification'),
            'last_update_uuid_time' => $this->dateTime()->comment('Thời gian update là '),
            'last_update_uuid_time_by' => $this->bigInteger(20)->comment('update bởi ai . 99999 : mac dinh la Weshop admin'),
            'client_id_ga' => $this->string(255)->comment(' dánh dau khách hàng ẩn danh không phải là khách hàng đăng kí --> đến khi chuyển đổi thành khách hàng user đăng kí'),
            'last_update_client_id_ga_time' => $this->dateTime()->comment(' Thời gian sinh ra mã client_id_ga'),
            'last_update_client_id_ga_time_by' => $this->bigInteger(20)->comment('update bởi ai . 99999 : mac dinh la Weshop admin'),
            'last_token_fcm_time' => $this->dateTime()->comment('Thời gian update là '),
            'last_token_fcm_by' => $this->bigInteger(20)->comment('update bởi ai . 99999 : mac dinh la Weshop admin'),
            'last_token_apn_time' => $this->dateTime()->comment('Thời gian update là '),
            'last_token_apn_time_by' => $this->bigInteger(20)->comment('update bởi ai . 99999 : mac dinh la Weshop admin'),
        ], $tableOptions);

        $this->createIndex('password_reset_token', '{{%user}}', 'password_reset_token', true);
        $this->createIndex('username', '{{%user}}', 'username', true);
        $this->createIndex('email', '{{%user}}', 'email', true);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
