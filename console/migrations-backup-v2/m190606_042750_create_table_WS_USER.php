<?php

use yii\db\Migration;

class m190606_042750_create_table_WS_USER extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%USER}}', [
            'id' => $this->integer()->notNull(),
            'username' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255),
            'email' => $this->string(255)->notNull(),
            'status' => $this->integer()->notNull()->defaultValue('0'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'scopes' => $this->string(500)->comment('nhi?u scope cach nhau b?ng d?u ,. scope chinh d?t t?i d?u'),
            'store_id' => $this->integer(),
            'locale' => $this->string(10),
            'github' => $this->string(500)->comment(' user co link github'),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'phone' => $this->string(255),
            'gender' => $this->integer(),
            'birthday' => $this->timestamp(),
            'avatar' => $this->string(255),
            'link_verify' => $this->string(255),
            'email_verified' => $this->integer(),
            'phone_verified' => $this->integer(),
            'last_order_time' => $this->timestamp(),
            'note_by_employee' => $this->text(),
            'type_customer' => $this->integer()->comment(' set 1 la Khach L? va 2 la Khach buon - WholeSale Customer '),
            'employee' => $this->integer()->defaultValue('0')->comment(' 1 La Nhan vien , 0 la khach hang'),
            'active_shipping' => $this->integer()->defaultValue('0')->comment('0 la Khach th??ng , 1 la khach buon cho phep shiping'),
            'total_xu' => $this->decimal()->defaultValue('0.0'),
            'total_xu_start_date' => $this->integer()->comment('Thoi di?m b?t d?u di?m tich l?y '),
            'total_xu_expired_date' => $this->integer()->comment('Thoi di?m reset di?m tich l?y v? 0'),
            'usable_xu' => $this->decimal()->defaultValue('0.00')->comment('//t?ng s? xu co th? s? d?ng (tgian 1 thang)'),
            'usable_xu_start_date' => $this->integer()->comment('Thoi di?m b?t d?u di?m tich l?y '),
            'usable_xu_expired_date' => $this->integer()->comment('Thoi di?m reset di?m tich l?y v? 0'),
            'last_use_xu' => $this->decimal(),
            'last_use_time' => $this->integer(),
            'last_revenue_xu' => $this->decimal()->defaultValue('0.00'),
            'last_revenue_time' => $this->integer(),
            'verify_code' => $this->string(10),
            'verify_code_expired_at' => $this->integer(),
            'verify_code_count' => $this->integer(),
            'verify_code_type' => $this->string(255),
            'remove' => $this->integer()->defaultValue('0')->comment(' 0 la ch?a xoa , t?c la ?n , 1 la danh d?u d? xoa'),
            'vip' => $this->integer()->defaultValue('0')->comment(' M?c d? Vip C?a Khach Hang khong ap d?ng cho nhan vien , theo thang di?m 0-5 s?'),
            'uuid' => $this->string(255)->comment(' uuid t??ng d??ng fingerprint la  s? dinh danh c?a user tren toan h? th?ng WS + h? th?ng qu?ng cao + h? th?ng tracking '),
            'token_fcm' => $this->string(255)->comment(' Google FCM notification'),
            'token_apn' => $this->string(255)->comment(' Apple APN number notification'),
            'last_update_uuid_time' => $this->timestamp()->comment('Th?i gian update la '),
            'last_update_uuid_time_by' => $this->integer()->comment('update b?i ai . 99999 : mac dinh la Weshop admin'),
            'client_id_ga' => $this->string(255)->comment(' danh dau khach hang ?n danh khong ph?i la khach hang dang ki --> d?n khi chuy?n d?i thanh khach hang user dang ki'),
            'last_update_client_id_ga_time' => $this->timestamp()->comment(' Th?i gian sinh ra m? client_id_ga'),
            'last_update_client_id_ga_time_by' => $this->integer()->comment('update b?i ai . 99999 : mac dinh la Weshop admin'),
            'last_token_fcm_time' => $this->timestamp()->comment('Th?i gian update la '),
            'last_token_fcm_by' => $this->integer()->comment('update b?i ai . 99999 : mac dinh la Weshop admin'),
            'last_token_apn_time' => $this->timestamp()->comment('Th?i gian update la '),
            'last_token_apn_time_by' => $this->integer()->comment('update b?i ai . 99999 : mac dinh la Weshop admin'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108626C00024$$', '{{%USER}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%USER}}');
    }
}
