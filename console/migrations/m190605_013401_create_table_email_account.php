<?php

use yii\db\Migration;

class m190605_013401_create_table_email_account extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%email_account}}', [
            'id' => $this->primaryKey(),
            'Email' => $this->string(255)->comment('email'),
            'DisplayName' => $this->string(255)->comment('Tên hiện thị'),
            'Host' => $this->string(255)->comment('Tên host'),
            'Port' => $this->integer(11)->comment('Cổng '),
            'Username' => $this->string(255)->comment('Tên đăng nhập'),
            'Password' => $this->string(255)->comment('Mật khẩu'),
            'EnableSsl' => $this->tinyInteger(1)->comment('Cho phép sử dụng ssl?'),
            'UseDefaultCredentials' => $this->tinyInteger(1),
            'OrganizationId' => $this->integer(11),
            'StoreId' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%email_account}}');
    }
}
