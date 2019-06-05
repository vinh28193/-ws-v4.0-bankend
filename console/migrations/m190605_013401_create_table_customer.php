<?php

use yii\db\Migration;

class m190605_013401_create_table_customer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'email' => $this->string(255),
            'phone' => $this->string(255),
            'username' => $this->string(255),
            'password_hash' => $this->string(255),
            'gender' => $this->tinyInteger(4),
            'birthday' => $this->dateTime(),
            'avatar' => $this->string(255),
            'link_verify' => $this->string(255),
            'email_verified' => $this->tinyInteger(4),
            'phone_verified' => $this->tinyInteger(4),
            'last_order_time' => $this->dateTime(),
            'note_by_employee' => $this->text(),
            'type_customer' => $this->integer(11)->comment(' set 1 là Khách Buôn và 2 là Khách buôn - WholeSale Customer '),
            'access_token' => $this->string(255),
            'auth_client' => $this->string(255),
            'verify_token' => $this->string(255),
            'reset_password_token' => $this->string(255),
            'store_id' => $this->integer(11),
            'active_shipping' => $this->integer(11),
            'total_xu' => $this->decimal(18, 1),
            'total_xu_start_date' => $this->bigInteger(20)->comment('Thoi điểm bắt đầu điểm tích lũy '),
            'total_xu_expired_date' => $this->bigInteger(20)->comment('Thoi điểm reset điểm tích lũy về 0'),
            'usable_xu' => $this->decimal(18, 2)->comment('//tổng số xu có thể sử dụng (tgian 1 tháng)'),
            'usable_xu_start_date' => $this->bigInteger(20)->comment('Thoi điểm bắt đầu điểm tích lũy '),
            'usable_xu_expired_date' => $this->bigInteger(20)->comment('Thoi điểm reset điểm tích lũy về 0'),
            'last_use_xu' => $this->decimal(18, 2),
            'last_use_time' => $this->bigInteger(20),
            'last_revenue_xu' => $this->decimal(18, 2),
            'last_revenue_time' => $this->bigInteger(20),
            'verify_code' => $this->string(10),
            'verify_code_expired_at' => $this->bigInteger(20),
            'verify_code_count' => $this->integer(11),
            'verify_code_type' => $this->string(255),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'active' => $this->tinyInteger(4),
            'remove' => $this->tinyInteger(4)->defaultValue('0'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'auth_key' => $this->string(255),
        ], $tableOptions);

        $this->createIndex('idx-customer-store_id', '{{%customer}}', 'store_id');
         // $this->addForeignKey('fk-customer-store_id', '{{%customer}}', 'store_id', '{{%store}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%customer}}');
    }
}
