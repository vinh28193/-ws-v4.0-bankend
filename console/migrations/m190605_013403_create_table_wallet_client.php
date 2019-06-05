<?php

use yii\db\Migration;

class m190605_013403_create_table_wallet_client extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wallet_client}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255),
            'auth_key' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'customer_id' => $this->integer(11)->notNull(),
            'customer_phone' => $this->string(255)->notNull(),
            'customer_name' => $this->string(255),
            'current_balance' => $this->double()->notNull()->defaultValue('0')->comment('Tổng số dư hiện tại (=freeze_balance+usable_balance)'),
            'freeze_balance' => $this->double()->notNull()->defaultValue('0')->comment('Số tiền bị đóng băng hiện tại'),
            'usable_balance' => $this->double()->notNull()->defaultValue('0')->comment('Số tiền có thể sử dụng để thanh toán'),
            'withdrawable_balance' => $this->double()->notNull()->defaultValue('0')->comment('Số tiền có thể sử dụng để rút khỏi ví'),
            'total_refunded_amount' => $this->double()->defaultValue('0')->comment('Tổng số tiền được refund'),
            'total_topup_amount' => $this->double()->defaultValue('0')->comment('Tổng số tiền đã nạp'),
            'total_using_amount' => $this->double()->defaultValue('0')->comment('Tổng số tiền đã thanh toán đơn hàng'),
            'total_withdraw_amount' => $this->double()->defaultValue('0')->comment('Tổng số tiền đã rút'),
            'last_refund_amount' => $this->double()->defaultValue('0')->comment('số tiền được refund lần cuôi'),
            'last_refund_at' => $this->dateTime()->comment('thời gian refund lần cuối'),
            'last_topup_amount' => $this->double()->defaultValue('0')->comment('Số tiền nạp lần cuôi'),
            'last_topup_at' => $this->dateTime()->comment('thời gian nạp lần cuối'),
            'last_using_amount' => $this->double()->defaultValue('0')->comment('Số tiền giao dịch lần cuối'),
            'last_using_at' => $this->dateTime()->comment('Thời gian thực hiện giao dịch cuối cùng'),
            'last_withdraw_amount' => $this->double()->defaultValue('0')->comment('Số tiền rút lần cuối'),
            'last_withdraw_at' => $this->dateTime()->comment('Thời gian rút lần cuối'),
            'current_bulk_point' => $this->integer(11)->defaultValue('0')->comment('Số điểm bulk hiện tại'),
            'current_bulk_balance' => $this->double()->defaultValue('0')->comment('Số tiền được quy đổi bulk hiện tại'),
            'otp_veryfy_code' => $this->string(10)->comment('Mã xác thực otp hiện tại'),
            'otp_veryfy_expired_at' => $this->dateTime()->comment('Thời gian hết hạn mã otp'),
            'otp_veryfy_count' => $this->integer(1)->comment('Tổng số mã xác thực đã sử dụng'),
            'store_id' => $this->integer(11),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'identity_number' => $this->string(20),
            'identity_issued_date' => $this->date(),
            'identity_issued_by' => $this->string(255),
            'identity_image_url_before' => $this->string(255),
            'identity_verified' => $this->tinyInteger(1)->defaultValue('0'),
            'identity_image_url_after' => $this->string(255),
            'status' => $this->integer(255)->comment('0:inactive;1active;2:freeze'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%wallet_client}}');
    }
}
