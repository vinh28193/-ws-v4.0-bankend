<?php

use yii\db\Migration;

class m190606_041728_create_table_WS_COUPON extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%COUPON}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'name' => $this->string(255),
            'code' => $this->string(255),
            'message' => $this->string(255)->comment('thong bao khi ap d?ng coupon'),
            'type_coupon' => $this->string(255)->comment('REFUND, COUPON ... h? th?ng t? sinh, va ph?i la 1 const'),
            'type_amount' => $this->string(255)->comment('percent,money. n?u la money s? l?y theo ti?n local.'),
            'store_id' => $this->integer(),
            'amount' => $this->decimal()->comment('d?n v?: % or ti?n local. ph? thu?c vao type_amount'),
            'percent_for' => $this->string(255)->comment('tinh % cho cai gi. Theo cac cel trong b?ng tinh gia. A1 , A2. N?u d? tr?ng s? m?c d?nh tinh theo gia t?ng'),
            'created_by' => $this->integer()->comment('id ng??i t?o'),
            'start_time' => $this->integer(),
            'end_time' => $this->integer(),
            'limit_customer_count_use' => $this->integer()->comment('gi?i h?n s? l?n s? d?ng cho 1 user'),
            'limit_count_use' => $this->integer()->comment('gi?i h?n s? l?n s? d?ng'),
            'count_use' => $this->integer()->comment('s? l?n s? d?ng'),
            'limit_amount_use' => $this->decimal()->comment('gi?i h?n s? ti?n t?i da th? s? d?ng'),
            'limit_amount_use_order' => $this->decimal()->comment('gi?i h?n s? ti?n t?i da th? s? d?ng cho 1 order'),
            'for_email' => $this->string(255)->comment('coupon cho email nao'),
            'for_portal' => $this->string(255)->comment('coupon cho portal nao'),
            'for_category' => $this->string(255)->comment('coupon cho category nao'),
            'for_min_order_amount' => $this->string(255)->comment('coupon cho gia tr? t?i thi?u c?a 1 d?n hang . d?n v? ti?n local'),
            'for_max_order_amount' => $this->string(255)->comment('coupon cho gia tr? t?i da c?a 1 d?n hang . d?n v? ti?n local'),
            'total_amount_used' => $this->decimal()->comment('t?ng s? ti?n d? tr? t? coupon nay'),
            'used_first_time' => $this->integer(),
            'used_last_time' => $this->integer(),
            'can_use_instalment' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'remove' => $this->integer(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%COUPON}}');
    }
}
