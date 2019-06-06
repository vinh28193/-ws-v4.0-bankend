<?php

use yii\db\Migration;

class m190606_041742_create_table_WS_CUSTOMER extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%CUSTOMER}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'email' => $this->string(255),
            'phone' => $this->string(255),
            'username' => $this->string(255),
            'password_hash' => $this->string(255),
            'gender' => $this->integer(),
            'birthday' => $this->timestamp(),
            'avatar' => $this->string(255),
            'link_verify' => $this->string(255),
            'email_verified' => $this->integer(),
            'phone_verified' => $this->integer(),
            'last_order_time' => $this->timestamp(),
            'note_by_employee' => $this->text(),
            'type_customer' => $this->integer()->comment(' set 1 la Khach Buon va 2 la Khach buon - WholeSale Customer '),
            'access_token' => $this->string(255),
            'auth_client' => $this->string(255),
            'verify_token' => $this->string(255),
            'reset_password_token' => $this->string(255),
            'store_id' => $this->integer(),
            'active_shipping' => $this->integer(),
            'total_xu' => $this->decimal(),
            'total_xu_start_date' => $this->integer()->comment('Thoi di?m b?t d?u di?m tich l?y '),
            'total_xu_expired_date' => $this->integer()->comment('Thoi di?m reset di?m tich l?y v? 0'),
            'usable_xu' => $this->decimal()->comment('//t?ng s? xu co th? s? d?ng (tgian 1 thang)'),
            'usable_xu_start_date' => $this->integer()->comment('Thoi di?m b?t d?u di?m tich l?y '),
            'usable_xu_expired_date' => $this->integer()->comment('Thoi di?m reset di?m tich l?y v? 0'),
            'last_use_xu' => $this->decimal(),
            'last_use_time' => $this->integer(),
            'last_revenue_xu' => $this->decimal(),
            'last_revenue_time' => $this->integer(),
            'verify_code' => $this->string(10),
            'verify_code_expired_at' => $this->integer(),
            'verify_code_count' => $this->integer(),
            'verify_code_type' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'active' => $this->integer(),
            'remove' => $this->integer()->defaultValue('0'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'auth_key' => $this->string(255),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108642C00015$$', '{{%CUSTOMER}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%CUSTOMER}}');
    }
}
